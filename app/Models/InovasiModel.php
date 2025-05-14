<?php

namespace App\Models;

use CodeIgniter\Model;

class InovasiModel extends Model
{
    protected $table = 'tabel_inovasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'judul', 'deskripsi', 'lampiran', 'status', 'created_at'];
    protected $useTimestamps = false;
}
