<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax Tabel with Search, Pagination, and Purchase</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Pilih Barang</h2>
    
    <!-- Search Box -->
    <div class="form-group">
        <label for="search_barang">Cari Nama Barang:</label>
        <input type="text" id="search_barang" class="form-control" placeholder="Masukkan nama barang...">
    </div>

    <!-- Combo Box to Select Barang by Code -->
    <div class="form-group">
        <label for="kode_barang">Kode Barang:</label>
        <select id="kode_barang" class="form-control">
            <option value="">Pilih Barang</option>
            <?php 
            include 'koneksi.php';
            $query = "SELECT kode_barang FROM barang";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='".$row['kode_barang']."'>".$row['kode_barang']."</option>";
            }
            ?>
        </select>
    </div>

    <!-- Tabel untuk menampilkan detail barang -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stock</th>
                <th>Harga</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody id="barang_detail">
            <!-- Data barang akan tampil di sini -->
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div id="pagination_controls"></div>

    <!-- Purchase Card -->
    <div id="purchase_form" class="card mt-4" style="border-color: #3498db;">
        <div class="card-header text-white" style="background-color: #3498db;">
            <h4>Pembelian</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="jumlah_barang">Jumlah:</label>
                <input type="number" id="jumlah_barang" class="form-control" placeholder="Jumlah" min="1">
            </div>
            <div class="form-group">
                <label for="total_harga">Total:</label>
                <input type="text" id="total_harga" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="uang_bayar">Uang Bayar:</label>
                <input type="number" id="uang_bayar" class="form-control" placeholder="Masukkan uang bayar" min="0">
            </div>
            <div class="form-group">
                <label for="uang_kembalian">Uang Kembalian:</label>
                <input type="text" id="uang_kembalian" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- Receipt Section -->
    <div id="receipt" class="mt-4" style="display: none;">
        <h4>Struk Pembayaran</h4>
        <div class="receipt-content p-3 border" style="border: 1px solid #3498db; border-radius: 5px; background-color: white;">
            <p><strong>Nama Barang:</strong> <span id="receipt_nama_barang"></span></p>
            <p><strong>Jumlah:</strong> <span id="receipt_jumlah_barang"></span></p>
            <p><strong>Total Harga:</strong> Rp <span id="receipt_total_harga"></span></p>
            <p><strong>Uang Bayar:</strong> Rp <span id="receipt_uang_bayar"></span></p>
            <p><strong>Uang Kembalian:</strong> Rp <span id="receipt_uang_kembalian"></span></p>
            <p><strong>Terbilang <span id="receipt_terbilang"></span> rupiah</strong></p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    let currentPage = 1;
    let selectedKodeBarang = '';
    let selectedNamaBarang = '';
    let selectedHarga = 0;
    let selectedStock = 0;

    // Function to load data with search, pagination, or kode_barang selection
    function loadData(search = '', kode = '', page = 1) {
        $.ajax({
            url: 'get_detail_barang.php',
            type: 'GET',
            data: { search: search, kode_barang: kode, page: page },
            success: function(data) {
                let result = JSON.parse(data);
                $('#barang_detail').html(result.detail);
                $('#pagination_controls').html(result.pagination);
            }
        });
    }

    // Event listeners
    $('#kode_barang').on('change', function() {
        let kode = $(this).val();
        selectedKodeBarang = kode;
        loadData('', kode, currentPage);
        resetForm(); // Clear form inputs
    });

    $('#search_barang').on('keyup', function() {
        let search = $(this).val();
        loadData(search, '', currentPage);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        currentPage = $(this).attr('data-page');
        loadData($('#search_barang').val(), $('#kode_barang').val(), currentPage);
    });

    // Reset form inputs
    function resetForm() {
        $('#jumlah_barang').val('');
        $('#total_harga').val('');
        $('#uang_bayar').val('');
        $('#uang_kembalian').val('');
        $('#receipt').hide();
    }

    // Calculate total price when the user changes quantity
    $('#jumlah_barang').on('input', function() {
        let jumlah = $(this).val();
        if (jumlah && selectedHarga > 0) {
            let total = jumlah * selectedHarga;
            $('#total_harga').val(total); // Display total in textbox

            // Update receipt details
            $('#receipt_kode_barang').text(selectedKodeBarang);
            $('#receipt_nama_barang').text(selectedNamaBarang);
            $('#receipt_jumlah_barang').text(jumlah);
            $('#receipt_total_harga').text(total);
        } else {
            $('#total_harga').val(''); // Clear total if invalid input
        }
        calculateChange(); // Trigger change calculation if the user also entered payment
    });

    // Calculate change automatically when the user enters the payment amount
    $('#uang_bayar').on('input', function() {
        calculateChange(); // Trigger change calculation
    });

    // Function to calculate change (kembalian)
    function calculateChange() {
        let total = parseFloat($('#total_harga').val());
        let bayar = parseFloat($('#uang_bayar').val());
        if (!isNaN(total) && !isNaN(bayar) && bayar >= total) {
            let kembalian = bayar - total;
            $('#uang_kembalian').val(kembalian); // Display change
            $('#receipt_uang_bayar').text(bayar);
            $('#receipt_uang_kembalian').text(kembalian);
            $('#receipt_terbilang').text(terbilang(kembalian)); // Display terbilang
            $('#receipt').show(); // Show receipt
        } else {
            $('#uang_kembalian').val(''); // Clear change if invalid input
        }
    }

    // Update selected item's details after clicking on a row
    $(document).on('click', 'tr', function() {
        selectedNamaBarang = $(this).find('td:nth-child(2)').text(); // Get item name
        selectedHarga = parseFloat($(this).find('td:nth-child(4)').text()); // Get item price
        selectedStock = parseInt($(this).find('td:nth-child(3)').text()); // Get item stock

        // Set the maximum quantity to stock
        $('#jumlah_barang').attr('max', selectedStock);
    });

    // Function to convert number to words (terbilang)
    function terbilang(angka) {
        var bilangan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        var hasil = "";
        
        if (angka < 12) {
            hasil = bilangan[angka];
        } else if (angka < 20) {
            hasil = bilangan[angka - 10] + " belas";
        } else if (angka < 100) {
            hasil = bilangan[Math.floor(angka / 10)] + " puluh " + bilangan[angka % 10];
        } else if (angka < 200) {
            hasil = "seratus " + terbilang(angka - 100);
        } else if (angka < 1000) {
            hasil = bilangan[Math.floor(angka / 100)] + " ratus " + terbilang(angka % 100);
        } else if (angka < 2000) {
            hasil = "seribu " + terbilang(angka - 1000);
        } else if (angka < 1000000) {
            hasil = terbilang(Math.floor(angka / 1000)) + " ribu " + terbilang(angka % 1000);
        } else if (angka < 1000000000) {
            hasil = terbilang(Math.floor(angka / 1000000)) + " juta " + terbilang(angka % 1000000);
        } else {
            hasil = "Angka terlalu besar!";
        }

        return hasil;
    }
});
</script>
</body>
</html>
