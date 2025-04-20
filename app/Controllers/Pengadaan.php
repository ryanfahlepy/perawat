<?php


namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controllers\BaseController;
use App\Models\PengadaanModel;
use App\Models\FileModel;
use App\Models\TemplateDokumenModel;
use App\Models\DipaModel;
use App\Models\JenisModel;
use App\Models\MetodeModel;
use App\Models\UserModel;


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
        $this->templateDokumenModel = new TemplateDokumenModel();
        $this->userModel = new UserModel();

        // Mengambil jumlah dokumen dari masing-masing tabel dan menyimpannya di properti class
        $this->dokumenCounts = [
            'tabel_pl' => $this->templateDokumenModel->getAllCount('tabel_pl'),
            'tabel_juksung' => $this->templateDokumenModel->getAllCount('tabel_juksung'),
            'tabel_tender' => $this->templateDokumenModel->getAllCount('tabel_tender'),
            'tabel_ep' => $this->templateDokumenModel->getAllCount('tabel_ep'),
            'tabel_swakelola' => $this->templateDokumenModel->getAllCount('tabel_swakelola'),
        ];
    }

    public function index()
    {
        $nama_level = session()->get('nama_level');
        $whereCondition = [];

        if ($nama_level == 'Pokja') {
            $whereCondition = ['dipa' => 'MABES TNI AL'];
        } elseif ($nama_level == 'PP') {
            $whereCondition = ['dipa' => 'DISINFOLAHTAL'];
        }

        // Ambil tahun dari POST (filter)
        $tahun_dipilih = $this->request->getPost('tahun');

        // Ambil data pengadaan
        $pengadaanData = $this->pengadaanModel->getAllbyColumn($whereCondition, $tahun_dipilih);

        // Ambil semua tahun dari database (untuk dropdown)
        $tahun_tersedia = $this->pengadaanModel
            ->select('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'DESC')
            ->findAll();

        // Hitung jumlah dokumen berdasarkan metode
        foreach ($pengadaanData as &$pengadaan) {
            switch ($pengadaan['metode']) {
                case 'Pengadaan Langsung':
                    $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_pl'];
                    break;
                case 'Penunjukkan Langsung':
                    $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_juksung'];
                    break;
                case 'Tender':
                    $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_tender'];
                    break;
                case 'E-Purchasing':
                    $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_ep'];
                    break;
                case 'Swakelola':
                    $pengadaan['jumlah_dokumen'] = $this->dokumenCounts['tabel_swakelola'];
                    break;
                default:
                    $pengadaan['jumlah_dokumen'] = 0;
            }
        }

        // Hitung jumlah file dan progress
        foreach ($pengadaanData as &$pengadaan) {
            $pengadaan['jumlah_file'] = $this->fileModel->getFileCountByPengadaanId($pengadaan['id']);
            $pengadaan['progress'] = ($pengadaan['jumlah_dokumen'] > 0)
                ? round(($pengadaan['jumlah_file'] / $pengadaan['jumlah_dokumen']) * 100)
                : 0;
        }

        // Cek apakah ada filter DIPA (hindari error undefined index)
        $dipaFilter = isset($whereCondition['dipa']) ? $whereCondition['dipa'] : null;

        return view('layout/pengadaan', [
            'level_akses' => $nama_level,
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Pengadaan',
            'pengadaan' => $pengadaanData,
            'tahun_dipilih' => $tahun_dipilih,
            'tahun_tersedia' => $tahun_tersedia,

            // Statistik keseluruhan (filtered by tahun & dipa)
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan($tahun_dipilih, $dipaFilter),
            'total_perencanaan' => $this->pengadaanModel->total_perencanaan($tahun_dipilih, $dipaFilter),
            'total_pelaksanaan' => $this->pengadaanModel->total_pelaksanaan($tahun_dipilih, $dipaFilter),
            'total_pembayaran' => $this->pengadaanModel->total_pembayaran($tahun_dipilih, $dipaFilter),

            // Statistik belanja rutin
            'jumlah_pengadaan_belanja_rutin' => $this->pengadaanModel->jumlah_pengadaan_belanja_rutin($tahun_dipilih),
            'perencanaan_belanja_dipa_disinfolahtal' => $this->pengadaanModel->perencanaan_belanja_dipa_disinfolahtal($tahun_dipilih),
            'pelaksanaan_belanja_dipa_disinfolahtal' => $this->pengadaanModel->pelaksanaan_belanja_dipa_disinfolahtal($tahun_dipilih),
            'pembayaran_belanja_dipa_disinfolahtal' => $this->pengadaanModel->pembayaran_belanja_dipa_disinfolahtal($tahun_dipilih),

            // Statistik belanja modal
            'jumlah_pengadaan_belanja_modal' => $this->pengadaanModel->jumlah_pengadaan_belanja_modal($tahun_dipilih),
            'perencanaan_belanja_dipa_mabesal' => $this->pengadaanModel->perencanaan_belanja_dipa_mabesal($tahun_dipilih),
            'pelaksanaan_belanja_dipa_mabesal' => $this->pengadaanModel->pelaksanaan_belanja_dipa_mabesal($tahun_dipilih),
            'pembayaran_belanja_dipa_mabesal' => $this->pengadaanModel->pembayaran_belanja_dipa_mabesal($tahun_dipilih),
        ]);
    }

    public function filterByTahun()
    {
        $tahun = $this->request->getPost('tahun');
        $nama_level = session()->get('nama_level');
        $whereCondition = $nama_level == 'Pokja' ? ['dipa' => 'MABES TNI AL'] : ($nama_level == 'PP' ? ['dipa' => 'DISINFOLAHTAL'] : []);

        $data = [
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan($tahun = null, $whereCondition['dipa']),
            'total_perencanaan' => number_format($this->pengadaanModel->total_perencanaan($tahun, $whereCondition['dipa']), 0, ',', '.'),
            'total_pelaksanaan' => number_format($this->pengadaanModel->total_pelaksanaan($tahun, $whereCondition['dipa']), 0, ',', '.'),
            'total_pembayaran' => number_format($this->pengadaanModel->total_pembayaran($tahun, $whereCondition['dipa']), 0, ',', '.'),
        ];

        return $this->response->setJSON($data);
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
            'tanggal_mulai',
            'tanggal_berakhir'
        ]);

        if (!$data) {
            return redirect()->back()->with('error', 'Data yang diteruskan tidak valid');
        }

        $this->pengadaanModel->insertData($data);
        return redirect()->to('/pengadaan')->with('success', 'Data baru pengadaan berhasil disimpan');
    }

    public function hapus_data_pengadaan($id)
    {
        $this->pengadaanModel->deletePengadaan($id);
        return redirect()->to('/pengadaan')->with('success', 'Data berhasil dihapus');
    }

    public function detail_pengadaan($id)
    {
        $search = $this->request->getGet('search'); // ambil keyword pencarian dari query string

        $listDipa = $this->dipaModel->findAll();
        $listJenis = $this->jenisModel->findAll();
        $listMetode = $this->metodeModel->findAll();
        $pengadaan = $this->pengadaanModel->getById($id);
        if (!$pengadaan) {
            session()->setFlashdata('error', 'Pengadaan tidak ditemukan!');
            return redirect()->to('/pengadaan');
        }

        $fileQuery = $this->fileModel
            ->where('ref_id_pengadaan', $id)
            ->where('deleted_at', null);

        if (!empty($search)) {
            $fileQuery->like('nama_file', $search);
        }

        $fileList = $fileQuery->findAll();




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
            $builder = $db->table($dokumenTable)
                ->select('id_dokumen, dokumen');

            // jika ada input pencarian, tambahkan filter LIKE
            if (!empty($search)) {
                $builder->like('dokumen', $search);
            }

            $dokumenList = $builder->get()->getResultArray();
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
            'search' => $search, // kirim ke view

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
        $username_user = session()->get('username');
        $nama_user = $this->userModel->getNamaByUsername($username_user);
        $nama_user = $nama_user->nama;

        if (!filter_var($id_pengadaan, FILTER_VALIDATE_INT) || !filter_var($id_dokumen, FILTER_VALIDATE_INT)) {
            session()->setFlashdata('error', 'ID Pengadaan atau ID Dokumen tidak valid.');
            return redirect()->back();
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $mime = $file->getMimeType();
            $ext = strtolower($file->getClientExtension());

            if ($ext !== 'pdf' || $mime !== 'application/pdf') {
                session()->setFlashdata('error', 'Hanya file PDF yang diperbolehkan');
                return redirect()->back();
            }

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
                    'pengunggah' => $nama_user,
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


    public function unduh_dokumen_laporan($id_pengadaan)
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

        // Tentukan tabel dokumen berdasarkan metode
        switch ($namaPengadaan['metode']) {
            case 'Pengadaan Langsung':
                $tableDokumen = 'tabel_pl';
                break;
            case 'Penunjukkan Langsung':
                $tableDokumen = 'tabel_juksung';
                break;
            case 'Tender':
                $tableDokumen = 'tabel_tender';
                break;
            case 'E-Purchasing':
                $tableDokumen = 'tabel_ep';
                break;
            case 'Swakelola':
                $tableDokumen = 'tabel_swakelola';
                break;
            default:
                $tableDokumen = '';
                break;
        }

        $templateDokumen = $this->templateDokumenModel->getDokumenByTable($tableDokumen);

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

        // Tambahkan file dokumen ke zip
        foreach ($files as $file) {
            $path = $folder . $file['nama_file'];
            if (file_exists($path)) {
                $zip->addFile($path, $file['nama_file']);
            }
        }

        // Mapping dokumen yang sudah diupload
        $uploadedFileMap = []; // [ref_id_dokumen => [nama_file1, nama_file2, ...]]
        foreach ($files as $file) {
            $refId = $file['ref_id_dokumen'];
            if (!isset($uploadedFileMap[$refId])) {
                $uploadedFileMap[$refId] = [];
            }
            $uploadedFileMap[$refId][] = $file['nama_file'];
        }

        // === Generate RESUME LAPORAN PDF ===
        $html = '<h3 style="text-align:center;">RESUME LAPORAN</h3>';

        // Detail Pengadaan
        $html .= '<h4 style="margin-top:15px; margin-bottom:8px;">Detail Pengadaan</h4>';
        $html .= '<table border="1" cellpadding="3" cellspacing="0" width="100%" style="margin-bottom: 15px; border-collapse: collapse; font-size: 11px;">';
        $html .= '<tbody>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>ID</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['id'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Tahun Anggaran</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['tahun_anggaran'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>DIPA</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['dipa'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Jenis</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['jenis'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Metode</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['metode'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Kode RUP</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['kode_rup'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Nama Pengadaan</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['nama_pengadaan'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Perencanaan</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['perencanaan'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Pelaksanaan</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['pelaksanaan'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Pembayaran</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['pembayaran'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Tanggal Mulai</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['tanggal_mulai'] . '</td></tr>';
        $html .= '<tr><td style="padding: 3px 6px;"><b>Tanggal Berakhir</b></td><td style="padding: 3px 6px;">' . $namaPengadaan['tanggal_berakhir'] . '</td></tr>';
        $html .= '</tbody></table>';

        // Daftar Dokumen
        $html .= '<h4 style="margin-top:15px; margin-bottom:8px;">Daftar Dokumen</h4>';
        $html .= '<table border="1" cellpadding="3" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 11px;">';
        $html .= '<thead>
<tr>
    <th style="width: 50%; padding: 3px 6px;">Dokumen</th>
    <th style="width: 40%; padding: 3px 6px;">Nama File</th>
    <th style="width: 10%; padding: 3px 6px; text-align: center;">Ceklis</th>
</tr>
</thead><tbody>';

        foreach ($templateDokumen as $template) {
            $idDokumen = $template['id_dokumen'];
            $namaDokumen = $template['dokumen'];

            if (isset($uploadedFileMap[$idDokumen])) {
                $fileList = '<ul><li>' . implode('</li><li>', $uploadedFileMap[$idDokumen]) . '</li></ul>';
                $html .= "<tr>
            <td style=\"padding: 3px 6px;\">$namaDokumen</td>
            <td style=\"padding: 3px 6px;\">$fileList</td>
            <td style=\"padding: 3px 6px; text-align: center; font-size: 16px; color: #00c853;\">&#10004;</td>
        </tr>";
            } else {
                $html .= "<tr>
            <td style=\"padding: 3px 6px;\">$namaDokumen</td>
            <td style=\"padding: 3px 6px; text-align: center;\"><i>Belum diunggah</i></td>
            <td style=\"padding: 3px 6px; text-align: center; font-size: 16px; color: #d50000;\">&#10008;</td>
        </tr>";
            }
        }

        $html .= '</tbody></table>';

        // Generate PDF dengan Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans'); // Font ini support banyak simbol
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $resumePath = WRITEPATH . 'uploads/RESUME_LAPORAN.pdf';
        file_put_contents($resumePath, $dompdf->output());

        // Tambahkan resume ke dalam ZIP
        if (file_exists($resumePath)) {
            $zip->addFile($resumePath, '_RESUME_LAPORAN_.pdf');
        }

        $zip->close();

        // Unduh ZIP
        $response = $this->response->download($zipFileName, null)->setFileName($namaPengadaan['nama_pengadaan'] . '.zip');

        // Hapus file sementara setelah proses selesai
        register_shutdown_function(function () use ($zipFileName, $resumePath) {
            if (file_exists($zipFileName)) {
                unlink($zipFileName);
            }
            if (file_exists($resumePath)) {
                unlink($resumePath);
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

            $username_user = session()->get('username');
            $nama_user = $this->userModel->getNamaByUsername($username_user);
            $nama_user = $nama_user->nama;

            // Debugging - Periksa apakah file ada
            if (!file_exists($oldFilePath)) {
                session()->setFlashdata('error', 'File tidak ditemukan di folder: ' . $oldFilePath);
                return redirect()->to(base_url('pengadaan/detail_pengadaan/' . $file['ref_id_pengadaan']));
            }

            // Coba ubah nama file
            if (rename($oldFilePath, $newFilePath)) {

                // Update informasi penghapus dan waktu hapus
                $this->fileModel->update($id_file, [
                    'penghapus' => $nama_user,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                // Hapus data dari database
                // $this->fileModel->delete($id_file);

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