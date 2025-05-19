<?php

namespace App\Models;

use CodeIgniter\Model;

class DaftarFormModel extends Model
{
    protected $table = 'tabel_daftar_form';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nama', 'user_id', 'mentor_id', 'tanggal_mulai', 'tanggal_berakhir'];
    protected $useTimestamps = false;
}
