<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModel extends Model
{
    protected $table = 'tabel_votes_inovasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'inovasi_id', 'vote', 'created_at'];
    protected $useTimestamps = false;
}
