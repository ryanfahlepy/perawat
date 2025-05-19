<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanHasilModel extends Model
{
    protected $table = 'tabel_pelatihan_hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'pelatihan_id', 'judul', 'lokasi', 'tanggal', 'tanggal', 'sertifikat','created_at'];
}
