<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSmtpServers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'host' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'port' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'encryption' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'tls', 'ssl'],
                'default'    => 'none',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'from_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'from_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'priority' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'daily_sent' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'hourly_sent' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'max_daily' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1000,
            ],
            'max_hourly' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
            ],
            'connection_pooling' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('smtp_servers');
    }

    public function down()
    {
        $this->forge->dropTable('smtp_servers');
    }
}
