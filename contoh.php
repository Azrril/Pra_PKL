<?php


// Ambil ID pembayaran dari URL
$id_pembayaran = isset($_GET['id_pembayaran']) ? intval($_GET['id_pembayaran']) : 0;

if ($id_pembayaran == 0) {
    header("Location: semua_produk.php");
    exit();
}

// Query untuk mengambil data pembayaran dengan JOIN
$query = "SELECT 
    p.*, 
    pr.nama as nama_produk, 
    pr.gambar, 
    pr.harga as harga_produk,
    pg.nama_jasa, 
    pg.harga as ongkir,
    mp.metode as metode_pembayaran
    FROM pembayaran p
    JOIN produk pr ON p.id_produk = pr.id_produk
    JOIN pengiriman pg ON p.id_pengiriman = pg.id_pengiriman
    JOIN metode_pembayaran mp ON p.id_metode_bayar = mp.id_pembayaran
    WHERE p.id_pembayaran = $id_pembayaran";

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Data pembayaran tidak ditemukan!";
    exit();
}

$data = mysqli_fetch_assoc($result);

// Hitung subtotal produk
$subtotal_produk = $data['qty'] * $data['harga_produk'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - Zari Petshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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

        .struk-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .struk-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .struk-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .struk-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .struk-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .struk-body {
            padding: 30px;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            flex: 0 0 140px;
        }

        .info-value {
            color: #212529;
            text-align: right;
            flex: 1;
        }

        .product-section {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-details h4 {
            margin: 0 0 8px 0;
            color: #212529;
            font-size: 18px;
        }

        .product-details p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        .product-price {
            margin-left: auto;
            text-align: right;
        }

        .product-price .qty {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .product-price .price {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        .summary-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-subtotal {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 600;
            color: #495057;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            border-top: 3px solid #4CAF50;
            padding-top: 20px;
            margin-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        .btn-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #45a049;
            color: white;
        }

        .btn-outline {
            background: white;
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .btn-outline:hover {
            background: #4CAF50;
            color: white;
        }

        /* Footer */
        footer {
            background-color: #4CAF50;
            padding: 40px 20px 50px 20px;
            font-family: Arial, sans-serif;
            margin-top: 50px;
        }

        footer img {
            max-width: 140px;
            margin-bottom: 10px;
        }

        footer p {
            font-size: 14px;
            line-height: 1.5;
            margin: 5px 0;
        }

        footer h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #2d2b75;
        }

        footer a {
            color: black;
            margin-right: 15px;
        }

        footer .social-icons {
            font-size: 24px;
        }

        footer > div {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        footer > div > div {
            flex: 1;
            min-width: 200px;
        }

        @media (max-width: 768px) {
            .struk-container {
                padding: 0 15px;
                margin: 20px auto;
            }
            
            .product-item {
                flex-direction: column;
                text-align: center;
            }
            
            .product-price {
                margin-left: 0;
                text-align: center;
            }
            
            .btn-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
            
            .info-value {
                text-align: left;
            }
        }

        @media print {
            .custom-navbar,
            footer,
            .btn-actions {
                display: none !important;
            }
            
            body {
                background: white;
            }
            
            .struk-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
<!-- NAVBAR -->
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
                    <a class="nav-link" href="semua_produk.php">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang</a>
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
<br><br>

<div class="struk-container">
    <div class="struk-card">
        <!-- HEADER -->
        <div class="struk-header">
            <h1>✅ Pesanan Berhasil!</h1>
            <p>Terima kasih telah berbelanja di Zari Petshop</p>
        </div>

        <div class="struk-body">
            <!-- INFO PEMESANAN -->
            <div class="info-section">
                <h3 style="margin-bottom: 20px; color: #495057;">📋 Informasi Pemesanan</h3>
                <div class="info-row">
                    <span class="info-label">ID Pesanan:</span>
                    <span class="info-value"><strong>#<?php echo str_pad($data['id_pembayaran'], 6, '0', STR_PAD_LEFT); ?></strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal:</span>
                    <span class="info-value"><?php echo date('d F Y, H:i'); ?> WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-pending"><?php echo strtoupper($data['status']); ?></span>
                    </span>
                </div>
            </div>

            <!-- PRODUK -->
            <div class="product-section">
                <h3 style="margin-bottom: 20px; color: #495057;">🛍️ Produk yang Dibeli</h3>
                <div class="product-item">
                    <div class="product-image">
                        <img src="<?php echo $data['gambar']; ?>" alt="<?php echo $data['nama_produk']; ?>">
                    </div>
                    <div class="product-details">
                        <h4><?php echo $data['nama_produk']; ?></h4>
                        <p>Harga satuan: Rp <?php echo number_format($data['harga_produk']); ?></p>
                    </div>
                    <div class="product-price">
                        <div class="qty">Jumlah: <?php echo $data['qty']; ?> pcs</div>
                        <div class="price">Rp <?php echo number_format($subtotal_produk); ?></div>
                    </div>
                </div>
            </div>

            <!-- ALAMAT PENGIRIMAN -->
            <div class="info-section">
                <h3 style="margin-bottom: 20px; color: #495057;">🚚 Alamat Pengiriman</h3>
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value"><?php echo $data['nama_lengkap']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat:</span>
                    <span class="info-value"><?php echo $data['alamat']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon:</span>
                    <span class="info-value"><?php echo $data['nomor_telp']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kurir:</span>
                    <span class="info-value"><?php echo $data['nama_jasa']; ?></span>
                </div>
            </div>

            <!-- RINGKASAN PEMBAYARAN -->
            <div class="summary-section">
                <h3 style="margin-bottom: 20px; color: #495057;">💰 Ringkasan Pembayaran</h3>
                <div class="summary-row">
                    <span>Subtotal Produk (<?php echo $data['qty']; ?> item)</span>
                    <span>Rp <?php echo number_format($subtotal_produk); ?></span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim (<?php echo $data['nama_jasa']; ?>)</span>
                    <span>Rp <?php echo number_format($data['ongkir']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Metode Pembayaran</span>
                    <span><?php echo $data['metode_pembayaran']; ?></span>
                </div>
                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <span>Rp <?php echo number_format($data['total_akhir']); ?></span>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="btn-actions">
                <button onclick="window.print()" class="btn btn-outline">
                    🖨️ Cetak Struk
                </button>
                <a href="semua_produk.php" class="btn btn-primary">
                    🛒 Belanja Lagi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div>
        <div>
            <img src="img/logo_zari.png" alt="Zari Petshop Logo">
            <p>Zari Petshop bergerak dalam bidang retail petfood, aksesoris, kandang dan perlengkapan kebutuhan hewan kesayangan Anda.</p>
        </div>

        <div>
            <h3>Hubungi Kami</h3>
            <p>📱 +62 858-1614-2460</p>
            <p>📧 zaripetshop_official@gmail.com</p>
            <p>📍 Jl. Mayor Jend. Sungkono No.34, Selabaya, Kalimanah, Purbalingga</p>
        </div>

        <div>
            <h3>Social Media</h3>
            <div class="social-icons">
                <a href="https://instagram.com/zaripetshop_pbg"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com/profile.php?id=61575280174667"><i class="fab fa-facebook-square"></i></a>
                <a href="https://api.whatsapp.com/send/?phone=6287837148304"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>

</body>
</html>