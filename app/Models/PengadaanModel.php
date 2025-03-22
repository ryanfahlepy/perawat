<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanModel extends Model
{
    // Tentukan nama tabel yang digunakan
    protected $table = 'tabel_pengadaan';


    // Kolom yang dapat diubah pada setiap tabel
    protected $allowedFieldsPerencanaan = [
        'id',
        'tahun_anggaran',
        'dipa',
        'jenis',
        'metode',
        'kode_rup',
        'nama_pengadaan',
        'perencanaan',
        'pelaksanaan',
        'pembayaran'
    ];

    // Fungsi untuk mengambil semua data dari tabel tertentu
    public function getAll($table)
    {
        return $this->db->table($table)->get()->getResultArray();
    }

    public function getAllByColumn($where = [])
    {
        // $this->where(...) otomatis pakai builder di balik layar
        return $this->where($where)
            ->orderBy('id', 'DESC')
            ->findAll(); // hasilnya sudah array
    }

    // Fungsi untuk mengambil data per ID dari tabel tertentu
    public function getById($id)
    {
        return $this->where('id', $id)->get()->getRowArray();
    }

    // Fungsi untuk menambah data ke tabel tertentu
    public function insertData($data)
{
    if (!is_array($data)) {
        throw new \InvalidArgumentException('Parameter data harus berupa array.');
    }

    foreach ($data as $entry) {
        if (!is_array($entry)) {
            throw new \InvalidArgumentException('Setiap entry dalam data harus berupa array.');
        }
    }

    return $this->insertBatch($data); // langsung pakai fungsi bawaan model
}


    // Fungsi untuk menghapus data berdasarkan ID
    public function deletePengadaan($id)
    {
        return $this->delete($id); // otomatis pakai primary key
    }


    // Fungsi untuk memperbarui data di tabel tertentu
    public function updatePengadaan($id, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data harus berupa array.');
        }
    
        return $this->update($id, $data); // pakai fungsi bawaan model
    }
    


    //KESELURUHAN
    public function jumlah_pengadaan()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    public function total_perencanaan()
    {
        return $this->db->table($this->table)
            ->selectSum('perencanaan')
            ->get()
            ->getRow()
            ->perencanaan;
    }

    public function total_pelaksanaan()
    {
        return $this->db->table($this->table)
            ->selectSum('pelaksanaan')
            ->get()
            ->getRow()
            ->pelaksanaan;
    }

    public function total_pembayaran()
    {
        return $this->db->table($this->table)
            ->selectSum('pembayaran')
            ->get()
            ->getRow()
            ->pembayaran;
    }


    //BELANJA RUTIN

    public function jumlah_pengadaan_belanja_rutin()
    {

        return $this->db->table($this->table)
            ->where('dipa', 'DISINFOLAHTAL')
            ->countAllResults();
    }

    public function perencanaan_belanja_rutin()
    {
        return $this->db->table($this->table)
            ->selectSum('perencanaan')
            ->where('dipa', 'DISINFOLAHTAL')
            ->get()
            ->getRow()
            ->perencanaan;
    }

    public function pelaksanaan_belanja_rutin()
    {
        return $this->db->table($this->table)
            ->selectSum('pelaksanaan')
            ->where('dipa', 'DISINFOLAHTAL')
            ->get()
            ->getRow()
            ->pelaksanaan;
    }

    public function pembayaran_belanja_rutin()
    {
        return $this->db->table($this->table)
            ->selectSum('pembayaran')
            ->where('dipa', 'DISINFOLAHTAL')
            ->get()
            ->getRow()
            ->pembayaran;
    }

    //BELANJA MODAL
    public function jumlah_pengadaan_belanja_modal()
    {

        return $this->db->table($this->table)
            ->where('dipa', 'MABES TNI AL')
            ->countAllResults();
    }

    public function perencanaan_belanja_modal()
    {
        return $this->db->table($this->table)
            ->selectSum('perencanaan')
            ->where('dipa', 'MABES TNI AL')
            ->get()
            ->getRow()
            ->perencanaan;
    }

    public function pelaksanaan_belanja_modal()
    {
        return $this->db->table($this->table)
            ->selectSum('pelaksanaan')
            ->where('dipa', 'MABES TNI AL')
            ->get()
            ->getRow()
            ->pelaksanaan;
    }

    public function pembayaran_belanja_modal()
    {
        return $this->db->table($this->table)
            ->selectSum('pembayaran')
            ->where('dipa', 'MABES TNI AL')
            ->get()
            ->getRow()
            ->pembayaran;
    }

}
