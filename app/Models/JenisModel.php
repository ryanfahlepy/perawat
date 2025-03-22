<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisModel extends Model
{
    protected $table = 'tabel_ref_jenis';
    protected $primaryKey = 'id';
    protected $allowedFields = ['jenis'];
}
