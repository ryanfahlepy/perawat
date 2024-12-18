<?php

namespace App\Controllers\Lpse;

use App\Controllers\BaseController;
use App\Models\LpseModel;

class Templatedokumen extends BaseController
{
    protected $templatedokumenModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->templatedokumenModel = new LpseModel();
    }

    public function index()
{
    $filter_type = $this->request->getGet('filter_type');
    switch ($filter_type) {
        case 'tender':
            $this->templatedokumenModel->setTable('tabel_tender');
            break;
        case 'ep':
            $this->templatedokumenModel->setTable('tabel_ep');
            break;
        case 'pl':
            $this->templatedokumenModel->setTable('tabel_pl');
            break;
    }

    // Proses submit form untuk update dokumen
    if ($this->request->getMethod() === 'post') {
        $this->updateDokumen();
    }

    // Ambil daftar dokumen
    $dokumen_list = $this->templatedokumenModel->getAllDokumen();

    $data = [
        'level_akses' => $this->session->nama_level,
        'dtmenu' => $this->tampil_menu($this->session->level),
        'nama_menu' => 'Template Dokumen',
        'dokumen_list' => $dokumen_list,
        'filter_type' => $filter_type
    ];

    return view('lpse/templatedokumen', $data);
}

public function updateDokumen()
{
    $filter_type = $this->request->getPost('filter_type');
    
    switch ($filter_type) {
        case 'tender':
            $this->templatedokumenModel->setTable('tabel_tender');
            break;
        case 'ep':
            $this->templatedokumenModel->setTable('tabel_ep');
            break;
        case 'pl':
            $this->templatedokumenModel->setTable('tabel_pl');
            break;
    }

    // Ambil data dokumen yang dikirim melalui form
    $dokumen = $this->request->getPost('dokumen');

    // Update dokumen yang ada
    foreach ($dokumen as $id_dokumen => $dokumen_name) {
        if ($id_dokumen !== 'new') {
            $this->templatedokumenModel->update($id_dokumen, ['dokumen' => $dokumen_name]);
        }
    }

    // Jika ada dokumen baru (id_dokumen == 'new')
    if (!empty($dokumen['new'])) {
        $this->templatedokumenModel->customInsert(['dokumen' => $dokumen['new'], 'filter_type' => $filter_type]);
    }

    return redirect()->to(current_url())->with('message', 'Dokumen berhasil diperbarui');
}



public function hapus()
{
    // Get the document ID from the POST request
    $id_dokumen = $this->request->getPost('id_dokumen');

    // Perform the delete operation
    $result = $this->templatedokumenModel->delete($id_dokumen);

    // Check if the delete operation was successful
    if ($result) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false]);
    }
}


}
