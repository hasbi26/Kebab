<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5 Simple Admin Dashboard</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" >
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
    <link href="<?= base_url('assets/css/jquery.dataTables.min.css') ?>" rel="stylesheet">



    <style>
        .card{
            width : 5rem;
            height : 5rem;
        }
        .card-body {
            padding : 0;
            text-align : center;
        }


        .item-select {
    width: 120px;
}

.qty-input {
    width: 50px;
}

.harga-input {
    width: 60px;
}

.subtotal-input {
    width: 60px;
}

.remove-item {
    width: 60px;
}

    </style>

</head>
<body>


<div class="accordion" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        Penjualan Harian
      </button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
      <div class="accordion-body">
      <div class="row g-1">
            <div class="col">
            <div class="card">
                    <div class="card-body">
                        item
                        <h5><span class="badge rounded-pill bg-primary" id="total-quantity">0.0</span></h5>
                    </div>
                    </div>
            </div>
            <div class="col">
            <div class="card">
                <div class="card-body">
                penjualan
                    <h5><span class="badge rounded-pill bg-warning text-dark" id="total-sales">0.0</span></h5>
                </div>
                </div>

            </div>
            <div class="col">
            <div class="card">
            <div class="card-body">
            point
                    <h5><span class="badge rounded-pill bg-danger" id="total-point">0.0</span></h5>
                </div>
            </div>
            </div>
            </div>
            </div>

            <div class="input-group my-3">
    <span class="input-group-text">Date :</span>
    <input type="date" class="form-control"  id="filter-date" value="<?= date('Y-m-d') ?>">
    <button id="filter-btn" class="btn btn-warning">Tampilkan</button>
    </div>


    <!-- Tambahkan Tabel HTML -->
        <table id="purchases-table" class="display">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Tanggal</th>
                    <!-- <th>Supplier</th> -->
                    <th>Total Harga</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
            <tfoot>
        <tr>
            <th style="text-align:right">Total:</th>
            <th id="grand-total">Rp 0</th>
        </tr>
    </tfoot>
        </table>


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

    
    </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
        Penjualan Bulanan
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
      <div class="accordion-body">

      <div class="filter-section mb-3">
    <label for="filterMonth">Pilih Bulan:</label>
    <select id="filterMonth" class="form-control d-inline-block w-auto">
        <!-- Pilihan bulan akan diisi oleh JavaScript -->
    </select>

    <label for="filterWeek" class="ms-2">Pilih Minggu ke:</label>
    <select id="filterWeek" class="form-control d-inline-block w-auto">
        <!-- Pilihan minggu akan diisi oleh JavaScript -->
    </select>

    <button id="filterData" class="btn btn-primary ms-3">Filter</button>
</div>

<!-- Tabel Data -->
<div class="table-responsive">
    <h3>Penjualan</h3>
    <table class="table table-bordered table-striped" id="salesTable">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Minggu ke</th>
                <th>Tahun</th>
                <th>qty</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat dengan AJAX -->
        </tbody>
    </table>
</div>





    </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        Pembelian
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
      <div class="accordion-body p-0">

      <form id="purchase-form">
    <input type="date" id="tanggal" name="tanggal" required>
    <input type="text" id="supplier_name" name="supplier_name" placeholder="Supplier Name" required>
    <table class="table-responsive" id="details-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="item_id[]" class="item-select" required>
                        <!-- Options akan diisi oleh AJAX -->
                    </select>
                </td>
                <td><input type="number" name="qty[]" class="qty-input" required></td>
                <td><input type="number" name="harga[]" class="harga-input" readonly required></td>
                <td><input type="number" name="subtotal[]" class="subtotal-input" readonly></td>
                <td><button type="button" class="remove-item">Hapus</button></td>
            </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="1" style="font-weight: bold;">Total :</td>
            <td colspan="3"><input type="number" id="total-keseluruhan" readonly></td>
        </tr>
    </tfoot>
    </table>
    <button type="button" id="add-item">Tambah Item</button>
    <button type="submit">Submit</button>
</form>


    </div>
    </div>
  </div>
</div>




    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>" ></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>" ></script>
    <!-- <script src="<?= base_url('assets/js/bootstrapAdmin.min.js') ?>" ></script> -->
    <script src="<?= base_url('assets/js/chartist.min.js') ?>"></script>
    <!-- Github buttons -->
    <script>

    </script>
<script>


$(document).ready(function () {
  loadSales();
  loadItems();
 calculateTotalKeseluruhan();



              $('#filter-btn').click(function () {
                const date = $('#filter-date').val();
                loadSales(date);
            });
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1; // Bulan dimulai dari 0
    const currentWeek = getCurrentWeek(currentDate);

    // Inisialisasi pilihan bulan (Januari - Desember)
    const months = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    months.forEach((month, index) => {
        $('#filterMonth').append(
            `<option value="${index + 1}" ${index + 1 === currentMonth ? 'selected' : ''}>${month}</option>`
        );
    });

    // Fungsi untuk mendapatkan minggu dari bulan tertentu
    function getWeeksInMonth(year, month) {
        const firstDay = new Date(year, month - 1, 1);
        const lastDay = new Date(year, month, 0);
        const weeks = [];
        let start = new Date(firstDay);

        while (start <= lastDay) {
            const week = getCurrentWeek(start);
            if (!weeks.includes(week)) weeks.push(week);
            start.setDate(start.getDate() + 7); // Tambah 7 hari
        }
        return weeks;
    }

    // Fungsi untuk mendapatkan minggu ke-n
    function getCurrentWeek(date) {
        const oneJan = new Date(date.getFullYear(), 0, 1);
        const numberOfDays = Math.floor((date - oneJan) / (24 * 60 * 60 * 1000));
        return Math.ceil((date.getDay() + 1 + numberOfDays) / 7);
    }

    // Isi minggu berdasarkan bulan
    function populateWeeks(month, year) {
        const weeks = getWeeksInMonth(year, month);
        $('#filterWeek').html(''); // Kosongkan dulu
        weeks.forEach(week => {
            $('#filterWeek').append(
                `<option value="${week}" ${week === currentWeek ? 'selected' : ''}>Minggu ke-${week}</option>`
            );
        });
    }

    // Isi minggu untuk bulan sekarang
    populateWeeks(currentMonth, currentYear);

    // Update minggu saat bulan diubah
    $('#filterMonth').on('change', function () {
        const selectedMonth = $(this).val();
        populateWeeks(selectedMonth, currentYear);
    });

    // Fungsi untuk memuat data
    function loadData() {
        const month = $('#filterMonth').val();
        const week = $('#filterWeek').val();
        const year = currentYear; // Default ke tahun sekarang

        $.post('<?= base_url('/sales/getFilteredSales') ?>', { month, week, year }, function (data) {
            let rows = '';
            data.forEach(item => {
                rows += `
                    <tr>
                        <td>${months[item.month - 1]}</td>
                        <td>${item.week}</td>
                        <td>${item.year}</td>
                        <td>${item.qty}</td>
                        <td>${item.total_sales.toLocaleString()}</td>
                    </tr>
                `;
            });
            $('#salesTable tbody').html(rows);
        });
    }

    // Muat data awal saat halaman dibuka
    loadData();

    // Filter data saat tombol filter diklik
    $('#filterData').on('click', function () {
        loadData();
    });


    $('#purchases-table').DataTable({
    ajax: {
                url: '<?= base_url('/pembelian/getPembelian') ?>', // Endpoint ke controller
                dataSrc: 'data' // Akses array 'data' dari JSON
            },
    columns: [
        // { data: 'id' },
        { data: 'tanggal' },
        // { data: 'supplier_name' },
        { data: 'total_harga', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
        // {
        //     data: null,
        //     render: function (data, type, row) {
        //         return `<button class="btn btn-info view-details" data-id="${row.id}">Detail</button>`;
        //     }
        // }
    ],
    footerCallback: function (row, data, start, end, display) {
        let total = 0;
        data.forEach(function(item) {
            total += parseFloat(item.total_harga);
        });

        // Menampilkan total pada footer
        $(row).find('#grand-total').html(
            $.fn.dataTable.render.number(',', '.', 2, 'Rp ').display(total)
        );
    },
    // Event untuk memperbarui total ketika tabel digambar ulang (misalnya saat pencarian)
    drawCallback: function(settings) {
        let total = 0;
        let api = this.api();
        // Ambil data yang sedang ditampilkan di tabel
        api.rows({ page: 'current' }).data().each(function (item) {
            total += parseFloat(item.total_harga);
        });

        // Update total pada footer
        $('#grand-total').html($.fn.dataTable.render.number(',', '.', 2, 'Rp ').display(total));
    }
});

   
});





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
    $('#total-quantity').text(totalQuantity);
    $('#total-sales').text(totalSales.toLocaleString());
});

$.post('<?= base_url('/sales/point') ?>', { date: date }, function (data) {
     // Pastikan data tidak null
    const totalPoint = data.total_points ? data.total_points : 0.0;
    // Tampilkan data di elemen HTML
    $('#total-point').text(totalPoint.toLocaleString());
});


$.post('<?= base_url('/sales/acumulatepoint') ?>', { date: date }, function (data) {
    $('#monthPoint').text(data.total_points.toLocaleString());
     console.log(data.total_points);
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


$('#filterMonthly').on('click', function () {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.post('<?= base_url('/sales/getMonthlySales') ?>', { start_date: startDate, end_date: endDate }, function (data) {
        let rows = '';
        data.forEach(item => {
            rows += `
                <tr>
                    <td>${item.month}</td>
                    <td>${item.year}</td>
                    <td>${item.total_sales.toLocaleString()}</td>
                </tr>
            `;
        });

        $('#monthlySalesTable tbody').html(rows);
    });
});


$('#filterWeekly').on('click', function () {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.post('<?= base_url('/sales/getWeeklySales') ?>', { start_date: startDate, end_date: endDate }, function (data) {
        let rows = '';
        data.forEach(item => {
            rows += `
                <tr>
                    <td>${item.week}</td>
                    <td>${item.year}</td>
                    <td>${item.total_sales.toLocaleString()}</td>
                </tr>
            `;
        });

        $('#weeklySalesTable tbody').html(rows);
    });
});



//Pembelian Script


function loadItems() {
    $.ajax({
        url: '<?= base_url('/pembelian/getItems') ?>',
        type: 'GET',
        success: function (data) {
            // Buat options dari hasil data
            let options = '<option value="" disabled selected>Pilih Item</option>';
            data.forEach(item => {
                options += `<option value="${item.id}" data-harga="${item.harga}">${item.nama_item}</option>`;
            });

            // Isi hanya dropdown yang kosong
            $('.item-select').each(function () {
                if ($(this).children('option').length === 0) {
                    $(this).html(options);
                }
            });
        },
        error: function () {
            alert('Gagal memuat data item.');
        }
    });
}

$('#add-item').on('click', function () {
    // Opsi untuk dropdown baru
    let options = $('.item-select:first').html();

    let newRow = `
        <tr>
            <td>
                <select name="item_id[]" class="item-select" required>
                    ${options}
                </select>
            </td>
            <td><input type="number" name="qty[]" class="qty-input" required></td>
            <td><input type="number" name="harga[]" class="harga-input" readonly required></td>
            <td><input type="number" name="subtotal[]" class="subtotal-input" readonly></td>
            <td><button type="button" class="remove-item">Hapus</button></td>
        </tr>
    `;
    $('#details-table tbody').append(newRow);
});


$(document).on('click', '.remove-item', function () {
    $(this).closest('tr').remove();
});


// Saat item dipilih, set harga
$(document).on('change', '.item-select', function () {
    let selectedOption = $(this).find('option:selected');
    let harga = selectedOption.data('harga');

    $(this).closest('tr').find('.harga-input').val(harga);
    updateSubtotal($(this).closest('tr'));
});

// Saat qty berubah, hitung subtotal
$(document).on('input', '.qty-input', function () {
    updateSubtotal($(this).closest('tr'));
});

// Fungsi untuk memperbarui subtotal
function updateSubtotal(row) {
    let qty = parseInt(row.find('.qty-input').val()) || 0;
    let harga = parseInt(row.find('.harga-input').val()) || 0;

    let subtotal = qty * harga;
    row.find('.subtotal-input').val(subtotal);
}



$('#purchase-form').on('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    let purchaseData = {
        tanggal: $('#tanggal').val(),
        supplier_name: $('#supplier_name').val(),
        total_harga: 0, // Akan dihitung dari subtotal
        details: []
    };

    // Ambil detail item
    $('#details-table tbody tr').each(function () {
        // let item_id = $(this).find('input[name="item_id[]"]').val();
        let item_id = $(this).find('select[name="item_id[]"]').val(); // Pastikan menggunakan 'select' untuk item_id
        let qty = parseInt($(this).find('input[name="qty[]"]').val());
        let harga = parseInt($(this).find('input[name="harga[]"]').val());
        let subtotal = qty * harga;

        purchaseData.total_harga += subtotal;

        purchaseData.details.push({
            item_id: item_id,
            qty: qty,
            harga: harga,
            subtotal: subtotal
        });
    });

    // Kirim data ke server
    $.ajax({
        url: '<?= base_url('/pembelian/createPurchase') ?>',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(purchaseData),
        success: function (response) {
            alert('Transaksi berhasil disimpan! ID: ' + response.id);
            location.reload()
        },
        error: function (xhr, status, error) {
            alert('Gagal menyimpan transaksi: ' + error);
        }
    });
});



// Fungsi untuk menghitung subtotal per baris
function calculateSubtotal(row) {
    const qty = parseFloat(row.find('.qty-input').val()) || 0;
    const harga = parseFloat(row.find('.harga-input').val()) || 0;
    const subtotal = qty * harga;

    row.find('.subtotal-input').val(subtotal);
    return subtotal;
}

// Fungsi untuk menghitung total keseluruhan
function calculateTotalKeseluruhan() {
    let total = 0;
    $('#details-table tbody tr').each(function () {
        total += parseFloat($(this).find('.subtotal-input').val()) || 0;
    });

    $('#total-keseluruhan').val(total);
}

// Event listener untuk menghitung subtotal dan total saat qty atau harga berubah
$('#details-table').on('input', '.qty-input, .harga-input', function () {
    const row = $(this).closest('tr');
    calculateSubtotal(row);
    calculateTotalKeseluruhan();
});

// Event listener untuk menghapus baris dan memperbarui total
$('#details-table').on('click', '.remove-item', function () {
    $(this).closest('tr').remove();
    calculateTotalKeseluruhan();
});

// Hitung total awal jika data sudah diisi sebelumnya
// $(document).ready(function () {
//     calculateTotalKeseluruhan();
// });



</script>


</body>
</html>