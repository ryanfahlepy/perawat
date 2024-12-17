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
use App\Models\UserModel;

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
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
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
