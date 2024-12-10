<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo "Anda harus login terlebih dahulu.";
    header('Location: login.php');
    exit;
}

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $new_name = $_POST['name'];
    $new_kelas = $_POST['kelas'];
    
    // Proses upload gambar baru jika ada
    $new_image = $_SESSION['image']; // default ke gambar lama
    if (!empty($_FILES['image']['name'])) {
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

        // Jika semua cek lolos, upload file baru
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $new_image = $target_file;
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file gambar.";
            }
        }
    }

    // Baca file users.txt
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    $updated_users = [];
    $password_updated = false;

    foreach ($users as $user) {
        list($stored_username, $stored_password_hash, $stored_name, $stored_kelas, $stored_image) = explode(':', $user);

        // Cek apakah username cocok dan password lama sesuai
        if ($username == $stored_username && password_verify($old_password, $stored_password_hash)) {
            // Hash password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update nama, kelas, gambar, dan password
            $updated_users[] = $username . ':' . $hashed_new_password . ':' . $new_name . ':' . $new_kelas . ':' . $new_image;
            $password_updated = true;

            // Update sesi dengan data baru
            $_SESSION['name'] = $new_name;
            $_SESSION['kelas'] = $new_kelas;
            $_SESSION['image'] = $new_image;
        } else {
            // Jika bukan pengguna yang sedang login, biarkan data tetap
            $updated_users[] = $user;
        }
    }

    // Simpan perubahan ke dalam file jika password berhasil diubah
    if ($password_updated) {
        file_put_contents('users.txt', implode(PHP_EOL, $updated_users) . PHP_EOL);
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Password lama salah.";
    }
}
?>