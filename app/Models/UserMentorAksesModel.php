<?php

namespace App\Models;

use CodeIgniter\Model;

class UserMentorAksesModel extends Model
{
    protected $table = 'tabel_user_mentor_akses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'mentor_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;    
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

}
