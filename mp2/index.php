<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Baca file users.txt
    if (file_exists('users.txt')) {
        $users = file('users.txt', FILE_IGNORE_NEW_LINES);

        foreach ($users as $user) {
            list($stored_username, $stored_password_hash) = explode(':', $user);

            // Cek apakah username cocok dan password terverifikasi
            if ($username == $stored_username && password_verify($password, $stored_password_hash)) {
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
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
    }

    // Jika login gagal
    echo "Username atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cantik.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Page</title>
</head>
<body background="../../picture/ywhite.png">
    <div class="form-box">
        <form action="login.php" method="post">
        <h1>LOGiN FORM</h1>
            <div class="login-container">
                <div class="input-section">
                    <div class="input-box">
                        <i class="fa-regular fa-envelope"></i>
                        <label for="username">Username</label>
                        <input type="text" placeholder="Input your username" name="username" required><br><br>
                        <i class="fa fa-key"></i>
                        <label for="password">Password</label>
                        <input type="password" placeholder="Put down your password" id="myInput" name="password" required>
                        <span class="eye" onclick="myFunction()">
                            <i id="hide1" class="fa fa-eye"></i>
                            <i id="hide2" class="fa fa-eye-slash"></i>
                        </span>
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                </div>
            </div>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    </div>
<script src="fungsi.js"></script>
</body>
</html>