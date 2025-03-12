<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    // Tentukan nama tabel yang digunakan
    protected $tablePerencanaan = 'paket_perencanaan';
    protected $tablePelaksanaan = 'paket_pelaksanaan';
    protected $tablePembayaran = 'paket_pembayaran';

    // Kolom yang dapat diubah pada setiap tabel
    protected $allowedFieldsPerencanaan = [
        'tahun_anggaran',
        'kategori',
        'kode_rup',
        'nama_paket',
        'total_perencanaan',
        'pdn'
    ];

    protected $allowedFieldsPelaksanaan = [
        'tahun_anggaran',
        'kategori',
        'kode_rup',
        'nama_paket',
        'status_pelaksanaan',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $allowedFieldsPembayaran = [
        'tahun_anggaran',
        'kategori',
        'kode_rup',
        'nama_paket',
        'status_pembayaran',
        'jumlah_pembayaran'
    ];

    // Fungsi untuk mengambil semua data dari tabel tertentu
    public function getAll($table)
    {
        return $this->db->table($table)->get()->getResultArray();
    }

    public function getAllbyColumn($table, $where = [])
{
    $builder = $this->db->table($table);
    if (!empty($where)) {
        $builder->where($where);
    }
    $query = $builder->get();

    return $query->getResultArray(); // Jadi array, bukan stdClass
}

    // Fungsi untuk mengambil data per ID dari tabel tertentu
    public function getById($table, $id)
    {
        return $this->db->table($table)->where('id', $id)->get()->getRowArray();
    }

    // Fungsi untuk menambah data ke tabel tertentu
    public function insertData($table, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Parameter data harus berupa array.');
        }

        foreach ($data as $entry) {
            if (!is_array($entry)) {
                throw new \InvalidArgumentException('Setiap entry dalam data harus berupa array.');
            }
        }

        $this->db->table($table)->insertBatch($data);
    }

    // Fungsi untuk menghapus data berdasarkan ID
    public function deletePaket($table, $id)
    {
        return $this->db->table($table)->delete(['id' => $id]);
    }

    // Fungsi untuk memperbarui data di tabel tertentu
    public function updatePaket($table, $id, $data)
    {
        return $this->db->table($table)->update($data, ['id' => $id]);
    }

    public function jumlah_paket()
{
    return $this->db->table($this->tablePerencanaan)->countAllResults();
}

public function total_perencanaan()
{
    return $this->db->table($this->tablePerencanaan)
                    ->selectSum('total_perencanaan')
                    ->get()
                    ->getRow()
                    ->total_perencanaan;
}

public function total_pelaksanaan()
{
    return $this->db->table($this->tablePelaksanaan)
                    ->selectSum('total_pelaksanaan')
                    ->get()
                    ->getRow()
                    ->total_pelaksanaan;
}

public function total_pembayaran()
{
    return $this->db->table($this->tablePembayaran)
                    ->selectSum('total_pembayaran')
                    ->get()
                    ->getRow()
                    ->total_pembayaran;
}

}
