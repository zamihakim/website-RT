<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'role'       => ['type' => "ENUM('pengurus','warga')", 'null' => false, 'default' => 'warga'],
            'aktif'      => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
