<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilKinerjaModel extends Model
{
    protected $table = 'tabel_hasil_kinerja';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_id', 'kinerja_id', 'tahun', 'bulan', 'berkas' ,  'hasil',  'status', 'catatan_karu'];
    protected $useTimestamps = false;
}
