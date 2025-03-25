<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengadaanModel;
use App\Models\FileModel;
use App\Models\DokumenModel;
use App\Models\DipaModel;
use App\Models\JenisModel;
use App\Models\MetodeModel;

class Pengadaan extends BaseController
{
    protected $pengadaanModel;

    public function __construct()
    {
        $this->pengadaanModel = new PengadaanModel();
        $this->fileModel = new FileModel();
        $this->dipaModel = new DipaModel();
        $this->jenisModel = new JenisModel();
        $this->metodeModel = new MetodeModel();
        $this->dokumenModel = new DokumenModel();

        // Mengambil jumlah dokumen dari masing-masing tabel dan menyimpannya di properti class
        $this->dokumenCounts = [
            'tabel_pl' => $this->dokumenModel->getAllCount('tabel_pl'),
            'tabel_juksung' => $this->dokumenModel->getAllCount('tabel_juksung'),
            'tabel_tender' => $this->dokumenModel->getAllCount('tabel_tender'),
            'tabel_ep' => $this->dokumenModel->getAllCount('tabel_ep'),
            'tabel_swakelola' => $this->dokumenModel->getAllCount('tabel_swakelola'),
        ];
    }

    public function index()
    {
        $nama_level = session()->get('nama_level');
        $whereCondition = $nama_level == 'Pokja' ? ['dipa' => 'MABES TNI AL'] : ($nama_level == 'PP' ? ['dipa' => 'DISINFOLAHTAL'] : []);
        $pengadaanData = $this->pengadaanModel->getAllbyColumn($whereCondition);

        // Update nilai pengadaanData berdasarkan ref_tabel
        foreach ($pengadaanData as &$pengadaan) {
            if ($pengadaan['metode'] == 'Pengadaan Langsung') {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_pl'];
            } elseif ($pengadaan['metode'] == 'Penunjukkan Langsung') {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_juksung'];
            } elseif ($pengadaan['metode'] == 'Tender') {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_tender'];
            } elseif ($pengadaan['metode'] == 'E-Purchasing') {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_ep'];
            } elseif ($pengadaan['metode'] == 'Swakelola') {
                $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_swakelola'];
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

        return view('layout/pengadaan', [
            'level_akses' => $nama_level,
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Pengadaan',
            'pengadaan' => $pengadaanData,
            // Debugging

        ]);

    }

    public function tambah_pengadaan()
    {
        $listDipa = $this->dipaModel->findAll();
        $listJenis = $this->jenisModel->findAll();
        $listMetode = $this->metodeModel->findAll();

        return view('layout/tambah_pengadaan', [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Tambah Pengadaan',
            'listDipa' => $listDipa,
            'listJenis' => $listJenis,
            'listMetode' => $listMetode
        ]);
    }

    public function tambah_data_pengadaan()
    {
        $data = $this->request->getPost([
            'tahun_anggaran',
            'dipa',
            'jenis',
            'metode',
            'kode_rup',
            'nama_pengadaan',
            'perencanaan',
            'pelaksanaan',
            'pembayaran',
            'tangga_mulai',
            'tanggal_berakhir'
        ]);

        if (!$data) {
            return redirect()->back()->with('error', 'Data yang diteruskan tidak valid.');
        }

        $this->pengadaanModel->insertData($data);
        return redirect()->to('/pengadaan')->with('success', 'Data baru Pengadaan berhasil disimpan.');
    }

    public function hapus_data_pengadaan($id)
    {
        $this->pengadaanModel->deletePengadaan($id);
        return redirect()->to('/pengadaan')->with('success', 'Data berhasil dihapus.');
    }

    public function detail_pengadaan($id)
    {

        $listDipa = $this->dipaModel->findAll();
        $listJenis = $this->jenisModel->findAll();
        $listMetode = $this->metodeModel->findAll();
        $pengadaan = $this->pengadaanModel->getById($id);
        if (!$pengadaan) {
            session()->setFlashdata('error', 'Pengadaan tidak ditemukan!');
            return redirect()->to('/pengadaan');
        }

        $fileList = $this->fileModel->where('ref_id_pengadaan', $id)
            ->where('deleted_at', null) // Menambahkan kondisi untuk memastikan hanya file yang belum dihapus yang diambil
            ->findAll();



        // Tentukan tabel dokumen berdasarkan ref_tabel
        $dokumenTable = '';
        switch ($pengadaan['metode']) {
            case 'Pengadaan Langsung':
                $dokumenTable = 'tabel_pl';
                break;
            case 'Penunjukkan Langsung':
                $dokumenTable = 'tabel_juksung';
                break;
            case 'E-Purchasing':
                $dokumenTable = 'tabel_ep';
                break;
            case 'Swakelola':
                $dokumenTable = 'tabel_swakelola';
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


        if ($pengadaan['metode'] == 'Pengadaan Langsung') {
            $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_pl'];
        } elseif ($pengadaan['metode'] == 'Penunjukkan Langsung') {
            $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_juksung'];
        } elseif ($pengadaan['metode'] == 'Tender') {
            $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_tender'];
        } elseif ($pengadaan['metode'] == 'E-Purchasing') {
            $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_ep'];
        } elseif ($pengadaan['metode'] == 'Swakelola') {
            $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_swakelola'];
        } else {
            $pengadaan['jumlah_dokumen'] = 0; // Default jika tidak sesuai
        }



        $pengadaan['jumlah_file'] = $this->fileModel->getFileCountByPengadaanId($pengadaan['id']);

        // Menghitung progress (persentase)
        if ($pengadaan['jumlah_dokumen'] > 0) {
            $pengadaan['progress'] = round(($pengadaan['jumlah_file'] / $pengadaan['jumlah_dokumen']) * 100); // Membulatkan ke angka terdekat
        } else {
            $pengadaan['progress'] = 0; // Jika tidak ada dokumen, set progress ke 0
        }


        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Detail Pengadaan',
            'pengadaan' => $pengadaan,
            'dokumenList' => $dokumenList,
            'fileList' => $fileList,
            'listDipa' => $listDipa,
            'listJenis' => $listJenis,
            'listMetode' => $listMetode,

        ];

        return view('layout/detail_pengadaan', $data);
    }


    public function update_pengadaan($id)
    {
        $data = $this->request->getPost([
            'tahun_anggaran',
            'dipa',
            'jenis',
            'metode',
            'kode_rup',
            'nama_pengadaan',
            'perencanaan',
            'pelaksanaan',
            'pembayaran',
            'tanggal_mulai',
            'tanggal_berakhir'
        ]);

        $this->pengadaanModel->updatePengadaan($id, $data);

        session()->setFlashdata('success', 'Data berhasil diperbarui');
        return redirect()->to('/pengadaan/detail_pengadaan/' . $id);
    }

    public function ekspor_pengadaan()
    {
        $pengadaanData = $this->pengadaanModel->getAll('tabel_pengadaan');
        $filename = "pengadaan.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Tahun Anggaran', 'DIPA', 'Jenis', 'Metode', 'Kode Rup', 'Nama Pengadaan', 'Perencanaan', 'Pelaksanaan', 'Pembayaran', 'Tanggal_Mulai', 'Tanggal_Berakhir']);

        foreach ($pengadaanData as $index => $pengadaan) {
            fputcsv($output, array_merge([$index + 1], array_values($pengadaan)));
        }

        fclose($output);
        exit();
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
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->fileModel->save($data);

                session()->setFlashdata('success', 'Dokumen berhasil diunggah');
                return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $id_pengadaan));
            } else {
                session()->setFlashdata('error', 'Gagal mengunggah dokumen');
            }
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah dokumen ' . $file->getErrorString());
        }

        return redirect()->back();
    }

    public function unduh_semua_dokumen($id_pengadaan)
    {
        helper('download');
        $zip = new \ZipArchive();
        $this->fileModel = new FileModel();

        if (!$id_pengadaan) {
            session()->setFlashdata('error', 'ID pengadaan tidak ditemukan.');
            return redirect()->to(base_url('pengadaan'));
        }

        $files = $this->fileModel->get_all_files($id_pengadaan);
        $namaPengadaan = $this->pengadaanModel->find($id_pengadaan);


        if (empty($files)) {
            session()->setFlashdata('error', 'Tidak ada dokumen untuk diunduh');
            return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $id_pengadaan));
        }

        $folder = FCPATH . 'uploads/' . $id_pengadaan . '/';
        $zipFileName = WRITEPATH . 'uploads/' . $namaPengadaan['nama_pengadaan'] . '.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            session()->setFlashdata('error', 'Gagal membuat file ZIP.');
            return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $id_pengadaan));
        }

        foreach ($files as $file) {
            $path = $folder . $file['nama_file']; // Gunakan indeks array
            if (file_exists($path)) {
                $zip->addFile($path, $file['nama_file']); // Gunakan indeks array
            }
        }


        $zip->close();

        // Menggunakan download bawaan CodeIgniter
        $response = $this->response->download($zipFileName, null)->setFileName($namaPengadaan['nama_pengadaan'] . '.zip');

        // Hapus file ZIP setelah download
        register_shutdown_function(function () use ($zipFileName) {
            if (file_exists($zipFileName)) {
                unlink($zipFileName);
            }
        });

        return $response;
    }

    public function hapus_dokumen($id_file)
    {
        // Validasi apakah ID file ada di database
        $file = $this->fileModel->find($id_file);
        if ($file) {

            $uploadFolder = FCPATH . 'uploads/' . $file['ref_id_pengadaan'] . '/';
            $oldFilePath = $uploadFolder . $file['nama_file'];
            $newFileName = 'deleted-' . $file['nama_file'];
            $newFilePath = $uploadFolder . $newFileName;

            // Debugging - Periksa apakah file ada
            if (!file_exists($oldFilePath)) {
                session()->setFlashdata('error', 'File tidak ditemukan di folder: ' . $oldFilePath);
                return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $file['ref_id_pengadaan']));
            }

            // Coba ubah nama file
            if (rename($oldFilePath, $newFilePath)) {
                // Jika berhasil rename, hapus data file dari database
                $this->fileModel->delete($id_file);

                session()->setFlashdata('success', 'Dokumen berhasil dihapus');
            } else {
                session()->setFlashdata('error', 'Gagal mengubah nama dokumen');
            }
        } else {
            session()->setFlashdata('error', 'Dokumen tidak ditemukan di database');
        }

        // Redirect kembali ke halaman detail pengadaan
        return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $file['ref_id_pengadaan']));
    }
}