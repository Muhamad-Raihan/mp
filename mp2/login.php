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
            header("Location: login.php?error=Login tidak valid.");
        }

    // Jika login gagal
    echo "Username atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
