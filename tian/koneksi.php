<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tian_ajax"; // Pastikan nama database Anda sesuai

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>