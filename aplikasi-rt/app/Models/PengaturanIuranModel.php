<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanIuranModel extends Model
{
    protected $table            = 'pengaturan_iuran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'nominal',
        'berlaku_mulai',
        'keterangan',
    ];

    protected $useTimestamps = false;

    public function getBerjalan()
    {
        return $this->where('berlaku_mulai <=', date('Y-m-d'))
            ->orderBy('berlaku_mulai', 'DESC')
            ->first();
    }

    public function getByPeriode(string $periode)
    {
        return $this->where('berlaku_mulai <=', $periode . '-01')
            ->orderBy('berlaku_mulai', 'DESC')
            ->first();
    }

    public function getRiwayat()
    {
        return $this->orderBy('berlaku_mulai', 'DESC')->findAll();
    }
}
