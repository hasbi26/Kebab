<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales with AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Form Input Penjualan -->
    <h2>Input Penjualan Baru</h2>
    <form id="sales-form">
        <label for="menu_id">Menu:</label>
        <select name="menu_id" id="menu_id" required>
            <?php foreach ($menus as $menu): ?>
                <option value="<?= $menu['menu_id'] ?>">
                    <?= $menu['menu_name'] ?> - <?= number_format($menu['price'], 0, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="quantity">Jumlah:</label>
        <input type="number" name="quantity" id="quantity" min="1" required><br><br>

        <label for="payment_type_id">Jenis Pembayaran:</label>
        <select name="payment_type_id" id="payment_type_id">
            <?php foreach ($paymentTypes as $type): ?>
                <option value="<?= $type['payment_type_id'] ?>"><?= $type['payment_type_name'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Simpan</button>
    </form>


    <h2>Data Penjualan</h2>
    <p>Total Jumlah Item Terjual: <span id="total-quantity">0</span></p>
    <p>Total Penjualan: Rp <span id="total-sales">0</span></p>
    <hr>
    <label for="filter-date">Filter Tanggal:</label>
    <input type="date" id="filter-date" value="<?= date('Y-m-d') ?>">
    <button id="filter-btn">Tampilkan</button>

    <hr>
    <!-- Tabel Penjualan -->
  
    <table border="1" id="sales-table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat dengan AJAX -->
        </tbody>
    </table>

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

                // Tampilkan data di elemen HTML
                $('#total-quantity').text(totalQuantity);
                $('#total-sales').text(totalSales.toLocaleString());
            });


            $.post('<?= base_url('/sales/getSales') ?>', { date: date }, function (data) {
                let rows = '';
                data.forEach(sale => {
                    rows += `
                        <tr>
                            <td>${sale.menu_name}</td>
                            <td>${sale.quantity}</td>
                            <td>${sale.payment_type_name}</td>
                            <td>${sale.total_price.toLocaleString()}</td>
                            <td>${sale.sale_date}</td>
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


</body>
</html>
