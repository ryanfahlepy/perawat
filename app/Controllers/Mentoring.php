<?php

namespace App\Controllers;

use App\Models\PrapkModel;
use App\Models\PrapkHasilModel;
use App\Models\UserModel;

class Mentoring extends BaseController
{
    protected $session;
    protected $prapkModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->prapkModel = new PrapkModel(); // simpan ke properti kelas
        $this->prapkHasilModel = new PrapkHasilModel(); // simpan ke properti kelas
        $this->userModel = new UserModel(); // Inisialisasi model User
    }

    // Fungsi index untuk menampilkan daftar user dan data mentoring
    public function index()
    {
        // Ambil daftar user dengan level tertentu (misalnya level 4, 5, 6)
        $dataUser = $this->userModel->getUser()->getResultArray();

        // Konversi objek menjadi array
        $dataUserArray = [];
        foreach ($dataUser as $user) {
            $dataUserArray[] = (array) $user;  // Ubah objek menjadi array
        }

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Mentoring',
            'dataUser' => $dataUserArray,  // Data sudah berupa array
        ];

        return view('layout/mentoring', $data);  // Kirim ke view yang benar
    }


    public function prapk($userId = null)
    {
        $dataPrapk = $this->prapkModel->orderBy('no ASC')->findAll();

        // Mengambil data hasil berdasarkan user
        $userId = $this->session->get('user_id');
        $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUser($userId);

        // Ubah jadi array dengan key = prapk_id
        $dataHasil = [];
        foreach ($dataHasilRaw as $row) {
            $dataHasil[$row['prapk_id']] = $row;
        }


        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Mentoring',
            'dataPrapk' => $dataPrapk,
            'dataHasil' => $dataHasil // Data hasil untuk ditampilkan di view
        ];

        return view('layout/form_prapk', $data);
    }


    public function form($pkId = null)
    {
        // Check if pkId is provided
        if ($pkId) {
            // Fetch user data using pkId
            $userData = $this->userModel->find($pkId);

            if ($userData) {
                // Fetch data for Prapk
                $dataPrapk = $this->prapkModel->orderBy('no ASC')->findAll();

                // Fetch data hasil based on the current user
                $userId = $this->session->get('user_id');
                $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUser($userId);

                // Ubah jadi array dengan key = prapk_id
                $dataHasil = [];
                foreach ($dataHasilRaw as $row) {
                    $dataHasil[$row['prapk_id']] = $row;
                }

                // Now check the user level and redirect accordingly
                switch ($userData->level_user) { // Use -> to access object properties
                    case 4:
                        // Redirect to PK2 page
                        return redirect()->to('/mentoring/pk2'); // Modify with the correct URL for PK2
                    case 5:
                        // Redirect to PK1 page
                        return redirect()->to('/mentoring/pk1'); // Modify with the correct URL for PK1
                    case 6:
                        // Redirect to Prapk page
                        // Send the data to the view for level 6 (Prapk page)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPrapk' => $dataPrapk,
                            'dataHasil' => $dataHasil // Data hasil for level 6
                        ];

                        return view('layout/form_prapk', $data); // View for Prapk
                    default:
                        // If the level doesn't match any of the expected ones, redirect to a default page
                        return redirect()->to('/mentoring');
                }
            } else {
                // If no user is found, redirect to a default page
                return redirect()->to('/mentoring');
            }
        } else {
            // If pkId is not provided, redirect to a default page
            return redirect()->to('/mentoring');
        }
    }



    public function simpan()
    {
        if ($this->request->isAJAX()) {
            try {
                // Ambil data dari request
                $userId = $this->session->get('user_id');
                $prapkId = (int) $this->request->getPost('prapk_id');
                $nilaiId = (int) $this->request->getPost('nilai_id');

                // Jika nilaiId == 0, artinya user ingin menghapus nilai (uncheck)
                if ($nilaiId === 0) {
                    $this->prapkHasilModel->where([
                        'user_id' => $userId,
                        'prapk_id' => $prapkId
                    ])->delete();

                    return $this->response->setJSON(['status' => 'ok']);
                }

                $model = new PrapkHasilModel();

                if ($model->simpanData($userId, $prapkId, $nilaiId)) {
                    return $this->response->setJSON(['status' => 'ok']);
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal menyimpan ke database'
                    ])->setStatusCode(500);
                }

            } catch (\Throwable $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ])->setStatusCode(500);
            }
        }

        return $this->response->setJSON(['status' => 'failed'])->setStatusCode(400);
    }

    public function tambah_kompetensi()
    {
        $kategori = $this->request->getVar('kategori');
        $kompetensi = $this->request->getVar('kompetensi');

        if ($kategori && $kompetensi) {
            // Ambil nilai 'no' terbesar dari tabel
            $lastData = $this->prapkModel->orderBy('no', 'DESC')->first();
            $lastNo = $lastData ? (int) $lastData['no'] : 0;
            $newNo = $lastNo + 1;

            // Simpan data kompetensi baru
            $this->prapkModel->insert([
                'kategori' => $kategori,
                'kompetensi' => $kompetensi,
                'no' => $newNo
            ]);

            return redirect()->back()->with('message', 'Kompetensi berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Kategori dan kompetensi tidak boleh kosong');
    }


    public function update()
    {
        $id = $this->request->getPost('id');
        $this->db->table('tabel_kompetensi')->where('id', $id)->update([
            'kategori' => $this->request->getPost('kategori'),
            'kompetensi' => $this->request->getPost('kompetensi')
        ]);
        return redirect()->back()->with('message', 'Kompetensi berhasil diperbarui');
    }

    public function hapus($id)
    {
        $this->db->table('tabel_kompetensi')->where('id', $id)->delete();
        return redirect()->back()->with('message', 'Kompetensi berhasil dihapus');
    }


    public function tambah_kategori()
    {
        $kategori = $this->request->getVar('nama');

        if ($kategori) {
            // Ambil nilai 'no' terbesar dari tabel
            $lastData = $this->prapkModel->orderBy('no', 'DESC')->first();
            $lastNo = $lastData ? (int) $lastData['no'] : 0;
            $newNo = $lastNo + 1;

            // Simpan kategori baru dengan kompetensi NULL dan no berikutnya
            $this->prapkModel->insert([
                'kategori' => $kategori,
                'kompetensi' => null,  // NULL, bukan string kosong
                'no' => $newNo
            ]);

            return redirect()->back()->with('message', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Nama kategori tidak boleh kosong');
    }

    public function edit_kategori()
    {
        $old = $this->request->getVar('old');
        $new = $this->request->getVar('new');
        if ($old && $new) {
            $this->prapkModel->where('kategori', $old)->set(['kategori' => $new])->update();
            return redirect()->back()->with('message', 'Kategori berhasil diperbarui');
        }
        return redirect()->back()->with('error', 'Data kategori tidak valid');
    }
    public function hapus_kategori()
    {
        $kategori = $this->request->getVar('kategori');

        if ($kategori) {
            // Ambil semua data prapk yang memiliki kategori ini
            $dataKategori = $this->prapkModel->where('kategori', $kategori)->findAll();

            foreach ($dataKategori as $row) {
                // Gunakan model untuk hapus data hasil
                $this->prapkHasilModel->where('prapk_id', $row['id'])->delete();
            }

            // Hapus data utama dari tabel_prapk
            $this->prapkModel->where('kategori', $kategori)->delete();

            return redirect()->back()->with('message', 'Kategori dan semua kompetensi di dalamnya berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Kategori tidak ditemukan');
    }

    public function update_kompetensi()
    {
        $id = $this->request->getVar('id');
        $kompetensi = $this->request->getVar('kompetensi');
        if ($id && $kompetensi) {
            $this->prapkModel->update($id, ['kompetensi' => $kompetensi]);
            return redirect()->back()->with('message', 'Kompetensi berhasil diperbarui');
        }
        return redirect()->back()->with('error', 'Data update tidak valid');
    }
    public function hapus_kompetensi($id)
    {
        // Ambil data kompetensi sebelum dihapus
        $kompetensi = $this->prapkModel->find($id);

        if (!$kompetensi) {
            return redirect()->back()->with('error', 'ID kompetensi tidak ditemukan');
        }

        $noHapus = $kompetensi['no'];
        $kategori = $kompetensi['kategori'];

        // Hapus dulu hasilnya
        $this->prapkHasilModel->where('prapk_id', $id)->delete();

        // Hapus kompetensinya
        $this->prapkModel->delete($id);

        // Ambil semua kompetensi di kategori yang sama dan no > yang dihapus
        $kompetensiSetelah = $this->prapkModel
            ->where('kategori', $kategori)
            ->where('no >', $noHapus)
            ->orderBy('no', 'ASC')
            ->findAll();

        // Turunkan semua nomornya 1 tingkat
        foreach ($kompetensiSetelah as $item) {
            $this->prapkModel->update($item['id'], ['no' => $item['no'] - 1]);
        }

        return redirect()->back()->with('message', 'Kompetensi berhasil dihapus dan nomor diperbarui');
    }



}
