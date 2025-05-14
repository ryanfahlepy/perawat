<?php

namespace App\Models;

use CodeIgniter\Model;

class Pk1Model extends Model
{
    protected $table = 'tabel_pk1';
    protected $allowedFields = ['no', 'kategori', 'kompetensi'];
}
