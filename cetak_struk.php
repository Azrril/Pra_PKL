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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Cetak Struk Pembayaran - #<?= $data['id_pembayaran'] ?></title>
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
            background: white;
            max-width: 58mm;
            margin: 0 auto;
            padding: 10px;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
                padding: 5mm;
                font-size: 11px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-before: always;
            }
        }

        /* HEADER STYLES */
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
            top: 10px;
            right: 10px;
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn {
            padding: 8px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print {
            background: #4CAF50;
            color: white;
        }

        .btn-back {
            background: #2196F3;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        /* RESPONSIVE */
        @media screen and (max-width: 600px) {
            body {
                max-width: 100%;
                padding: 5px;
            }
            
            .print-controls {
                position: relative;
                margin-bottom: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Print Controls (Hidden when printing) -->
<div class="print-controls no-print">
    <button onclick="window.print()" class="btn btn-print">🖨️ Cetak</button>
    <a href="javascript:window.close()" class="btn btn-back">← Kembali</a>
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
            Dicetak pada: <?= date('d/m/Y H:i:s') ?>
        </div>
    </div>
</div>

<script>
// Auto print when page loads (optional)
// window.onload = function() {
//     window.print();
// }

// Print function
function printReceipt() {
    window.print();
}

// Close window after printing
window.onafterprint = function() {
    // Uncomment if you want to auto close after printing
    // window.close();
}
</script>

</body>
</html>