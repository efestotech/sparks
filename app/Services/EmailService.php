<?php

namespace App\Services;

use App\Models\EmailQueueModel;
use App\Models\EmailLogModel;
use App\Models\SmtpServerModel;
use App\Models\SettingsModel;
use App\Entities\EmailQueue;
use App\Entities\EmailLog;
use Config\Services;
use Exception;

class EmailService
{
    protected $queueModel;
    protected $logModel;
    protected $smtpModel;
    protected $settings;
    protected $smtpManager;
    protected $attachmentService;
    protected $webhookService;

    public function __construct()
    {
        $this->queueModel        = new EmailQueueModel();
        $this->logModel          = new EmailLogModel();
        $this->smtpModel         = new SmtpServerModel();
        $this->settings          = new SettingsModel();
        $this->smtpManager       = new SmtpManager();
        $this->attachmentService = new AttachmentService();
        $this->webhookService    = new WebhookService();
    }

    /**
     * Adds an email to the queue
     */
    public function queueEmail(array $data, int $userId)
    {
        $email = new EmailQueue($data);
        $email->user_id = $userId;
        $email->status  = 'pending';
        $email->max_attempts = $this->settings->getSetting('global_max_attempts') ?? 3;
        
        // Support for legacy bridge fields
        if (isset($data['legacy_id_a'])) $email->legacy_id_a = $data['legacy_id_a'];
        if (isset($data['legacy_id_b'])) $email->legacy_id_b = $data['legacy_id_b'];
        
        if ($this->queueModel->insert($email)) {
            return $this->queueModel->getInsertID();
        }

        return false;
    }

    /**
     * Processes a batch of emails from the queue
     */
    public function processQueue(int $chunkSize = null)
    {
        $limit  = $chunkSize ?? ($this->settings->getSetting('chunk_size') ?? 50);
        $emails = $this->queueModel->getPendingEmails($limit);
        
        $stats = ['processed' => 0, 'sent' => 0, 'failed' => 0];

        foreach ($emails as $email) {
            $stats['processed']++;
            
            // Mark as processing
            $this->queueModel->update($email->id, ['status' => 'processing']);

            if ($this->sendSingleEmail($email)) {
                $stats['sent']++;
            } else {
                $stats['failed']++;
            }
        }

        return $stats;
    }

    /**
     * Sends a single email using SMTP rotation
     */
    public function sendSingleEmail(EmailQueue $email)
    {
        $startTime = microtime(true);
        $server    = $this->smtpManager->selectSmtpServer();

        if (!$server) {
            $error = "No SMTP server available (all limits reached or inactive)";
            $this->queueModel->update($email->id, [
                'status'        => 'pending', 
                'error_message' => $error,
                'attempts'      => $email->attempts + 1,
                'last_attempt_at' => date('Y-m-d H:i:s')
            ]);
            $this->logModel->logEvent($email->id, null, 'fail', $error);
            log_message('error', 'EQS: ' . $error);
            return false;
        }

        $mail = $this->smtpManager->getMailerInstance($server);
        $resolvedAttachments = [];

        try {
            // Recipients
            $mail->setFrom($server->from_email, $server->from_name);
            $mail->addAddress($email->to_email, $email->to_name ?? '');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $email->subject;
            $mail->Body    = $email->body_html;
            
            // AltBody (plain text)
            $mail->AltBody = strip_tags($email->body_html);

            // Custom Headers
            if ($email->custom_headers) {
                foreach ($email->custom_headers as $key => $value) {
                    $mail->addCustomHeader($key, $value);
                }
            }

            // Attachments
            if ($email->attachments) {
                $resolvedAttachments = $this->attachmentService->resolveAttachments($email->attachments);
                foreach ($resolvedAttachments as $att) {
                    $mail->addAttachment($att['path'], $att['name']);
                }
            }

            // Send
            $mail->send();

            // Success
            $duration = (int)((microtime(true) - $startTime) * 1000);
            $this->queueModel->update($email->id, [
                'status'         => 'sent',
                'sent_at'        => date('Y-m-d H:i:s'),
                'smtp_server_id' => $server->id,
                'attempts'       => $email->attempts + 1
            ]);

            $this->smtpModel->incrementCounters($server->id);
            $this->logModel->logEvent($email->id, $server->id, 'success', 'Sent successfully', 250, $duration);
            
            $this->webhookService->notifySuccess($this->queueModel->find($email->id));

            return true;

        } catch (Exception $e) {
            $error = $mail->ErrorInfo ?: $e->getMessage();
            $duration = (int)((microtime(true) - $startTime) * 1000);

            $attempts = $email->attempts + 1;
            $status   = ($attempts >= $email->max_attempts) ? 'failed' : 'pending';

            $this->queueModel->update($email->id, [
                'status'          => $status,
                'error_message'   => $error,
                'attempts'        => $attempts,
                'last_attempt_at' => date('Y-m-d H:i:s'),
                'smtp_server_id'  => $server->id
            ]);

            $this->logModel->logEvent($email->id, $server->id, 'fail', $error, null, $duration);

            if ($status === 'failed') {
                $this->webhookService->notifyFailed($this->queueModel->find($email->id));
            }

            return false;

        } finally {
            $this->attachmentService->cleanup($resolvedAttachments);
            if ($server->connection_pooling && isset($mail)) {
                $mail->smtpClose();
            }
        }
    }
}
