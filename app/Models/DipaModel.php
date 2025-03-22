<?php

namespace App\Models;

use CodeIgniter\Model;

class DipaModel extends Model
{
    protected $table = 'tabel_ref_dipa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['dipa'];
}
