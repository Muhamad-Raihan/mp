<?php
include 'koneksi.php';

$koneksi = new Koneksi();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Upload gambar
    $target_dir = "foto/";
    $gambar = $target_dir . basename($_FILES["gambar"]["name"]);
    move_uploaded_file($_FILES["gambar"]["tmp_name"], $gambar);

    // Tambah barang
    if ($koneksi->addBarang($kode_barang, $nama_barang, $stok, $harga, $gambar)) {
        echo "Data berhasil ditambahkan.";
        header("Location: manajemen_barang.php");
        exit();
    } else {
        echo "Gagal menambahkan data.";
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">

<h1 class="my-4 text-center">Tambah Data Barang</h1>

<form method="post" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label for="kode_barang" class="form-label">Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" id="kode_barang" required>
    </div>
    <div class="col-md-6">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
    </div>
    <div class="col-md-6">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" class="form-control" name="stok" id="stok" required>
    </div>
    <div class="col-md-6">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" step="0.01" class="form-control" name="harga" id="harga" required>
    </div>
    <div class="col-md-6">
        <label for="gambar" class="form-label">Gambar Barang</label>
        <input type="file" class="form-control" name="gambar" id="gambar" required>
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Tambah Data</button>
        <a href="manajemen_barang.php" class="btn btn-secondary">Kembali ke Daftar Barang</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
