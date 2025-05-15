<?php

namespace App\Controllers;

use App\Models\PelatihanWajibModel;
use App\Models\PelatihanHasilModel;
use App\Models\PelatihanTambahanModel;
use App\Models\UserModel;
use CodeIgniter\Files\File;

class Pelatihan extends BaseController
{
    protected $pelatihanWajibModel;
    protected $pelatihanHasilModel;
    protected $pelatihanTambahanModel;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pelatihanWajibModel = new PelatihanWajibModel();
        $this->pelatihanHasilModel = new PelatihanHasilModel();
        $this->pelatihanTambahanModel = new PelatihanTambahanModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = session();
        $user_id = $session->get('user_id');
        $nama = $session->get('nama');
    
        // Ambil level_user dari tabel user
        $user = $this->userModel->find($user_id);
        $level_user = $user->level_user ?? null;
    
        // Ambil nama_level dari tabel user_level berdasarkan level_user
        $userLevelModel = new \App\Models\User_levelModel(); // pastikan model ini ada
        $user_level_data = $userLevelModel->where('id', $level_user)->first();
        $nama_level = $user_level_data->nama_level ?? null; // menggunakan -> untuk akses properti objek
    
        // Ambil daftar pelatihan wajib dari tabel_pelatihan berdasarkan level (nama_level)
        $pelatihan_wajib = $this->pelatihanWajibModel
            ->where('level', $nama_level)  // menggunakan nama_level
            ->findAll();
    
        // Ambil pelatihan hasil yang sudah diisi user
        $pelatihan_terisi = $this->pelatihanHasilModel
            ->where('user_id', $user_id)
            ->findAll();
    
        $pelatihan_map = [];
        foreach ($pelatihan_terisi as $item) {
            $pelatihan_map[$item['pelatihan_id']] = $item;
        }
    
        // Ambil pelatihan tambahan yang sudah diisi user
        $pelatihan_tambahan = $this->pelatihanTambahanModel
            ->where('user_id', $user_id)
            ->findAll();
    
        return view('/layout/pelatihan', [
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu'=> 'Pelatihan',
            'nama' => $nama,
            'pelatihan_wajib' => $pelatihan_wajib,
            'pelatihan_map' => $pelatihan_map,
            'pelatihan_tambahan' => $pelatihan_tambahan,
        ]);
    }

    public function detail($pelatihan_id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Ambil data pelatihan wajib
        $pelatihan = $this->pelatihanWajibModel->find($pelatihan_id);

        if (!$pelatihan) {
            return redirect()->to('/pelatihan')->with('error', 'Data pelatihan tidak ditemukan.');
        }

        $kategori = $pelatihan['kategori'];

        // Ambil daftar pelatihan yang sudah diikuti user berdasarkan pelatihan_id dan kategori
        $pelatihan_dijalani = $this->pelatihanHasilModel
            ->where('user_id', $user_id)
            ->where('pelatihan_id', $pelatihan_id)
            ->findAll();

        return view('/layout/detail_pelatihan', [
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu'=> 'Detail',
            'pelatihan' => $pelatihan,
            'kategori' => $kategori,
            'pelatihan_list' => $pelatihan_dijalani,
        ]);
    }

    public function simpan()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Ambil data dari form
        $judul = $this->request->getPost('judul');
        $lokasi = $this->request->getPost('lokasi');
        $tanggal = $this->request->getPost('tanggal');
        $kategori = $this->request->getPost('kategori');

        // Handle file upload
        $sertifikat = $this->request->getFile('sertifikat');

        $fileName = null;
        if ($sertifikat->isValid() && !$sertifikat->hasMoved()) {
            // Generate a random file name
            $fileName = $sertifikat->getRandomName();
            // Move the file to the uploads/sertifikat folder
            $sertifikat->move('uploads/sertifikat', $fileName);
        }

        // Prepare data to be saved
        $data = [
            'judul' => $judul,
            'lokasi' => $lokasi,
            'tanggal' => $tanggal,
            'kategori' => $kategori,
            'sertifikat' => $fileName,
            'user_id' => $user_id,
            'pelatihan_id' => $this->pelatihanWajibModel->getPelatihanIdByKategori($kategori), // Assume you have a method to get pelatihan_id by category
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $pelatihan_id = $data['pelatihan_id'];
        // Save the data into PelatihanTambahanModel (or another model if necessary)
        $this->pelatihanHasilModel->insert($data);

        return redirect()->to('/pelatihan/detail/' . $pelatihan_id)
                 ->with('success', 'Pelatihan berhasil ditambahkan!');
    }
    public function simpanPelatihanTambahan()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'lokasi' => 'required|max_length[255]',
            'tanggal' => 'required|valid_date',
            'sertifikat' => 'uploaded[sertifikat]|max_size[sertifikat,2048]|ext_in[sertifikat,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        $fileSertifikat = $this->request->getFile('sertifikat');
        if ($fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            $newName = $fileSertifikat->getRandomName();
            $fileSertifikat->move(ROOTPATH . 'public/uploads/sertifikat', $newName);
        } else {
            $newName = null;
        }

        $data = [
            'user_id' => $user_id,
            'judul' => $this->request->getPost('judul'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'sertifikat' => $newName,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->pelatihanTambahanModel->insert($data);

        return redirect()->to('/pelatihan')->with('message', 'Pelatihan tambahan berhasil ditambahkan.');
    }

}
