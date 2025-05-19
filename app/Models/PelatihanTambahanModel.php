<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanTambahanModel extends Model
{
    protected $table      = 'tabel_pelatihan_tambahan';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'user_id',
        'judul',
        'jenis',
        'lokasi',
        'tanggal',
        'berlaku',
        'sertifikat',
        'created_at'
    ];

    protected $useTimestamps = false; // Karena created_at pakai CURRENT_TIMESTAMP dari DB, bukan sistem CI4

    // Jika ingin menambahkan validasi bisa digunakan:
    // protected $validationRules = [];
    // protected $validationMessages = [];
}
