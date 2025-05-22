<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
Use App\Models\InovasiModel;
Use App\Models\VoteModel;
Use App\Models\KomentarModel;
Use App\Models\PersetujuanInovasiModel;

class Inovasi extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $statusFilter = $this->request->getGet('status'); // ambil dari query string
        $inovasiModel = new InovasiModel();
        $userModel = new UserModel();
        $voteModel = new VoteModel();
        $komentarModel = new KomentarModel();
        $persetujuanModel = new PersetujuanInovasiModel();
        $userId = $this->session->get('user_id'); 

        $inovasiList = $statusFilter
            ? $inovasiModel->where('status', $statusFilter)->findAll()
            : $inovasiModel->findAll();

        $inovasiData = [];

        foreach ($inovasiList as $item) {
            $jumlahSetuju = $voteModel->where(['inovasi_id' => $item['id'], 'vote' => 'setuju'])->countAllResults();
            $jumlahTidakSetuju = $voteModel->where(['inovasi_id' => $item['id'], 'vote' => 'tidak_setuju'])->countAllResults();

            $komentar = $komentarModel
                ->select('tabel_komentar_inovasi.*, tabel_user.nama, tabel_user.photo')
                ->join('tabel_user', 'tabel_user.id = tabel_komentar_inovasi.user_id')
                ->where('inovasi_id', $item['id'])
                ->findAll();

            $pengusul = $userModel->select('tabel_user.id, tabel_user.nama, tabel_user.photo, tabel_user_level.nama_level AS grade')
                ->join('tabel_user_level', 'tabel_user.level_user = tabel_user_level.id', 'left')
                ->where('tabel_user.id', $item['user_id'])
                ->first();

            $persetujuan = $persetujuanModel
                ->where('inovasi_id', $item['id'])
                ->first();

            $userVote = $voteModel->where([
                'user_id' => $userId,
                'inovasi_id' => $item['id']
            ])->first();
                
            $inovasiData[] = [
                'id' => $item['id'],
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'],
                'lampiran' => $item['lampiran'] ?? null,
                'status' => $item['status'],
                'created_at' => $item['created_at'],
                'pengusul' => $pengusul->nama ?? 'Unknown',
                'photo' => $pengusul->photo ?? null,
                'grade' => $pengusul->grade ?? '-',
                'user_vote' => $userVote['vote'] ?? null,
                'jumlah_setuju' => $jumlahSetuju,
                'jumlah_tidak_setuju' => $jumlahTidakSetuju,
                'komentar' => $komentar,
                'persetujuan' => $persetujuan,
                'catatan' => $persetujuan['catatan'] ?? ''

            ];
        }

        $levelUser = (int) $this->session->get('level');


        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Inovasi',
            'inovasi' => $inovasiData,
            'status_filter' => $statusFilter,
            'level_user' => $levelUser,
        ];

        return view('layout/inovasi', $data);
    }

    public function simpan()
    {
        $session = \Config\Services::session();
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'judul' => 'required|min_length[3]',
            'deskripsi' => 'required|min_length[10]',
            'lampiran' => 'permit_empty|uploaded[lampiran]|max_size[lampiran,10240]|ext_in[lampiran,pdf,doc,docx,jpg,jpeg,png]'
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        $file = $this->request->getFile('lampiran');
        $fileName = null;
    
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/inovasi', $fileName);
        }
    
        $model = new \App\Models\InovasiModel();
        $model->save([
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'lampiran' => $fileName,
            'user_id' => $session->get('user_id'),
            'status' => 'diajukan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
    
        return redirect()->to('/inovasi')->with('success', 'Saran berhasil diajukan!');
    }
    

    public function vote()
{
    $voteModel = new VoteModel();
    $inovasiId = $this->request->getPost('inovasi_id');
    $vote = $this->request->getPost('vote');
    $userId = session()->get('user_id');

    // Cek apakah user sudah pernah vote
    $existingVote = $voteModel
        ->where(['inovasi_id' => $inovasiId, 'user_id' => $userId])
        ->first();

    if ($existingVote) {
        if ($existingVote['vote'] === $vote) {
            // Jika vote sama, hapus (batalkan vote)
            $voteModel->delete($existingVote['id']);
        } else {
            // Jika vote beda, update
            $voteModel->update($existingVote['id'], [
                'vote' => $vote,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    } else {
        // Simpan vote baru
        $voteModel->save([
            'inovasi_id' => $inovasiId,
            'user_id' => $userId,
            'vote' => $vote,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    return redirect()->back()->with('success', 'Voting berhasil dikirim.');
}

public function voteAjax()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
    }

    $voteModel = new VoteModel();
    $inovasiId = $this->request->getPost('inovasi_id');
    $vote = $this->request->getPost('vote');
    $userId = session()->get('user_id');

    // Cek apakah user sudah pernah vote
    $existingVote = $voteModel->where([
        'inovasi_id' => $inovasiId,
        'user_id' => $userId
    ])->first();

    $userVote = null;

    if ($existingVote) {
        if ($existingVote['vote'] === $vote) {
            $voteModel->delete($existingVote['id']); // batal vote
        } else {
            $voteModel->update($existingVote['id'], [
                'vote' => $vote,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $userVote = $vote;
        }
    } else {
        $voteModel->save([
            'inovasi_id' => $inovasiId,
            'user_id' => $userId,
            'vote' => $vote,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $userVote = $vote;
    }

    // Hitung jumlah terbaru
    $jumlah_setuju = $voteModel->where(['inovasi_id' => $inovasiId, 'vote' => 'setuju'])->countAllResults();
    $jumlah_tidak_setuju = $voteModel->where(['inovasi_id' => $inovasiId, 'vote' => 'tidak_setuju'])->countAllResults();

    return $this->response->setJSON([
        'success' => true,
        'user_vote' => $userVote,
        'jumlah_setuju' => $jumlah_setuju,
        'jumlah_tidak_setuju' => $jumlah_tidak_setuju
    ]);
}

 

    public function kirimkomentar()
    {
        $komentar = $this->request->getPost('komentar');
        $inovasi_id = $this->request->getPost('inovasi_id');
        $session = session();
        
        // Cek jika komentar kosong
        if (!$komentar || !$inovasi_id) {
            return redirect()->back()->with('error', 'Komentar tidak boleh kosong.');
        }
        
        $data = [
            'inovasi_id' => $inovasi_id,
            'user_id' => $session->user_id,
            'komentar' => $komentar, // Ganti 'isi' dengan 'komentar'
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $komentarModel = new \App\Models\KomentarModel();
        $komentarModel->insert($data);
        
        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }   

    public function persetujuan()
    {
        $inovasiId = $this->request->getPost('inovasi_id');
        $aksi = $this->request->getPost('aksi'); // 'setujui' atau 'tolak'
        $userId = $this->session->user_id;

        $persetujuanModel = new PersetujuanInovasiModel();
        $inovasiModel = new InovasiModel();
        $catatan = $this->request->getPost('catatan');

        // Simpan atau update persetujuan
        $existing = $persetujuanModel
            ->where(['inovasi_id' => $inovasiId])
            ->first();

        $status = $aksi === 'setujui' ? 'disetujui' : 'ditolak';

        if ($existing) {
            $persetujuanModel->update($existing['id'], [
                'status' => $status,
                'user_id' => $userId,
                'catatan' => $catatan,
            ]);
        } else {
            $persetujuanModel->insert([
                'inovasi_id' => $inovasiId,
                'user_id' => $userId,
                'status' => $status,
                'catatan' => $catatan,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Update status inovasi
        $inovasiModel->update($inovasiId, ['status' => $status]);

        return redirect()->back()->with('success', 'Persetujuan berhasil disimpan.');
    }

    public function hapus()
    {
        $request = service('request');
        $id = $request->getPost('inovasi_id');

        if ($id) {
            // Inisialisasi semua model terkait
            $voteModel = new \App\Models\VoteModel();
            $komentarModel = new \App\Models\KomentarModel();
            $persetujuanModel = new \App\Models\PersetujuanInovasiModel();
            $inovasiModel = new \App\Models\InovasiModel();

            // Hapus data terkait terlebih dahulu
            $voteModel->where('inovasi_id', $id)->delete();
            $komentarModel->where('inovasi_id', $id)->delete();
            $persetujuanModel->where('inovasi_id', $id)->delete();

            // Hapus data inovasi terakhir
            $inovasiModel->delete($id);

            return redirect()->to(site_url('Inovasi/index'))->with('success', 'Inovasi dan data terkait berhasil dihapus.');
        }

        return redirect()->to(site_url('Inovasi/index'))->with('error', 'Gagal menghapus inovasi.');
    }

    

}



