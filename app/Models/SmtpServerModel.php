<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\SmtpServer;

class SmtpServerModel extends Model
{
    protected $table            = 'smtp_servers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = SmtpServer::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_email',
        'from_name',
        'priority',
        'is_active',
        'daily_sent',
        'hourly_sent',
        'max_daily',
        'max_hourly',
        'connection_pooling',
        'last_used_at',
        'auth_type',
        'oauth_provider',
        'client_id',
        'client_secret',
        'access_token',
        'refresh_token',
        'expires_at',
        'oauth_scopes',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveServersOrdered()
    {
        return $this->where('is_active', 1)
                    ->orderBy('priority', 'ASC')
                    ->orderBy('daily_sent', 'ASC')
                    ->findAll();
    }

    public function incrementCounters(int $id)
    {
        return $this->where('id', $id)
                    ->set('daily_sent', 'daily_sent + 1', false)
                    ->set('hourly_sent', 'hourly_sent + 1', false)
                    ->set('last_used_at', date('Y-m-d H:i:s'))
                    ->update();
    }

    public function resetDailyCounters()
    {
        return $this->where('id >', 0)
                    ->set('daily_sent', 0)
                    ->update();
    }

    public function resetHourlyCounters()
    {
        return $this->where('id >', 0)
                    ->set('hourly_sent', 0)
                    ->update();
    }
}
