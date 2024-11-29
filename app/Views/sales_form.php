<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales with AJAX</title>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" >

    

</head>
<style>

#total-quantity{
    font-size : 15px;
}
#total-sales{
    font-size : 15px;
}
#total-point{
    font-size : 15px;
}


hr {
  border: none;
  height: 0.5px !important;
  /* Set the hr color */
  color: #333;  /* old IE */
  background-color: #333;  /* Modern Browsers */
  margin-top : 1px
}

</style>
<body>
    <!-- Form Input Penjualan -->
    <div class="container-sm">
        <div class="row justify-content-center">
            <div class="col">
                <h4 class="text-center">Form Sales</h4>
                <hr>
            </div>
        </div>
    <form id="sales-form">

        <div class="input-group mb-3">
        <label class="input-group-text" for="menu_id">Menu : </label>
        <select class="form-select-sm" id="menu_id" name="menu_id" required >
        <?php foreach ($menus as $menu): ?>
                <option value="<?= $menu['menu_id'] ?>">
                    <?= $menu['menu_name'] ?> - <?= number_format($menu['price'], 0, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select>
        </div>

        <div class="input-group mb-3">
        <span class="input-group-text">Jumlah</span>
        <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
        </div>
        <div class="row">
            <div class="col-8">
            <div class="input-group mb-3">
            <label class="input-group-text" for="pembayaran">Pembayaran : </label>
            <select class="form-select-sm" id="payment_type_id" name="payment_type_id" required >
            <?php foreach ($paymentTypes as $type): ?>
                <option value="<?= $type['payment_type_id'] ?>"><?= $type['payment_type_name'] ?></option>
            <?php endforeach; ?>
            </select>
            </div>
            </div>
            <div class="col-4">
            <button type="submit" class="btn btn-success">Simpan</button>
            </div>

        </div>
    </form>
<hr>

    <div class="row justify-content-center mb-3">
            <div class="col mb-1">
                <h4 class="text-center">Data Penjualan</h4>
            </div>

            <div class="row">
                <div class="col-4 px-0">
                <span class="badge bg-warning text-dark" id="total-quantity">0</span>
                </div>
                <div class="col-4 px-0">
                <span class="badge bg-info text-dark" id="total-sales">0</span>
                </div>
                <div class="col-4 px-0">
                <span class="badge bg-danger" id="total-point" >0</span>
                </div>
            </div>


            </div>
    <div class="input-group mb-3">
    <span class="input-group-text">Date :</span>
    <input type="date" class="form-control"  id="filter-date" value="<?= date('Y-m-d') ?>">
    <button id="filter-btn" class="btn btn-warning">Tampilkan</button>
    </div>


    <table border="1" class="table table-striped table-hover" id="sales-table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat dengan AJAX -->
        </tbody>
    </table>


            </div>
</body>


<script>
        function loadSales(date = '') {

            if (date === '') {
                    const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
                    date = today;
                }

                $.post('<?= base_url('/sales/summary') ?>', { date: date }, function (data) {
                 // Pastikan data tidak null
                const totalQuantity = data.total_quantity ? data.total_quantity : 0;
                const totalSales = data.total_sales ? data.total_sales : 0;
                const totalPoint = data.total_point ? data.total_point : 0.0;
                // Tampilkan data di elemen HTML
                $('#total-quantity').text("Item : " + totalQuantity);
                $('#total-sales').text("Rp : " + totalSales.toLocaleString());
            });

            $.post('<?= base_url('/sales/point') ?>', { date: date }, function (data) {
                 // Pastikan data tidak null
                const totalPoint = data.total_points ? data.total_points : 0.0;
                // Tampilkan data di elemen HTML
                $('#total-point').text("Point : " + totalPoint.toLocaleString());
            });





            $.post('<?= base_url('/sales/getSales') ?>', { date: date }, function (data) {
                let rows = '';
                data.forEach(sale => {
                    rows += `
                        <tr>
                            <td>${sale.alias}</td>
                            <td>${sale.quantity}</td>
                            <td>${sale.payment_type_name}</td>
                            <td>${sale.total_price.toLocaleString()}</td>
                            <td>${sale.sale_time}</td>
                        </tr>
                    `;
                });
                $('#sales-table tbody').html(rows);
            });
        }




        // Muat data penjualan default (hari ini) saat halaman dimuat
        $(document).ready(function () {
            loadSales();

            // Filter data berdasarkan tanggal
            $('#filter-btn').click(function () {
                const date = $('#filter-date').val();
                loadSales(date);
            });

            // Simpan data penjualan baru
            $('#sales-form').submit(function (e) {
                e.preventDefault(); // Mencegah reload halaman
                const formData = $(this).serialize();

                $.post('<?= base_url('/sales/save') ?>', formData, function (response) {
                    if (response.success) {
                        alert('Penjualan berhasil disimpan!');
                        $('#sales-form')[0].reset(); // Reset form
                        loadSales($('#filter-date').val()); // Perbarui tabel
                    }
                });
            });
        });
    </script>
</html>
