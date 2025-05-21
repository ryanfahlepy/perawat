<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'tabel_notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_tujuan_id', 'pesan', 'status', 'url', 'created_at'];
    protected $useTimestamps = false;

    public function tambahNotifikasi($pesan)
    {
        return $this->insert([
            'pesan' => $pesan,
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }


}
