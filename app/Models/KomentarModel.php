<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentarModel extends Model
{
    protected $table = 'tabel_komentar_inovasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'inovasi_id', 'komentar', 'created_at'];
    protected $useTimestamps = false;
}
