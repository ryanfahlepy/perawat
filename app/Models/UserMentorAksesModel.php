<?php

namespace App\Models;

use CodeIgniter\Model;

class UserMentorAksesModel extends Model
{
    protected $table = 'tabel_user_mentor_akses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'mentor_id', 'tanggal_mulai', 'tanggal_berakhir', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getMentorIdByUserId($userId)
    {
        return $this->where(['user_id' => $userId])->first();
    }

    public function getMentorId($userId)
    {
        $data = $this->where('user_id', $userId)->first();

        // Jika data dalam bentuk array, akses pakai array
        return isset($data['mentor_id']) ? $data['mentor_id'] : null;
    }






}
