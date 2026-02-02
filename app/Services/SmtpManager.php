<?php

namespace App\Services;

use App\Models\SmtpServerModel;
use App\Models\SettingsModel;
use PHPMailer\PHPMailer\PHPMailer;
use Config\Services;

class SmtpManager
{
    protected $smtpModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->smtpModel     = new SmtpServerModel();
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Selects the best available SMTP server based on priority and limits
     */
    public function selectSmtpServer()
    {
        $servers = $this->smtpModel->getActiveServersOrdered();

        foreach ($servers as $server) {
            if ($server->hourly_sent < $server->max_hourly && $server->daily_sent < $server->max_daily) {
                return $server;
            }
        }

        return null; // No available server found
    }

    /**
     * Returns a configured PHPMailer instance
     */
    public function getMailerInstance($server)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $server->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $server->username;
        $mail->Password   = $this->decryptPassword($server->password);
        $mail->SMTPSecure = $server->encryption === 'none' ? '' : $server->encryption;
        $mail->Port       = $server->port;
        $mail->CharSet    = 'UTF-8';

        if ($server->connection_pooling) {
            $mail->SMTPKeepAlive = true;
        }

        return $mail;
    }

    /**
     * Decrypts SMTP password
     */
    protected function decryptPassword($encryptedPassword)
    {
        $encrypter = Services::encrypter();
        try {
            return $encrypter->decrypt(base64_decode($encryptedPassword));
        } catch (\Exception $e) {
            return $encryptedPassword; // Fallback if not encrypted or key changed
        }
    }
}
