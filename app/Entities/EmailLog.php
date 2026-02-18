<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EmailLog extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at'];
    protected $casts   = [
        'email_queue_id'     => 'integer',
        'smtp_server_id'     => 'integer',
        'processing_time_ms' => 'integer',
    ];
}
