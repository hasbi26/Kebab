<?php
namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false; // Pastikan Auto Increment dimatikan
    protected $allowedFields = ['id', 'tanggal', 'supplier_name', 'total_harga'];
}
