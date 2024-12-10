<?php
$filename = "data.txt"; // Nama file data yang digunakan

// Fungsi untuk membuat ID berformat USR01, USR02, dst.
function generateId($filename) {
    $file = fopen($filename, "r");
    $maxId = 0;
    while (($line = fgets($file)) !== false) {
        $data = explode(" | ", trim($line));
        $idNum = (int) str_replace("USR", "", $data[0]);
        if ($idNum > $maxId) {
            $maxId = $idNum;
        }
    }
    fclose($file);
    return "USR" . str_pad($maxId + 1, 2, "0", STR_PAD_LEFT);
}

// Fungsi untuk menambahkan atau memperbarui data
function simpanData($filename, $id, $nama, $alamat, $telepon) {
    $isNew = empty($id);
    if ($isNew) {
        $id = generateId($filename);
    }

    $file = fopen($filename, "r");
    $tempFile = fopen("temp.txt", "w");
    $updated = false;

    while (($line = fgets($file)) !== false) {
        $data = explode(" | ", trim($line));
        if (!$isNew && $data[0] === $id) {
            $line = "$id | $nama | $alamat | $telepon\n";
            $updated = true;
        }
        fwrite($tempFile, $line);
    }

    if ($isNew || !$updated) {
        fwrite($tempFile, "$id | $nama | $alamat | $telepon\n");
    }

    fclose($file);
    fclose($tempFile);
    rename("temp.txt", $filename);
}

// Fungsi untuk menghapus data
function hapusData($filename, $id) {
    $file = fopen($filename, "r");
    $tempFile = fopen("temp.txt", "w");

    while (($line = fgets($file)) !== false) {
        $data = explode(" | ", trim($line));
        if ($data[0] !== $id) {
            fwrite($tempFile, $line);
        }
    }

    fclose($file);
    fclose($tempFile);
    rename("temp.txt", $filename);
}

// Proses data untuk menyimpan, mengedit, atau menghapus sebelum HTML ditampilkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['simpan'])) {
        $id = $_POST['id'] ?? '';
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        simpanData($filename, $id, $nama, $alamat, $telepon);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['delete_id'])) {
        hapusData($filename, $_POST['delete_id']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2f33;
            color: #ffffff;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: #23272a;
        }
        .btn-dark, .btn-secondary, .btn-danger {
            color: #ffffff;
        }
        .table .action-buttons {
            gap: 10px;
            justify-content: center;
        }
        table {
            background-color: #2c2f33;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h2>Manajemen Data</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo isset($_POST['edit_id']) ? $_POST['edit_id'] : ''; ?>">
                <div class="form-group">
                    <label>Nama:</label>
                    <input type="text" name="nama" class="form-control" required value="<?php echo isset($_POST['edit_nama']) ? $_POST['edit_nama'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Alamat:</label>
                    <input type="text" name="alamat" class="form-control" required value="<?php echo isset($_POST['edit_alamat']) ? $_POST['edit_alamat'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Nomor Telepon:</label>
                    <input type="text" name="telepon" class="form-control" required value="<?php echo isset($_POST['edit_telepon']) ? $_POST['edit_telepon'] : ''; ?>">
                </div>
                <button type="submit" name="simpan" class="btn btn-dark">Simpan Data</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="">
                <div class="form-group">
                    <label>Cari Berdasarkan:</label>
                    <select id="kategori" class="form-control">
                        <option value="ID">ID</option>
                        <option value="Nama">Nama</option>
                        <option value="Alamat">Alamat</option>
                        <option value="Telepon">Nomor Telepon</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="cariNama" class="form-control" onkeyup="filterData()" placeholder="Cari...">
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header text-center">
            <h4>Data Tersimpan</h4>
        </div>
        <div class="card-body">
            <table class="table table-dark table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!file_exists($filename)) {
                        $file = fopen($filename, "w");
                        fclose($file);
                    }

                    $file = fopen($filename, "r");
                    while (($line = fgets($file)) !== false) {
                        $data = explode(" | ", trim($line));
                        echo "<tr>";
                        echo "<td>{$data[0]}</td>";
                        echo "<td>{$data[1]}</td>";
                        echo "<td>{$data[2]}</td>";
                        echo "<td>{$data[3]}</td>";
                        echo "<td class='action-buttons'>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='edit_id' value='{$data[0]}'>
                                    <input type='hidden' name='edit_nama' value='{$data[1]}'>
                                    <input type='hidden' name='edit_alamat' value='{$data[2]}'>
                                    <input type='hidden' name='edit_telepon' value='{$data[3]}'>
                                    <button type='submit' class='btn btn-secondary btn-sm'>Edit</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='delete_id' value='{$data[0]}'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    fclose($file);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterData() {
    const input = document.getElementById('cariNama').value.toLowerCase();
    const category = document.getElementById('kategori').value;
    const table = document.getElementById('dataTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let cellValue = "";

        if (category === "ID") cellValue = cells[0].innerText;
        else if (category === "Nama") cellValue = cells[1].innerText;
        else if (category === "Alamat") cellValue = cells[2].innerText;
        else if (category === "Telepon") cellValue = cells[3].innerText;

        rows[i].style.display = cellValue.toLowerCase().includes(input) ? "" : "none";
    }
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
