<?php

namespace App\Models;

use CodeIgniter\Model;

class Pk3Model extends Model
{
    protected $table = 'tabel_pk3';
    protected $allowedFields = ['no', 'kategori', 'kompetensi'];

    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
}


