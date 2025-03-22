<?php

namespace App\Models;

use CodeIgniter\Model;

class MetodeModel extends Model
{
    protected $table = 'tabel_ref_metode';
    protected $primaryKey = 'id';
    protected $allowedFields = ['metode'];
}
