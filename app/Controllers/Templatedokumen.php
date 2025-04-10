<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TemplateDokumenModel;

class Templatedokumen extends BaseController
{
    protected $templateDokumenModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->templateDokumenModel = new TemplateDokumenModel();
    }

    public function index()
    {
        // Mengambil dokumen dari masing-masing tabel
        $plDocuments = $this->templateDokumenModel->getDokumenByTable('tabel_pl');
        $juksungDocuments = $this->templateDokumenModel->getDokumenByTable('tabel_juksung');
        $tenderDocuments = $this->templateDokumenModel->getDokumenByTable('tabel_tender');
        $epDocuments = $this->templateDokumenModel->getDokumenByTable('tabel_ep');
        $swakelolaDocuments = $this->templateDokumenModel->getDokumenByTable('tabel_swakelola');

        // Data yang dikirimkan ke view
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Template Dokumen',
            'plDocuments' => $plDocuments,
            'juksungDocuments' => $juksungDocuments,
            'tenderDocuments' => $tenderDocuments,
            'epDocuments' => $epDocuments,
            'swakelolaDocuments' => $swakelolaDocuments,
        ];

        return view('layout/templatedokumen', $data);
    }

    public function update_order()
    {
        $orderData = $this->request->getPost('order');
        $table = $this->request->getPost('table');

        if (!$orderData || !$table) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak valid'
            ]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table($table);

        try {
            foreach ($orderData as $index => $item) {
                $builder->where('id_dokumen', $item['id'])->update([
                    'no_urut' => $index + 1,
                    'dokumen' => $item['dokumen']
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Urutan dokumen berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui urutan dokumen: ' . $e->getMessage()
            ]);
        }
    }


    public function add_document()
    {
        $table = $this->request->getPost('table');
        $dokumen = $this->request->getPost('dokumen');

        if (!$table || !$dokumen) {
            session()->setFlashdata('error', 'Data tidak valid');
            return redirect()->to(base_url('templatedokumen'));
        }

        try {
            $inserted = $this->templateDokumenModel->addDokumen($table, $dokumen);

            if ($inserted) {
                session()->setFlashdata('success', 'Dokumen berhasil ditambahkan');
            } else {
                session()->setFlashdata('error', 'Gagal menambahkan dokumen');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->to(base_url('templatedokumen'));
    }
    public function delete_document()
    {
        $id = $this->request->getPost('id');
        $table = $this->request->getPost('table');


        if (!$id || !$table) {
            $this->session->setFlashdata('error', 'Data tidak lengkap!');
            return redirect()->to(base_url('admin/templatedokumen'));
        }

        if ($this->templateDokumenModel->deleteDokumen($table, $id)) {
            $this->session->setFlashdata('success', 'Dokumen berhasil dihapus');
        } else {
            $this->session->setFlashdata('error', 'Gagal menghapus dokumen');
        }

        return redirect()->to(base_url('templatedokumen'))->with('refresh', true);
    }

}



