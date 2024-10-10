<?php
include 'koneksi.php';

$koneksi = new Koneksi();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = $_POST['kode_barang'];
    
    // Ambil data barang berdasarkan kode_barang
    $barang = $koneksi->getBarangById($kode_barang);
    
    if ($barang) {
        echo json_encode($barang); // Mengirim data sebagai JSON
    } else {
        echo json_encode([]); // Kembalikan array kosong jika tidak ditemukan
    }
}

$koneksi->close();
