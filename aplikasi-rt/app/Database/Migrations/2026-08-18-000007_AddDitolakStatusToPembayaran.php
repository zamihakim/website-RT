<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDitolakStatusToPembayaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $db->query("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('lunas','tertunda','ditolak') NOT NULL DEFAULT 'lunas'");
        $db->query("ALTER TABLE pembayaran ADD COLUMN catatan_tolak VARCHAR(255) DEFAULT NULL AFTER catatan");
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $db->query("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('lunas','tertunda') NOT NULL DEFAULT 'lunas'");
        $db->query("ALTER TABLE pembayaran DROP COLUMN catatan_tolak");
    }
}
