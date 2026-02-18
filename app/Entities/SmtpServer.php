<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SmtpServer extends Entity
{
    protected $datamap = [];
    protected $dates   = ['last_used_at', 'expires_at', 'created_at', 'updated_at'];
    protected $casts   = [
        'is_active'          => 'boolean',
        'connection_pooling' => 'boolean',
        'port'               => 'integer',
        'priority'           => 'integer',
        'daily_sent'         => 'integer',
        'hourly_sent'        => 'integer',
        'max_daily'          => 'integer',
        'max_hourly'         => 'integer',
    ];
}
