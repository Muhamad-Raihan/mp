<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Baca file users.txt
    if (file_exists('users.txt')) {
        $users = file('users.txt', FILE_IGNORE_NEW_LINES);

        foreach ($users as $user) {
            list($stored_username, $stored_password_hash, $name, $kelas, $image) = explode(':', $user);

            // Cek apakah username cocok dan password terverifikasi
            if ($username == $stored_username && password_verify($password, $stored_password_hash)) {
                // Simpan data ke sesi
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $name;
                $_SESSION['kelas'] = $kelas;
                $_SESSION['image'] = $image;
                header('Location: dashboard.php');
                exit;
            }
        }
    }

    // Validasi username dan password
    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['image'] = $users[$username]['image'];
        $_SESSION['nama'] = $users[$username]['nama'];
        $_SESSION['kelas'] = $users[$username]['kelas'];
        header("Location: dashboard.php");
        exit;
    }
    
    if (!isset($_SESSION['attempt'])) {
        $_SESSION['attempt'] = 0;
    }
    
    $_SESSION['attempt'] += 1;    
        if ($_SESSION['attempt'] >= 3) {
            echo "<script>
                alert('Anda telah gagal login 3 kali. Halaman akan tertutup.');
                window.close();
            </script>";
            exit;
        } else {
            header("Location: index.php?error=Login tidak valid.");
        }

    // Jika login gagal
    echo "Username atau password salah.";
}

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

            echo "Registrasi berhasil. Silakan login!";
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file gambar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login MyWebsite</title>
    <link rel="stylesheet" href="style1.css">
    <link href="../../fontawesome/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form method="POST" action="register.php" enctype="multipart/form-data">
                <h1>Pendaftaran</h1><br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <div class="password-container">
                <input type="password" id="myInput" name="password" required>
                <span class="eye" onclick="myFunction()">
                            <i id="hide1" class="fa fa-eye"></i>
                            <i id="hide2" class="fa fa-eye-slash"></i>
                </span>  
                </div>

                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>

                <label for="kelas">Kelas:</label>
                <input type="text" id="kelas" name="kelas" required>

                <label for="image">Upload Foto Profil:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Daftar</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form method="POST" action="index.php">
                <h1>Login</h1><br>
                <label for="username"><i class="fa-regular fa-envelope"></i> Username:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password"><i class="fa fa-key"></i> Password:</label>
                <div class="password-container">
                <input type="password" id="myInput" name="password" required>
                <span class="eye" onclick="myFunction()">
                            <i id="hide1" class="fa fa-eye"></i>
                            <i id="hide2" class="fa fa-eye-slash"></i>
                </span>  
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome to MyWebsite</h1>
                    <p>Silahkan mendaftar untuk mengikuti info terbaru dari MyWebsite</p><br>
                    <p>Sudah punya akun? Ayo langsung login! >//< </p>
                    <button class="ghost" id="signIn">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Welcome to MyWebsite</h1>
                    <p>Hey, how are you today? i hope we'll in the good healty today</p><br>
                    <p>Belum punya akun? silahkan daftar</p>
                    <button class="ghost" id="signUp">Halaman Pendaftaran</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="login.js"></script>
</html>