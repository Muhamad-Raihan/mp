<?php
session_start();

// Define valid credentials (hardcoded for this example)
$valid_username = "user";
$valid_password_hash = password_hash("password", PASSWORD_DEFAULT);

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: dashboard.php');
    exit;
}

// Restrict access to the dashboard page if not logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate login
    if ($username === $valid_username && password_verify($password, $valid_password_hash)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
    } else {
        $_SESSION['error'] = "Invalid login. Please try again.";
        header('Location: login.html');
    }
    exit;
}
?>
