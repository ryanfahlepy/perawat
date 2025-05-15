<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tabel_user';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\Output';
    protected $allowedFields = ['photo', 'nama', 'username', 'password', 'level_user', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    public function getUserById($id)
    {
        return $this->find($id);
    }


    public function getUser($username = false)
    {
        $this->builder()->select('tabel_user.*, tabel_user_level.nama_level');
        if ($username) {
            return $this->builder()->join('tabel_user_level', 'tabel_user_level.id = tabel_user.level_user')
                ->where('username', $username)->get();
        } else {
            return $this->builder()->join('tabel_user_level', 'tabel_user_level.id = tabel_user.level_user')->get();
        }
    }

    public function getUserByLevel($level_ids = [])
    {
        $this->builder()
            ->select('tabel_user.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_user.level_user');

        if (!empty($level_ids)) {
            $this->builder()->whereIn('tabel_user.level_user', $level_ids);
        }

        $this->builder()->orderBy('tabel_user.level_user', 'ASC'); // Urutkan berdasarkan level

        return $this->builder()->get();
    }


    public function getNamaByUsername($username)
    {
        return $this->select('nama')
            ->where('username', $username)
            ->first(); // return satu baris
    }

    public function getMenteesWithLevelName($mentorId)
    {
        return $this->select('tabel_user.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user.level_user = tabel_user_level.id', 'left')
            ->join('tabel_user_mentor_akses', 'tabel_user.id = tabel_user_mentor_akses.user_id')
            ->where('tabel_user_mentor_akses.mentor_id', $mentorId)
            ->whereIn('tabel_user.level_user', [3, 4, 5, 6]);
    }

}
