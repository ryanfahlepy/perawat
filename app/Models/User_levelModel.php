<?php

namespace App\Models;

use CodeIgniter\Model;

class User_levelModel extends Model
{
    protected $table = 'tabel_user_level';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_level', 'keterangan'];
    protected $returnType = 'App\Entities\Output';

    public function findByLevel($level_ids = [])
    {
        return $this->whereIn('id', $level_ids)->findAll();
    }
}

