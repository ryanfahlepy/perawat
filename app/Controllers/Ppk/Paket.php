<?php

namespace App\Controllers\Ppk;

use App\Controllers\BaseController;
use App\Models\PaketModel;

class Paket extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
    }

    public function index()
    {
        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Paket',
            'paket_perencanaan' => $this->paketModel->getAll('paket_perencanaan'),
            'paket_pelaksanaan' => $this->paketModel->getAll('paket_pelaksanaan'),
            'paket_pembayaran' => $this->paketModel->getAll('paket_pembayaran')
        ];
        return view('ppk/paket', $data);
    }
    public function tambah_paket_perencanaan()
    {
        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Tambah Paket Perencaan',
        ];
        return view('ppk/tambah_paket_perencanaan', $data);
    }
    

    public function tambah_data_paket_perencanaan()
{
    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'kategori' => $this->request->getPost('kategori'),
        'kode_rup' => $this->request->getPost('kode_rup'),
        'nama_paket' => $this->request->getPost('nama_paket'),
        'total_perencanaan' => $this->request->getPost('total_perencanaan'),
        'pdn' => $this->request->getPost('pdn')
    ];

    // Memastikan data adalah array yang valid
    if (!is_array($data)) {
        return redirect()->back()->with('error', 'Data yang diteruskan tidak valid.');
    }

    // Membungkus data dalam array sehingga insertBatch dapat menerima data dalam format array of arrays
    $this->paketModel->insertData('paket_perencanaan', [$data]);

    // Redirect setelah berhasil
    return redirect()->to('/ppk/paket')->with('success', 'Data Baru Paket Perencanaan berhasil disimpan.');
}

public function hapus_data_paket_perencanaan($id)
{
    // Panggil model untuk menghapus data berdasarkan ID
    $this->paketModel->deletePaket('paket_perencanaan',$id);

    // Redirect dengan pesan sukses
    return redirect()->to('/ppk/paket')->with('success', 'Data berhasil dihapus.');
}

public function edit_data_paket_perencanaan($id) 
{
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Edit Data Paket Perencanaan',
    ];
    
    // Memuat model
    $paketModel = new PaketModel();

    // Ambil data paket berdasarkan ID
    $paket = $paketModel->getById('paket_perencanaan', $id);

    // Jika data tidak ditemukan, redirect ke halaman daftar paket
    if (!$paket) {
        session()->setFlashdata('error', 'Paket tidak ditemukan!');
        return redirect()->to('/ppk/paket');
    }

    // Kirim data paket ke view untuk ditampilkan pada form
    $data['paket'] = $paket;

    // Kirim data ke view
    return view('ppk/edit_paket_perencanaan', $data);
}


    // Fungsi untuk memperbarui data paket
    public function update_data_paket_perencanaan($id)
{
    $paketModel = new PaketModel();

    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'kategori' => $this->request->getPost('kategori'),
        'kode_rup' => $this->request->getPost('kode_rup'),
        'nama_paket' => $this->request->getPost('nama_paket'),
        'total_perencanaan' => $this->request->getPost('total_perencanaan'),
        'pdn' => $this->request->getPost('pdn'),
    ];

    // Update data di database
    $paketModel->updatePaket('paket_perencanaan', $id, $data);

    session()->setFlashdata('success', 'Data berhasil diperbarui!');
    return redirect()->to('/ppk/paket');
}

public function ekspor_paket_perencanaan()
{
    // Mengambil data paket pembayaran dari model
    $paketModel = new PaketModel();
    $paketData = $paketModel->getAll('paket_perencanaan'); // Mengambil semua data paket pembayaran

    // Menyiapkan header CSV
    $filename = "paket_perencanaan" . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Membuka file PHP output stream
    $output = fopen('php://output', 'w');
    
    // Menulis header CSV dengan kolom 'No'
    fputcsv($output, ['No', 'Tahun Anggaran', 'Kode Rup', 'Nama Paket', 'Total Perencanaan', 'PDN']);
    
    // Menulis data ke CSV dengan kolom 'No' sebagai urutan
    $no = 1; // Menambahkan variabel untuk nomor urut
    foreach ($paketData as $paket) {
        fputcsv($output, [
            $no++, // Menambahkan nomor urut
            $paket['tahun_anggaran'],
            $paket['kode_rup'],
            $paket['nama_paket'],
            $paket['total_perencanaan'],
            $paket['pdn']
        ]);
    }
    
    // Menutup output stream
    fclose($output);
    exit();
}



public function tambah_paket_pelaksanaan()
{
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Tambah Paket Pelaksanaan',
    ];
    return view('ppk/tambah_paket_pelaksanaan', $data);
}

public function tambah_data_paket_pelaksanaan()
{
    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'sumber_data' => $this->request->getPost('sumber_data'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'kode' => $this->request->getPost('kode'),
        'kode_rup' => $this->request->getPost('kode_rup'),
        'nama_paket' => $this->request->getPost('nama_paket'),
        'total_pelaksanaan' => $this->request->getPost('total_pelaksanaan'),
        'pdn' => $this->request->getPost('pdn')
    ];

    // Memastikan data adalah array yang valid
    if (!is_array($data)) {
        return redirect()->back()->with('error', 'Data yang diteruskan tidak valid.');
    }

    // Membungkus data dalam array sehingga insertBatch dapat menerima data dalam format array of arrays
    $this->paketModel->insertData('paket_pelaksanaan', [$data]);

    // Redirect setelah berhasil
    return redirect()->to('/ppk/paket')->with('success', 'Data Baru Paket Pelaksanaan berhasil disimpan.');
}

public function hapus_data_paket_pelaksanaan($id)
{
    // Panggil model untuk menghapus data berdasarkan ID
    $this->paketModel->deletePaket('paket_pelaksanaan', $id);

    // Redirect dengan pesan sukses
    return redirect()->to('/ppk/paket')->with('success', 'Data berhasil dihapus.');
}

public function edit_data_paket_pelaksanaan($id) 
{
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Edit Data Paket Pelaksanaan',
    ];
    
    // Memuat model
    $paketModel = new PaketModel();

    // Ambil data paket berdasarkan ID
    $paket = $paketModel->getById('paket_pelaksanaan', $id);

    // Jika data tidak ditemukan, redirect ke halaman daftar paket
    if (!$paket) {
        session()->setFlashdata('error', 'Paket tidak ditemukan!');
        return redirect()->to('/ppk/paket');
    }

    // Kirim data paket ke view untuk ditampilkan pada form
    $data['paket'] = $paket;

    // Kirim data ke view
    return view('ppk/edit_paket_pelaksanaan', $data);
}

// Fungsi untuk memperbarui data paket pelaksanaan
public function update_data_paket_pelaksanaan($id)
{
    $paketModel = new PaketModel();

    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'sumber_data' => $this->request->getPost('sumber_data'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'kode' => $this->request->getPost('kode'),
        'kode_rup' => $this->request->getPost('kode_rup'),
        'nama_paket' => $this->request->getPost('nama_paket'),
        'total_pelaksanaan' => $this->request->getPost('total_pelaksanaan'),
        'pdn' => $this->request->getPost('pdn'),
    ];

    // Update data di database
    $paketModel->updatePaket('paket_pelaksanaan', $id, $data);

    session()->setFlashdata('success', 'Data berhasil diperbarui!');
    return redirect()->to('/ppk/paket');
}

public function ekspor_paket_pelaksanaan()
{
    // Mengambil data paket pembayaran dari model
    $paketModel = new PaketModel();
    $paketData = $paketModel->getAll('paket_pelaksanaan'); // Mengambil semua data paket pembayaran

    // Menyiapkan header CSV
    $filename = "paket_pelaksanaan" . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Membuka file PHP output stream
    $output = fopen('php://output', 'w');
    
    // Menulis header CSV dengan kolom 'No'
    fputcsv($output, ['No', 'Tahun Anggaran', 'Sumber Data', 'Nama Penyedia', 'Kode', 'Kode Rup', 'Nama Paket', 'Total Pelaksanaan', 'PDN']);
    
    // Menulis data ke CSV dengan kolom 'No' sebagai urutan
    $no = 1; // Menambahkan variabel untuk nomor urut
    foreach ($paketData as $paket) {
        fputcsv($output, [
            $no++, // Menambahkan nomor urut
            $paket['tahun_anggaran'],
            $paket['sumber_data'],
            $paket['nama_penyedia'],
            $paket['kode'],
            $paket['kode_rup'],
            $paket['nama_paket'],
            $paket['total_pelaksanaan'],
            $paket['pdn']
        ]);
    }
    
    // Menutup output stream
    fclose($output);
    exit();
}

public function tambah_paket_pembayaran()
{
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Tambah Paket Pembayaran',
    ];
    return view('ppk/tambah_paket_pembayaran', $data);
}

public function tambah_data_paket_pembayaran()
{
    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'sumber_data' => $this->request->getPost('sumber_data'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'kode_dokumen' => $this->request->getPost('kode_dokumen'),
        'kode_sp2d' => $this->request->getPost('kode_sp2d'),
        'total_pembayaran' => $this->request->getPost('total_pembayaran'),
        'pdn' => $this->request->getPost('pdn')
    ];

    // Memastikan data adalah array yang valid
    if (!is_array($data)) {
        return redirect()->back()->with('error', 'Data yang diteruskan tidak valid.');
    }

    // Membungkus data dalam array sehingga insertBatch dapat menerima data dalam format array of arrays
    $this->paketModel->insertData('paket_pembayaran', [$data]);

    // Redirect setelah berhasil
    return redirect()->to('/ppk/paket')->with('success', 'Data Baru Paket Pembayaran berhasil disimpan.');
}

public function hapus_data_paket_pembayaran($id)
{
    // Panggil model untuk menghapus data berdasarkan ID
    $this->paketModel->deletePaket('paket_pembayaran', $id);

    // Redirect dengan pesan sukses
    return redirect()->to('/ppk/paket')->with('success', 'Data berhasil dihapus.');
}

public function edit_data_paket_pembayaran($id) 
{
    $data = [
        'level_akses' => session()->get('nama_level'),
        'dtmenu' => $this->tampil_menu(session()->get('level')),
        'nama_menu' => 'Edit Data Paket Pembayaran',
    ];
    
    // Memuat model
    $paketModel = new PaketModel();

    // Ambil data paket berdasarkan ID
    $paket = $paketModel->getById('paket_pembayaran', $id);

    // Jika data tidak ditemukan, redirect ke halaman daftar paket
    if (!$paket) {
        session()->setFlashdata('error', 'Paket tidak ditemukan!');
        return redirect()->to('/ppk/paket');
    }

    // Kirim data paket ke view untuk ditampilkan pada form
    $data['paket'] = $paket;

    // Kirim data ke view
    return view('ppk/edit_paket_pembayaran', $data);
}

// Fungsi untuk memperbarui data paket pembayaran
public function update_data_paket_pembayaran($id)
{
    $paketModel = new PaketModel();

    // Ambil data dari form
    $data = [
        'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
        'sumber_data' => $this->request->getPost('sumber_data'),
        'nama_penyedia' => $this->request->getPost('nama_penyedia'),
        'kode_dokumen' => $this->request->getPost('kode_dokumen'),
        'kode_sp2d' => $this->request->getPost('kode_sp2d'),
        'total_pembayaran' => $this->request->getPost('total_pembayaran'),
        'pdn' => $this->request->getPost('pdn'),
    ];

    // Update data di database
    $paketModel->updatePaket('paket_pembayaran', $id, $data);

    session()->setFlashdata('success', 'Data berhasil diperbarui!');
    return redirect()->to('/ppk/paket');
}

public function ekspor_paket_pembayaran()
{
    // Mengambil data paket pembayaran dari model
    $paketModel = new PaketModel();
    $paketData = $paketModel->getAll('paket_pembayaran'); // Mengambil semua data paket pembayaran

    // Menyiapkan header CSV
    $filename = "paket_pembayaran" . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Membuka file PHP output stream
    $output = fopen('php://output', 'w');
    
    // Menulis header CSV dengan kolom 'No'
    fputcsv($output, ['No', 'Tahun Anggaran', 'Sumber Data', 'Nama Penyedia', 'Kode Dokumen', 'Kode SP2D', 'Total Pembayaran', 'PDN']);
    
    // Menulis data ke CSV dengan kolom 'No' sebagai urutan
    $no = 1; // Menambahkan variabel untuk nomor urut
    foreach ($paketData as $paket) {
        fputcsv($output, [
            $no++, // Menambahkan nomor urut
            $paket['tahun_anggaran'],
            $paket['sumber_data'],
            $paket['nama_penyedia'],
            $paket['kode_dokumen'],
            $paket['kode_sp2d'],
            $paket['total_pembayaran'],
            $paket['pdn']
        ]);
    }
    
    // Menutup output stream
    fclose($output);
    exit();
}









    // ============================
    // IMPORT PERENCANAAN
    // ============================
    public function import_csv_perencanaan()
    {
        return $this->import_csv_generic('paket_perencanaan', [
            'tahun_anggaran' => 1,
            'kategori' => 2,
            'kode_rup' => 3,
            'nama_paket' => 4,
            'total_perencanaan' => 5,
            'pdn' => 6
        ]);
    }

    // ============================
    // IMPORT PELAKSANAAN
    // ============================
    public function import_csv_pelaksanaan()
    {
        return $this->import_csv_generic('paket_pelaksanaan', [
            'tahun_anggaran' => 1,
            'sumber_data' => 2,
            'nama_penyedia' => 3,
            'kode' => 4,
            'kode_rup' => 5,
            'nama_paket' => 6,
            'total_pelaksanaan' => 7,
            'pdn' => 8
        ]);
    }

    // ============================
    // IMPORT PEMBAYARAN
    // ============================
    public function import_csv_pembayaran()
{
    return $this->import_csv_generic('paket_pembayaran', [
        'tahun_anggaran' => 1,
        'sumber_data' => 2,
        'nama_penyedia' => 3,
        'kode_dokumen' => 4,
        'kode_sp2d' => 5,
        'total_pembayaran' => 6,
        'pdn' => 7
    ]);
}

    // ============================
    // GENERIC CSV IMPORT FUNCTION
    // ============================
    private function import_csv_generic($table, $columnMap)
    {
        $file = $this->request->getFile('file_csv');

        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/ppk/paket')->with('error', 'File tidak valid atau sudah diproses.');
        }

        $filePath = $file->getTempName();
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return redirect()->to('/ppk/paket')->with('error', 'Gagal membuka file.');
        }

        // Deteksi delimiter (koma atau titik koma)
        $firstLine = fgets($handle);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
        rewind($handle);

        $data = [];
        $rowIndex = 0;
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if ($rowIndex == 0) { // Lewati header
                $rowIndex++;
                continue;
            }

            // Pastikan jumlah kolom cukup sebelum menyimpan data
            if (count($row) < max(array_values($columnMap))) {
                fclose($handle);
                return redirect()->to('/ppk/paket')->with('error', 'Format CSV tidak sesuai. Pastikan jumlah kolom mencukupi.');
            }

            // Mapping kolom
            $entry = [];
            foreach ($columnMap as $field => $index) {
                $entry[$field] = str_replace(',', '', $row[$index]); // Hilangkan koma dari angka
            }

            $data[] = $entry;
        }

        fclose($handle);

        if (!empty($data)) {
            $this->paketModel->insertData($table, $data);
            return redirect()->to('/ppk/paket')->with('success', "Data berhasil diimpor ke tabel $table.");
        }

        return redirect()->to('/ppk/paket')->with('error', 'Gagal mengimpor data.');
    }
}
