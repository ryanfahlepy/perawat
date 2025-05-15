<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User_levelModel;
use App\Models\UserMentorAksesModel;
use App\Models\UserModel;
use App\Models\PrapkModel;
use App\Models\Pk1Model;
use App\Models\Pk2Model;
use App\Models\Pk3Model;
use App\Models\PrapkHasilModel;
use App\Models\Pk1HasilModel;
use App\Models\Pk2HasilModel;
use App\Models\Pk3HasilModel;

class Manmentor extends BaseController
{
    public function __construct()
    {
        $this->user_levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
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

    }
    public function index()
    {
        $dataUser = $this->userModel->getUserByLevel([3, 4, 5, 6])->getResultArray();
        $dataLevel = $this->user_levelModel->findByLevel([3, 4, 5, 6]);

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

        // Ambil data mentor dan tanggal mulai/berakhir dari tabel akses
        $aksesData = $this->userMentorAksesModel->findAll();

        $userMentorMapping = [];
        $userTanggalMapping = [];

        foreach ($aksesData as $a) {
            $userMentorMapping[$a['user_id']] = $a['mentor_id'];
            $userTanggalMapping[$a['user_id']] = [
                'tanggal_mulai' => $a['tanggal_mulai'],
                'tanggal_berakhir' => $a['tanggal_berakhir'],
            ];
        }

        // Gabungkan tanggal mulai dan berakhir ke $dataUser agar bisa tampil di view
        foreach ($dataUser as &$user) {
            $id = $user['id'];
            $user['tanggal_mulai'] = $userTanggalMapping[$id]['tanggal_mulai'] ?? null;
            $user['tanggal_berakhir'] = $userTanggalMapping[$id]['tanggal_berakhir'] ?? null;
        }
        unset($user);

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Kelola Mentor',
            'dataUser' => $dataUser,
            'dataLevel' => $dataLevel,
            'mentorOptions' => $mentorOptions,
            'userMentorMapping' => $userMentorMapping,
        ];

        return view('admin/kelolamentor', $data);
    }


    public function setMentor()
    {
        $user_id = $this->request->getPost('user_id');
        $mentor_id = $this->request->getPost('mentor_id');

        $existing = $this->userMentorAksesModel->where('user_id', $user_id)->first();
        if ($existing) {
            $this->userMentorAksesModel->update($existing['id'], [
                'mentor_id' => $mentor_id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->userMentorAksesModel->insert([
                'user_id' => $user_id,
                'mentor_id' => $mentor_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('message', 'Mentor berhasil diperbarui');
    }
    // Tambahkan fungsi bantu ini di controller Anda
    private function convertDatetimeLocalToSQL(?string $datetimeLocal): ?string
    {
        if (empty($datetimeLocal)) {
            return null;
        }
        $dt = str_replace('T', ' ', $datetimeLocal);
        if (strlen($dt) === 16) {
            $dt .= ':00'; // tambahkan detik jika belum ada
        }
        return $dt;
    }

    public function updateTanggal()
    {
        $userId = $this->request->getPost('user_id');
        $tanggalMulaiRaw = $this->request->getPost('tanggal_mulai');
        $tanggalBerakhirRaw = $this->request->getPost('tanggal_berakhir');

        $tanggalMulai = $this->convertDatetimeLocalToSQL($tanggalMulaiRaw);
        $tanggalBerakhir = $this->convertDatetimeLocalToSQL($tanggalBerakhirRaw);

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID tidak ditemukan']);
        }

        if (!empty($tanggalMulai) && strtotime($tanggalMulai) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tanggal mulai tidak valid']);
        }
        if (!empty($tanggalBerakhir) && strtotime($tanggalBerakhir) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tanggal berakhir tidak valid']);
        }

        $existing = $this->userMentorAksesModel->where('user_id', $userId)->first();

        $dataUpdate = [
            'tanggal_mulai' => $tanggalMulai !== '' ? $tanggalMulai : null,
            'tanggal_berakhir' => $tanggalBerakhir !== '' ? $tanggalBerakhir : null,
        ];

        if ($existing) {
            $this->userMentorAksesModel->update($existing['id'], $dataUpdate);
        } else {
            $dataUpdate['user_id'] = $userId;
            $this->userMentorAksesModel->insert($dataUpdate);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Tanggal berhasil diperbarui']);
    }




    public function kelolaform($levelId = null)
    {
        $levelId = (int) $levelId;
        $levelAkses = $this->user_levelModel->find($levelId);
        $namaLevel = $levelAkses->nama_level;


        // Siapkan data kompetensi sesuai level
        switch ($levelId) {
            case 3:
                $dataKompetensi = $this->pk3Model->orderBy('no ASC')->findAll();
                break;
            case 4:
                $dataKompetensi = $this->pk2Model->orderBy('no ASC')->findAll();
                break;
            case 5:
                $dataKompetensi = $this->pk1Model->orderBy('no ASC')->findAll();
                break;
            case 6:
                $dataKompetensi = $this->prapkModel->orderBy('no ASC')->findAll();
                break;
            default:
                return redirect()->to('/admin/manmentor');
        }

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Kelola Mentor',
            'dataKompetensi' => $dataKompetensi,
            'levelId' => $levelId,
            'namaLevel' => $namaLevel
        ];

        return view('admin/kelolaform', $data);
    }

    public function tambah_kompetensi($levelId = null)
    {
        $levelId = (int) $levelId;
        $kategori = $this->request->getVar('kategori');
        $kompetensi = $this->request->getVar('kompetensi');

        // Pilih model berdasarkan level
        switch ($levelId) {
            case 3:
                $model = $this->pk3Model;
                break;
            case 4:
                $model = $this->pk2Model;
                break;
            case 5:
                $model = $this->pk1Model;
                break;
            case 6:
                $model = $this->prapkModel;
                break;
            default:
                return redirect()->to('/admin/manmentor');
        }


        if ($kategori && $kompetensi) {
            // Ambil 'no' terakhir dari model yang sesuai
            $lastData = $model->orderBy('no', 'DESC')->first();
            $lastNo = $lastData ? (int) $lastData['no'] : 0;
            $newNo = $lastNo + 1;

            // Simpan data kompetensi baru
            $model->insert([
                'kategori' => $kategori,
                'kompetensi' => $kompetensi,
                'no' => $newNo
            ]);

            return redirect()->back()->with('message', 'Kompetensi berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Kategori dan kompetensi tidak boleh kosong');
    }

    public function update_kompetensi()
    {
        $id = $this->request->getVar('id');
        $kompetensi = $this->request->getVar('kompetensi');
        $levelId = $this->request->getVar('level_id');

        if ($id && $kompetensi && $levelId) {
            // Tentukan model berdasarkan level
            switch ((int) $levelId) {
                case 3:
                    $model = $this->pk3Model;
                    break;
                case 4:
                    $model = $this->pk2Model;
                    break;
                case 5:
                    $model = $this->pk1Model;
                    break;
                case 6:
                    $model = $this->prapkModel;
                    break;
                default:
                    return redirect()->back()->with('error', 'Level tidak dikenali');
            }

            // Lakukan update
            $model->update($id, ['kompetensi' => $kompetensi]);
            return redirect()->back()->with('message', 'Kompetensi berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Data update tidak valid');
    }

    public function hapus_kompetensi($id, $levelId)
    {
        $levelId = (int) $levelId;

        // Tentukan model berdasarkan level
        switch ($levelId) {
            case 3:
                $model = $this->pk3Model;
                $hasilModel = $this->pk3HasilModel ?? null;
                break;
            case 4:
                $model = $this->pk2Model;
                $hasilModel = $this->pk2HasilModel ?? null;
                break;
            case 5:
                $model = $this->pk1Model;
                $hasilModel = $this->pk1HasilModel ?? null;
                break;
            case 6:
                $model = $this->prapkModel;
                $hasilModel = $this->prapkHasilModel ?? null;
                break;
            default:
                return redirect()->back()->with('error', 'Level tidak valid');
        }

        $kompetensi = $model->find($id);

        if (!$kompetensi) {
            return redirect()->back()->with('error', 'ID kompetensi tidak ditemukan');
        }

        $noHapus = $kompetensi['no'];
        $kategori = $kompetensi['kategori'];

        // Hapus hasil (jika ada model hasil terkait)
        if ($hasilModel) {
            $hasilModel->where('kompetensi_id', $id)->delete();
        }

        // Hapus kompetensinya
        $model->delete($id);

        // Ambil semua kompetensi di kategori sama dan no > yang dihapus
        $kompetensiSetelah = $model->where('kategori', $kategori)
            ->where('no >', $noHapus)
            ->orderBy('no', 'ASC')
            ->findAll();

        foreach ($kompetensiSetelah as $item) {
            $model->update($item['id'], ['no' => $item['no'] - 1]);
        }

        return redirect()->back()->with('message', 'Kompetensi berhasil dihapus dan nomor diperbarui');
    }
    public function tambah_kategori()
    {
        $kategori = $this->request->getVar('nama');
        $levelId = (int) $this->request->getVar('levelId');

        if (!$levelId) {
            return redirect()->back()->with('error', 'Level ID tidak valid');
        }

        if ($kategori) {
            // Pilih model berdasarkan levelId, sama seperti di tambah_kompetensi()
            switch ($levelId) {
                case 3:
                    $model = $this->pk3Model;
                    break;
                case 4:
                    $model = $this->pk2Model;
                    break;
                case 5:
                    $model = $this->pk1Model;
                    break;
                case 6:
                    $model = $this->prapkModel;
                    break;
                default:
                    return redirect()->back()->with('error', 'Level ID tidak valid');
            }

            $lastData = $model->orderBy('no', 'DESC')->first();
            $lastNo = $lastData ? (int) $lastData['no'] : 0;
            $newNo = $lastNo + 1;

            $model->insert([
                'kategori' => $kategori,
                'kompetensi' => null,
                'no' => $newNo
            ]);

            return redirect()->back()->with('message', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Nama kategori tidak boleh kosong');
    }
    public function edit_kategori($levelId = null)
    {
        $levelId = (int) $levelId;

        // Pilih model berdasarkan levelId
        switch ($levelId) {
            case 3:
                $model = $this->pk3Model;
                break;
            case 4:
                $model = $this->pk2Model;
                break;
            case 5:
                $model = $this->pk1Model;
                break;
            case 6:
                $model = $this->prapkModel;
                break;
            default:
                return redirect()->back()->with('error', 'Level ID tidak valid');
        }

        $old = $this->request->getPost('old');
        $new = $this->request->getPost('new');

        if ($old && $new) {
            if ($old === $new) {
                return redirect()->back()->with('error', 'Nama kategori baru tidak boleh sama dengan yang lama');
            }

            $model->where('kategori', $old)->set(['kategori' => $new])->update();

            return redirect()->back()->with('message', 'Kategori berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Data kategori tidak valid');
    }

    public function hapus_kategori()
    {
        $kategori = $this->request->getVar('kategori');
        $levelId = (int) $this->request->getVar('level_id');

        if (!$kategori || !$levelId) {
            return redirect()->back()->with('error', 'Data kategori atau level tidak valid');
        }

        // Pilih model berdasarkan levelId
        switch ($levelId) {
            case 3:
                $model = $this->pk3Model;
                break;
            case 4:
                $model = $this->pk2Model;
                break;
            case 5:
                $model = $this->pk1Model;
                break;
            case 6:
                $model = $this->prapkModel;
                break;
            default:
                return redirect()->to('/admin/manmentor')->with('error', 'Level ID tidak valid');
        }

        // Hapus semua kompetensi dalam kategori di model yang sesuai
        $model->where('kategori', $kategori)->delete();

        return redirect()->back()->with('message', 'Kategori dan semua kompetensi di dalamnya berhasil dihapus');
    }


}


