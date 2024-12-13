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

.badge{
    font-size : 15px;
}


.card{
            width : 5rem;
            height : 5rem;
        }
        .card-body {
            padding : 0;
            text-align : center;
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

<div class="collapse" id="navbarToggleExternalContent">
  <div class="bg-dark p-4">


  <ul class="list-group">
  <li class="list-group-item">Point Bulan <span class="badge bg-danger text-white" id="month"> </span>  <span class="badge bg-success text-white" id="monthPoint">0</span> </li>

  <li class="list-group-item list-group-item-primary">Total Penjualan Cash <span class="badge bg-danger text-white" id="TotCash"> 0.0 </span> </li>
  <li class="list-group-item list-group-item-secondary">Total Penjualan Qris <span class="badge bg-danger text-white" id="TotQris"> 0.0 </span></li>
  <li class="list-group-item list-group-item-success">Total Penjualan Gojek <span class="badge bg-danger text-white" id="TotGojek"> 0.0 </span></li>
  <li class="list-group-item list-group-item-danger">Total Penjualan Shopee <span class="badge bg-danger text-white" id="TotShopee"> 0.0 </span></li>
  <li class="list-group-item list-group-item-warning">Total Penjualan Grab <span class="badge bg-danger text-white" id="TotGrab"> 0.0 </span></li>
  <li class="list-group-item list-group-item-info">A simple info list group item</li>
  <li class="list-group-item list-group-item-light">A simple light list group item</li>
  <li class="list-group-item list-group-item-dark">A simple dark list group item</li>
</ul>
  
  </div>
</div>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>


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



            <div class="row">
                <div class="col text-center">
                <span class="badge bg-danger" id="total-point" >0</span>
                </div>
            </div>


            </div>
    <div class="input-group my-3">
    <span class="input-group-text">Date :</span>
    <input type="date" class="form-control"  id="filter-date" value="<?= date('Y-m-d') ?>">
    <button id="filter-btn" class="btn btn-warning">Tampilkan</button>
    </div>


    <table border="1" class="table table-striped table-hover" id="sales-table">
        <thead>
            <tr>
                <th>#Menu</th>
                <th>qty</th>
                <th>Pay</th>
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


                $.post('<?= base_url('/sales/getSalesByPaymentType') ?>', { date: date }, function (data) {
    // Reset semua nilai badge ke 0.0
                $('#TotCash').text('0.0');
                $('#TotQris').text('0.0');
                $('#TotGojek').text('0.0');
                $('#TotShopee').text('0.0');
                $('#TotGrab').text('0.0');

    // Update nilai badge berdasarkan data dari server
    data.forEach(item => {
        switch (item.payment_type_name.toLowerCase()) {
            case 'cash':
                $('#TotCash').text(item.total_sales.toLocaleString());
                break;
            case 'qris':
                $('#TotQris').text(item.total_sales.toLocaleString());
                break;
            case 'gojek':
                $('#TotGojek').text(item.total_sales.toLocaleString());
                break;
            case 'shopee':
                $('#TotShopee').text(item.total_sales.toLocaleString());
                break;
            case 'grab':
                $('#TotGrab').text(item.total_sales.toLocaleString());
                break;
            default:
                console.warn(`Payment type ${item.payment_type_name} tidak dikenali.`);
        }
    });
});








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


            $.post('<?= base_url('/sales/acumulatepoint') ?>', { date: date }, function (data) {
                $('#monthPoint').text(data.total_points.toLocaleString());
            });


            $.post('<?= base_url('/sales/getSales') ?>', { date: date }, function (data) {
    let rows = '';
    let totalQty = 0;
    let totalPrice = 0;


    data.forEach(sale => {
        totalQty += parseInt(sale.quantity); // Menambahkan ke total quantity
        totalPrice += parseFloat(sale.total_price); // Menambahkan ke total price
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

    // Menambahkan baris ke tabel
    rows += `
        <tr style="font-weight: bold;">
            <td colspan="1" style="text-align: right;">Total</td>
            <td>${totalQty}</td>
            <td>-</td>
            <td>${totalPrice.toLocaleString()}</td>
            <td>-</td>
        </tr>
    `;

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


        document.addEventListener('DOMContentLoaded', function () {
    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const filterDateInput = document.getElementById('filter-date');
    const monthBadge = document.getElementById('month');

    // Fungsi untuk memperbarui nama bulan
    function updateMonthName(dateString) {
        const date = new Date(dateString);
        const monthName = monthNames[date.getMonth()]; // Ambil nama bulan berdasarkan indeks
        monthBadge.textContent = monthName; // Tampilkan nama bulan di badge
    }

    // Inisialisasi dengan nilai awal
    updateMonthName(filterDateInput.value);

    // Tambahkan event listener untuk perubahan pada input tanggal
    filterDateInput.addEventListener('change', function () {
        updateMonthName(this.value);
    });
});

    </script>

<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>" > </script>


</html>
