<?php

namespace App\Models;

use CodeIgniter\Model;

class DaftarFormModel extends Model
{
    protected $table = 'tabel_daftar_form';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nama', 'user_id', 'mentor_id', 'tanggal_mulai', 'tanggal_berakhir'];
    protected $useTimestamps = false;
    public function getIdByUserId(int $userId): array
    {
        $ids = $this->select('id')
            ->where('user_id', $userId)
            ->findColumn('id');
        return $ids ?? [];
    }

    /**
     * Ambil semua ID form berdasarkan mentor_id
     *
     * @param int $mentorId
     * @return int[] Array of form IDs (empty if none)
     */
    public function getIdByMentorId(int $mentorId): array
    {
        $ids = $this->select('id')
            ->where('mentor_id', $mentorId)
            ->findColumn('id');
        return $ids ?? [];
    }
}
