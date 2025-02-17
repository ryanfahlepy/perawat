<?php

namespace App\Controllers\Pokja;

use App\Controllers\BaseController;
use App\Models\PengadaanModel;

class Pengadaan extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pengadaanModel = new PengadaanModel();  // Menginisialisasi model
    }

    public function detail_pengadaan($id)
    {
        // Mengambil data pengadaan berdasarkan ID
        
        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Detail Pengadaan',
            'pengadaan' => $this->pengadaanModel->find($id)
        ];
        // Pastikan data ditemukan
        if (!$data['pengadaan']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengadaan tidak ditemukan!');
        }

        // Menampilkan halaman detail pengadaan
        return view('pokja/detail_pengadaan', $data);
    }

    public function update_pengadaan($id)
{
    $data = [
        'nama_pengadaan' => $this->request->getPost('nama_pengadaan'),
        'ref_tabel' => $this->request->getPost('ref_tabel'),
        'ppk' => $this->request->getPost('ppk'),
        'pokja_pp' => $this->request->getPost('pokja_pp'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
        'tanggal_berakhir' => $this->request->getPost('tanggal_berakhir'),
        'dokumen_terunggah' => $this->request->getPost('dokumen_terunggah'),
        'total_dokumen' => $this->request->getPost('total_dokumen')
    ];

    $this->pengadaanModel->update($id, $data);

    return redirect()->to(base_url('pokja/pengadaan/detail_pengadaan/' . $id))->with('success', 'Data pengadaan berhasil diperbarui');
}

public function hapus_pengadaan($id)
    {
        // Validasi apakah ID ada di database
        $pengadaan = $this->pengadaanModel->find($id);
        if ($pengadaan) {
            // Menghapus data berdasarkan ID
            $this->pengadaanModel->delete($id);

            // Menyimpan pesan sukses ke dalam session dan mengarahkan ke halaman daftar pengadaan
            $this->session->setFlashdata('success', 'Pengadaan berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan
            $this->session->setFlashdata('error', 'Pengadaan tidak ditemukan.');
        }

        // Redirect ke halaman daftar pengadaan
        return redirect()->to('/pokja/home');
    }

}
