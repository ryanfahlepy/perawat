<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table                = 'tbmenu';
    protected $primaryKey           = 'id';
    protected $returnType           = 'App\Entities\Output';
    protected $allowedFields        = ['user_level_id', 'nama', 'url', 'icon', 'urutan', 'aktif', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';

    public function getMenu($user_level_id = false)
    {
        $this->builder()->select('tbmenu.*, tbuser_level.nama_level');
        if ($user_level_id) {
            return $this->builder()->join('tbuser_level', 'tbuser_level.id = tbmenu.user_level_id')
                ->where(['user_level_id' => $user_level_id, 'aktif' => '1'])->orderBy('urutan ASC')->get();
        } else {
            return $this->builder()->join('tbuser_level', 'tbuser_level.id = tbmenu.user_level_id')
                ->orderBy('urutan ASC')->get();
        }
    }
}
