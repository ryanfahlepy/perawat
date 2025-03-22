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
    
    // Jumlah pengadaan keseluruhan
    public function jumlah_pengadaan($tahun = null)
    {
        $builder = $this->db->table($this->table);
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->countAllResults();
    }

    public function total_perencanaan($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->perencanaan;
    }

    public function total_pelaksanaan($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pelaksanaan;
    }

    public function total_pembayaran($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pembayaran');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pembayaran;
    }

    // BELANJA RUTIN (DISINFOLAHTAL)
    public function jumlah_pengadaan_belanja_rutin($tahun = null)
    {
        $builder = $this->db->table($this->table)->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->countAllResults();
    }

    public function perencanaan_belanja_rutin($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan')->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->perencanaan;
    }

    public function pelaksanaan_belanja_rutin($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan')->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pelaksanaan;
    }

    public function pembayaran_belanja_rutin($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pembayaran')->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pembayaran;
    }

    // BELANJA MODAL (MABES TNI AL)
    public function jumlah_pengadaan_belanja_modal($tahun = null)
    {
        $builder = $this->db->table($this->table)->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->countAllResults();
    }

    public function perencanaan_belanja_modal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->perencanaan;
    }

    public function pelaksanaan_belanja_modal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pelaksanaan;
    }

    public function pembayaran_belanja_modal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pembayaran')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pembayaran;
    }

    // Jumlah pengadaan per bulan
    public function jumlahPengadaanPerBulan($tahun = null)
    {
        $builder = $this->db->table($this->table)
            ->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS jumlah");

        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->groupBy("MONTH(tanggal_mulai)")
            ->orderBy("bulan", "ASC")
            ->get()
            ->getResultArray();
    }


}
