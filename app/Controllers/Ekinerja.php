<?php

namespace App\Controllers;

use App\Models\KinerjaModel;
use App\Models\HasilKinerjaModel;
use App\Models\User_levelModel;
use App\Models\PicaKinerjaModel;

class Ekinerja extends BaseController
{
    protected $session;
    protected $kinerjaModel;
    protected $hasilKinerjaModel;
    protected $user_levelModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->kinerjaModel = new KinerjaModel();
        $this->user_levelModel = new User_levelModel();
        $this->hasilKinerjaModel = new HasilKinerjaModel();
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

        // // Ambil daftar tahun unik dari tabel hasil_kinerja
        // $daftarTahun = $this->hasilKinerjaModel
        //     ->select('tahun')
        //     ->distinct()
        //     ->orderBy('tahun', 'DESC')
        //     ->findAll();

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
        
        
    

        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Kinerja',
            'data_kinerja' => $dataKinerja,
            'user_levels' => $this->user_levelModel->findAll(),
            'daftar_tahun' => [],
            'tahun_terpilih' => $tahun,
        ];

        return view('layout/ekinerja', $data);
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
        'target' => $target,
        'hasil' => $hasilData['hasil'] ?? '',
        'catatan' => $hasilData['catatan'] ?? '',
        'berkas' => $hasilData['berkas'] ?? '',
    ];

    return $this->response->setJSON($response);
}

public function get_pica_by_kinerja($id)
{
    $model = new \App\Models\PicaKinerjaModel();
    $data = $model->where('kinerja_id', $id)->first();

    return $this->response->setJSON($data ?? []);
}


public function update_hasil()
{
    $user_id = $this->session->get('user_id');
    $kinerja_id = $this->request->getPost('kinerja_id');
    $hasil = $this->request->getPost('hasil');
    $tahun = $this->request->getPost('tahun') ?: date('Y');
    $bulan = $this->request->getPost('bulan') ?: null;
    $nilai = null;
    $berkasPath = null;

    // Validasi awal
    if (!$kinerja_id || !$hasil || !$user_id) {
        return redirect()->back()->with('error', 'Data tidak lengkap untuk menyimpan hasil kinerja.');
    }

    // Cek kinerja
    $kinerja = $this->kinerjaModel->find($kinerja_id);
    if (!$kinerja) {
        return redirect()->back()->with('error', 'Data kinerja tidak ditemukan.');
    }

    $target = $kinerja['target'];

    if (is_numeric($hasil) && is_numeric($target) && $target != 0) {
        $nilai = ($hasil / $target) * 100;
    }

    // Proses upload berkas
    $berkas = $this->request->getFile('berkas');
    if ($berkas && $berkas->isValid() && !$berkas->hasMoved()) {
        $newName = $berkas->getRandomName();
        $uploadPath = 'uploads/berkas_kinerja';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true); // Pastikan folder ada
        }
        $berkas->move($uploadPath, $newName);
        $berkasPath = $uploadPath . '/' . $newName;
    }

    // Cek apakah data hasil kinerja sudah ada
    $where = [
        'user_id' => $user_id,
        'kinerja_id' => $kinerja_id,
        'tahun' => $tahun,
        'bulan' => $bulan,
    ];

    $existing = $this->hasilKinerjaModel->where($where)->first();

    $dataToSave = [
        'user_id' => $user_id,
        'kinerja_id' => $kinerja_id,
        'tahun' => $tahun,
        'bulan' => $bulan,
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

    // Tambah PICA jika belum ada
    $picaModel = new \App\Models\PicaKinerjaModel();
    $existingPica = $picaModel
        ->where('kinerja_id', $kinerja_id)
        ->where('hasil_id', $hasilId)
        ->first();

    if (!$existingPica) {
        $picaModel->insert([
            'user_id' => $user_id,
            'kinerja_id' => $kinerja_id,
            'hasil_id' => $hasilId,
            'status' => 'diajukan',
        ]);
    }

    return redirect()->to(base_url('ekinerja'))->with('success', 'Data hasil kinerja berhasil disimpan.');
}



    
}
