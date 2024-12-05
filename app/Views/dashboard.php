<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5 Simple Admin Dashboard</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" >
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>

    <style>
        .card{
            width : 5rem;
            height : 5rem;
        }
        .card-body {
            padding : 0;
            text-align : center;
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
        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
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
      <div class="accordion-body">
        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>" ></script>
    <!-- <script src="<?= base_url('assets/js/bootstrapAdmin.min.js') ?>" ></script> -->
    <script src="<?= base_url('assets/js/chartist.min.js') ?>"></script>
    <!-- Github buttons -->
    <!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
    <script>
        // new Chartist.Line('#traffic-chart', {
        //     labels: ['January', 'Februrary', 'March', 'April', 'May', 'June'],
        //     series: [
        //         [23000, 25000, 19000, 34000, 56000, 64000]
        //     ]
        //     }, {
        //     low: 0,
        //     showArea: true
        // });
    </script>
<script>

$(document).ready(function () {
            loadSales();

            $('#filter-btn').click(function () {
                const date = $('#filter-date').val();
                loadSales(date);
            });

})
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

// document.addEventListener('DOMContentLoaded', function () {
//     const monthNames = [
//         "Januari", "Februari", "Maret", "April", "Mei", "Juni",
//         "Juli", "Agustus", "September", "Oktober", "November", "Desember"
//     ];

//     const currentYear = new Date().getFullYear();
//     const currentMonth = new Date().getMonth(); // Indeks bulan (0-11)

//     const monthSelect = document.getElementById('filter-month');
//     const yearSelect = document.getElementById('filter-year');

//     const salesTable = document.getElementById('sales-table').querySelector('tbody');


    // Isi opsi bulan
    // monthNames.forEach((month, index) => {
    //     const option = document.createElement('option');
    //     option.value = index + 1; // Nilai bulan (1-12)
    //     option.textContent = month;
    //     if (index === currentMonth) {
    //         option.selected = true; // Set bulan default ke bulan ini
    //     }
    //     monthSelect.appendChild(option);
    // });

    // // Isi opsi tahun (-1 dan +1 dari tahun sekarang)
    // for (let year = currentYear - 1; year <= currentYear + 1; year++) {
    //     const option = document.createElement('option');
    //     option.value = year;
    //     option.textContent = year;
    //     if (year === currentYear) {
    //         option.selected = true; // Set tahun default ke tahun ini
    //     }
    //     yearSelect.appendChild(option);
    // }

    // Tambahkan event listener jika diperlukan
    // monthSelect.addEventListener('change', function () {
    //   loadSalesData()
    //     // console.log('Selected Month:', this.value); // Ambil bulan yang dipilih
    // });

    // yearSelect.addEventListener('change', function () {
    //   loadSalesData() // console.log('Selected Year:', this.value); // Ambil tahun yang dipilih
    // });


//     function loadSalesData() {

// const selectedMonth = monthSelect.value;
// const selectedYear = yearSelect.value;

// // Format tanggal untuk dikirim ke server
// const formattedDate = `${selectedYear}-${String(selectedMonth).padStart(2, '0')}-01`;

// // Ajax request ke server
// $.post('<?= base_url('/dash/salesMonth') ?>', { date: formattedDate }, function (data) {
//     // Kosongkan tabel

//     salesTable.innerHTML = '';

//     // Tambahkan data ke tabel
//     data.forEach(sale => {
//         const row = `
//             <tr>
//                 <td>${sale.alias}</td>
//                 <td>${sale.payment_type_name}</td>
//                 <td>${sale.quantity}</td>
//                 <td>${sale.total_price}</td>
//                 <td>${sale.sale_date}</td>
//             </tr>
//         `;
//         salesTable.innerHTML += row;
//     });
// });
// }

// loadSalesData();

// });








</script>


</body>
</html>