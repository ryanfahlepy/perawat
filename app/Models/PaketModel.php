<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    // Tentukan nama tabel yang digunakan
    protected $table = 'paket';


    // Kolom yang dapat diubah pada setiap tabel
    protected $allowedFieldsPerencanaan = [
        'id',
        'tahun_anggaran',
        'dipa',
        'jenis',
        'metode',
        'kode_rup',
        'nama_paket',
        'perencanaan',
        'pelaksanaan',
        'pembayaran'
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


    //KESELURUHAN
    public function jumlah_paket()
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

public function jumlah_paket_belanja_rutin()
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
public function jumlah_paket_belanja_modal()
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
