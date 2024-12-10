<?php
// Mulai sesi
session_start();

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari input form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $kelas = $_POST['kelas'];

    // Proses upload gambar
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file yang diupload adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File yang diupload bukan gambar.";
        $uploadOk = 0;
    }

    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Cek ukuran file (misalnya maksimal 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Batasi format file gambar
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Cek apakah uploadOk masih 1, jika ya, upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Hash password sebelum menyimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Cek apakah file 'users.txt' sudah ada
            if (!file_exists('users.txt')) {
                // Buat file baru
                file_put_contents('users.txt', '');
            }

            // Simpan username, password, nama, kelas, dan path gambar ke dalam file
            $user_data = $username . ':' . $hashed_password . ':' . $name . ':' . $kelas . ':' . $target_file . PHP_EOL;
            file_put_contents('users.txt', $user_data, FILE_APPEND);

            header("Location: index.php");
            exit;
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file gambar.";
        }
    }
}
?>