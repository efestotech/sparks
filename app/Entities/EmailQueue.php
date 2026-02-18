<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EmailQueue extends Entity
{
    protected $datamap = [];
    protected $dates   = ['scheduled_at', 'last_attempt_at', 'sent_at', 'created_at', 'updated_at'];
    protected $casts   = [
        'user_id'                => 'integer',
        'smtp_server_id'         => 'integer',
        'priority'               => 'integer',
        'attempts'               => 'integer',
        'max_attempts'           => 'integer',
        'webhook_success_called' => 'boolean',
        'webhook_failed_called'  => 'boolean',
        'custom_headers'         => 'json',
        'attachments'            => 'json',
        'legacy_id_a'            => 'integer',
        'legacy_id_b'            => 'integer',
    ];
}
