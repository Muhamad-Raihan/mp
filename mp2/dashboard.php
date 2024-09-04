<?php
session_start();

// Restrict access if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
}

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p><a href="dashboard.php?action=logout">Logout</a></p>
</body>
</html>
