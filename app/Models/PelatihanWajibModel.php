<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanWajibModel extends Model
{
    protected $table      = 'tabel_pelatihan';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['kategori', 'level', 'created_at'];

    protected $useTimestamps = false; // karena Anda menggunakan `timestamp` manual, bukan `created_at` dan `updated_at` CI4;

    public function getPelatihanIdByKategori($kategori)
    {
        // Mengambil pelatihan_id berdasarkan kategori
        return $this->where('kategori', $kategori)->first()['id'] ?? null;
    }

}
