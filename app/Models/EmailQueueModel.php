<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\EmailQueue;

class EmailQueueModel extends Model
{
    protected $table            = 'email_queue';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = EmailQueue::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'smtp_server_id',
        'priority',
        'status',
        'to_email',
        'to_name',
        'subject',
        'body_html',
        'custom_headers',
        'attachments',
        'scheduled_at',
        'attempts',
        'max_attempts',
        'last_attempt_at',
        'sent_at',
        'error_message',
        'webhook_success_called',
        'webhook_failed_called',
        'legacy_id_a',
        'legacy_id_b',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPendingEmails(int $limit)
    {
        return $this->where('status', 'pending')
                    ->groupStart()
                        ->where('scheduled_at', null)
                        ->orWhere('scheduled_at <=', date('Y-m-d H:i:s'))
                    ->groupEnd()
                    ->orderBy('priority', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll($limit);
    }

    public function getEmailsForRetry(int $limit)
    {
        return $this->where('status', 'failed')
                    ->where('attempts <', 'max_attempts', false)
                    ->orderBy('priority', 'ASC')
                    ->orderBy('last_attempt_at', 'ASC')
                    ->findAll($limit);
    }
}
