<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $password = 'admin123';
        $apiKey   = bin2hex(random_bytes(32)); // 64 chars

        $data = [
            'username'   => 'admin',
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'api_key'    => $apiKey,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);

        echo "\nInitial Admin Created:\n";
        echo "Username: admin\n";
        echo "Password: {$password}\n";
        echo "API Key:  {$apiKey}\n\n";
    }
}
