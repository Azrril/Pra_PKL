<?php
include "../koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
                  *{
      font-weight: bold;
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
            height: 50px;
            margin-right: 10px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="admin_dashboard.php"><img src="../img/logo_zari.png" alt="Zari Petshop"></a>
            <h2>ZARI PETSHOP - Admin</h2>
        </div>
        <nav>
            <ul>
                <li><a href="admin_logout.php">Logout</a></li>
                </div>
            </ul>
        </nav>
    </header>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Dashboard</h2>
        <div class="row">

            <div class="col-md-4 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-box-archive" style="font-size: 5rem"></i>
                        <h5 class="card-title">Kelola Produk</h5>
                        <p class="card-text">Tambahkan, edit, atau hapus produk.</p>
                        <a href="admin_tambah.php" class="btn btn-success">Masuk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-box-archive" style="font-size: 5rem"></i>
                        <h5 class="card-title">Laporan</h5>
                        <p class="card-text">Laporan Hasil Penjualan Di Zari Petsho</p>
                        <a href="laporan.php" class="btn btn-success">Masuk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-cart-shopping" style="font-size: 5rem"></i>
                        <h5 class="card-title">Pesanan Masuk</h5>
                        <p class="card-text">Pantau pesanan yang telah dibayar.</p>
                        <a href="admin_pesanan.php" class="btn btn-primary">Masuk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-users" style="font-size: 5rem"></i>
                        <h5 class="card-title">Data Pengguna</h5>
                        <p class="card-text">Lihat dan kelola akun pelanggan.</p>
                        <a href="admin_user.php" class="btn btn-warning text-white">Masuk</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
