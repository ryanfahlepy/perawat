<?php

namespace App\Controllers;

use App\Models\KinerjaModel;
use App\Models\HasilKinerjaModel;
use App\Models\User_levelModel;
use App\Models\PicaKinerjaModel;
use App\Models\UserModel;

class Ekinerja extends BaseController
{
    protected $session;
    protected $kinerjaModel;
    protected $hasilKinerjaModel;
    protected $user_levelModel;
    protected $picaModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->kinerjaModel = new KinerjaModel();
        $this->user_levelModel = new User_levelModel();
        $this->hasilKinerjaModel = new HasilKinerjaModel();
        $this->picaModel = new PicaKinerjaModel();
        $this->userModel = new UserModel();
    }
    public function index()
    {
        $user_id = $this->session->get('user_id');
        $level = $this->session->get('level');

        $tahun = $this->request->getGet('tahun');
        if (!$tahun) {
            $tahun = date('Y');
        }

        // Ambil data kinerja yang join ke hasil_kinerja berdasarkan user dan tahun
        $builder = $this->kinerjaModel
            ->select('tabel_kinerja.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_kinerja.level_user', 'left');

        $dataKinerja = $builder->asArray()->findAll();


        $dataKinerja = $builder->asArray()->findAll();

        // Ambil semua hasil kinerja user ini untuk tahun terpilih
        $hasilKinerjaSemua = $this->hasilKinerjaModel
            ->where('user_id', $user_id)
            ->where('tahun', $tahun)
            ->findAll();

        // Buat map hasil per kinerja_id dan bulan
        $mapHasil = [];
        foreach ($hasilKinerjaSemua as $row) {
            $bulan = $row['bulan'] ?? null;
            if ($bulan) {
                $mapHasil[$row['kinerja_id']][$bulan] = [
                    'hasil' => $row['hasil'],
                    'status' => $row['status'] ?? '',
                ];
            } else {
                $mapHasil[$row['kinerja_id']]['tahunan'] = [
                    'hasil' => $row['hasil'],
                    'status' => $row['status'] ?? '',
                ];
            }
        }

        // ⬇️ TARUH DI SINI
        foreach ($dataKinerja as &$item) {
            $id = $item['id'];
            $periode = strtolower($item['periode_assesment']);

            if ($periode == 'bulanan') {
                $item['hasil_bulanan'] = [];
                $item['status_bulanan'] = [];
                for ($b = 1; $b <= 12; $b++) {
                    if (isset($mapHasil[$id][$b])) {
                        $hasil = $mapHasil[$id][$b]['hasil'];
                        $status = $mapHasil[$id][$b]['status'] ?? null;
                        $item['hasil_bulanan'][$b] = ($status === 'disetujui') ? $hasil : ucfirst($status ?? '-');
                        $item['status_bulanan'][$b] = $status ?? 'Tidak Ada Data';
                    } else {
                        $item['hasil_bulanan'][$b] = '-';
                        $item['status_bulanan'][$b] = 'Tidak Ada Data';
                    }
                }
            } else {
                if (isset($mapHasil[$id]['tahunan'])) {
                    $hasil = $mapHasil[$id]['tahunan']['hasil'];
                    $status = $mapHasil[$id]['tahunan']['status'] ?? null;
                    $item['hasil_aktual'] = ($status === 'disetujui') ? $hasil : ucfirst($status ?? '-');
                    $item['status_tahunan'] = $status ?? 'Tidak Ada Data';
                } else {
                    $item['hasil_aktual'] = '-';
                    $item['status_tahunan'] = 'Tidak Ada Data';
                }
            }
        }
        $user = $this->userModel->find($user_id);
        $users = $this->userModel->getAllUsersWithLevel();
        if ($level == 2) {
            return view('/layout/daftar_kinerja_perawat', [
                'dtmenu' => $this->tampil_menu($level),
                'nama_menu' => 'Kinerja',
                'user' => $user,

                'users' => $users,

            ]);
        }



        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Kinerja',
            'data_kinerja' => $dataKinerja,
            'user' => $user,
            'user_levels' => $this->user_levelModel->findAll(),
            'daftar_tahun' => [],
            'tahun_terpilih' => $tahun,
        ];

        return view('layout/ekinerja', $data);
    }

    public function lihat_kinerja($user_id)
    {


        $level = $this->session->get('level');

        $tahun = $this->request->getGet('tahun');
        if (!$tahun) {
            $tahun = date('Y');
        }

        // Ambil data kinerja yang join ke hasil_kinerja berdasarkan user dan tahun
        $builder = $this->kinerjaModel
            ->select('tabel_kinerja.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_kinerja.level_user', 'left');

        $dataKinerja = $builder->asArray()->findAll();


        $dataKinerja = $builder->asArray()->findAll();

        // Ambil semua hasil kinerja user ini untuk tahun terpilih
        $hasilKinerjaSemua = $this->hasilKinerjaModel
            ->where('user_id', $user_id)
            ->where('tahun', $tahun)
            ->findAll();

        // Buat map hasil per kinerja_id dan bulan
        $mapHasil = [];
        foreach ($hasilKinerjaSemua as $row) {
            $bulan = $row['bulan'] ?? null;
            if ($bulan) {
                $mapHasil[$row['kinerja_id']][$bulan] = [
                    'hasil' => $row['hasil'],
                    'status' => $row['status'] ?? '',
                ];
            } else {
                $mapHasil[$row['kinerja_id']]['tahunan'] = [
                    'hasil' => $row['hasil'],
                    'status' => $row['status'] ?? '',
                ];
            }
        }

        // ⬇️ TARUH DI SINI
        foreach ($dataKinerja as &$item) {
            $id = $item['id'];
            $periode = strtolower($item['periode_assesment']);

            if ($periode == 'bulanan') {
                $item['hasil_bulanan'] = [];
                $item['status_bulanan'] = [];
                for ($b = 1; $b <= 12; $b++) {
                    if (isset($mapHasil[$id][$b])) {
                        $hasil = $mapHasil[$id][$b]['hasil'];
                        $status = $mapHasil[$id][$b]['status'] ?? null;
                        $item['hasil_bulanan'][$b] = ($status === 'disetujui') ? $hasil : ucfirst($status ?? '-');
                        $item['status_bulanan'][$b] = $status ?? 'Tidak Ada Data';
                    } else {
                        $item['hasil_bulanan'][$b] = '-';
                        $item['status_bulanan'][$b] = 'Tidak Ada Data';
                    }
                }
            } else {
                if (isset($mapHasil[$id]['tahunan'])) {
                    $hasil = $mapHasil[$id]['tahunan']['hasil'];
                    $status = $mapHasil[$id]['tahunan']['status'] ?? null;
                    $item['hasil_aktual'] = ($status === 'disetujui') ? $hasil : ucfirst($status ?? '-');
                    $item['status_tahunan'] = $status ?? 'Tidak Ada Data';
                } else {
                    $item['hasil_aktual'] = '-';
                    $item['status_tahunan'] = 'Tidak Ada Data';
                }
            }
        }

        $user = $this->userModel->find($user_id);

        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Kinerja',
            'user' => $user,
            'user_id' => $user->id,
            'data_kinerja' => $dataKinerja,
            'user_levels' => $this->user_levelModel->findAll(),
            'daftar_tahun' => [],
            'tahun_terpilih' => $tahun,
        ];

        return view('layout/ekinerja_karu', $data);
    }

    public function get_hasil()
    {
        $kinerja_id = $this->request->getGet('kinerja_id');
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        // Ambil target dari tabel_kinerja
        $kinerjaModel = new \App\Models\KinerjaModel();
        $target = $kinerjaModel->find($kinerja_id)['target'] ?? '';

        // Ambil hasil dari tabel_hasil_kinerja
        $hasilModel = new \App\Models\HasilKinerjaModel();
        $hasilData = $hasilModel
            ->where('kinerja_id', $kinerja_id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan ?: null)
            ->first();

        $response = [
            'id' => $hasilData['id'] ?? '',
            'target' => $target,
            'hasil' => $hasilData['hasil'] ?? '',
            'catatan' => $hasilData['catatan'] ?? '',
            'berkas' => $hasilData['berkas'] ?? '',
        ];


        return $this->response->setJSON($response);
    }

    public function get_pica_by_kinerja()
    {
        $kinerja_id = $this->request->getGet('kinerja_id');
        $user_id = $this->request->getGet('user_id');
        $hasil_id = $this->request->getGet('hasil_id');

        $picaData = $this->picaModel
            ->where('kinerja_id', $kinerja_id)
            ->where('user_id', $user_id)
            ->where('hasil_id', $hasil_id)
            ->first();

        $response = [
            'problem_identification' => $picaData['problem_identification'] ?? '',
            'corrective_action' => $picaData['corrective_action'] ?? '',
            'due_date' => $picaData['due_date'] ?? '',
        ];

        return $this->response->setJSON($response);
    }



    public function update_hasil()
    {
        $user_id = $this->session->get('user_id');
        $kinerja_id = $this->request->getPost('kinerja_id');
        $hasil = $this->request->getPost('hasil');
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $bulan = $this->request->getPost('bulan');
        $nilai = null;
        $berkasPath = null;

        $target = $this->kinerjaModel->find($kinerja_id)['target'] ?? null;

        if (is_numeric($hasil) && is_numeric($target) && $target != 0) {
            $nilai = ($hasil / $target) * 100;
        } else {
            $nilai = 0; // ← default value supaya tidak error di database
        }


        $berkas = $this->request->getFile('berkas');
        if ($berkas && $berkas->isValid() && !$berkas->hasMoved()) {
            $newName = $berkas->getRandomName();
            $berkas->move('uploads/berkas_kinerja', $newName);
            $berkasPath = 'uploads/berkas_kinerja/' . $newName;
        }

        $problemidentification = $this->request->getPost('problem_identification');
        $correctiveaction = $this->request->getPost('corrective_action');
        $due = $this->request->getPost('due_date');

        $where = [
            'user_id' => $user_id,
            'kinerja_id' => $kinerja_id,
            'tahun' => $tahun,
        ];
        if (!empty($bulan)) {
            $where['bulan'] = $bulan;
        }

        $existing = $this->hasilKinerjaModel->where($where)->first();
        $hasilId = null;

        $dataToSave = [
            'user_id' => $user_id,
            'kinerja_id' => $kinerja_id,
            'tahun' => $tahun,
            'bulan' => $bulan ?: null,
            'hasil' => $hasil,
            'nilai' => $nilai,
            'status' => 'diajukan',
        ];

        if ($berkasPath !== null) {
            $dataToSave['berkas'] = $berkasPath;
        }

        if ($existing) {
            $this->hasilKinerjaModel->update($existing['id'], $dataToSave);
            $hasilId = $existing['id'];
        } else {
            $this->hasilKinerjaModel->insert($dataToSave);
            $hasilId = $this->hasilKinerjaModel->getInsertID();
        }

        if ($nilai < 100) {
            $dataPica = [
                'user_id' => $user_id,
                'kinerja_id' => $kinerja_id,
                'hasil_id' => $hasilId,
                'status' => 'diajukan',
                'problem_identification' => $problemidentification,
                'corrective_action' => $correctiveaction,
                'due_date' => $due,
            ];

            $existingPica = $this->picaModel
                ->where('kinerja_id', $kinerja_id)
                ->where('hasil_id', $hasilId)
                ->first();

            if ($existingPica) {
                $this->picaModel->update($existingPica['id'], $dataPica);
            } else {
                $this->picaModel->insert($dataPica);
            }
        }
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Data berhasil disimpan'
            ]);
        } else {
            // Untuk akses biasa dari form non-AJAX (jaga-jaga)
            return redirect()->to(base_url('ekinerja'))->with('success', 'Data hasil kinerja berhasil disimpan.');
        }


    }
    public function update_status()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getJSON();

            $hasil_id = $data->hasil_id ?? null;
            $status = $data->status ?? null;

            if (!$hasil_id || !$status) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
            }

          

            // Update tabel_hasil_kinerja (berdasarkan hasil_id)
            $successKpi = $this->hasilKinerjaModel->update_status_by_hasil_id($hasil_id, $status);

            // Update tabel_pica_kinerja (berdasarkan hasil_id)
            $successPica = $this->picaModel->update_status_pica($hasil_id, $status);

            if ($successKpi && $successPica) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Update gagal']);
            }
        }

        return $this->response->setStatusCode(403, 'Forbidden');
    }


}