<?php
session_start();
include "koneksi.php";

$id_pembayaran = isset($_GET['id_pembayaran']) ? intval($_GET['id_pembayaran']) : 0;

$query = "
SELECT 
    pembayaran.*, 
    pengiriman.nama_jasa, 
    pengiriman.harga AS harga_pengiriman,
    produk.nama AS nama_produk,
    produk.harga AS harga_produk,
    metode_pembayaran.metode,
    metode_pembayaran.nomor_akun
FROM pembayaran 
JOIN pengiriman ON pembayaran.id_pengiriman = pengiriman.id_pengiriman 
JOIN produk ON pembayaran.id_produk = produk.id_produk
JOIN metode_pembayaran ON pembayaran.id_metode_bayar = metode_pembayaran.id_pembayaran
WHERE pembayaran.id_pembayaran = $id_pembayaran
";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
$total_bayar = ($data['harga_produk'] * $data['Qty']) + $data['harga_pengiriman'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cetak Struk Pembayaran - #12345</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #f5f5f5;
        }

        /* HEADER STYLES - FULL WIDTH */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: #4CAF50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .custom-navbar {
            background-color: #4CAF50 !important; 
            padding: 15px 0;
        }

        .custom-navbar .nav-link,
        .custom-navbar .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .custom-navbar .nav-link:hover,
        .custom-navbar .nav-link:focus {
            color: #c8e6c9 !important;
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

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        /* MAIN CONTENT CONTAINER */
        .main-container {
            margin-top: 80px; /* Space for fixed header */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }

        /* RECEIPT CONTAINER */
        .receipt-container {
            background: white;
            max-width: 58mm;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 11px;
                background: white;
            }
            
            .main-header,
            .no-print {
                display: none !important;
            }

            .main-container {
                margin-top: 0;
                padding: 0;
                min-height: auto;
            }

            .receipt-container {
                max-width: none;
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                padding: 5mm;
            }
            
            .page-break {
                page-break-before: always;
            }
        }

        /* RECEIPT HEADER */
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .receipt-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .receipt-date {
            font-size: 10px;
            color: #666;
        }

        /* STATUS BADGE */
        .status-section {
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            border: 1px solid #000;
            background: #f0f0f0;
        }

        .status-badge {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-paid {
            color: #2e7d32;
        }

        .status-pending {
            color: #f57c00;
        }

        /* SECTION STYLES */
        .section {
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        /* INFO ROWS */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 10px;
        }

        .info-label {
            font-weight: 500;
            flex: 1;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        /* CALCULATION STYLES */
        .calculation-section {
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #000;
            background: #f9f9f9;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 10px;
        }

        .calc-label {
            font-weight: 500;
        }

        .calc-value {
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .total-separator {
            border-top: 2px solid #000;
            margin: 10px 0 5px 0;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 700;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 2px solid #000;
        }

        /* PAYMENT PROOF */
        .payment-proof-section {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .payment-proof-section img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        .no-proof-text {
            font-style: italic;
            color: #666;
            font-size: 10px;
        }

        /* FOOTER */
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #000;
            font-size: 9px;
            color: #666;
        }

        /* CONTROL BUTTONS */
        .print-controls {
            position: fixed;
            top: 90px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 999;
        }

        .btn-custom {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-print {
            background: #4CAF50;
            color: white;
        }

        .btn-back {
            background: #2196F3;
            color: white;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* RESPONSIVE */
        @media screen and (max-width: 768px) {
            .main-container {
                padding: 10px;
                margin-top: 70px;
            }
            
            .receipt-container {
                max-width: 100%;
            }
            
            .print-controls {
                position: relative;
                top: auto;
                right: auto;
                margin-bottom: 20px;
                text-align: center;
                width: 100%;
            }

            .btn-custom {
                display: block;
                margin: 5px 0;
                width: 100%;
            }
        }

        @media screen and (max-width: 480px) {
            .custom-navbar .navbar-brand {
                font-size: 16px;
            }
            
            .navbar-brand img {
                height: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- FULL WIDTH HEADER -->
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
          <a class="nav-link" href="pesanan.php">Pesanan</a>
        </li>

        <?php
        if (isset($_SESSION['username'])) {
          // Sudah login
          echo '
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown">
              Akun
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="Profil.php">Profil</a></li>
              <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
            </ul>
          </li>';
        } else {
          // Belum login
          echo '
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>
</header>

    <!-- MAIN CONTAINER -->
    <div class="main-container">
        <!-- Print Controls (Hidden when printing) -->
        <div class="print-controls no-print">
            <button onclick="window.print()" class="btn-custom btn-print">🖨️ Cetak Struk</button>
        </div>

<!-- Receipt Content -->
<div class="receipt-content">
    <!-- Header -->
    <div class="receipt-header">
        <div class="company-name">TOKO ONLINE</div>
        <div class="receipt-title">STRUK PEMBAYARAN</div>
        <div class="receipt-date">
            <?= date('d/m/Y H:i:s') ?><br>
            ID: #<?= $data['id_pembayaran'] ?>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <div class="section-title">DATA PELANGGAN</div>
        <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value"><?= htmlspecialchars($data['nama_lengkap']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Telepon:</span>
            <span class="info-value"><?= htmlspecialchars($data['nomor_telp']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat:</span>
            <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
        </div>
    </div>

    <!-- Product Information -->
    <div class="section">
        <div class="section-title">DETAIL PESANAN</div>
        <div class="info-row">
            <span class="info-label">Produk:</span>
            <span class="info-value"><?= htmlspecialchars($data['nama_produk']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Harga:</span>
            <span class="info-value">Rp<?= number_format($data['harga_produk'], 0, ',', '.') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Qty:</span>
            <span class="info-value"><?= $data['Qty'] ?> pcs</span>
        </div>
        <div class="info-row">
            <span class="info-label">Subtotal:</span>
            <span class="info-value">Rp<?= number_format($data['Qty'] * $data['harga_produk'], 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Shipping Information -->
    <div class="section">
        <div class="section-title">PENGIRIMAN</div>
        <div class="info-row">
            <span class="info-label">Kurir:</span>
            <span class="info-value"><?= htmlspecialchars($data['nama_jasa']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Ongkir:</span>
            <span class="info-value">Rp<?= number_format($data['harga_pengiriman'], 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Payment Method -->
    <div class="section">
        <div class="section-title">METODE PEMBAYARAN</div>
        <div class="info-row">
            <span class="info-label">Bank:</span>
            <span class="info-value"><?= htmlspecialchars($data['metode']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">No. Rekening:</span>
            <span class="info-value"><?= htmlspecialchars($data['nomor_akun']) ?></span>
        </div>
    </div>

    <!-- Calculation Summary -->
    <div class="calculation-section">
        <div class="calc-row">
            <span class="calc-label">Subtotal Produk:</span>
            <span class="calc-value">Rp<?= number_format($data['Qty'] * $data['harga_produk'], 0, ',', '.') ?></span>
        </div>
        <div class="calc-row">
            <span class="calc-label">Ongkos Kirim:</span>
            <span class="calc-value">Rp<?= number_format($data['harga_pengiriman'], 0, ',', '.') ?></span>
        </div>
        <div class="total-separator"></div>
        <div class="total-final">
            <span>TOTAL BAYAR:</span>
            <span>Rp<?= number_format($data['total_akhir'], 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Payment Proof Status -->
    <div class="payment-proof-section">
        <div class="section-title">BUKTI PEMBAYARAN</div>
        <?php if ($data['bukti_pembayaran']): ?>
            <div style="font-size: 10px; color: #2e7d32; font-weight: 600;">
                ✓ Bukti pembayaran telah diterima
            </div>
            <div style="font-size: 9px; color: #666; margin-top: 5px;">
                File: <?= htmlspecialchars($data['bukti_pembayaran']) ?>
            </div>
        <?php else: ?>
            <div class="no-proof-text">
                ⚠️ Bukti pembayaran belum diunggah
            </div>
        <?php endif; ?>
    </div>
                <!-- Footer -->
                <div class="receipt-footer">
                    <div>═══════════════════════════</div>
                    <div>TERIMA KASIH</div>
                    <div>Simpan struk ini sebagai bukti</div>
                    <div>transaksi yang sah</div>
                    <div>═══════════════════════════</div>
                    <div style="margin-top: 5px; font-size: 8px;">
                        Dicetak pada: 23/06/2025 14:30:45
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Print function
        function printReceipt() {
            window.print();
        }

        // Close window after printing (optional)
        window.onafterprint = function() {
            // Uncomment if you want to auto close after printing
            // window.close();
        }

        // Responsive navbar handling
        document.addEventListener('DOMContentLoaded', function() {
            // Handle navbar collapse on small screens
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener('click', function() {
                    navbarCollapse.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>