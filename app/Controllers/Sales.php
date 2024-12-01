<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\SalesModel;
use App\Models\PaymentTypeModel;

class Sales extends BaseController
{
    public function index()
    {
        $menuModel = new MenuModel();
        $data['menus'] = $menuModel->findAll(); // Ambil semua data menu
        $paymentTypeModel = new PaymentTypeModel();
        $data = [
            'menus' => $menuModel->findAll(),
            'paymentTypes' => $paymentTypeModel->findAll(),

        ];
   
        return view('sales_form', $data);
    }

    public function getSales()
    {
        $salesModel = new SalesModel();

        // Ambil tanggal dari request, gunakan default hari ini jika kosong
        $date = $this->request->getPost('date') ?? date('Y-m-d');


        // Filter data penjualan berdasarkan tanggal
        $sales = $salesModel
        ->select('sales.*, menu.menu_name, payment_types.payment_type_name, menu.alias, TIME(sales.sale_date) AS sale_time')
        ->join('menu', 'menu.menu_id = sales.menu_id')
        ->join('payment_types', 'payment_types.payment_type_id = sales.payment_type_id')
        ->where('DATE(sales.sale_date)', $date)
        ->orderBy('sale_time', 'ASC') // Sorting berdasarkan jam
        ->findAll();


        // Kirim data dalam format JSON
        return $this->response->setJSON($sales);
    }


    public function getSalesSummary()
{
    $salesModel = new SalesModel();
    $date = $this->request->getPost('date') ?? date('Y-m-d'); // Default ke hari ini jika tanggal kosong

    $summary = $salesModel
        ->select('SUM(quantity) as total_quantity, SUM(total_price) as total_sales')
        ->where('DATE(sale_date)', $date)
        ->first();

    return $this->response->setJSON($summary);
}


public function calculatePoints()
    {
        $salesModel = new SalesModel();

        $date = $this->request->getPost('date') ?? date('Y-m-d'); // Default ke hari ini jika tanggal kosong

        // Query untuk menghitung total point
        $point = $salesModel
                ->select('SUM(
                    CASE
                        WHEN sales.payment_type_id NOT IN (1, 2) THEN menu.point * 0.7 * sales.quantity
                        ELSE menu.point * sales.quantity
                    END
                ) AS total_points')
                ->join('menu', 'menu.menu_id = sales.menu_id') // Join untuk mendapatkan data point
                ->where('DATE(sales.sale_date)', $date) // Filter berdasarkan tanggal
                ->first(); // Ambil hasil pertama

            return $this->response->setJSON($point);

        // Menampilkan hasil
        // return view('sales/points', ['sales' => $sales]);
    }



public function save()
{
    $salesModel = new SalesModel();
    $menuId = $this->request->getPost('menu_id');
    $quantity = $this->request->getPost('quantity');
    $paymentType = $this->request->getPost('payment_type_id');
    $datetime = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));  // Pastikan menggunakan namespace global "\DateTime"
    $formattedDate = $datetime->format('Y-m-d\TH:i');
    $saleDate = $formattedDate; // Ambil tanggal dari input form

    // Jika tidak ada tanggal yang diberikan, gunakan tanggal saat ini
    if (empty($saleDate)) {
        $saleDate = date('Y-m-d H:i:s'); // Format default untuk MySQL DATETIME
    }

    // Dapatkan harga dari menu
    $menuModel = new MenuModel();
    $menu = $menuModel->find($menuId);
    $price = $menu['price'];

    $totalPrice = $quantity * $price;

    // Simpan ke database
    $salesModel->insert([
        'menu_id' => $menuId,
        'quantity' => $quantity,
        'total_price' => $totalPrice,
        'payment_type_id' => $paymentType,
        'sale_date' => $saleDate // Kirimkan tanggal yang diterima dari pengguna
    ]);

    return $this->response->setJSON(['success' => true]);
}

}
