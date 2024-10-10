<?php
include 'koneksi.php';

$koneksi = new Koneksi();
$barangList = $koneksi->getBarang();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Barang</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (already included in Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">

<h1 class="mb-4">Data Barang</h1>

<!-- Dropdown to select kode_barang -->
<div class="mb-4">
    <select id="barangSelect" class="form-select">
        <option value="">-- Pilih Barang --</option>
        <?php foreach ($barangList as $barang): ?>
            <option value="<?= $barang['kode_barang'] ?>"><?= $barang['kode_barang'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Table to display barang details, including an image -->
<table class="table table-bordered table-hover mt-4" id="barangTable" style="display: none;">
    <thead class="table-dark">
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="kode_barang"></td>
            <td id="nama_barang"></td>
            <td id="harga"></td>
            <td id="stok"></td>
            <td>
                <img id="gambar_barang" src="" alt="Gambar Barang" class="img-fluid img-thumbnail" style="max-width: 100px;">
            </td> <!-- Image display -->
        </tr>
    </tbody>
</table>

<!-- Link to manajemen barang page -->
<h4>
    <a href="manajemen_barang.php" class="btn btn-primary" style="text-decoration: none;">Klik disini untuk melihat manajemen data Barang</a>
</h4>

<!-- Bootstrap JS (for optional Bootstrap features like dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // When the dropdown selection changes
    $('#barangSelect').change(function() {
        var kode_barang = $(this).val();
        
        if (kode_barang) {
            // Send an AJAX request to get_barang.php
            $.ajax({
                url: 'get_barang.php',
                method: 'POST',
                data: { kode_barang: kode_barang },
                dataType: 'json',
                success: function(response) {
                    if (response && Object.keys(response).length > 0) {
                        // Populate the table with the fetched data
                        $('#kode_barang').text(response.kode_barang);
                        $('#nama_barang').text(response.nama_barang);

                        // Format the harga with commas, IDR, and no decimal places
                        var formattedHarga = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(response.harga);
                        
                        $('#harga').text(formattedHarga);

                        $('#stok').text(response.stok);
                        
                        // Display the image
                        if (response.gambar) {
                            $('#gambar_barang').attr('src', response.gambar); // Set image source
                        } else {
                            $('#gambar_barang').attr('src', ''); // Clear image if none
                        }
                        
                        // Show the table
                        $('#barangTable').show();
                    } else {
                        alert('Data barang tidak ditemukan!');
                        $('#barangTable').hide();
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        } else {
            // Hide the table if no item is selected
            $('#barangTable').hide();
        }
    });
});

</script>

</body>
</html>
