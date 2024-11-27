<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';
    protected $allowedFields = ['menu_name', 'price'];
}
