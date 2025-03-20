<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\FileModel;
use App\Models\DokumenModel;

class Paket extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->fileModel = new FileModel();  // Menginisialisasi model
        $this->dokumenModel = new DokumenModel();  // Menginisialisasi model
    }

    public function index()
    {
        $nama_level = session()->get('nama_level');
        $whereCondition = $nama_level == 'Pokja' ? ['dipa' => 'MABES TNI AL'] : ($nama_level == 'PP' ? ['dipa' => 'DISINFOLAHTAL'] : []);
        $paket = $this->paketModel->getAllbyColumn('paket', $whereCondition);

        return view('ppk/paket', [
            'level_akses' => $nama_level,
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Paket',
            'paket' => $paket,
            // Debugging

        ]);
        
    }

    public function tambah_paket()
    {
        return view('ppk/tambah_paket', [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Tambah Paket',
        ]);
    }

    public function tambah_data_paket()
    {
        $data = $this->request->getPost([
            'tahun_anggaran', 'dipa', 'jenis', 'metode', 'kode_rup', 'nama_paket', 'perencanaan', 'pelaksanaan', 'pembayaran'
        ]);

        if (!$data) {
            return redirect()->back()->with('error', 'Data yang diteruskan tidak valid.');
        }

        $this->paketModel->insertData('paket', [$data]);
        return redirect()->to('/paket')->with('success', 'Data baru Paket berhasil disimpan.');
    }

    public function hapus_data_paket($id)
    {
        $this->paketModel->deletePaket('perencanaan', $id);
        return redirect()->to('/paket')->with('success', 'Data berhasil dihapus.');
    }

    public function detail_paket($id)
    {
        $paket = $this->paketModel->getById('paket', $id);
        if (!$paket) {
            session()->setFlashdata('error', 'Paket tidak ditemukan!');
            return redirect()->to('/paket');
        }

        $fileList = $this->fileModel->where('ref_id_paket', $id)
            ->where('deleted_at', null) // Menambahkan kondisi untuk memastikan hanya file yang belum dihapus yang diambil
            ->findAll();



        // Tentukan tabel dokumen berdasarkan ref_tabel
        $dokumenTable = '';
        switch ($paket['metode']) {
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

        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Detail Paket',
            'paket' => $paket,
            'dokumenList' => $dokumenList,
            'fileList' => $fileList // Tambahkan data file yang sudah diunggah
        ];

        return view('ppk/detail_paket', $data);
    }


    public function update_paket($id)
    {
        $data = $this->request->getPost([
            'tahun_anggaran', 'dipa', 'jenis', 'metode', 'kode_rup', 'nama_paket', 'perencanaan', 'pelaksanaan', 'pembayaran'
        ]);

        $this->paketModel->updatePaket('paket', $id, $data);
        session()->setFlashdata('success', 'Data berhasil diperbarui!');
        return redirect()->to('/paket/detail_paket/'.$id);
    }

    public function ekspor_paket()
    {
        $paketData = $this->paketModel->getAll('paket');
        $filename = "paket.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Tahun Anggaran', 'DIPA', 'Jenis', 'Metode', 'Kode Rup', 'Nama Paket', 'Perencanaan', 'Pelaksanaan', 'Pembayaran']);
        
        foreach ($paketData as $index => $paket) {
            fputcsv($output, array_merge([$index + 1], array_values($paket)));
        }
        
        fclose($output);
        exit();
    }

    public function unggah_dokumen()
    {
        $id_paket = $this->request->getPost('id_paket');
        $id_dokumen = $this->request->getPost('id_dokumen');

        if (!filter_var($id_paket, FILTER_VALIDATE_INT) || !filter_var($id_dokumen, FILTER_VALIDATE_INT)) {
            session()->setFlashdata('error', 'ID Paket atau ID Dokumen tidak valid.');
            return redirect()->back();
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getName();
            $folder = 'uploads/' . $id_paket . '/';

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            if ($file->move($folder, $newName)) {
                $data = [
                    'ref_id_paket' => (int) $id_paket,
                    'ref_id_dokumen' => (int) $id_dokumen,
                    'nama_file' => $newName,
                    'created_at'   => date('Y-m-d H:i:s')
                ];

                $this->fileModel->save($data);

                session()->setFlashdata('success', 'Dokumen berhasil diunggah');
                return redirect()->to(base_url('paket/detail_paket/' . $id_paket));
            } else {
                session()->setFlashdata('error', 'Gagal mengunggah dokumen');
            }
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah dokumen ' . $file->getErrorString());
        }

        return redirect()->back();
    }

    public function unduh_semua_dokumen($id_paket)
    {
        helper('download');
        $zip = new \ZipArchive();
        $this->fileModel = new FileModel();

        if (!$id_paket) {
            session()->setFlashdata('error', 'ID paket tidak ditemukan.');
            return redirect()->to(base_url('paket'));
        }

        $files = $this->fileModel->get_all_files($id_paket);
        $namaPaket = $this->paketModel->find($id_paket);
        

        if (empty($files)) {
            session()->setFlashdata('error', 'Tidak ada dokumen untuk diunduh');
            return redirect()->to(base_url('paket/detail_paket/'.$id_paket));
        }

        $folder = FCPATH . 'uploads/' . $id_paket . '/';
        $zipFileName = WRITEPATH . 'uploads/' . $namaPaket['nama_paket'] . '.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            session()->setFlashdata('error', 'Gagal membuat file ZIP.');
            return redirect()->to(base_url('paket/detail_paket/'.$id_paket));
        }

        foreach ($files as $file) {
            $path = $folder . $file['nama_file']; // Gunakan indeks array
            if (file_exists($path)) {
                $zip->addFile($path, $file['nama_file']); // Gunakan indeks array
            }
        }
        

        $zip->close();

        // Menggunakan download bawaan CodeIgniter
        $response = $this->response->download($zipFileName, null)->setFileName($namaPaket['nama_paket'] . '.zip');

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
            // Path file lama dengan struktur: uploads/{id_pengadaan}/{nama_file}
            $uploadFolder = FCPATH . 'uploads/' . $file['ref_id_paket'] . '/';  
            $oldFilePath = $uploadFolder . $file['nama_file'];  
            $newFileName = 'deleted-' . $file['nama_file'];
            $newFilePath = $uploadFolder . $newFileName;
    
            // Debugging - Periksa apakah file ada
            if (!file_exists($oldFilePath)) {
                session()->setFlashdata('error', 'File tidak ditemukan di folder: ' . $oldFilePath);
                return redirect()->to(base_url('paket/detail_paket/' . $file['ref_id_paket']));
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
        return redirect()->to(base_url('paket/detail_paket/' . $file['ref_id_paket']));
    }
}