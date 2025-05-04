<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'tabel_notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pesan', 'status', 'created_at'];
    protected $useTimestamps = false; // kalau pakai 'created_at' manual

    public function tambahNotifikasi($pesan)
    {
        return $this->insert([
            'pesan' => $pesan,
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    
}
