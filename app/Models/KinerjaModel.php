<?php

namespace App\Models;

use CodeIgniter\Model;

class KinerjaModel extends Model
{
    protected $table = 'tabel_kinerja';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'indikator',
        'kode_kpi',
        'kategori',
        'formula',
        'sumber_data',
        'periode_assesment',
        'bobot',
        'target',
        'deskripsi_target',
        'hasil_aktual',
        'level_user'
    ];

    protected $useTimestamps = false;
}


