<?php
session_start();
include 'koneksi.php'; 

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Query untuk mengambil pesanan aktif (belum selesai)
$query_aktif = "
SELECT 
    pembayaran.id_pembayaran,
    pembayaran.status,
    pembayaran.nama_lengkap AS nama_pelanggan,
    pembayaran.alamat,
    pembayaran.Qty,
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
AND pembayaran.status IN ('pending', 'di kemas', 'di kirim')
ORDER BY pembayaran.id_pembayaran DESC
";

// Query untuk mengambil riwayat pembelian (sudah selesai)
$query_riwayat = "
SELECT 
    pembayaran.id_pembayaran,
    pembayaran.status,
    pembayaran.nama_lengkap AS nama_pelanggan,
    pembayaran.alamat,
    pembayaran.Qty,
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
AND pembayaran.status = 'di terima'
ORDER BY pembayaran.id_pembayaran DESC
";

$result_aktif = mysqli_query($koneksi, $query_aktif);
$result_riwayat = mysqli_query($koneksi, $query_riwayat);

// Hitung total pembelian
$query_total = "SELECT SUM(total_akhir) as total_pembelian FROM pembayaran WHERE id_user = $id_user AND status = 'di terima'";
$result_total = mysqli_query($koneksi, $query_total);
$total_pembelian = mysqli_fetch_assoc($result_total)['total_pembelian'] ?? 0;

// Hitung jumlah pesanan selesai
$query_count = "SELECT COUNT(*) as jumlah_pesanan FROM pembayaran WHERE id_user = $id_user AND status = 'di terima'";
$result_count = mysqli_query($koneksi, $query_count);
$jumlah_pesanan = mysqli_fetch_assoc($result_count)['jumlah_pesanan'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan & Riwayat - Zari Petshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
   <style>
        * {
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

        .custom-navbar {
            background-color: #4CAF50 !important; 
        }

        .custom-navbar .nav-link,
        .custom-navbar .navbar-brand {
            color: white;
        }

        .custom-navbar .nav-link:hover,
        .custom-navbar .nav-link:focus {
            color: #c8e6c9;
        }

        .custom-navbar .dropdown-menu {
            background-color: #4CAF50;
        }

        .custom-navbar .dropdown-item {
            color: white;
        }

        .custom-navbar .dropdown-item:hover {
            background-color: #81c784;
            color: black;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(135deg, #4CAF50, #81c784);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card h3 {
            margin: 0;
            font-size: 2rem;
        }

        .summary-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }

        .nav-tabs {
            border-bottom: 2px solid #4CAF50;
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            color: #4CAF50;
            font-weight: bold;
            border: none;
            padding: 15px 30px;
        }

        .nav-tabs .nav-link.active {
            background-color: #4CAF50;
            color: white;
            border-radius: 5px 5px 0 0;
        }

        .nav-tabs .nav-link:hover {
            background-color: #81c784;
            color: white;
        }

        .pesanan-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .pesanan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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

        .status-selesai {
            background-color: #6c757d;
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
            background: white;
            border-radius: 8px;
            margin: 20px 0;
        }

        .total-harga {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        .riwayat-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .riwayat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .tanggal-pembelian {
            color: #6c757d;
            font-size: 14px;
        }

        .badge-selesai {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .produk-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .status-steps {
                font-size: 10px;
            }
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
    <br><br>

    <div class="container">
        <h2 style="color: #4CAF50; margin-bottom: 30px;">📦 Pesanan & Riwayat Pembelian</h2>
        
        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <h3><?= $jumlah_pesanan ?></h3>
                <p>Total Pesanan Selesai</p>
            </div>
            <div class="summary-card">
                <h3>Rp. <?= number_format($total_pembelian, 0, ',', '.') ?></h3>
                <p>Total Pembelian</p>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="pesananTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab">
                    Pesanan Aktif (<?= mysqli_num_rows($result_aktif) ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">
                    Riwayat Pembelian (<?= mysqli_num_rows($result_riwayat) ?>)
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pesananTabContent">
            <!-- Pesanan Aktif -->
            <div class="tab-pane fade show active" id="aktif" role="tabpanel">
                <?php if (mysqli_num_rows($result_aktif) == 0) { ?>
                    <div class="no-pesanan">
                        <h4>Tidak Ada Pesanan Aktif</h4>
                        <p>Semua pesanan Anda sudah selesai atau belum ada pesanan.</p>
                        <a href="semua_produk.php" class="btn btn-success">Mulai Belanja</a>
                    </div>
                <?php } else { ?>
                    <?php while($row = mysqli_fetch_assoc($result_aktif)) { 
                        // Tentukan progress berdasarkan status
                        $progress = 0;
                        if ($row['status'] == 'pending') $progress = 25;
                        elseif ($row['status'] == 'di kemas') $progress = 50;
                        elseif ($row['status'] == 'di kirim') $progress = 75;
                        elseif ($row['status'] == 'di terima') $progress = 100;
                        
                        $status_class = str_replace(' ', '', $row['status']);
                    ?>
                        <div class="pesanan-card">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h5>Pesanan (<strong><?= date('d/m/Y', strtotime($row['tanggal'])) ?></strong>)</h5>
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
                                    <p style="margin: 0; font-size: 12px; color: #6c757d;">
                                        Jumlah: <?= $row['Qty'] ?>
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
                                    <?php if ($row['bukti_pembayaran']) { ?>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalBukti<?= $row['id_pembayaran'] ?>">
                                            Lihat Bukti Bayar
                                        </button>
                                    <?php } else { ?>
                                        <a href="struk.php?id_pembayaran=<?= $row['id_pembayaran'] ?>" class="btn btn-warning btn-sm">
                                            Bayar Sekarang
                                        </a>
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

            <!-- Riwayat Pembelian -->
            <div class="tab-pane fade" id="riwayat" role="tabpanel">
                <?php if (mysqli_num_rows($result_riwayat) == 0) { ?>
                    <div class="no-pesanan">
                        <h4>Belum Ada Riwayat Pembelian</h4>
                        <p>Anda belum menyelesaikan pembelian apapun.</p>
                        <a href="semua_produk.php" class="btn btn-success">Mulai Belanja</a>
                    </div>
                <?php } else { ?>
                    <?php while($row = mysqli_fetch_assoc($result_riwayat)) { ?>
                        <div class="riwayat-card">
                            <div class="riwayat-header">
                                <div>
                                    <h6 style="margin: 0;">Pesanan </h6>
                                    <span class="tanggal-pembelian"><?= date('d F Y', strtotime($row['tanggal'])) ?></span>
                                </div>
                                <span class="badge-selesai">SELESAI</span>
                            </div>
                            
                            <div class="produk-info">
                                <img src="<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" class="produk-gambar">
                                <div style="flex: 1;">
                                    <h6 style="margin-bottom: 5px;"><?= $row['nama_produk'] ?></h6>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;">
                                        Qty: <?= $row['Qty'] ?> | Harga: Rp. <?= number_format($row['harga'], 0, ',', '.') ?>
                                    </p>
                                    <p style="margin: 0; font-size: 12px; color: #6c757d;">
                                        Alamat: <?= $row['alamat'] ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <div class="total-harga" style="font-size: 16px;">
                                        Rp. <?= number_format($row['total_akhir'], 0, ',', '.') ?>
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <a href="cetak_struk.php?id_pembayaran=<?= $row['id_pembayaran'] ?>" target="_blank" class="btn btn-success btn-sm">
                                            Cetak Struk
                                        </a>
                                        <?php if ($row['bukti_pembayaran']) { ?>
                                            <button type="button" class="btn btn-info btn-sm ml-1" data-toggle="modal" data-target="#modalBuktiRiwayat<?= $row['id_pembayaran'] ?>">
                                                Bukti Bayar
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Bukti Pembayaran Riwayat -->
                        <?php if ($row['bukti_pembayaran']) { ?>
                        <div class="modal fade" id="modalBuktiRiwayat<?= $row['id_pembayaran'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Bukti Pembayaran - Pesanan #<?= $row['id_pembayaran'] ?></h5>
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
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>
    
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

        // Auto-refresh pesanan aktif setiap 30 detik
        setInterval(function() {
            if ($('#aktif-tab').hasClass('active')) {
                // Hanya refresh jika tab aktif sedang terbuka
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>