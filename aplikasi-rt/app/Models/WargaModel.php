<?php

namespace App\Models;

use CodeIgniter\Model;

class WargaModel extends Model
{
    protected $table            = 'warga';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'nama',
        'no_rumah',
        'alamat',
        'no_hp',
        'status',
    ];

    protected $useTimestamps = true;

    public function findByUserId(int $userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
