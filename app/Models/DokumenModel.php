<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table;
    protected $primaryKey = 'id_dokumen';
    protected $allowedFields = ['id_dokumen', 'dokumen'];

    public function __construct($table = null)
    {
        parent::__construct();
        if ($table) {
            $this->table = $table;
        }
    }

    public function getDokumenByPengadaan($id)
    {
        return $this->where('ref_tabel', $id)->findAll();
    }
}
