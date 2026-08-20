<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturanIuranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'nominal'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => false],
            'berlaku_mulai' => ['type' => 'DATE', 'null' => false],
            'keterangan'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengaturan_iuran');
    }

    public function down()
    {
        $this->forge->dropTable('pengaturan_iuran');
    }
}
