<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'warga_id',
        'iuran_id',
        'periode',
        'nominal',
        'tanggal_bayar',
        'metode',
        'bukti',
        'catatan',
        'catatan_tolak',
        'status',
    ];

    protected $useTimestamps = false;

    public function getStatusPeriode(string $periode): array
    {
        return $this->select('pembayaran.*, warga.nama, warga.no_rumah')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->where('pembayaran.periode', $periode)
            ->orderBy('warga.no_rumah', 'ASC')
            ->findAll();
    }

    public function getWargaPaidPeriode(string $periode): array
    {
        return $this->select('pembayaran.warga_id')
            ->where('periode', $periode)
            ->where('status', 'lunas')
            ->findAll();
    }

    public function getTotalByPeriode(string $periode): float
    {
        $result = $this->where('periode', $periode)
            ->where('status', 'lunas')
            ->selectSum('nominal')
            ->first();
        return (float)($result['nominal'] ?? 0);
    }

    public function countPaidByPeriode(string $periode): int
    {
        return $this->where('periode', $periode)
            ->where('status', 'lunas')
            ->countAllResults();
    }

    public function getHistoryByUserId(int $userId): array
    {
        return $this->select('pembayaran.*, pengaturan_iuran.nominal as iuran_nominal')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->join('pengaturan_iuran', 'pengaturan_iuran.id = pembayaran.iuran_id')
            ->where('warga.user_id', $userId)
            ->orderBy('pembayaran.periode', 'DESC')
            ->findAll();
    }

    public function getUserStatusPeriode(int $userId, string $periode)
    {
        return $this->select('pembayaran.*')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->where('warga.user_id', $userId)
            ->where('pembayaran.periode', $periode)
            ->first();
    }

    public function getAllByUserId(int $userId): array
    {
        return $this->select('pembayaran.*, pengaturan_iuran.nominal as iuran_nominal')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->join('pengaturan_iuran', 'pengaturan_iuran.id = pembayaran.iuran_id')
            ->where('warga.user_id', $userId)
            ->orderBy('pembayaran.periode', 'DESC')
            ->findAll();
    }

    public function findWargaIdByUserId(int $userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('warga');
        $builder->where('user_id', $userId);
        $row = $builder->get()->getRowArray();
        return $row ? $row['id'] : null;
    }
}
