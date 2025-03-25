<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table                = 'tabel_user';
    protected $primaryKey           = 'id';
    protected $returnType           = 'App\Entities\Output';
    protected $allowedFields        = ['photo', 'nama', 'username', 'password', 'level_user', 'status'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';

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
}
