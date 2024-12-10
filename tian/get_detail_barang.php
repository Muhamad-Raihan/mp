<?php
include 'koneksi.php';

// Pagination variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rows_per_page = 4;
$offset = ($page - 1) * $rows_per_page;

// Handle search and kode_barang filtering
$search = isset($_GET['search']) ? $_GET['search'] : '';
$kode_barang = isset($_GET['kode_barang']) ? $_GET['kode_barang'] : '';

$where = "WHERE 1=1";  // Base query condition
if ($kode_barang != '') {
    $where .= " AND kode_barang = '$kode_barang'";
}
if ($search != '') {
    $where .= " AND nama_barang LIKE '%$search%'";
}

// Query to get barang data with pagination
$query = "SELECT * FROM barang $where LIMIT $offset, $rows_per_page";
$result = $conn->query($query);

// Fetch and display data
$detail = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gambar_path = 'images/' . $row['gambar'];
        $gambar = file_exists($gambar_path) ? "<img src='$gambar_path' alt='{$row['nama_barang']}' class='img-fluid' width='100'>" : "Gambar tidak ditemukan";
        $detail .= "
            <tr>
                <td>{$row['kode_barang']}</td>
                <td>{$row['nama_barang']}</td>
                <td>{$row['stock']}</td>
                <td>{$row['harga']}</td>
                <td>$gambar</td>
            </tr>
        ";
    }
} else {
    $detail = '<tr><td colspan="5">Data tidak ditemukan</td></tr>';
}

// Query to get total number of rows for pagination
$total_rows_query = "SELECT COUNT(*) AS total FROM barang $where";
$total_rows_result = $conn->query($total_rows_query);
$total_rows = $total_rows_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $rows_per_page);

// Generate pagination controls
$pagination = '<nav><ul class="pagination justify-content-center">';
for ($i = 1; $i <= $total_pages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $pagination .= "<li class='page-item $active'><a class='page-link' href='#' data-page='$i'>$i</a></li>";
}
$pagination .= '</ul></nav>';

// Return JSON response
$data = array('detail' => $detail, 'pagination' => $pagination);
echo json_encode($data);
?>
