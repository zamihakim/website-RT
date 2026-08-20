<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWargaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'user_id'    => ['type' => 'INT UNSIGNED', 'null' => true, 'default' => null],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'no_rumah'   => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false, 'unique' => true],
            'alamat'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'no_hp'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status'     => ['type' => "ENUM('aktif','nonaktif')", 'null' => false, 'default' => 'aktif'],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('warga');
    }

    public function down()
    {
        $this->forge->dropTable('warga');
    }
}
