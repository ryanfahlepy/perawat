<?php

namespace App\Controllers;

use App\Models\PrapkModel;
use App\Models\Pk1Model;
use App\Models\Pk2Model;
use App\Models\Pk3Model;
use App\Models\PrapkHasilModel;
use App\Models\Pk1HasilModel;
use App\Models\Pk2HasilModel;
use App\Models\Pk3HasilModel;
use App\Models\UserModel;
use App\Models\UserMentorAksesModel;

class Mentoring extends BaseController
{
    protected $session;
    protected $prapkModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->prapkModel = new PrapkModel(); // simpan ke properti kelas
        $this->pk1Model = new Pk1Model(); // simpan ke properti kelas
        $this->pk2Model = new Pk2Model(); // simpan ke properti kelas
        $this->pk3Model = new Pk3Model(); // simpan ke properti kelas
        $this->prapkHasilModel = new PrapkHasilModel(); // simpan ke properti kelas
        $this->pk1HasilModel = new Pk1HasilModel(); // simpan ke properti kelas
        $this->pk2HasilModel = new Pk2HasilModel(); // simpan ke properti kelas
        $this->pk3HasilModel = new Pk3HasilModel(); // simpan ke properti kelas
        $this->userModel = new UserModel(); // Inisialisasi model User
        $this->usermentoraksesModel = new UserMentorAksesModel(); // Inisialisasi model User
    }

    // Fungsi index untuk menampilkan daftar user dan data mentoring
    public function index()
    {
        $loggedInUserId = $this->session->get('user_id'); // Ambil user_id dari session


        // Ambil hanya user_id-nya
        $loggedInUserId = $this->session->get('user_id');
        $dataUser = $this->userModel->getMenteesWithLevelName($loggedInUserId)->asArray()->findAll();

        // Ambil mentor yang berlevel 2 dan 3 (semua kemungkinan mentor)
        $mentorLevel2 = $this->userModel->getUserByLevel([2])->getResultArray();
        $mentorLevel3 = $this->userModel->getUserByLevel([3])->getResultArray();

        // Susun opsi mentor untuk masing-masing user
        $mentorOptions = [];
        foreach ($dataUser as $user) {
            if ($user['level_user'] == 3) {
                $mentorOptions[$user['id']] = $mentorLevel2;
            } elseif (in_array($user['level_user'], [4, 5, 6])) {
                $mentorOptions[$user['id']] = $mentorLevel3;
            } else {
                $mentorOptions[$user['id']] = [];
            }
        }



        // Ambil data mentor yang sudah diset
        $aksesData = $this->usermentoraksesModel->findAll();
        $userMentorMapping = [];
        foreach ($aksesData as $a) {
            $userMentorMapping[$a['user_id']] = $a['mentor_id'];
        }

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Mentoring',
            'dataUser' => $dataUser,
            'mentorOptions' => $mentorOptions,
            'userMentorMapping' => $userMentorMapping,
        ];

        return view('layout/mentoring', $data);
    }

    public function form($pkId = null)
    {
        // Check if pkId is provided
        if ($pkId) {
            // Fetch user data using pkId
            $userData = $this->userModel->find($pkId);

            if ($userData) {
                // Fetch data for Prapk, PK1, PK2, PK3
                $dataPrapk = $this->prapkModel->orderBy('no ASC')->findAll();
                $dataPk1 = $this->pk1Model->orderBy('no ASC')->findAll();
                $dataPk2 = $this->pk2Model->orderBy('no ASC')->findAll();
                $dataPk3 = $this->pk3Model->orderBy('no ASC')->findAll();

                // Fetch data hasil based on the current user
                $userId = $this->session->get('user_id');

                // Depending on the user level, use the appropriate model for fetching results
                $dataHasil = [];
                switch ($userData->level_user) {
                    case 3:
                        $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUser($userId); // Use PK3 model
                        break;
                    case 4:
                        $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUser($userId); // Use PK2 model
                        break;
                    case 5:
                        $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUser($userId); // Use PK1 model
                        break;
                    case 6:
                        $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUser($userId); // Use Prapk model
                        break;
                    default:
                        return redirect()->to('/mentoring');
                }

                // Ubah jadi array dengan key = kompetensi_id
                foreach ($dataHasilRaw as $row) {
                    $dataHasil[$row['kompetensi_id']] = $row;
                }

                // Now check the user level and prepare data accordingly
                switch ($userData->level_user) {
                    case 3:
                        // Redirect to PK3 page (case 3)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPrapk' => $dataPk3,
                            'dataHasil' => $dataHasil, // Data hasil for PK3
                            'userId' => $userId,  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for PK3
                        break;

                    case 4:
                        // Redirect to PK2 page (case 4)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPrapk' => $dataPk2,
                            'dataHasil' => $dataHasil, // Data hasil for PK2
                            'userId' => $userId,  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for PK2
                        break;

                    case 5:
                        // Redirect to PK1 page (case 5)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPrapk' => $dataPk1,
                            'dataHasil' => $dataHasil, // Data hasil for PK1
                            'userId' => $userId,  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for PK1
                        break;

                    case 6:
                        // Redirect to Prapk page (case 6)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPrapk' => $dataPrapk,
                            'dataHasil' => $dataHasil, // Data hasil for Prapk
                            'userId' => $userId,  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for Prapk
                        break;

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
                $userId = (int) $this->request->getPost('user_id');
                $kompetensiId = (int) $this->request->getPost('kompetensi_id');
                $nilaiId = (int) $this->request->getPost('nilai_id');
                $mentorId = $this->session->get('user_id'); // ID mentor
                $levelUser = (int) $this->session->get('level');

                // Pilih model sesuai level peserta (bukan level mentor)
                switch ($levelUser) {
                    case 3:
                        $model = $this->pk3HasilModel;
                        break;
                    case 4:
                        $model = $this->pk2HasilModel;
                        break;
                    case 5:
                        $model = $this->pk1HasilModel;
                        break;
                    case 6:
                        $model = $this->prapkHasilModel;
                        break;
                    default:
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Level user tidak valid'
                        ])->setStatusCode(400);
                }

                // Hapus jika unchecked
                if ($nilaiId === 0) {
                    $model->where([
                        'user_id' => $userId,
                        'kompetensi_id' => $kompetensiId
                    ])->delete();

                    // Mengirim data dalam format JSON
                    return $this->response->setJSON(['status' => 'ok']);
                }

                // Cek apakah sudah ada data
                $existing = $model->where([
                    'user_id' => $userId,
                    'kompetensi_id' => $kompetensiId
                ])->first();

                $data = [
                    'user_id' => $userId,
                    'mentor_id' => $mentorId,
                    'kompetensi_id' => $kompetensiId,
                    'nilai_id' => $nilaiId
                ];

                if ($existing) {
                    $model->update($existing['id'], $data);
                } else {
                    $model->insert($data);
                }

                
                // Kirim data dalam format JSON ke JavaScript
                return $this->response->setJSON([
                    'status' => 'ok',
                    'data' => $data // Pastikan ini adalah format JSON yang valid
                ]);

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
                $this->prapkHasilModel->where('kompetensi_id', $row['id'])->delete();
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
        $this->prapkHasilModel->where('kompetensi_id', $id)->delete();

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
