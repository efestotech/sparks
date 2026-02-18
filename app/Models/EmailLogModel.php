<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\EmailLog;

class EmailLogModel extends Model
{
    protected $table            = 'email_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = EmailLog::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email_queue_id',
        'smtp_server_id',
        'event_type',
        'message',
        'smtp_code',
        'processing_time_ms',
        'created_at',
    ];

    protected $useTimestamps = false; // Manually handled as per schema

    public function logEvent($queueId, $smtpId, $type, $message, $smtpCode = null, $timeMs = null)
    {
        return $this->insert([
            'email_queue_id'     => $queueId,
            'smtp_server_id'     => $smtpId,
            'event_type'         => $type,
            'message'            => $message,
            'smtp_code'          => $smtpCode,
            'processing_time_ms' => $timeMs,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);
    }
}
