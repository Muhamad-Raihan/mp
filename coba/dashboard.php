<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo "Anda harus login terlebih dahulu.";
    header('Location: index.php');
    exit;
}

// Ambil data dari sesi
$name = $_SESSION['name'];
$kelas = $_SESSION['kelas'];
$image = $_SESSION['image'];
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Halaman Utama</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class="navigation">
			<ul>
				<li>
					<a href="#">
						<span class="icon"><ion-icon name="game-controller"></ion-icon></span>
						<span class="title" style="font-size: 1.5em;font-weight: 500;">MyWebsite</span>
					</a>
				</li>
				<li>
					<a href="dashboard.php?modul=dashboard">
						<span class="icon"><ion-icon name="home-outline"></ion-icon></span>
						<span class="title">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="dashboard.php?modul=profil">
						<span class="icon"><ion-icon name="people-outline"></ion-icon></span>
						<span class="title">Profil</span>
					</a>
				</li>
				<li>
					<a href="dashboard.php?modul=privasi">
						<span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
						<span class="title">Privasi</span>
					</a>
				</li>
				<li>
					<a href="logout.php">
						<span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
						<span class="title">Keluar Akun</span>
					</a>
				</li>
			</ul>
		</div>

        <?php 
        if(isset($_GET['modul'])){
            $pages = $_GET['modul'];
        } else {
            $pages = "dashboard";
        }

        if(!isset($_GET['modul']) || $pages == "dashboard"){
        ?>
		<!-- main -->
		<div class="main">
			<div class="topbar">
				<div class="toggle">
					<ion-icon name="menu-outline"></ion-icon>
				</div>
				<!-- search -->
				<div class="search">
					<label>
						<input type="text" placeholder="Search here">
						<ion-icon name="search-outline"></ion-icon>
					</label>
				</div>
				<!-- Foto User -->
				<div class="user">
                    <a href="dashboard.php?modul=profil">
					<img src="<?php echo $image; ?>">
                    </a>
				</div>
			</div>
		</div>

        <?php
        } if ($pages == "profil"){
        ?>
        <div>
            <div class="main">
                <div class="topbar">
	    			<div class="toggle">
		    			<ion-icon name="menu-outline"></ion-icon>
			    	</div>
			    </div>
                <h1 style="margin-left: 20px;">Selamat datang, <?php echo $name; ?></h1>
                <div class="login-container">
                    <div class="image-section">
                        <img src="<?php echo $image; ?>" alt="Profile Picture" width="150" class="login-image">
                    </div>
                    <div class="input-section">
                        <div class="data-box">
                            <label for="nama">Nama: <?php echo $name; ?></label><br>
                            <label for="kelas">Kelas: <?php echo $kelas; ?></label>
                        </div>
                    </div>
                </div>
            </div>
		</div>
       

        <?php
        } if ($pages == "privasi"){
        ?>
            <div class="main">
                <div class="topbar">
	    			<div class="toggle">
		    			<ion-icon name="menu-outline"></ion-icon>
			    	</div>
			    </div>
            <div class="ubah">
            <h2>Ubah Data Pengguna</h2>
            <form method="POST" action="ubah-data.php" enctype="multipart/form-data">
                <label for="old_password">Password Lama :</label>
                <input type="password" id="old_password" name="old_password" required><br>

                <label for="new_password">Password Baru :</label>
                <input type="password" id="new_password" name="new_password" required><br>

                <label for="name">Nama Baru :</label>
                <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required><br>

                <label for="kelas">Kelas Baru   :</label>
                <input type="text" id="kelas" name="kelas" value="<?php echo $_SESSION['kelas']; ?>" required><br>

                <label for="image">Ganti Foto Profil    :</label>
                <input type="file" id="image" name="image" accept="image/*"><br>

                <button type="submit">Update Data</button>
            </form>
			</div>
        </div>

    <?php
    }
    ?>
	</div>

	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
	<script src="my_chart.js"></script>
	<script>
		// MenuToggle
		let toggle = document.querySelector('.toggle');
		let navigation = document.querySelector('.navigation');
		let main = document.querySelector('.main');

		toggle.onclick = function(){
			navigation.classList.toggle('active');
			main.classList.toggle('active');
		}

		// add hovered class in selected list item
		let list = document.querySelectorAll('.navigation li');
		function activeLink(){
			list.forEach((item) =>
			item.classList.remove('hovered'));
			this.classList.add('hovered');
		}
		list.forEach((item) => 
		item.addEventListener('mouseover',activeLink));
	</script>
</body>
</html>
