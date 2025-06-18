<?php
include "../koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:../login.php");
    exit;
}

// Filter variables
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$filter_minggu = isset($_GET['minggu']) ? $_GET['minggu'] : '';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'bulanan';

// Build query based on filter
$where_conditions = [];
$params = [];

if ($filter_type == 'tahunan') {
    $where_conditions[] = "YEAR(tanggal) = ?";
    $params[] = $filter_tahun;
} elseif ($filter_type == 'bulanan') {
    $where_conditions[] = "YEAR(tanggal) = ? AND MONTH(tanggal) = ?";
    $params[] = $filter_tahun;
    $params[] = $filter_bulan;
} elseif ($filter_type == 'mingguan' && $filter_minggu) {
    $where_conditions[] = "YEARWEEK(tanggal, 1) = ?";
    $params[] = $filter_minggu;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Main query for report data
$query = "SELECT 
    p.id_pembayaran,
    p.nama_lengkap,
    p.alamat,
    p.nomor_telp,
    p.total_akhir,
    p.status,
    p.tanggal,
    p.Qty,
    pr.nama,
    pr.harga,
    mb.metode,
    pg.nama_jasa,
    pg.harga as harga_pengiriman
FROM pembayaran p
LEFT JOIN produk pr ON p.id_produk = pr.id_produk
LEFT JOIN metode_pembayaran mb ON p.id_metode_bayar = mb.id_pembayaran
LEFT JOIN pengiriman pg ON p.id_pengiriman = pg.id_pengiriman
$where_clause
ORDER BY p.tanggal DESC";

if (!empty($params)) {
    $stmt = $koneksi->prepare($query);
    
    // Bind parameters
    $types = str_repeat('s', count($params)); // Assuming all are strings, adjust if needed
    $stmt->bind_param($types, ...$params);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data_pembayaran = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $result = $koneksi->query($query);
    $data_pembayaran = $result->fetch_all(MYSQLI_ASSOC);
}

// Summary statistics
$total_transaksi = count($data_pembayaran);
$total_pendapatan = array_sum(array_column($data_pembayaran, 'total_akhir'));
$total_qty = array_sum(array_column($data_pembayaran, 'Qty'));

// Status statistics
$status_counts = [];
foreach ($data_pembayaran as $row) {
    $status = $row['status'];
    $status_counts[$status] = ($status_counts[$status] ?? 0) + 1;
}

// Get available years for filter
$year_query = "SELECT DISTINCT YEAR(tanggal) as tahun FROM pembayaran ORDER BY tahun DESC";
$year_result = $koneksi->query($year_query);
$years = $year_result->fetch_all(MYSQLI_ASSOC);

// Get available weeks for current year
$week_query = "SELECT DISTINCT YEARWEEK(tanggal, 1) as minggu, 
               MIN(tanggal) as start_date, MAX(tanggal) as end_date 
               FROM pembayaran 
               WHERE YEAR(tanggal) = ? 
               GROUP BY YEARWEEK(tanggal, 1) 
               ORDER BY minggu DESC";
$week_stmt = $koneksi->prepare($week_query);
$week_stmt->bind_param('i', $filter_tahun);
$week_stmt->execute();
$week_result = $week_stmt->get_result();
$weeks = $week_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
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
        .summary-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .summary-card:hover {
            transform: translateY(-3px);
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .status-pending { color: #ffc107; }
        .status-dikemas { color: #17a2b8; }
        .status-dikirim { color: #007bff; }
        .status-diterima { color: #28a745; }
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        @media print {
            .no-print { display: none !important; }
            .print-title { display: block !important; }
        }
        .print-title { display: none; }
    </style>
</head>
<body>
    <header class="no-print">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="../img/logo_zari.png" alt="Zari Petshop"></a>
            <h2>ZARI PETSHOP - Admin</h2>
        </div>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container mt-4">
        <div class="print-title">
            <h2 class="text-center mb-4">LAPORAN PEMBAYARAN ZARI PETSHOP</h2>
        </div>
        
        <h2 class="text-center mb-4 no-print">Laporan Pembayaran</h2>

        <!-- Filter Section -->
        <div class="filter-section no-print">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <label>Jenis Laporan:</label>
                    <select name="filter_type" class="form-control" onchange="toggleFilters()">
                        <option value="tahunan" <?= $filter_type == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                        <option value="bulanan" <?= $filter_type == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                        <option value="mingguan" <?= $filter_type == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                    </select>
                </div>
                
                <div class="col-md-2" id="tahun-filter">
                    <label>Tahun:</label>
                    <select name="tahun" class="form-control" onchange="updateWeeks()">
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year['tahun'] ?>" <?= $filter_tahun == $year['tahun'] ? 'selected' : '' ?>>
                                <?= $year['tahun'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2" id="bulan-filter" style="display: <?= $filter_type == 'bulanan' ? 'block' : 'none' ?>">
                    <label>Bulan:</label>
                    <select name="bulan" class="form-control">
                        <?php 
                        $bulan_names = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>" <?= $filter_bulan == $i ? 'selected' : '' ?>>
                                <?= $bulan_names[$i] ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-3" id="minggu-filter" style="display: <?= $filter_type == 'mingguan' ? 'block' : 'none' ?>">
                    <label>Minggu:</label>
                    <select name="minggu" class="form-control">
                        <option value="">Pilih Minggu</option>
                        <?php foreach ($weeks as $week): ?>
                            <option value="<?= $week['minggu'] ?>" <?= $filter_minggu == $week['minggu'] ? 'selected' : '' ?>>
                                Minggu ke-<?= date('W', strtotime($week['start_date'])) ?> 
                                (<?= date('d M', strtotime($week['start_date'])) ?> - <?= date('d M Y', strtotime($week['end_date'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card summary-card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <h4><?= $total_transaksi ?></h4>
                        <p>Total Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <h4>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-boxes fa-2x mb-2"></i>
                        <h4><?= $total_qty ?></h4>
                        <p>Total Item Terjual</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-percentage fa-2x mb-2"></i>
                        <h4><?= $total_transaksi > 0 ? number_format($total_pendapatan / $total_transaksi, 0, ',', '.') : 0 ?></h4>
                        <p>Rata-rata per Transaksi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Ringkasan Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($status_counts as $status => $count): ?>
                                <div class="col-md-3 text-center">
                                    <h4 class="status-<?= str_replace(' ', '', $status) ?>"><?= $count ?></h4>
                                    <p><?= ucfirst($status) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>ID Pembayaran</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                        <th class="no-print">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data_pembayaran)): ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data untuk periode yang dipilih</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data_pembayaran as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td><?= $row['id_pembayaran'] ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['nama'] ?? 'N/A') ?></td>
                                <td><?= $row['Qty'] ?></td>
                                <td>Rp <?= number_format($row['total_akhir'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $row['status'] == 'pending' ? 'warning' : 
                                        ($row['status'] == 'di kemas' ? 'info' : 
                                        ($row['status'] == 'di kirim' ? 'primary' : 'success')) 
                                    ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['metode'] ?? 'N/A') ?></td>
                                <td class="no-print">
                                    <button class="btn btn-sm btn-info" onclick="showDetail(<?= htmlspecialchars(json_encode($row)) ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4 no-print">
            <p class="text-muted">
                <?php 
if ($filter_type == 'tahunan') {
    echo "Laporan Tahunan $filter_tahun";
} elseif ($filter_type == 'bulanan') {
    $bulan_int = (int)$filter_bulan;
    echo "Laporan Bulanan " . $bulan_names[$bulan_int] . " $filter_tahun";
} elseif ($filter_type == 'mingguan' && $filter_minggu) {
    echo "Laporan Mingguan $filter_minggu";
}
                ?>
                | Dicetak pada: <?= date('d/m/Y H:i:s') ?>
            </p>
        </div>
    </div>

    <!-- Print Button -->
    <button class="btn btn-success btn-lg print-btn no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <script>
        function toggleFilters() {
            const filterType = document.querySelector('select[name="filter_type"]').value;
            const bulanFilter = document.getElementById('bulan-filter');
            const mingguFilter = document.getElementById('minggu-filter');
            
            if (filterType === 'bulanan') {
                bulanFilter.style.display = 'block';
                mingguFilter.style.display = 'none';
            } else if (filterType === 'mingguan') {
                bulanFilter.style.display = 'none';
                mingguFilter.style.display = 'block';
            } else {
                bulanFilter.style.display = 'none';
                mingguFilter.style.display = 'none';
            }
        }

        function showDetail(data) {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pelanggan</h6>
                        <p><strong>Nama:</strong> ${data.nama_lengkap}</p>
                        <p><strong>Alamat:</strong> ${data.alamat}</p>
                        <p><strong>No. Telp:</strong> ${data.nomor_telp}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Pesanan</h6>
                        <p><strong>Tanggal:</strong> ${new Date(data.tanggal).toLocaleString('id-ID')}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Metode Bayar:</strong> ${data.metode || 'N/A'}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Detail Produk</h6>
                        <p><strong>Produk:</strong> ${data.nama || 'N/A'}</p>
                        <p><strong>Harga Satuan:</strong> Rp ${parseInt(data.harga || 0).toLocaleString('id-ID')}</p>
                        <p><strong>Quantity:</strong> ${data.Qty}</p>
                        <p><strong>Pengiriman:</strong> ${data.nama_jasa || 'N/A'}</p>
                        <p><strong>Biaya Kirim:</strong> Rp ${parseInt(data.harga || 0).toLocaleString('id-ID')}</p>
                        <hr>
                        <h5><strong>Total Akhir: Rp ${parseInt(data.total_akhir).toLocaleString('id-ID')}</strong></h5>
                    </div>
                </div>
            `;
            document.getElementById('detailContent').innerHTML = content;
            $('#detailModal').modal('show');
        }

        toggleFilters();
    </script>
</body>
</html>