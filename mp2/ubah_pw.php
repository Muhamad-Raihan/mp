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

    // Baca file users.txt
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    $updated_users = [];
    $password_updated = false;

    foreach ($users as $user) {
        list($stored_username, $stored_password_hash) = explode(':', $user);

        // Cek apakah username cocok dan password lama sesuai
        if ($username == $stored_username && password_verify($old_password, $stored_password_hash)) {
            // Hash password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password
            $updated_users[] = $username . ':' . $hashed_new_password;
            $password_updated = true;
        } else {
            $updated_users[] = $user; // Tetap masukkan pengguna lain tanpa perubahan
        }
    }

    // Simpan perubahan ke dalam file jika password berhasil diubah
    if ($password_updated) {
        file_put_contents('users.txt', implode(PHP_EOL, $updated_users) . PHP_EOL);
        echo "Password berhasil diubah!";
    } else {
        echo "Password lama salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h2>Ubah Password</h2>
    <form method="POST" action="ubah_pw.php">
        <label for="old_password">Password Lama:</label>
        <input type="password" id="old_password" name="old_password" required><br>
        <label for="new_password">Password Baru:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <button type="submit">Ubah Password</button>
    </form>
</body>
</html>
