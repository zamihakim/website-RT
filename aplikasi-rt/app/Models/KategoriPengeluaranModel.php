<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriPengeluaranModel extends Model
{
    protected $table            = 'kategori_pengeluaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama',
        'keterangan',
    ];

    protected $useTimestamps = false;
}
