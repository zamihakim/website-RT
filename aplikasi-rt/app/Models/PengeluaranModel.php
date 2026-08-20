<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table            = 'pengeluaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'kategori_id',
        'tanggal',
        'jumlah',
        'keterangan',
        'foto_bukti',
    ];

    protected $useTimestamps = false;

    public function getAll(): array
    {
        return $this->select('pengeluaran.*, kategori_pengeluaran.nama as kategori_nama')
            ->join('kategori_pengeluaran', 'kategori_pengeluaran.id = pengeluaran.kategori_id')
            ->orderBy('pengeluaran.tanggal', 'DESC')
            ->findAll();
    }

    public function getByPeriode(string $periode): array
    {
        return $this->select('pengeluaran.*, kategori_pengeluaran.nama as kategori_nama')
            ->join('kategori_pengeluaran', 'kategori_pengeluaran.id = pengeluaran.kategori_id')
            ->where("DATE_FORMAT(pengeluaran.tanggal, '%Y-%m')", $periode)
            ->orderBy('pengeluaran.tanggal', 'DESC')
            ->findAll();
    }

    public function getTotalByPeriode(string $periode): float
    {
        $result = $this->where("DATE_FORMAT(pengeluaran.tanggal, '%Y-%m')", $periode)
            ->selectSum('jumlah')
            ->first();
        return (float)($result['jumlah'] ?? 0);
    }

    public function getTotalByKategoriPeriode(string $periode): array
    {
        return $this->select('kategori_pengeluaran.nama as kategori, SUM(pengeluaran.jumlah) as total')
            ->join('kategori_pengeluaran', 'kategori_pengeluaran.id = pengeluaran.kategori_id')
            ->where("DATE_FORMAT(pengeluaran.tanggal, '%Y-%m')", $periode)
            ->groupBy('pengeluaran.kategori_id')
            ->findAll();
    }
}
