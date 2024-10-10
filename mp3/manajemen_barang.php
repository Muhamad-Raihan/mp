<?php
include 'koneksi.php';

$koneksi = new Koneksi();
$barangList = $koneksi->getBarang();

if (isset($_GET['delete'])) {
    $kode_barang = $_GET['delete'];
    $koneksi->deleteBarang($kode_barang);
    header("Location: manajemen_barang.php"); // Refresh halaman setelah delete
    exit();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">

<h1 class="my-4 text-center">Data Barang</h1>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($barang = $barangList->fetch_assoc()): ?>
            <tr>
                <td><?= $barang['kode_barang'] ?></td>
                <td><?= $barang['nama_barang'] ?></td>
                <td><?= $barang['stok'] ?></td>
                <td><?= number_format($barang['harga'], 2) ?></td>
                <td><img src="<?= $barang['gambar'] ?>" alt="Gambar Barang" width="100"></td>
                <td>
                    <a href="update_data.php?kode_barang=<?= $barang['kode_barang'] ?>" class="btn btn-warning btn-sm">Edit</a> 
                    <a href="manajemen_barang.php?delete=<?= $barang['kode_barang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="text-center mt-4">
    <a href="tambah_data.php" class="btn btn-primary">Tambah Data Barang</a>
    <a href="index.php" class="btn btn-secondary">Cari Data Barang</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
