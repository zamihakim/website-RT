<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->seedUsers();
        $this->seedWarga();
        $this->seedPengaturanIuran();
        $this->seedKategoriPengeluaran();
        $this->seedPembayaran();
        $this->seedPengeluaran();
    }

    private function seedUsers()
    {
        $data = [
            [
                'nama'       => 'H. Slamet Riyadi',
                'email'      => 'ketua@rt012.id',
                'password'   => '$2y$10$dKNrz19iKaWJ.TrodmdsJexN.WefT0ZGcH9VE5DiaTO/S3o83y5G6',
                'role'       => 'pengurus',
                'aktif'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Budi Santoso',
                'email'      => 'budi@rt012.id',
                'password'   => '$2y$10$dKNrz19iKaWJ.TrodmdsJexN.WefT0ZGcH9VE5DiaTO/S3o83y5G6',
                'role'       => 'warga',
                'aktif'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Citra Lestari',
                'email'      => 'citra@rt012.id',
                'password'   => '$2y$10$dKNrz19iKaWJ.TrodmdsJexN.WefT0ZGcH9VE5DiaTO/S3o83y5G6',
                'role'       => 'warga',
                'aktif'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->db->table('users');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }

    private function seedWarga()
    {
        $data = [
            [
                'user_id'    => 2,
                'nama'       => 'Budi Santoso',
                'no_rumah'   => '02',
                'alamat'     => 'Jl. Melati No. 02',
                'no_hp'      => '081234567802',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 3,
                'nama'       => 'Citra Lestari',
                'no_rumah'   => '03',
                'alamat'     => 'Jl. Melati No. 03',
                'no_hp'      => '081234567803',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => null,
                'nama'       => 'Ahmad Fauzi',
                'no_rumah'   => '01',
                'alamat'     => 'Jl. Melati No. 01',
                'no_hp'      => '081234567801',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => null,
                'nama'       => 'Dedi Kurniawan',
                'no_rumah'   => '04',
                'alamat'     => 'Jl. Melati No. 04',
                'no_hp'      => '081234567804',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => null,
                'nama'       => 'Eka Wijaya',
                'no_rumah'   => '05',
                'alamat'     => 'Jl. Melati No. 05',
                'no_hp'      => '081234567805',
                'status'     => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->db->table('warga');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }

    private function seedPengaturanIuran()
    {
        $data = [
            [
                'nominal'       => 50000.00,
                'berlaku_mulai' => '2026-01-01',
                'keterangan'    => 'Januari',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 60000.00,
                'berlaku_mulai' => '2026-02-01',
                'keterangan'    => 'Februari',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 70000.00,
                'berlaku_mulai' => '2026-03-01',
                'keterangan'    => 'Maret',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 80000.00,
                'berlaku_mulai' => '2026-04-01',
                'keterangan'    => 'April',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 90000.00,
                'berlaku_mulai' => '2026-05-01',
                'keterangan'    => 'Mei',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 100000.00,
                'berlaku_mulai' => '2026-06-01',
                'keterangan'    => 'Juni',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 110000.00,
                'berlaku_mulai' => '2026-07-01',
                'keterangan'    => 'Juli',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nominal'       => 120000.00,
                'berlaku_mulai' => '2026-08-01',
                'keterangan'    => 'Agustus',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->db->table('pengaturan_iuran');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }

    private function seedKategoriPengeluaran()
    {
        $data = [
            [
                'nama'       => 'Kas',
                'keterangan' => 'Pembelanjaan rutin / inventaris RT',
            ],
            [
                'nama'       => 'Sosial',
                'keterangan' => 'Santunan, kegiatan sosial, keagamaan',
            ],
            [
                'nama'       => 'Konsumsi',
                'keterangan' => 'Konsumsi rapat dan arisan',
            ],
        ];

        $table = $this->db->table('kategori_pengeluaran');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }

    private function seedPembayaran()
    {
        $data = [
            ['warga_id' => 1, 'iuran_id' => 1, 'periode' => '2026-01', 'nominal' => 50000.00,  'tanggal_bayar' => '2026-01-05', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 2, 'iuran_id' => 1, 'periode' => '2026-01', 'nominal' => 50000.00,  'tanggal_bayar' => '2026-01-06', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 3, 'iuran_id' => 1, 'periode' => '2026-01', 'nominal' => 50000.00,  'tanggal_bayar' => '2026-01-07', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 4, 'iuran_id' => 1, 'periode' => '2026-01', 'nominal' => 50000.00,  'tanggal_bayar' => '2026-01-08', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 5, 'iuran_id' => 1, 'periode' => '2026-01', 'nominal' => 50000.00,  'tanggal_bayar' => '2026-01-10', 'metode' => 'tunai', 'status' => 'lunas'],

            ['warga_id' => 1, 'iuran_id' => 2, 'periode' => '2026-02', 'nominal' => 60000.00,  'tanggal_bayar' => '2026-02-03', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 2, 'iuran_id' => 2, 'periode' => '2026-02', 'nominal' => 60000.00,  'tanggal_bayar' => '2026-02-05', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 3, 'iuran_id' => 2, 'periode' => '2026-02', 'nominal' => 60000.00,  'tanggal_bayar' => '2026-02-06', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 4, 'iuran_id' => 2, 'periode' => '2026-02', 'nominal' => 60000.00,  'tanggal_bayar' => '2026-02-08', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 5, 'iuran_id' => 2, 'periode' => '2026-02', 'nominal' => 60000.00,  'tanggal_bayar' => '2026-02-10', 'metode' => 'tunai', 'status' => 'lunas'],

            ['warga_id' => 1, 'iuran_id' => 3, 'periode' => '2026-03', 'nominal' => 70000.00,  'tanggal_bayar' => '2026-03-04', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 2, 'iuran_id' => 3, 'periode' => '2026-03', 'nominal' => 70000.00,  'tanggal_bayar' => '2026-03-05', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 3, 'iuran_id' => 3, 'periode' => '2026-03', 'nominal' => 70000.00,  'tanggal_bayar' => '2026-03-07', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 4, 'iuran_id' => 3, 'periode' => '2026-03', 'nominal' => 70000.00,  'tanggal_bayar' => '2026-03-09', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 5, 'iuran_id' => 3, 'periode' => '2026-03', 'nominal' => 70000.00,  'tanggal_bayar' => '2026-03-10', 'metode' => 'tunai', 'status' => 'lunas'],

            ['warga_id' => 1, 'iuran_id' => 4, 'periode' => '2026-04', 'nominal' => 80000.00,  'tanggal_bayar' => '2026-04-03', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 2, 'iuran_id' => 4, 'periode' => '2026-04', 'nominal' => 80000.00,  'tanggal_bayar' => '2026-04-05', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 3, 'iuran_id' => 4, 'periode' => '2026-04', 'nominal' => 80000.00,  'tanggal_bayar' => '2026-04-06', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 4, 'iuran_id' => 4, 'periode' => '2026-04', 'nominal' => 80000.00,  'tanggal_bayar' => '2026-04-08', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 5, 'iuran_id' => 4, 'periode' => '2026-04', 'nominal' => 80000.00,  'tanggal_bayar' => '2026-04-09', 'metode' => 'tunai', 'status' => 'lunas'],

            ['warga_id' => 1, 'iuran_id' => 5, 'periode' => '2026-05', 'nominal' => 90000.00,  'tanggal_bayar' => '2026-05-02', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 2, 'iuran_id' => 5, 'periode' => '2026-05', 'nominal' => 90000.00,  'tanggal_bayar' => '2026-05-04', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 3, 'iuran_id' => 5, 'periode' => '2026-05', 'nominal' => 90000.00,  'tanggal_bayar' => '2026-05-06', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 4, 'iuran_id' => 5, 'periode' => '2026-05', 'nominal' => 90000.00,  'tanggal_bayar' => '2026-05-07', 'metode' => 'tunai', 'status' => 'lunas'],
            ['warga_id' => 5, 'iuran_id' => 5, 'periode' => '2026-05', 'nominal' => 90000.00,  'tanggal_bayar' => '2026-05-09', 'metode' => 'tunai', 'status' => 'lunas'],
        ];

        $table = $this->db->table('pembayaran');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }

    private function seedPengeluaran()
    {
        $data = [
            [
                'kategori_id' => 1,
                'tanggal'     => '2026-08-10',
                'jumlah'      => 1000000.00,
                'keterangan'  => 'Perbaikan pos kamling',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'kategori_id' => 2,
                'tanggal'     => '2026-08-12',
                'jumlah'      => 1750000.00,
                'keterangan'  => 'Santunan warga sakit',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'kategori_id' => 3,
                'tanggal'     => '2026-08-15',
                'jumlah'      => 500000.00,
                'keterangan'  => 'Konsumsi rapat bulanan RT',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->db->table('pengeluaran');
        foreach ($data as $row) {
            $table->insert($row);
        }
    }
}
