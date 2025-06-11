<<<<<<< HEAD
<?php
session_start();
include 'koneksi.php'; // sesuaikan path koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Query untuk mengambil data pesanan user
$query = "
SELECT 
    pembayaran.id_pembayaran,
    pembayaran.status,
    pembayaran.nama_lengkap AS nama_pelanggan,
    pembayaran.alamat,
    pembayaran.bukti_pembayaran,
    pembayaran.tanggal,
    produk.nama AS nama_produk,
    produk.harga,
    produk.gambar,
    pembayaran.total_akhir
FROM pembayaran
JOIN users ON pembayaran.id_user = users.id_user
JOIN produk ON pembayaran.id_produk = produk.id_produk
WHERE pembayaran.id_user = $id_user
ORDER BY pembayaran.id_pembayaran DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan Saya - Zari Petshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
   <style>
              *{
      font-weight: bold;
    }
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        
 header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4CAF50;
            padding: 10px 20px;
            color: white;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 100px;
            margin-right: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a, .dropbtn {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            top: 40px;
            z-index: 1;
        }

        .dropdown-content li a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .brand-container {
            overflow-x: auto;
            padding: 10px 0;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; /* IE & Edge */
            scrollbar-width: none; /* Firefox */
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .custom-navbar {
            background-color: #4CAF50 !important; /* warna hijau yang kamu mau */
          }

          .custom-navbar .nav-link,
          .custom-navbar .navbar-brand {
            color: white; /* biar tulisannya putih */
          }

          .custom-navbar .nav-link:hover,
          .custom-navbar .nav-link:focus {
            color: #c8e6c9; /* warna hover yang lebih muda */
          }

          .custom-navbar .dropdown-menu {
            background-color: #4CAF50; /* dropdown juga hijau */
          }

          .custom-navbar .dropdown-item {
            color: white;
          }

          .custom-navbar .dropdown-item:hover {
            background-color: #81c784;
            color: black;
          }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .pesanan-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffc107;
            color: #856404;
        }

        .status-dikemas {
            background-color: #17a2b8;
            color: white;
        }

        .status-dikirim {
            background-color: #fd7e14;
            color: white;
        }

        .status-diterima {
            background-color: #28a745;
            color: white;
        }

        .produk-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }

        .produk-gambar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .progress-bar-custom {
            background-color: #e9ecef;
            height: 8px;
            border-radius: 4px;
            margin: 15px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.3s ease;
        }

        .status-steps {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            font-size: 12px;
        }

        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .step-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .step-active .step-circle {
            background-color: #4CAF50;
        }

        .step-completed .step-circle {
            background-color: #28a745;
        }

        .no-pesanan {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }

        .total-harga {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
<header>
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logo_zari.png" alt="Zari Petshop" width="50" class="me-3">
      <strong>ZARI PETSHOP</strong>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php#tentang">Tentang</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown">
            Akun
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="Profil.php">Edit Profil</a></li>
            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<br>
<br>

    <div class="container">
        <h2 style="color: #4CAF50; margin-bottom: 30px;">📦 Status Pesanan Saya</h2>
        
        <?php if (mysqli_num_rows($result) == 0) { ?>
            <div class="no-pesanan">
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum melakukan pemesanan apapun.</p>
                <a href="semua_produk.php" class="btn btn-success">Mulai Belanja</a>
            </div>
        <?php } else { ?>
            <?php while($row = mysqli_fetch_assoc($result)) { 
                // Tentukan progress berdasarkan status
                $progress = 0;
                if ($row['status'] == 'pending') $progress = 25;
                elseif ($row['status'] == 'di kemas') $progress = 50;
                elseif ($row['status'] == 'di kirim') $progress = 75;
                elseif ($row['status'] == 'di terima') $progress = 100;
                
                // Ganti spasi dengan underscore untuk class CSS
                $status_class = str_replace(' ', '', $row['status']);
            ?>
                <div class="pesanan-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h5>Pesanan</h5>
                        <span class="status-badge status-<?= $status_class ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </div>
                    
                    <div class="produk-info">
                        <img src="<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" class="produk-gambar">
                        <div>
                            <h6><?= $row['nama_produk'] ?></h6>
                            <p style="margin: 0; color: #6c757d;">Harga: Rp. <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p style="margin: 0; font-size: 12px; color: #6c757d;">
                                Alamat: <?= $row['alamat'] ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                    </div>
                    
                    <div class="status-steps">
                        <div class="step <?= $progress >= 25 ? 'step-completed' : '' ?>">
                            <div class="step-circle"><?= $progress >= 25 ? '✓' : '1' ?></div>
                            <div>Pending</div>
                        </div>
                        <div class="step <?= $progress >= 50 ? 'step-completed' : ($progress >= 25 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 50 ? '✓' : '2' ?></div>
                            <div>Dikemas</div>
                        </div>
                        <div class="step <?= $progress >= 75 ? 'step-completed' : ($progress >= 50 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 75 ? '✓' : '3' ?></div>
                            <div>Dikirim</div>
                        </div>
                        <div class="step <?= $progress >= 100 ? 'step-completed' : ($progress >= 75 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 100 ? '✓' : '4' ?></div>
                            <div>Diterima</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                        <div class="total-harga">
                            Total: Rp. <?= number_format($row['total_akhir'], 0, ',', '.') ?>
                        </div>
                        
                        <div>
                            <?php if ($row['status'] == 'di kirim') { ?>
                                <button class="btn btn-success btn-sm" onclick="konfirmasiTerima(<?= $row['id_pembayaran'] ?>)">
                                    Konfirmasi Diterima
                                </button>
                            <?php } ?>
                            
                            <?php if ($row['bukti_pembayaran']) { ?>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalBukti<?= $row['id_pembayaran'] ?>">
                                    Lihat Bukti Bayar
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Bukti Pembayaran -->
                <?php if ($row['bukti_pembayaran']) { ?>
                <div class="modal fade" id="modalBukti<?= $row['id_pembayaran'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="Bukti_pembayaran/<?= $row['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" style="max-width:100%; height:auto;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
            <?php } ?>
        <?php } ?>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    
    <script>
        function konfirmasiTerima(idPembayaran) {
            if (confirm('Apakah Anda yakin sudah menerima pesanan ini?')) {
                $.ajax({
                    url: 'konfirmasi_terima.php',
                    method: 'POST',
                    data: {
                        id_pembayaran: idPembayaran
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Terima kasih! Pesanan telah dikonfirmasi diterima.');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengirim konfirmasi.');
                    }
                });
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

</body>
=======
<?php
session_start();
include 'koneksi.php'; // sesuaikan path koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Query untuk mengambil data pesanan user
$query = "
SELECT 
    pembayaran.id_pembayaran,
    pembayaran.status,
    pembayaran.nama_lengkap AS nama_pelanggan,
    pembayaran.alamat,
    pembayaran.bukti_pembayaran,
    pembayaran.tanggal,
    produk.nama AS nama_produk,
    produk.harga,
    produk.gambar,
    pembayaran.total_akhir
FROM pembayaran
JOIN users ON pembayaran.id_user = users.id_user
JOIN produk ON pembayaran.id_produk = produk.id_produk
WHERE pembayaran.id_user = $id_user
ORDER BY pembayaran.id_pembayaran DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan Saya - Zari Petshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
   <style>
              *{
      font-weight: bold;
    }
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        
 header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4CAF50;
            padding: 10px 20px;
            color: white;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 100px;
            margin-right: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a, .dropbtn {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            top: 40px;
            z-index: 1;
        }

        .dropdown-content li a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .brand-container {
            overflow-x: auto;
            padding: 10px 0;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; /* IE & Edge */
            scrollbar-width: none; /* Firefox */
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .custom-navbar {
            background-color: #4CAF50 !important; /* warna hijau yang kamu mau */
          }

          .custom-navbar .nav-link,
          .custom-navbar .navbar-brand {
            color: white; /* biar tulisannya putih */
          }

          .custom-navbar .nav-link:hover,
          .custom-navbar .nav-link:focus {
            color: #c8e6c9; /* warna hover yang lebih muda */
          }

          .custom-navbar .dropdown-menu {
            background-color: #4CAF50; /* dropdown juga hijau */
          }

          .custom-navbar .dropdown-item {
            color: white;
          }

          .custom-navbar .dropdown-item:hover {
            background-color: #81c784;
            color: black;
          }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .pesanan-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffc107;
            color: #856404;
        }

        .status-dikemas {
            background-color: #17a2b8;
            color: white;
        }

        .status-dikirim {
            background-color: #fd7e14;
            color: white;
        }

        .status-diterima {
            background-color: #28a745;
            color: white;
        }

        .produk-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }

        .produk-gambar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .progress-bar-custom {
            background-color: #e9ecef;
            height: 8px;
            border-radius: 4px;
            margin: 15px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.3s ease;
        }

        .status-steps {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            font-size: 12px;
        }

        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .step-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .step-active .step-circle {
            background-color: #4CAF50;
        }

        .step-completed .step-circle {
            background-color: #28a745;
        }

        .no-pesanan {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }

        .total-harga {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
<header>
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logo_zari.png" alt="Zari Petshop" width="50" class="me-3">
      <strong>ZARI PETSHOP</strong>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php#tentang">Tentang</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown">
            Akun
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="Profil.php">Edit Profil</a></li>
            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<br>
<br>

    <div class="container">
        <h2 style="color: #4CAF50; margin-bottom: 30px;">📦 Status Pesanan Saya</h2>
        
        <?php if (mysqli_num_rows($result) == 0) { ?>
            <div class="no-pesanan">
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum melakukan pemesanan apapun.</p>
                <a href="semua_produk.php" class="btn btn-success">Mulai Belanja</a>
            </div>
        <?php } else { ?>
            <?php while($row = mysqli_fetch_assoc($result)) { 
                // Tentukan progress berdasarkan status
                $progress = 0;
                if ($row['status'] == 'pending') $progress = 25;
                elseif ($row['status'] == 'di kemas') $progress = 50;
                elseif ($row['status'] == 'di kirim') $progress = 75;
                elseif ($row['status'] == 'di terima') $progress = 100;
                
                // Ganti spasi dengan underscore untuk class CSS
                $status_class = str_replace(' ', '', $row['status']);
            ?>
                <div class="pesanan-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h5>Pesanan</h5>
                        <span class="status-badge status-<?= $status_class ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </div>
                    
                    <div class="produk-info">
                        <img src="<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" class="produk-gambar">
                        <div>
                            <h6><?= $row['nama_produk'] ?></h6>
                            <p style="margin: 0; color: #6c757d;">Harga: Rp. <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p style="margin: 0; font-size: 12px; color: #6c757d;">
                                Alamat: <?= $row['alamat'] ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                    </div>
                    
                    <div class="status-steps">
                        <div class="step <?= $progress >= 25 ? 'step-completed' : '' ?>">
                            <div class="step-circle"><?= $progress >= 25 ? '✓' : '1' ?></div>
                            <div>Pending</div>
                        </div>
                        <div class="step <?= $progress >= 50 ? 'step-completed' : ($progress >= 25 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 50 ? '✓' : '2' ?></div>
                            <div>Dikemas</div>
                        </div>
                        <div class="step <?= $progress >= 75 ? 'step-completed' : ($progress >= 50 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 75 ? '✓' : '3' ?></div>
                            <div>Dikirim</div>
                        </div>
                        <div class="step <?= $progress >= 100 ? 'step-completed' : ($progress >= 75 ? 'step-active' : '') ?>">
                            <div class="step-circle"><?= $progress >= 100 ? '✓' : '4' ?></div>
                            <div>Diterima</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                        <div class="total-harga">
                            Total: Rp. <?= number_format($row['total_akhir'], 0, ',', '.') ?>
                        </div>
                        
                        <div>
                            <?php if ($row['status'] == 'di kirim') { ?>
                                <button class="btn btn-success btn-sm" onclick="konfirmasiTerima(<?= $row['id_pembayaran'] ?>)">
                                    Konfirmasi Diterima
                                </button>
                            <?php } ?>
                            
                            <?php if ($row['bukti_pembayaran']) { ?>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalBukti<?= $row['id_pembayaran'] ?>">
                                    Lihat Bukti Bayar
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Bukti Pembayaran -->
                <?php if ($row['bukti_pembayaran']) { ?>
                <div class="modal fade" id="modalBukti<?= $row['id_pembayaran'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="Bukti_pembayaran/<?= $row['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" style="max-width:100%; height:auto;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
            <?php } ?>
        <?php } ?>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    
    <script>
        function konfirmasiTerima(idPembayaran) {
            if (confirm('Apakah Anda yakin sudah menerima pesanan ini?')) {
                $.ajax({
                    url: 'konfirmasi_terima.php',
                    method: 'POST',
                    data: {
                        id_pembayaran: idPembayaran
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Terima kasih! Pesanan telah dikonfirmasi diterima.');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengirim konfirmasi.');
                    }
                });
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

</body>
>>>>>>> b7516bf (mengubah file lama)
</html>