<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MenuModel;
use App\Models\User_level_aksesModel;
use App\Models\NotifikasiModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();
        $userId = $session->get('user_id');
        $level = $session->get('nama_level');

        // Inisialisasi model
        $pelatihanhasilModel = new \App\Models\PelatihanHasilModel();
        $pelatihantambahanModel = new \App\Models\PelatihanTambahanModel();
        $notifikasiModel = new \App\Models\NotifikasiModel();

        $url_pelatihan = base_url("pelatihan/");

        // ===== Cek pelatihan hasil yang hampir kadaluarsa =====
        $pelatihanHampirKadaluarsa = $pelatihanhasilModel
            ->where('user_id', $userId)
            ->where('berlaku IS NOT NULL')
            ->where('berlaku <=', date('Y-m-d', strtotime('+1 month')))
            ->where('berlaku >=', date('Y-m-d')) // pastikan belum kadaluarsa
            ->findAll();
        
        foreach ($pelatihanHampirKadaluarsa as $p) {
            
            $pesan = "Sertifikat pelatihan wajib anda hampir kadaluarsa";

            $sudahAda = $notifikasiModel
                ->where('user_tujuan_id', $userId)
                ->where('status', 'belum_dibaca')
                ->where('pesan', $pesan)
                ->first();

            if (!$sudahAda) {
                $notifikasiModel->insert([
                    'user_tujuan_id' => $userId,
                    'pesan' => $pesan,
                    'status' => 'belum_dibaca',
                    'url' => $url_pelatihan,
                ]);
            }
        }
        $pelatihanTambahanHampirKadaluarsa = $pelatihantambahanModel
            ->where('user_id', $userId)
            ->where('berlaku IS NOT NULL')
            ->where('berlaku <=', date('Y-m-d', strtotime('+1 month')))
            ->where('berlaku >=', date('Y-m-d')) // pastikan belum kadaluarsa
            ->findAll();


        foreach ($pelatihanTambahanHampirKadaluarsa as $pt) {
        
            $pesan = "Setifikat pelatihan tambahan anda hampir kadaluarsa";

            $sudahAdaTambahan = $notifikasiModel
                ->where('user_tujuan_id', $userId)
                ->where('status', 'belum_dibaca')
                ->where('pesan', $pesan)
                ->first();

            if (!$sudahAdaTambahan) {
                $notifikasiModel->insert([
                    'user_tujuan_id' => $userId,
                    'pesan' => $pesan,
                    'status' => 'belum_dibaca',
                    'url' => $url_pelatihan,
                ]);
            }
        }


        // ===== Ambil notifikasi untuk user =====
        $this->data['notifikasi'] = $notifikasiModel
            ->where('user_tujuan_id', $userId)
            ->where('status', 'belum_dibaca')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $this->data['jumlahNotif'] = $notifikasiModel
            ->where('user_tujuan_id', $userId)
            ->where('status', 'belum_dibaca')
            ->countAllResults();

        $this->data['level_akses'] = $level;

        return view('shared_page/right_navbar', $this->data);
    }







    public function tampil_menu($user_level_id)
    {
        $menuModel = new MenuModel();
        $user_level_aksesModel = new User_level_aksesModel();
        $dtlakses = $user_level_aksesModel->getMenu_akses($user_level_id)->getResult();
        $dtmenu = [];
        foreach ($dtlakses as $menu) {
            $dtmenu[$menu->nama_level] = $menuModel->getMenu($menu->menu_akses_id)->getResult();
        }
        return $dtmenu;
    }
    public function bersihkan($inputan)
    {
        $bersih = htmlentities($inputan, ENT_QUOTES, "UTF-8");
        return trim($bersih);
    }
}
