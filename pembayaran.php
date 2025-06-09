<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// PROSES FORM SUBMIT - HARUS DI PALING ATAS SEBELUM HTML
if (isset($_POST['submit_bayar'])) {
    $id_user = $_SESSION['id_user'] ?? 0;

    if ($id_user == 0) {
        // jika user belum login, redirect ke login atau hentikan proses
        header("Location: login.php");
        exit();
    }

    $id_produk = $_POST['id_produk'];
    $qty = $_POST['angka'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $nomor_telp = $_POST['nomor_telp'];
    $id_pengiriman = $_POST['id_pengiriman'];
    $id_metode_bayar = $_POST['id_metode_bayar'];
    $harga = $_POST['harga'];

    $queryOngkir = "SELECT harga FROM pengiriman WHERE id_pengiriman = $id_pengiriman";
    $resultOngkir = mysqli_query($koneksi, $queryOngkir);
    $rowOngkir = mysqli_fetch_assoc($resultOngkir);
    $ongkir = $rowOngkir['harga'];

    $total_akhir = ($qty * $harga) + $ongkir;

    $query = "INSERT INTO pembayaran 
        (id_produk, qty, id_user, id_pengiriman, nama_lengkap, alamat, status, nomor_telp, total_akhir, id_metode_bayar)
        VALUES 
        ('$id_produk', '$qty', '$id_user', '$id_pengiriman', '$nama_lengkap', '$alamat', 'pending', '$nomor_telp', '$total_akhir', '$id_metode_bayar')";

    if (mysqli_query($koneksi, $query)) {
        $id_pembayaran = mysqli_insert_id($koneksi); // ambil ID terakhir
        header("Location: struk.php?id_pembayaran=$id_pembayaran");
        exit();
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}

// PROSES QUANTITY
$nama = $_POST['nama'] ?? 'Produk tidak ditemukan';
$harga = $_POST['harga'] ?? 0;
$gambar = $_POST['gambar'] ?? '';
$angka = isset($_POST['angka']) ? (int)$_POST['angka'] : 1;

//penjumlahan dan pengurangan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $angka += 1;
    } elseif (isset($_POST['kurang']) && $angka > 1) { // Tambah kondisi agar tidak kurang dari 1
        $angka -= 1;
    }
}

// QUERY DATABASE
$id = isset($_GET['id_produk']) ? intval($_GET['id_produk']) : 0; 
$sql = 'SELECT * FROM produk WHERE id_produk = ' . $id;
$result = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));

$queryPengiriman = 'SELECT * FROM pengiriman';
$resultPengiriman = mysqli_query($koneksi, $queryPengiriman) or die(mysqli_error($koneksi));

$queryPembayaran = 'SELECT * FROM metode_pembayaran';
$resultPembayaran = mysqli_query($koneksi, $queryPembayaran) or die(mysqli_error($koneksi));

?>

<!DOCTYPE html>
<html>
<head>
  <title>Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <style>
    
    body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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


        /* CONTAINER UTAMA */
        .main-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        /* CARD STYLE */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .card-header {
            background: #f8f9fa;
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }

        /* LAYOUT 2 COL */
        .checkout-layout {
            display: flex;
            gap: 20px;
        }

        .left-section {
            flex: 2;
        }

        .right-section {
            flex: 1;
        }

        /* PRODUK SECTION */
        .product-section {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .product-image {
            width: 120px;
            height: 120px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-info h3 {
            margin: 0 0 8px 0;
            font-size: 18px;
            color: #333;
        }

        .product-info p {
            margin: 4px 0;
            color: #666;
        }

        .price {
            font-size: 20px;
            color: #ee4d2d;
            font-weight: bold;
            margin: 12px 0;
        }

        /* QUANTITY */
        .quantity-box {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
        }

        .qty-input {
            width: 50px;
            height: 32px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* FORM STYLE */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            box-sizing: border-box;
        }

        /* RINGKASAN */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .summary-subtotal {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border-top: 1px solid #eee;
            padding-top: 12px;
            margin-top: 12px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #ee4d2d;
            border-top: 2px solid #ee4d2d;
            padding-top: 12px;
            margin-top: 12px;
        }

        /* BUTTON */
        .btn-order {
            width: 100%;
            padding: 14px;
            background: #ee4d2d;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
        }

        .btn-order:hover {
            background: #d73f2a;
        }


        /* FOOTER TETAP SAMA */
        footer {
          background-color: #4CAF50;
          padding: 40px 20px 50px 20px;
          font-family: Arial, sans-serif;
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

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .checkout-layout {
                flex-direction: column;
            }
            
            .product-section {
                flex-direction: column;
                text-align: center;
            }
            
            .main-container {
                padding: 0 10px;
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
            <li><a class="dropdown-item" href="Profil.php">Profil</a></li>
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

<div class="main-container">
    <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Checkout</h2>
    
    <?php while($product = mysqli_fetch_object($result)) { ?>
    
    <div class="checkout-layout">
        <!-- BAGIAN KIRI -->
        <div class="left-section">
            
            <!-- PRODUK -->
            <div class="card">
                <div class="card-header">Produk yang Dibeli</div>
                <div class="card-body">
                    <div class="product-section">
                        <div class="product-image">
                            <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->nama; ?></h3>
                            <p><?php echo $product->deskripsi; ?></p>
                            <div class="price">Rp <?php echo number_format($product->harga); ?></div>
                            
                            <!-- QUANTITY -->
                            <form method="post" action="">
                                <input type="hidden" name="nama" value="<?= $product->nama ?>">
                                <input type="hidden" name="harga" value="<?= $product->harga ?>">
                                <input type="hidden" name="gambar" value="<?= $product->gambar ?>">
                                <input type="hidden" name="angka" value="<?= $angka ?>">

                                <div class="quantity-box">
                                    <span>Jumlah:</span>
                                    <button type="submit" name="kurang" class="qty-btn">-</button>
                                    <input type="text" name="angka" value="<?= $angka ?>" readonly class="qty-input">
                                    <button type="submit" name="tambah" class="qty-btn">+</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <form method="post" action="">
                <input type="hidden" name="id_produk" value="<?= $product->id_produk ?>">
                <input type="hidden" name="harga" value="<?= $product->harga ?>">
                <input type="hidden" name="angka" value="<?= $angka ?>">

                <!-- ALAMAT -->
                <div class="card">
                    <div class="card-header">Alamat Pengiriman</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="number" name="nomor_telp" required class="form-input">
                        </div>
                    </div>
                </div>

                <!-- PENGIRIMAN -->
                <div class="card">
                    <div class="card-header">Pilih Pengiriman</div>
                    <div class="card-body">
                        <select name="id_pengiriman" class="form-select" id="pengiriman" onchange="updateTotal()">
                            <option value="">-- Pilih Pengiriman --</option>
                            <?php 
                            mysqli_data_seek($resultPengiriman, 0); 
                            while($pengiriman = mysqli_fetch_object($resultPengiriman)) { ?> 
                                <option value="<?= $pengiriman->id_pengiriman ?>" data-harga="<?= $pengiriman->harga ?>">
                                    <?= $pengiriman->nama_jasa ?> 
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- PEMBAYARAN -->
                <div class="card">
                    <div class="card-header">Metode Pembayaran</div>
                    <div class="card-body">
                        <select name="id_metode_bayar" class="form-select">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <?php 
                            mysqli_data_seek($resultPembayaran, 0); 
                            while($pembayaran = mysqli_fetch_object($resultPembayaran)) { ?> 
                                <option value="<?= $pembayaran->id_pembayaran ?>">
                                    <?= $pembayaran->metode ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

        </div>

        <!-- BAGIAN KANAN -->
        <div class="right-section">
            <div class="card">
                <div class="card-header">Ringkasan Belanja</div>
                <div class="card-body">
                    <div class="summary-row">
                        <span>Harga Produk</span>
                        <span>Rp <span id="harga-produk"><?= number_format($product->harga * $angka) ?></span></span>
                    </div>
                    <div class="summary-subtotal">
                        <span>Subtotal</span>
                        <span>Rp <span id="subtotal"><?= number_format($product->harga * $angka) ?></span></span>
                    </div>
                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <span>Rp <span id="total"><?= number_format($product->harga * $angka) ?></span></span>
                    </div>
                    
                    <button type="submit" name="submit_bayar" class="btn-order">BUAT PESANAN</button>
                </div>
            </div>
        </div>
            </form>

    </div>
    
    <?php } ?>
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

<!-- ICONS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

<script>
// Simpan harga produk dan quantity di JavaScript
const productPrice = <?= $product->harga ?>;
const productQty = <?= $angka ?>;

// Update total saat ganti pengiriman
function updateTotal() {
    const pengirimanSelect = document.getElementById('pengiriman');
    const selectedOption = pengirimanSelect.options[pengirimanSelect.selectedIndex];
    
    const hargaProduk = productPrice * productQty;
    
    // Cek apakah ada option yang dipilih dan memiliki data-harga
    if (selectedOption && selectedOption.dataset.harga) {
        const ongkir = parseInt(selectedOption.dataset.harga);
        const subtotal = hargaProduk + ongkir;
        const totalPembayaran = subtotal; // Total pembayaran sama dengan subtotal
        
        // Update tampilan
        document.getElementById('ongkir').textContent = ongkir.toLocaleString('id-ID');
        document.getElementById('subtotal').textContent = subtotal.toLocaleString('id-ID');
        document.getElementById('total').textContent = totalPembayaran.toLocaleString('id-ID');
    } else {
        // Jika belum pilih pengiriman, ongkir = 0
        document.getElementById('ongkir').textContent = '0';
        document.getElementById('subtotal').textContent = hargaProduk.toLocaleString('id-ID');
        document.getElementById('total').textContent = hargaProduk.toLocaleString('id-ID');
    }
}

// Jalankan saat halaman load
window.onload = function() {
    // Set harga produk
    document.getElementById('harga-produk').textContent = (productPrice * productQty).toLocaleString('id-ID');
    
    // Update total (akan set ongkir = 0 karena belum ada yang dipilih)
    updateTotal();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

</body>
</html>