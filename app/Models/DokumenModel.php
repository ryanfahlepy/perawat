<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table;
    protected $primaryKey = 'id_dokumen';
    protected $allowedFields = ['id_dokumen', 'no_urut', 'dokumen'];

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
    public function getAllCount($table)
{
    // Sesuaikan dengan query yang sesuai untuk masing-masing tabel
    return $this->db->table($table)->countAllResults();
}


        public function countAllResult($table)
        {
            $this->table = $table;  // Menetapkan nama tabel yang diterima sebagai parameter
            return $this->countAll(); // Menghitung semua data dalam tabel
        }

        public function getDokumenByTable($table)
    {
        return $this->db->table($table)->orderBy('no_urut', 'ASC')->get()->getResultArray();
    }

    public function addDokumen($table, $dokumen)
    {
        $lastOrder = $this->countAllResult($table);
        
        return $this->db->table($table)->insert([
            'no_urut' => $lastOrder + 1,
            'dokumen' => $dokumen
        ]);
    }

    


}        
