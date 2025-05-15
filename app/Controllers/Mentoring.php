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
        $pkId = (int) $pkId;
        $mentorId = (int) $this->session->get('user_id');

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

                // Depending on the user level, use the appropriate model for fetching results
                $dataHasil = [];
                switch ($userData->level_user) {
                    case 3:
                        $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId); // Use PK3 model
                        break;
                    case 4:
                        $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId); // Use PK2 model
                        break;
                    case 5:
                        $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId); // Use PK1 model
                        break;
                    case 6:
                        $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUserAndMentor($pkId, $mentorId); // Use Prapk model
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
                            'dataPk' => $dataPk3,
                            'dataHasil' => $dataHasil, // Data hasil for PK3
                            'pkId' => $pkId, 
                            'userData'=>$userData
                        ];
                        return view('layout/form', $data); // View for PK3
                        break;

                    case 4:
                        // Redirect to PK2 page (case 4)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPk' => $dataPk2,
                            'dataHasil' => $dataHasil, // Data hasil for PK2
                            'pkId' => $pkId,
                            'userData'=>$userData  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for PK2
                        break;

                    case 5:
                        // Redirect to PK1 page (case 5)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPk' => $dataPk1,
                            'dataHasil' => $dataHasil, // Data hasil for PK1
                            'pkId' => $pkId,
                            'userData'=>$userData  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form', $data); // View for PK1
                        break;

                    case 6:
                        // Redirect to Prapk page (case 6)
                        $data = [
                            'level_akses' => $this->session->get('level'),
                            'dtmenu' => $this->tampil_menu($this->session->get('level')),
                            'nama_menu' => 'Mentoring',
                            'dataPk' => $dataPrapk,
                            'dataHasil' => $dataHasil, // Data hasil for Prapk
                            'pkId' => $pkId,
                            'userData'=>$userData  // Pastikan userId juga dikirim ke view
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
                $mentorId = $this->session->get('user_id');
                $user = $this->userModel->getUserById($userId);
                $levelUser = (int) $user->level_user;
                $catatan = trim((string) $this->request->getPost('catatan'));


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
                    'mentor_id' => $mentorId,
                    'kompetensi_id' => $kompetensiId,
                ])->first();

                $data = [
                    'user_id' => $userId,
                    'mentor_id' => $mentorId,
                    'kompetensi_id' => $kompetensiId,
                    'nilai_id' => $nilaiId,
                    'catatan' => $catatan
                ];

                if ($existing) {
                    $model->update($existing['id'], $data);
                } else {
                    $model->insert($data);
                }
                // Kirim data dalam format JSON ke JavaScript
                return $this->response->setJSON([
                    'status' => 'ok',
                    'data' => $data
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





    



}
