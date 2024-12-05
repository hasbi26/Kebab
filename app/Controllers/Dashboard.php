<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\SalesModel;
use App\Models\PaymentTypeModel;


class Dashboard extends BaseController
{




    public function index(): string
    {
        return view('dashboard');
    }


    public function GetSalesMonthly(){

        $salesModel = new SalesModel();

        // Ambil bulan dan tahun dari request, gunakan default bulan dan tahun ini jika kosong
        $month = $this->request->getPost('month') ?? date('m'); // Bulan (1-12)
        $year = $this->request->getPost('year') ?? date('Y'); // Tahun (YYYY)
    
        // Format tanggal pertama dan terakhir dalam bulan tersebut
        $startDate = "$year-$month-01"; // Tanggal pertama bulan
        $endDate = date("Y-m-t", strtotime($startDate)); // Tanggal terakhir bulan
    
        // Filter data penjualan berdasarkan rentang tanggal (bulan dan tahun)
        $sales = $salesModel
            ->select('sales.*, menu.menu_name, payment_types.payment_type_name, menu.alias, TIME(sales.sale_date) AS sale_time')
            ->join('menu', 'menu.menu_id = sales.menu_id')
            ->join('payment_types', 'payment_types.payment_type_id = sales.payment_type_id')
            ->where('sales.sale_date >=', $startDate)
            ->where('sales.sale_date <=', $endDate)
            ->orderBy('sales.sale_date', 'ASC') // Sorting berdasarkan waktu
            ->findAll();
    
        // Kirim data dalam format JSON
        return $this->response->setJSON($sales);


    }


}
