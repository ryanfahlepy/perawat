<?php

namespace App\Models;

use CodeIgniter\Model;

class PersetujuanInovasiModel extends Model
{
    protected $table = 'tabel_persetujuan_inovasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'inovasi_id', 'status', 'catatan', 'created_at'];
    protected $useTimestamps = false;
}
