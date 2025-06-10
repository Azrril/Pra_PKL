<?php
include 'koneksi.php'; 

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Struk Pembayaran</title>
    <style>
        :root {
            --primary-color: #FF9800;
            --primary-dark: #F57C00;
            --primary-light: #FFB74D;
            --secondary-color: #fff3e0;
            --accent-color: #E65100;
            --danger-color: #ef4444;
            --text-primary: #BF360C;
            --text-secondary: #FF9800;
            --border-color: #FFCC80;
            --shadow: 0 10px 25px -5px rgba(255, 152, 0, 0.15), 0 4px 6px -2px rgba(255, 152, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4CAF50 0%, #66bb6a 50%, #81c784 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--text-primary);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        /* LAYOUT LANDSCAPE - MAIN CONTAINER */
        .receipt-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            border: 2px solid rgba(255, 152, 0, 0.2);
            min-height: 80vh;
        }

        .receipt-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 40px 40px;
            text-align: center;
            position: relative;
        }

        .receipt-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        }

        .receipt-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .receipt-header .subtitle {
            opacity: 0.9;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        /* LAYOUT HORIZONTAL - MAIN CONTENT */
        .receipt-body {
            padding: 40px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
            min-height: 60vh;
        }

        .left-column, .right-column {
            display: flex;
            flex-direction: column;
            gap: 30px;
            height: 100%;
        }

        /* STATUS BADGE - FIXED TO CENTER */
        .status-container {
            grid-column: 1 / -1; /* Span full width */
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: fit-content;
        }

        .status-paid {
            background: #fff3e0;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .status-pending {
            background: #fff3e0;
            color: #f57c00;
            border: 2px solid #f57c00;
        }

        /* INFO SECTIONS */
        .info-section {
            background: var(--secondary-color);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-color);
            flex: 1;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
            word-break: break-word;
        }

        /* TOTAL SECTION - FIXED LAYOUT */
        .total-section {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--secondary-color), #fff3e0);
            padding: 30px;
            border-radius: 15px;
            border: 2px solid var(--primary-color);
            margin-top: 30px;
        }

        .calculation-breakdown {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            color: var(--text-primary);
            font-size: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .calc-row:last-child {
            border-bottom: none;
        }

        .calc-label {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .calc-value {
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        /* Separator line before total */
        .total-separator {
            height: 2px;
            background: var(--primary-color);
            margin: 20px 0;
            border-radius: 1px;
        }

        .total-final {
            text-align: center;
            padding: 25px;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            background: white;
            border-radius: 10px;
            border: 2px solid var(--primary-color);
        }

        /* PAYMENT PROOF SECTION */
        .payment-proof {
            grid-column: 1 / -1;
            background: var(--secondary-color);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid var(--border-color);
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .payment-proof h5 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 22px;
        }

        .payment-proof img {
            max-width: 450px;
            max-height: 350px;
            width: auto;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(255, 152, 0, 0.2);
            margin-top: 20px;
            border: 2px solid var(--border-color);
        }

        .no-proof {
            color: var(--text-secondary);
            font-style: italic;
            padding: 40px;
            background: #f9f9f9;
            border-radius: 10px;
            border: 2px dashed var(--border-color);
            font-size: 16px;
        }

        /* FOOTER */
        .receipt-footer {
            background: var(--secondary-color);
            padding: 30px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 16px;
            line-height: 1.8;
            border-top: 1px solid var(--border-color);
        }

        /* ACTION BUTTONS */
        .action-buttons {
            text-align: center;
            padding: 30px;
        }

        .btn-modern {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4);
            color: white;
        }

        /* MODAL STYLES */
        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            padding: 25px 30px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 24px;
        }

        .modal-body {
            padding: 30px;
        }

        .payment-info {
            background: var(--secondary-color);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .payment-method {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }

        .account-number {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            font-family: 'Courier New', monospace;
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid var(--border-color);
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        /* RESPONSIVE DESIGN */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .back-button, .action-buttons {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none;
                max-width: none;
            }
        }

        @media (max-width: 992px) {
            .receipt-body {
                grid-template-columns: 1fr;
                gap: 25px;
                min-height: auto;
            }
            
            .receipt-container {
                max-width: 800px;
                min-height: auto;
            }

            .info-section {
                min-height: auto;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .receipt-body {
                padding: 20px;
            }
            
            .info-row, .calc-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .info-value, .calc-value {
                text-align: left;
            }
            
            .total-final {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .receipt-header {
                padding: 20px;
            }
            
            .receipt-header h1 {
                font-size: 24px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .payment-proof img {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h1><i class="fas fa-receipt"></i> STRUK PEMBAYARAN</h1>
        <div class="subtitle">Bukti Transaksi Digital</div>
    </div>

    <div class="receipt-body">
        <!-- Status Badge - Centered Full Width -->
        <div class="status-container">
            <div class="status-badge <?= $data['status'] == 'lunas' ? 'status-paid' : 'status-pending' ?>">
                <i class="fas <?= $data['status'] == 'lunas' ? 'fa-check-circle' : 'fa-clock' ?>"></i>
                <?= ucfirst($data['status']) ?>
            </div>
        </div>

        <!-- Left Column -->
        <div class="left-column">
            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i> Informasi Pelanggan
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['nama_lengkap']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Alamat
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-phone"></i> No. Telepon
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['nomor_telp']) ?></span>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i> Pembayaran
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-money-check-alt"></i> Metode
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['metode']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-hashtag"></i> No. Rekening
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['nomor_akun']) ?></span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-shopping-cart"></i> Detail Pesanan
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-box"></i> Nama Produk
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['nama_produk']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-tag"></i> Harga Satuan
                    </span>
                    <span class="info-value">Rp<?= number_format($data['harga_produk'], 0, ',', '.') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-sort-numeric-up"></i> Quantity
                    </span>
                    <span class="info-value"><?= $data['Qty'] ?> pcs</span>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-truck"></i> Pengiriman
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-shipping-fast"></i> Kurir
                    </span>
                    <span class="info-value"><?= htmlspecialchars($data['nama_jasa']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-money-bill-wave"></i> Ongkos Kirim
                    </span>
                    <span class="info-value">Rp<?= number_format($data['harga_pengiriman'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Total Section - Full Width with Proper Calculation Layout -->
        <div class="total-section">
            <div class="calculation-breakdown">
                <div class="calc-row">
                    <div class="calc-label">
                        <i class="fas fa-shopping-cart"></i>
                        Subtotal Produk (<?= $data['Qty'] ?> × Rp<?= number_format($data['harga_produk'], 0, ',', '.') ?>)
                    </div>
                    <div class="calc-value">Rp<?= number_format($data['Qty'] * $data['harga_produk'], 0, ',', '.') ?></div>
                </div>
                <div class="calc-row">
                    <div class="calc-label">
                        <i class="fas fa-truck"></i>
                        Ongkos Kirim
                    </div>
                    <div class="calc-value">Rp<?= number_format($data['harga_pengiriman'], 0, ',', '.') ?></div>
                </div>
            </div>
            
            <div class="total-separator"></div>
            
            <div class="total-final">
                <i class="fas fa-calculator"></i> Total Pembayaran: Rp<?= number_format($data['total_akhir'], 0, ',', '.') ?>
            </div>
        </div>

        <!-- Payment Proof - Full Width -->
        <div class="payment-proof">
            <h5><i class="fas fa-camera"></i> Bukti Pembayaran</h5>
            <?php if ($data['bukti_pembayaran']): ?>
                <img src="Bukti_pembayaran/<?= htmlspecialchars($data['bukti_pembayaran']) ?>" alt="Bukti Pembayaran">
            <?php else: ?>
                <div class="no-proof">
                    <i class="fas fa-exclamation-triangle"></i><br>
                    Belum ada bukti pembayaran yang diunggah
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="receipt-footer">
        <i class="fas fa-heart" style="color: #FF9800;"></i><br>
        Terima kasih telah melakukan pembayaran!<br>
        Simpan struk ini sebagai bukti transaksi yang valid.
    </div>
</div>

<div class="action-buttons">
    <?php if (!$data['bukti_pembayaran']): ?>
        <button type="button" class="btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i>
            Kirim Bukti Pembayaran
        </button>
    <?php else: ?>
        <a href="dashboard.php" class="btn-success-modern btn-modern">
            <i class="fas fa-check-circle"></i>
            Selesai
        </a>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="upload_bukti.php" method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-info">
                    <p style="color: var(--text-secondary); margin-bottom: 15px;">
                        <i class="fas fa-info-circle"></i>
                        Mohon lakukan transfer sesuai nominal yang tertera ke rekening berikut:
                    </p>
                    <div class="payment-method"><?= htmlspecialchars($data['metode']) ?></div>
                    <div class="account-number"><?= htmlspecialchars($data['nomor_akun']) ?></div>
                    <div style="font-size: 18px; font-weight: 600; color: var(--primary-color); margin-top: 15px;">
                        Total: Rp<?= number_format($data['total_akhir'], 0, ',', '.') ?>
                    </div>
                </div>
                
                <input type="hidden" name="id_pembayaran" value="<?= $data['id_pembayaran'] ?>">
                <label for="bukti_file" class="form-label">
                    <i class="fas fa-file-image"></i> Pilih File Bukti Pembayaran
                </label>
                <input type="file" name="bukti_pembayaran" id="bukti_file" class="form-control" accept="image/*" required>
            </div>
            <div class="modal-footer" style="border: none; padding: 0 30px 30px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-primary-modern btn-modern">
                    <i class="fas fa-paper-plane"></i> Kirim Bukti
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>