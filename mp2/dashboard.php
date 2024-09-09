<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
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
            <h1>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?></h1>
            <div class="login-container">
                <div class="image-section">
                    <img src="<?= htmlspecialchars($_SESSION['image']); ?>" alt="Image" class="login-image">
                </div>
                <div class="input-section">
                    <div class="data-box">
                        <label for="nama">Nama: <?= htmlspecialchars($_SESSION['nama']); ?></label><br>
                        <label for="kelas">Kelas: <?= htmlspecialchars($_SESSION['kelas']); ?></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    <footer class="footer">
    <p>Created by Muhammad Raihan Syahfitrah - 2024</p>
</footer>
</body>
</html>
