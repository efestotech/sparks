<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOAuthFieldsToSmtpServers extends Migration
{
    public function up()
    {
        $fields = [
            'auth_type' => [
                'type'       => 'ENUM',
                'constraint' => ['basic', 'oauth2'],
                'default'    => 'basic',
                'after'      => 'encryption'
            ],
            'oauth_provider' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'microsoft', 'google', 'dummy'],
                'default'    => 'none',
                'after'      => 'auth_type'
            ],
            'client_id' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'oauth_provider'
            ],
            'client_secret' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'client_id'
            ],
            'access_token' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'client_secret'
            ],
            'refresh_token' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'access_token'
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'refresh_token'
            ],
            'oauth_scopes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'expires_at'
            ],
        ];
        $this->forge->addColumn('smtp_servers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('smtp_servers', [
            'auth_type',
            'oauth_provider',
            'client_id',
            'client_secret',
            'access_token',
            'refresh_token',
            'expires_at',
            'oauth_scopes'
        ]);
    }
}
