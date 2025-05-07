<?php

namespace App\Models;

use CodeIgniter\Model;

class PrapkModel extends Model
{
    protected $table = 'tabel_prapk';
    protected $allowedFields = ['no', 'kategori', 'kompetensi'];
}
