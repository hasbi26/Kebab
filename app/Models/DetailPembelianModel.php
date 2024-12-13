<?php
namespace App\Models;

use CodeIgniter\Model;

class DetailPembelianModel extends Model
{
    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false; // Pastikan Auto Increment dimatikan
    protected $allowedFields = ['id', 'pembelian_id', 'item_id', 'qty', 'harga', 'subtotal'];
}
