<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'key'   => 'global_max_attempts',
                'value' => '3',
                'type'  => 'int',
            ],
            [
                'key'   => 'chunk_size',
                'value' => '50',
                'type'  => 'int',
            ],
            [
                'key'   => 'retention_days',
                'value' => '365',
                'type'  => 'int',
            ],
            [
                'key'   => 'webhook_success_url',
                'value' => '',
                'type'  => 'string',
            ],
            [
                'key'   => 'webhook_failed_url',
                'value' => '',
                'type'  => 'string',
            ],
            [
                'key'   => 'worker_lock_timeout',
                'value' => '300',
                'type'  => 'int',
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }
}
