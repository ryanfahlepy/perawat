<?php

namespace App\Models;

use CodeIgniter\Model;

class PicaKinerjaModel extends Model
{
    protected $table = 'tabel_pica_kinerja';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_id', 'kinerja_id', 'hasil_id', 'problem_identification', 'corrective_action', 'due_date', 'catatan_karu', 'status'];
    protected $useTimestamps = false;

    public function update_status_pica($hasil_id, $status, $catatanKaruPica)
    {
        return $this->where('hasil_id', $hasil_id)
            ->set([
                'status' => $status,
                'catatan_karu' => $catatanKaruPica
            ])
            ->update();
    }

}
