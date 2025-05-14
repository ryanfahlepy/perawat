<?php

namespace App\Models;

use CodeIgniter\Model;

class Pk2Model extends Model
{
    protected $table = 'tabel_pk2';
    
    protected $allowedFields = ['no', 'kategori', 'kompetensi'];
}
