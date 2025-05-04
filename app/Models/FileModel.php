<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'tabel_file';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ref_id_pengadaan', 'ref_id_dokumen', 'nama_file', 'checksum', 'pengunggah', 'penghapus','created_at', 'deleted_at'];

    /**
     * Ambil semua dokumen berdasarkan id_pengadaan
     */
    // public function getFilesByPengadaan($id_pengadaan)
    // {
    //     return $this->where('id_pengadaan', $id_pengadaan)
    //         ->where('deleted_at', null) // Hanya dokumen yang belum dihapus
    //         ->findAll();
    // }

    public function get_all_files($id_pengadaan)
    {
        return $this->where('ref_id_pengadaan', $id_pengadaan)->where('deleted_at', null)->findAll();
    }



    /**
     * Tambah dokumen baru
     */

    /**
     * Hapus dokumen secara logis (soft delete)
     */ public function hapusFile($id_file)
    {
        return $this->update($id_file, ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Ambil dokumen berdasarkan ID
     */
    public function getFileById($id_dokumen)
    {
        return $this->where('id_dokumen', $id_dokumen)->first();
    }


    public function getFileCountByPengadaanId($refIdPengadaan)
    {
        return $this->where('ref_id_pengadaan', $refIdPengadaan)->where('deleted_at',NULL)
            ->countAllResults();
    }
}
