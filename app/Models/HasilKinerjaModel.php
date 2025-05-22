<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilKinerjaModel extends Model
{
    protected $table = 'tabel_hasil_kinerja';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_id', 'kinerja_id', 'tahun', 'bulan', 'berkas', 'nilai', 'hasil', 'status', 'catatan_karu'];
    protected $useTimestamps = false;


    public function update_status_by_hasil_id($hasil_id, $status, $catatanKaru)
    {
        return $this->where('id', $hasil_id)
            ->set([
                'status' => $status,
                'catatan_karu' => $catatanKaru
            ])
            ->update();
    }



}


