<?php
include 'koneksi.php';

$koneksi = new Koneksi();

if (isset($_GET['kode_barang'])) {
    $kode_barang = $_GET['kode_barang'];
    $barang = $koneksi->getBarangById($kode_barang);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Upload gambar
    $target_dir = "foto/";
    $gambar = $target_dir . basename($_FILES["gambar"]["name"]);
    if (!empty($_FILES["gambar"]["name"])) {
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $gambar);
    } else {
        $gambar = $barang['gambar']; // Jika tidak ada gambar baru, gunakan gambar lama
    }

    // Update barang
    if ($koneksi->updateBarang($kode_barang, $nama_barang, $stok, $harga, $gambar)) {
        echo "Data berhasil diupdate.";
        header("Location: manajemen_barang.php");
        exit();
    } else {
        echo "Gagal mengupdate data.";
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h1 class="text-center mb-4">Update Data Barang</h1>

    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="kode_barang" class="form-label">Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" value="<?php echo $barang['kode_barang']; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" required>
        </div>
        <div class="col-md-6">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" name="stok" value="<?php echo $barang['stok']; ?>" required>
        </div>
        <div class="col-md-6">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" step="0.01" class="form-control" name="harga" value="<?php echo $barang['harga']; ?>" required>
        </div>
        <div class="col-md-6">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" class="form-control" name="gambar">
            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>
        <div class="col-md-12 text-center mt-3">
            <input type="submit" value="Update Data" class="btn btn-primary">
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="manajemen_barang.php" class="btn btn-secondary">Kembali ke Daftar Barang</a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
