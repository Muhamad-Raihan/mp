<?php
session_start();

// Data user yang valid
$users = [
    'kenma' => [
        'password' => password_hash('kenma123', PASSWORD_DEFAULT),
        'image' => '../../picture/kenma.jpg',
        'nama' => 'Kenma',
        'kelas' => 'XII RPL 1'
    ],
    'lumine' => [
        'password' => password_hash('lumine123', PASSWORD_DEFAULT),
        'image' => '../../picture/lumine.jpg',
        'nama' => 'Lumine',
        'kelas' => 'XII RPL 1'
    ],
    'ao' => [
        'password' => password_hash('blue123', PASSWORD_DEFAULT),
        'image' => '../../picture/blue.jpg',
        'nama' => 'Ao Yozora',
        'kelas' => 'XII RPl 1'
    ],
];

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
        header("Location: index.php?error=Login tidak valid.");
    }
}
?>
