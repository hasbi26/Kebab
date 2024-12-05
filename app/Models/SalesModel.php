<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    protected $allowedFields = ['sales_id','menu_id', 'quantity', 'total_price', 'sale_date', 'payment_type_id'];
}
