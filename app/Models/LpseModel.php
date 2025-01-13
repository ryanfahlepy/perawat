<?php

namespace App\Models;

use CodeIgniter\Model;

class LpseModel extends Model
{
    protected $table = 'tabel_pl'; // Nama tabel default
    protected $primaryKey = 'id_dokumen'; // Primary key tabel
    protected $allowedFields = ['dokumen']; // Kolom yang dapat dimanipulasi
    protected $returnType = 'array';

    /**
     * Fungsi untuk mendapatkan semua dokumen
     */
    public function getAllDokumen()
    {
        return $this->select('id_dokumen, dokumen')->findAll();
    }

    /**
     * Fungsi untuk mengubah tabel yang digunakan
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Fungsi untuk memperbarui dokumen
     * 
     * @param int $id_dokumen ID dokumen yang akan diperbarui
     * @param string $dokumen Nama dokumen yang diperbarui
     * @return bool Hasil dari operasi update
     */
    public function updateDokumen($id_dokumen, $dokumen)
    {
        return $this->update($id_dokumen, ['dokumen' => $dokumen]);
    }

    /**
     * Fungsi untuk menghapus dokumen
     * 
     * @param int $id_dokumen ID dokumen yang akan dihapus
     * @return bool Hasil dari operasi delete
     */
    public function deleteDokumen($id_dokumen)
    {
        // Delete the document where the id matches
        return $this->delete($id_dokumen);
    }

    public function customInsert(array $data, bool $returnID = true)
    {
        // Add custom logic if needed

        // Call the parent insert method
        return parent::insert($data, $returnID);
    }
}
