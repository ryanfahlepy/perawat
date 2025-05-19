<?php


namespace App\Controllers;
use App\Models\KinerjaModel;
use App\Models\User_levelModel;


class Ekinerja extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->kinerjaModel = new KinerjaModel();
        $this->user_levelModel = new User_levelModel();
    }

    public function index()
    {
        $dataKinerja = $this->kinerjaModel
            ->select('tabel_kinerja.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_kinerja.level_user', 'left')
            ->asArray()
            ->findAll();

        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'E-Kinerja',
            'data_kinerja' => $dataKinerja,
            'user_levels' => $this->user_levelModel->findAll(),
        ];

        return view('layout/ekinerja', $data);
    }

}