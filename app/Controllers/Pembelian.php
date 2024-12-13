<?php
namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\DetailPembelianModel;
use App\Models\DaftarHargaModel;
class Pembelian extends BaseController
{
    public function generateTimestampedID($length = 32)
    {
        $timestamp = dechex(time()); // Timestamp dalam format heksadesimal
        $randomHex = bin2hex(random_bytes(8)); // Heksadesimal acak
        return substr($timestamp . $randomHex, 0, $length); // Gabungkan timestamp dan hex acak
    }

    public function createPurchase()
    {
        // Input dari frontend
        $data = $this->request->getJSON();

        // Generate ID untuk pembelian
        $purchaseID = $this->generateTimestampedID();

        // Buat Model untuk pembelian
        $pembelianModel = new PembelianModel();
        $detailPembelianModel = new DetailPembelianModel();

        // Simpan data pembelian
      if  (!$pembelianModel->save([
            'id' => $purchaseID,
            'tanggal' => $data->tanggal,
            'supplier_name' => $data->supplier_name,
            'total_harga' => $data->total_harga
        ])){
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $pembelianModel->errors() // Menampilkan pesan error validasi
            ]);
        };
        // Simpan detail pembelian
        foreach ($data->details as $detail) {
            $detailPembelianModel->save([
                'id' => $this->generateTimestampedID(), // Panggil dengan $this->
                'pembelian_id' => $purchaseID,
                'item_id' => $detail->item_id,
                'qty' => $detail->qty,
                'harga' => $detail->harga,
                'subtotal' => $detail->subtotal
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'id' => $purchaseID]);
    }




    public function getItems()
    {
        $model = new DaftarHargaModel();
        $items = $model->findAll();

        return $this->response->setJSON($items);
    }

}
