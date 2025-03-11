<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengadaanModel;
use App\Models\FileModel;
use App\Models\DokumenModel;

class Pengadaan extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pengadaanModel = new PengadaanModel();  // Menginisialisasi model
        $this->fileModel = new FileModel();  // Menginisialisasi model
        $this->dokumenModel = new DokumenModel();  // Menginisialisasi model

         // Mengambil jumlah dokumen dari masing-masing tabel dan menyimpannya di properti class
        $this->dokumenCounts = [
            'tabel_pl' => $this->dokumenModel->getAllCount('tabel_pl'),
            'tabel_tender' => $this->dokumenModel->getAllCount('tabel_tender'),
            'tabel_ep' => $this->dokumenModel->getAllCount('tabel_ep'),
        ];

     
    }

    
    public function index()
    {
        // Mengambil semua data pengadaan
        $pengadaanData = $this->pengadaanModel->getAllPengadaan();
    
        // Update nilai pengadaanData berdasarkan ref_tabel
        foreach ($pengadaanData as &$pengadaan) {
            if ($pengadaan['ref_tabel'] == 1) {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_pl'];
            } elseif ($pengadaan['ref_tabel'] == 2) {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_tender'];
            } elseif ($pengadaan['ref_tabel'] == 3) {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_ep'];
            } else {
                $pengadaan['jumlah_dokumen'] = 0; // Default jika tidak sesuai
            }
        }
    
        // Update pengadaanData untuk menghitung jumlah file dan progress
        foreach ($pengadaanData as &$pengadaan) {
            // Mengambil jumlah file berdasarkan id pengadaan
            $pengadaan['jumlah_file'] = $this->fileModel->getFileCountByPengadaanId($pengadaan['id']);
    
            // Menghitung progress (persentase)
            if ($pengadaan['jumlah_dokumen'] > 0) {
                $pengadaan['progress'] = round(($pengadaan['jumlah_file'] / $pengadaan['jumlah_dokumen']) * 100); // Membulatkan ke angka terdekat
            } else {
                $pengadaan['progress'] = 0; // Jika tidak ada dokumen, set progress ke 0
            }
        }
        
        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Daftar Pengadaan',
            'pengadaanData' => $pengadaanData,
        ];
    
        return view('pokja/pengadaan', $data);
    }
    
    
    

    public function detail_pengadaan($id)
{
    $pengadaan = $this->pengadaanModel->find($id);
    $fileList = $this->fileModel->where('ref_id_pengadaan', $id)
                            ->where('deleted_at', null) // Menambahkan kondisi untuk memastikan hanya file yang belum dihapus yang diambil
                            ->findAll();

    
    

    if (!$pengadaan) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengadaan tidak ditemukan!');
    }

    // Tentukan tabel dokumen berdasarkan ref_tabel
    $dokumenTable = '';
    switch ($pengadaan['ref_tabel']) {
        case '1':
            $dokumenTable = 'tabel_pl';
            break;
        case '2':
            $dokumenTable = 'tabel_tender';
            break;
        case '3':
            $dokumenTable = 'tabel_ep';
            break;
        default:
            $dokumenTable = null;
    }

    // Jika ref_tabel valid, ambil data dokumen dari tabel yang sesuai
    $dokumenList = [];
    if ($dokumenTable) {
        $db = \Config\Database::connect();
        $dokumenList = $db->table($dokumenTable)
                          ->select('id_dokumen, dokumen')
                          
                          ->get()
                          ->getResultArray();
    }

    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Detail Pengadaan',
        'pengadaan' => $pengadaan,
        'dokumenList' => $dokumenList,
        'fileList' => $fileList // Tambahkan data file yang sudah diunggah
    ];

    return view('pokja/detail_pengadaan', $data);
}

public function hapus_file($id_file)
{
    // Validasi apakah ID file ada di database
    $file = $this->fileModel->find($id_file);
    if ($file) {
        // Menghapus file dari folder
        $filePath = WRITEPATH . 'uploads/' . $file['nama_file'];  // Sesuaikan path folder file
        if (is_file($filePath)) {
            unlink($filePath);  // Menghapus file secara fisik
        }

        // Hapus data file dari database (soft delete)
        $this->fileModel->hapusFile($id_file);

        // Menyimpan pesan sukses ke dalam session
        session()->setFlashdata('success', 'File berhasil dihapus.');
    } else {
        // Jika file tidak ditemukan
        session()->setFlashdata('error', 'File tidak ditemukan.');
    }

    // Redirect kembali ke halaman detail pengadaan
    return redirect()->to(base_url('pokja/pengadaan/detail_pengadaan/' . $file['ref_id_pengadaan']));
}


public function unggah_dokumen()
{
    $id_pengadaan = $this->request->getPost('id_pengadaan');
    $id_dokumen = $this->request->getPost('id_dokumen');

    if (!filter_var($id_pengadaan, FILTER_VALIDATE_INT) || !filter_var($id_dokumen, FILTER_VALIDATE_INT)) {
        session()->setFlashdata('error', 'ID Pengadaan atau ID Dokumen tidak valid.');
        return redirect()->back();
    }

    $file = $this->request->getFile('file');

    if ($file->isValid() && !$file->hasMoved()) {
        $newName = $file->getName();
        $folder = 'uploads/' . $id_pengadaan . '/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if ($file->move($folder, $newName)) {
            $data = [
                'ref_id_pengadaan' => (int) $id_pengadaan,
                'ref_id_dokumen' => (int) $id_dokumen,
                'nama_file' => $newName,
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $this->fileModel->save($data);

            session()->setFlashdata('success', 'Dokumen berhasil diunggah.');
            return redirect()->to(base_url('pokja/pengadaan/detail_pengadaan/' . $id_pengadaan));
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah file.');
        }
    } else {
        session()->setFlashdata('error', 'Gagal mengunggah dokumen. ' . $file->getErrorString());
    }

    return redirect()->back();
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

    session()->setFlashdata('success', 'Data pengadaan berhasil diperbarui.');
    return redirect()->to(base_url('pokja/pengadaan/detail_pengadaan/' . $id));
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
        return redirect()->to('/pokja/pengadaan');
    }

    public function tambah_pengadaan()
{
    // Menyiapkan data untuk form tambah pengadaan
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Tambah Pengadaan',
    ];

    return view('pokja/tambah_pengadaan', $data);
}

public function simpan_pengadaan()
{
    // Validasi input dari form
    $validation = \Config\Services::validation();
    if (!$this->validate([
        'nama_pengadaan' => 'required|min_length[3]',
        'ref_tabel' => 'required|in_list[1,2,3]',
        'ppk' => 'required|min_length[3]',
        'pokja_pp' => 'required|min_length[3]',
        'nama_penyedia' => 'required|min_length[3]',
        'tanggal_mulai' => 'required|valid_date[Y-m-d]',
        'tanggal_berakhir' => 'required|valid_date[Y-m-d]',
    ])) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

     

    // Pastikan tanggal_berakhir tidak lebih awal dari tanggal_mulai
    $tanggal_mulai = $this->request->getPost('tanggal_mulai');
    $tanggal_berakhir = $this->request->getPost('tanggal_berakhir');
    if (strtotime($tanggal_berakhir) < strtotime($tanggal_mulai)) {
        session()->setFlashdata('error', 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.');
        return redirect()->back()->withInput();
    }

    // Menyimpan data pengadaan baru ke database
    $this->pengadaanModel->save([
        'nama_pengadaan' => $this->request->getPost('nama_pengadaan'),
        'ref_tabel' => (int) $this->request->getPost('ref_tabel'),
        'ppk' => $this->request->getPost('ppk'),
        'pokja_pp' => $this->request->getPost('pokja_pp'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
        'tanggal_berakhir' => $this->request->getPost('tanggal_berakhir'),
        'dokumen_terunggah' => 0,
        'total_dokumen' => 0
    ]);

    // Redirect dengan pesan sukses
    session()->setFlashdata('success', 'Pengadaan berhasil ditambahkan.');
    return redirect()->to(base_url('pokja/pengadaan'));
}


}
