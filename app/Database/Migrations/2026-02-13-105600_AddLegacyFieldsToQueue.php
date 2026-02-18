<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLegacyFieldsToQueue extends Migration
{
    public function up()
    {
        $fields = [
            'legacy_id_a' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'smtp_server_id'
            ],
            'legacy_id_b' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'legacy_id_a'
            ],
        ];
        $this->forge->addColumn('email_queue', $fields);
        $this->db->query('CREATE INDEX legacy_lookup ON email_queue (legacy_id_a, legacy_id_b)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX legacy_lookup ON email_queue');
        $this->forge->dropColumn('email_queue', 'legacy_id_a');
        $this->forge->dropColumn('email_queue', 'legacy_id_b');
    }
}
