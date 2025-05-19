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
use App\Models\DaftarFormModel;

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
        $this->userMentorAksesModel = new UserMentorAksesModel(); // Inisialisasi model User
        $this->daftarFormModel = new DaftarFormModel(); // tambahkan ini
    }

    // Fungsi index untuk menampilkan daftar user dan data mentoring
    public function index()
    {
        $session = $this->session;
        $loggedInUserId = $session->get('user_id');
        $level = $session->get('level');

        // Ambil data user sendiri
        $userData = $this->userModel->find($loggedInUserId);
        $levelUser = $userData->level_user ?? null;

        // Ambil daftar mentees untuk user yang jadi mentor
        $dataUser = $this->userModel->getMenteesWithLevelName($loggedInUserId)->asArray()->findAll();

        // Ambil data mentor level 2 & 3 untuk opsi mentor
        $mentorLevel2 = $this->userModel->getUserByLevel([2])->getResultArray();
        $mentorLevel3 = $this->userModel->getUserByLevel([3])->getResultArray();

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

        // Ambil mapping user → mentor yang sudah diset
        $aksesData = $this->userMentorAksesModel->findAll();
        $userMentorMapping = [];
        foreach ($aksesData as $a) {
            $userMentorMapping[$a['user_id']] = $a['mentor_id'];
        }

        $mentorData = $this->userMentorAksesModel->getMentorIdByUserId($loggedInUserId);
        $mentorId = $mentorData['mentor_id'] ?? null; // Pastikan hanya ambil ID-nya
        $mentorNama = $this->userModel->getUserById($mentorId);

        // Default kosong
        $dataPk = [];
        $dataHasilRaw = [];

        if ($mentorId) {
            switch ($levelUser) {
                case 3:
                    $dataPk = $this->pk3Model->findAll();
                    $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUserAndMentor($loggedInUserId, $mentorId);
                    break;
                case 4:
                    $dataPk = $this->pk3Model->findAll();
                    $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUserAndMentor($loggedInUserId, $mentorId);
                    break;
                case 5:
                    $dataPk = $this->pk3Model->findAll();
                    $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUserAndMentor($loggedInUserId, $mentorId);
                    break;
                case 6:
                    $dataPk = $this->pk3Model->findAll();
                    $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUserAndMentor($loggedInUserId, $mentorId);
                    break;
                default:
                    // Level tidak dikenali
                    $dataPk = [];
                    $dataHasilRaw = [];
                    break;
            }
        } else {
            // Kalau mentor belum diset, biar kosong saja
            $dataPk = [];
            $dataHasilRaw = [];
        }


        // Susun data hasil indexed by kompetensi_id agar mudah akses di view
        $dataHasil = [];
        foreach ($dataHasilRaw as $hasil) {
            $dataHasil[$hasil['kompetensi_id']] = $hasil;
        }

        
        $daftarForms = $this->daftarFormModel->where('user_id', (int) $loggedInUserId)
            ->where('mentor_id', $mentorId)
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();

        

        // Tambahkan formatting tanggal dan progress ke setiap form
        foreach ($daftarForms as &$form) {
            $form['tanggal_mulai_formatted'] = date('d-m-Y H:i', strtotime($form['tanggal_mulai']));
            $form['tanggal_berakhir_formatted'] = $form['tanggal_berakhir'] ? date('d-m-Y H:i', strtotime($form['tanggal_berakhir'])) : '-';

            // Contoh hitung progress: misal berdasarkan tanggal berakhir dan sekarang
            if ($form['tanggal_berakhir']) {
                $start = strtotime($form['tanggal_mulai']);
                $end = strtotime($form['tanggal_berakhir']);
                $now = time();
                if ($now >= $end) {
                    $progress = 100;
                } elseif ($now <= $start) {
                    $progress = 0;
                } else {
                    $progress = round((($now - $start) / ($end - $start)) * 100);
                }
            } else {
                $progress = 0;
            }
            $form['progress'] = $progress;
        }
        unset($form); // break reference

        // Kirim semua data ke view
        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Mentoring',
            'dataUser' => $dataUser,
            'mentorOptions' => $mentorOptions,
            'userMentorMapping' => $userMentorMapping,
            'dataPk' => $dataPk,
            'dataHasil' => $dataHasil,
            'userId' => $this->session->get('user_id'),
            'session' => $session,
            'level' => $level,
            'daftarForms' => $daftarForms,
            'userData' => $userData,
            'mentorNama' => $mentorNama,
        ];


        return view('layout/mentoring', $data);
    }

    public function form($pkId = null, $formId)
    {
        $pkId = (int) $pkId;
        $formId = (int) $formId;
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
                        $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK3 model
                        break;
                    case 4:
                        $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK2 model
                        break;
                    case 5:
                        $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK1 model
                        break;
                    case 6:
                        $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use Prapk model
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
                            'formid' => $formId,
                            'userData' => $userData
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
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
    public function form_hasil($pkId = null, $formId)
    {
        $pkId = (int) $pkId;
        $formId = (int) $formId;
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
                        $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK3 model
                        break;
                    case 4:
                        $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK2 model
                        break;
                    case 5:
                        $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use PK1 model
                        break;
                    case 6:
                        $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUserAndMentor($pkId, $mentorId, $formId); // Use Prapk model
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
                            'formid' => $formId,
                            'userData' => $userData
                        ];
                        return view('layout/form_hasil', $data); // View for PK3
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form_hasil', $data); // View for PK2
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form_hasil', $data); // View for PK1
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
                            'formid' => $formId,
                            'userData' => $userData  // Pastikan userId juga dikirim ke view
                        ];
                        return view('layout/form_hasil', $data); // View for Prapk
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
                $formId = (int) $this->request->getPost('form_id');
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
                    'form_id' => $formId,
                    'user_id' => $userId,
                    'mentor_id' => $mentorId,
                    'kompetensi_id' => $kompetensiId,
                ])->first();

                $data = [
                    'form_id' => $formId,
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

    public function daftar_form($userId = null)
    {
        $session = session();
        $loggedInUserId = $session->get('user_id');
        $level = $session->get('level');

        if ($userId === null) {
            return redirect()->to('/mentoring')->with('error', 'User ID tidak valid');
        }

        $userData = $this->userModel->find($userId);
        if (!$userData) {
            return redirect()->to('/mentoring')->with('error', 'User tidak ditemukan');
        }

        $mentorData = $this->userMentorAksesModel->where('user_id', $userId)->first();
        if (!$mentorData || $mentorData['mentor_id'] != $loggedInUserId) {
            return redirect()->to('/mentoring')->with('error', 'Anda bukan mentor dari user ini');
        }

        $daftarForms = $this->daftarFormModel->where('user_id', $userId)
            ->where('mentor_id', $loggedInUserId)
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();

        // Tambahkan formatting tanggal dan progress ke setiap form
        foreach ($daftarForms as &$form) {
            $form['tanggal_mulai_formatted'] = date('d-m-Y H:i', strtotime($form['tanggal_mulai']));
            $form['tanggal_berakhir_formatted'] = $form['tanggal_berakhir'] ? date('d-m-Y H:i', strtotime($form['tanggal_berakhir'])) : '-';

            // Contoh hitung progress: misal berdasarkan tanggal berakhir dan sekarang
            if ($form['tanggal_berakhir']) {
                $start = strtotime($form['tanggal_mulai']);
                $end = strtotime($form['tanggal_berakhir']);
                $now = time();
                if ($now >= $end) {
                    $progress = 100;
                } elseif ($now <= $start) {
                    $progress = 0;
                } else {
                    $progress = round((($now - $start) / ($end - $start)) * 100);
                }
            } else {
                $progress = 0;
            }
            $form['progress'] = $progress;
        }
        unset($form); // break reference

        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Daftar Form',
            'daftarForms' => $daftarForms,
            'userData' => $userData,
            'session' => $session,
        ];

        return view('layout/daftar_form', $data);
    }

    public function buat_form($userId)
    {
        $mentorId = session()->get('user_id');
        $nama = $this->request->getPost('nama_form');
        $mentorData = $this->userMentorAksesModel->where('user_id', $userId)
            ->where('mentor_id', $mentorId)
            ->first();

        if (!$mentorData) {
            return redirect()->to('/mentoring')->with('error', 'Anda bukan mentor dari user ini');
        }

        $activeForm = $this->daftarFormModel->where('user_id', $userId)
            ->where('mentor_id', $mentorId)
            ->where('tanggal_berakhir', null)
            ->first();

        if ($activeForm) {
            return redirect()->to('/mentoring/daftar_form/' . $userId)
                ->with('error', 'Masih ada form yang aktif. Akhiri terlebih dahulu sebelum membuat baru.');
        }

        $rules = [
            'nama_form' => 'required|string|min_length[3]',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_berakhir' => 'permit_empty|valid_date'
        ];


        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->daftarFormModel->insert([
            'nama' => $nama,
            'user_id' => $userId,
            'mentor_id' => $mentorId,
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_berakhir' => $this->request->getPost('tanggal_berakhir') ?: null,
        ]);

        return redirect()->to('/mentoring/daftar_form/' . $userId)
            ->with('success', 'Form baru berhasil dibuat');
    }



}
