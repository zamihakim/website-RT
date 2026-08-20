<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriPengeluaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false, 'unique' => true],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_pengeluaran');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_pengeluaran');
    }
}
