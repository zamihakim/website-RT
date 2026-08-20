<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengeluaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'kategori_id' => ['type' => 'INT UNSIGNED', 'null' => false],
            'tanggal'     => ['type' => 'DATE', 'null' => false],
            'jumlah'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => false],
            'keterangan'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'foto_bukti'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_pengeluaran', 'id');
        $this->forge->createTable('pengeluaran');
    }

    public function down()
    {
        $this->forge->dropTable('pengeluaran');
    }
}
