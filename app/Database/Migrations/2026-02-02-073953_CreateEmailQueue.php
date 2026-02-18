<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailQueue extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'smtp_server_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'priority' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'default'    => 5,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'sent', 'failed', 'cancelled'],
                'default'    => 'pending',
            ],
            'to_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'to_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'body_html' => [
                'type' => 'LONGTEXT',
            ],
            'custom_headers' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'attachments' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'attempts' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'default'    => 0,
            ],
            'max_attempts' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'default'    => 3,
            ],
            'last_attempt_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'webhook_success_called' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'webhook_failed_called' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('smtp_server_id');
        $this->forge->addKey('to_email');
        $this->forge->addKey('scheduled_at');
        $this->forge->addKey('status');
        $this->forge->createTable('email_queue');
    }

    public function down()
    {
        $this->forge->dropTable('email_queue');
    }
}
