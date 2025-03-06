<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanModel extends Model
{
    protected $table = 'tabel_pengadaan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'nama_pengadaan', 'ref_tabel', 'ppk', 'pokja_pp', 'nama_penyedia',
        'tanggal_mulai', 'tanggal_berakhir', 'dokumen_terunggah', 'total_dokumen'
    ];
    protected $useTimestamps = true;

    // Fungsi untuk mengambil semua data pengadaan
    public function getAllPengadaan()
    {
        return $this->findAll();
    }

    // Fungsi untuk mendapatkan pengadaan berdasarkan ID
    public function getPengadaanById($id)
    {
        return $this->find($id);
    }

    
    public function jumlah_pengadaan()
{
    return $this->db->table($this->table)->countAllResults();
}
}
