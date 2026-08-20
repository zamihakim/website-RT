<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'aktif',
    ];

    protected $useTimestamps = true;

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->insert($data);
        return $this->insertID();
    }
}
