<?php
class Koneksi {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'ajax');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getBarang() {
        $query = "SELECT * FROM barang";
        return $this->conn->query($query);
    }

    public function getBarangById($kode_barang) {
        $query = "SELECT * FROM barang WHERE kode_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $kode_barang);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addBarang($kode_barang, $nama_barang, $stok, $harga, $gambar) {
        $query = "INSERT INTO barang (kode_barang, nama_barang, stok, harga, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssids", $kode_barang, $nama_barang, $stok, $harga, $gambar);
        return $stmt->execute();
    }

    public function updateBarang($kode_barang, $nama_barang, $stok, $harga, $gambar) {
        $query = "UPDATE barang SET nama_barang = ?, stok = ?, harga = ?, gambar = ? WHERE kode_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sidss", $nama_barang, $stok, $harga, $gambar, $kode_barang);
        return $stmt->execute();
    }

    public function deleteBarang($kode_barang) {
        $query = "DELETE FROM barang WHERE kode_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $kode_barang);
        return $stmt->execute();
    }

    public function close() {
        $this->conn->close();
    }
}
?>
