<?php

namespace App\Models;

use CodeIgniter\Model;

class User_level_aksesModel extends Model
{
    protected $table                = 'tbuser_level_akses';
    protected $primaryKey           = 'id';
    protected $allowedFields        = ['user_level_id', 'menu_akses_id'];
    protected $returnType           = 'App\Entities\Output';

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';

    public function getMenu_akses($level_id)
    {
        $this->builder()->select('tbuser_level_akses.*, tbuser_level.*');
        return $this->builder()->join('tbuser_level', 'tbuser_level.id=tbuser_level_akses.menu_akses_id')
            ->where('user_level_id', $level_id)->get();
    }
}
