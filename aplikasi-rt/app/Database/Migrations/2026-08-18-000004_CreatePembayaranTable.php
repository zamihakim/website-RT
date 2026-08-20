<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT UNSIGNED', 'auto_increment' => true],
            'warga_id'      => ['type' => 'INT UNSIGNED', 'null' => false],
            'iuran_id'      => ['type' => 'INT UNSIGNED', 'null' => false],
            'periode'       => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => false],
            'nominal'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => false],
            'tanggal_bayar' => ['type' => 'DATE', 'null' => false],
            'metode'        => ['type' => "ENUM('tunai','transfer')", 'null' => false, 'default' => 'tunai'],
            'bukti'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'catatan'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'        => ['type' => "ENUM('lunas','tertunda')", 'null' => false, 'default' => 'lunas'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['warga_id', 'periode'], false, true, 'uq_warga_periode');
        $this->forge->addForeignKey('warga_id', 'warga', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('iuran_id', 'pengaturan_iuran', 'id');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
    }
}
