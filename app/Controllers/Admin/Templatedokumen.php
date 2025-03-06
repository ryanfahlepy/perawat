<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DokumenModel;

class Templatedokumen extends BaseController
{
    protected $dokumenModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->dokumenModel = new DokumenModel();
    }

    public function index()
    {
        // Mengambil dokumen dari masing-masing tabel
        $plDocuments = $this->dokumenModel->getDokumenByTable('tabel_pl');
        $tenderDocuments = $this->dokumenModel->getDokumenByTable('tabel_tender');
        $epDocuments = $this->dokumenModel->getDokumenByTable('tabel_ep');

        // Data yang dikirimkan ke view
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Template Dokumen',
            'plDocuments' => $plDocuments,
            'tenderDocuments' => $tenderDocuments,
            'epDocuments' => $epDocuments,
        ];

        return view('admin/templatedokumen', $data);
    }

    public function update_order()
    {
        $orderData = $this->request->getPost('order');
        $table = $this->request->getPost('table');
    
        if (!$orderData || !$table) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak valid']);
        }
    
        $db = \Config\Database::connect();
        $builder = $db->table("tabel_" . $table);
    
        foreach ($orderData as $index => $item) {
            $builder->where('id_dokumen', $item['id'])->update([
                'no_urut' => $index + 1, // Urutan baru
                'dokumen' => $item['dokumen'] // Nama dokumen baru
            ]);
        }
    
        return $this->response->setJSON(['status' => 'success']);
    }
    

}
