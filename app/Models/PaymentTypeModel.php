<?php

namespace App\Models;
use CodeIgniter\Model;

class PaymentTypeModel extends Model
{
    protected $table = 'payment_types';
    protected $primaryKey = 'payment_types_id';
    protected $allowedFields = ['payment_types_id', 'payment_types_name'];
}
