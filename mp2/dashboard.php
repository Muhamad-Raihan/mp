<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo "Anda harus login terlebih dahulu.";
    header('Location: login.php');
    exit;
}

// Ambil data dari sesi
$name = $_SESSION['name'];
$kelas = $_SESSION['kelas'];
$image = $_SESSION['image'];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="cantik.css">
</head>
<body background="../../picture/ywhite.png">
    <div class="form-box">
        <form action="logout.php" method="post">
            <h1>Selamat datang, <?php echo $name; ?></h1>
            <div class="login-container">
                <div class="image-section">
                    <img src="<?php echo $image; ?>" alt="Profile Picture" width="150" class="login-image">
                </div>
                <div class="input-section">
                    <div class="data-box">
                        <label for="nama">Nama: <?php echo $name; ?></label><br>
                        <label for="kelas">Kelas: <?php echo $kelas; ?></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="logout-btn">Logout</button>
        </form>
        <p><a href="ubah_pw.php">Ubah Password</a></p>
    </div>
    <footer class="footer">
    <p>Created by Muhammad Raihan Syahfitrah - 2024</p>
</footer>
</body>
</html>
