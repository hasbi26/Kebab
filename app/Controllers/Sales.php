<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\SalesModel;
use App\Models\PaymentTypeModel;

class Sales extends BaseController
{

    public function generateTimestampedID($length = 8)
    {
        $timestamp = dechex(time()); // Timestamp dalam format heksadesimal
        $randomHex = bin2hex(random_bytes(4)); // Heksadesimal acak
        return substr($timestamp . $randomHex, 0, $length); // Gabungkan timestamp dan hex acak
    }

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
        ->select('sales.*, menu.point, menu.menu_name, payment_types.payment_type_name, menu.alias, TIME(sales.sale_date) AS sale_time')
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


public function getSalesByPaymentType()
{
    $salesModel = new SalesModel();

    // Ambil tanggal dari request, gunakan default hari ini jika kosong
    $date = $this->request->getPost('date') ?? date('Y-m-d');

    // Query untuk mendapatkan total penjualan per payment type
    $salesByPaymentType = $salesModel
        ->select('payment_types.payment_type_name, SUM(sales.total_price) as total_sales, COUNT(sales.sales_id) as total_transactions')
        ->join('payment_types', 'payment_types.payment_type_id = sales.payment_type_id')
        ->where('DATE(sales.sale_date)', $date)
        ->groupBy('payment_types.payment_type_id') // Kelompokkan berdasarkan payment type
        ->orderBy('payment_types.payment_type_name', 'ASC') // Sorting berdasarkan nama payment type
        ->findAll();

    // Kirim data dalam format JSON
    return $this->response->setJSON($salesByPaymentType);
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
    }

    public function calculatePointsByMonth()
{
    $salesModel = new SalesModel();


    $date = $this->request->getPost('date');

    if (!$date || !strtotime($date)) {
        return $this->response->setJSON(['error' => 'Invalid date format'])->setStatusCode(400);
    }


        // Ekstrak bulan dan tahun dari tanggal
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

    // Tentukan rentang tanggal dari tanggal 1 hingga akhir bulan
    $startDate = "$year-$month-01";
    $endDate = date("Y-m-t", strtotime($startDate)); // Menghitung tanggal terakhir bulan

    // Query untuk menghitung total point
    $point = $salesModel
        ->select('SUM(
            CASE
                WHEN sales.payment_type_id NOT IN (1, 2) THEN menu.point * 0.7 * sales.quantity
                ELSE menu.point * sales.quantity
            END
        ) AS total_points')
        ->join('menu', 'menu.menu_id = sales.menu_id') // Join untuk mendapatkan data point
        ->where('sales.sale_date >=', $startDate) // Mulai dari tanggal 1
        ->where('sales.sale_date <=', $endDate)   // Sampai tanggal terakhir bulan
        ->first(); // Ambil hasil pertama

    return $this->response->setJSON($point);
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

    $salesId = $this->generateTimestampedID();

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
        'sales_id' => $salesId, // Gunakan ID unik pendek
        'menu_id' => $menuId,
        'quantity' => $quantity,
        'total_price' => $totalPrice,
        'payment_type_id' => $paymentType,
        'sale_date' => $saleDate // Kirimkan tanggal yang diterima dari pengguna
    ]);

    // $db = \Config\Database::connect();
    // var_dump($db->getLastQuery());
    // die();

    return $this->response->setJSON(['success' => true]);
}


public function getFilteredSales()
{
    $salesModel = new SalesModel();

    // Ambil data dari request
    $month = $this->request->getPost('month');
    $week = $this->request->getPost('week');
    $year = $this->request->getPost('year');

    // Query untuk filter bulan dan minggu
    $filteredSales = $salesModel
        ->select('MONTH(sale_date) AS month, WEEK(sale_date, 1) AS week, YEAR(sale_date) AS year, SUM(quantity) As qty, SUM(total_price) AS total_sales')
        ->where('YEAR(sale_date)', $year)
        ->where('MONTH(sale_date)', $month)
        ->where('WEEK(sale_date, 1)', $week)
        ->groupBy('YEAR(sale_date), MONTH(sale_date), WEEK(sale_date, 1)')
        ->findAll();

    return $this->response->setJSON($filteredSales);
}




}
