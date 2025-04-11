<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanModel extends Model
{
    // Tentukan nama tabel yang digunakan
    protected $table = 'tabel_pengadaan'; // ganti sesuai nama tabel kamu
    protected $primaryKey = 'id';   // ganti jika bukan 'id'

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'tahun_anggaran',
        'dipa',
        'jenis',
        'metode',
        'kode_rup',
        'nama_pengadaan',
        'perencanaan',
        'pelaksanaan',
        'pembayaran',
        'tanggal_mulai',
        'tanggal_berakhir'
    ];

    protected $useTimestamps = false;

    // optional method kamu
    public function insertData($data)
    {
        return $this->insert($data); // atau insertBatch kalau kirim banyak
    }
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
    public function jumlah_pengadaan($tahun = null, $dipa = null)
    {
        $builder = $this->db->table($this->table);

        if ($dipa !== null) {
            $builder->where('dipa', $dipa);
        }

        if ($tahun !== null) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->countAllResults();
    }



    public function total_perencanaan($tahun = null, $dipa = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan');

        if ($dipa !== null) {
            $builder->where('dipa', $dipa);
        }

        if ($tahun !== null) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->get()->getRow()->perencanaan;
    }

    public function total_pelaksanaan($tahun = null, $dipa = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan');

        if ($dipa !== null) {
            $builder->where('dipa', $dipa);
        }

        if ($tahun !== null) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->get()->getRow()->pelaksanaan;
    }

    public function total_pembayaran($tahun = null, $dipa = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pembayaran');

        if ($dipa !== null) {
            $builder->where('dipa', $dipa);
        }

        if ($tahun !== null) {
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

    public function perencanaan_belanja_dipa_disinfolahtal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan')->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->perencanaan;
    }

    public function pelaksanaan_belanja_dipa_disinfolahtal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan')->where('dipa', 'DISINFOLAHTAL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pelaksanaan;
    }

    public function pembayaran_belanja_dipa_disinfolahtal($tahun = null)
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

    public function perencanaan_belanja_dipa_mabesal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('perencanaan')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->perencanaan;
    }

    public function pelaksanaan_belanja_dipa_mabesal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pelaksanaan')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pelaksanaan;
    }

    public function pembayaran_belanja_dipa_mabesal($tahun = null)
    {
        $builder = $this->db->table($this->table)->selectSum('pembayaran')->where('dipa', 'MABES TNI AL');
        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }
        return $builder->get()->getRow()->pembayaran;
    }

    // Jumlah pengadaan per bulan
    public function jumlahPengadaanMulaiPerBulan($tahun = null)
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

    public function jumlahPengadaanBerakhirPerBulan($tahun = null)
    {
        $builder = $this->db->table($this->table)
            ->select("MONTH(tanggal_berakhir) AS bulan, COUNT(*) AS jumlah");

        if ($tahun) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->groupBy("MONTH(tanggal_berakhir)")
            ->orderBy("bulan", "ASC")
            ->get()
            ->getResultArray();
    }
    public function distribusiPengadaanPerJenis($tahun = null)
    {
        $builder = $this->select('jenis, COUNT(*) as jumlah')
            ->groupBy('jenis');

        if (!empty($tahun)) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->findAll();
    }
    public function distribusiPengadaanPerMetode($tahun = null)
    {
        $builder = $this->select('metode, COUNT(*) as jumlah')
            ->groupBy('metode');

        if (!empty($tahun)) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->findAll();
    }
    public function jumlahPengadaanPerJenisMetode($tahun = null)
    {
        $builder = $this->select('jenis, metode, COUNT(*) as jumlah')
            ->groupBy('jenis, metode');

        if (!empty($tahun)) {
            $builder->where('tahun_anggaran', $tahun);
        }

        return $builder->findAll();
    }


}
