<?php
$nama = $_POST['nama'] ?? 'Produk tidak ditemukan';
$harga = $_POST['harga'] ?? 0;
$gambar = $_POST['gambar'] ?? '';

$angka = isset($_POST['angka']) ? (int)$_POST['angka'] : 1;

    //penjumlahan dan pengurangan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['tambah'])) {
            $angka += 1;
        } elseif (isset($_POST['kurang'])) {
            $angka -= 1;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pembayaran</title>
  <style>
    
    body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
   .navbar {
            background-color: #4CAF50;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-left span {
            font-weight: bold;
            color: white;
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            gap: 30px;
        }

        .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }

        /* KONTEN */
        .content {
     position: relative;
     height: 100px; 
    }

    .back-arrow,
    .next-arrow {
        position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    padding: 10px;
    text-decoration: none;
    color: black;
    cursor: pointer;
    background-color: #eee;
    border-radius: 50%;
    }

    .back-arrow {
    left: 10px;
    }

    .next-arrow {
    right: 10px;
    }   

        .back-arrow {
            font-size: 24px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .logo-section {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .logo-section img {
            height: 60px;
            margin-left: 20px;
        }

        .logo-section p {
            max-width: 500px;
        }
        
    .container {
      display: flex;
      gap: 30px;
      align-items: flex-start;
      padding: 40px;
      min-height: 29dvw;
    }
    .gambar-produk {
      width: 300px;
      height: 300px;
      
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 10px;
      overflow: hidden;
    }
    .gambar-produk img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .detail {
      max-width: 400px;
    }
    .harga {
      font-size: 28px;
      color: #111;
      margin: 10px 0;
    }
    .btn-bayar {
      width: 100%;
      padding: 10px;
      background-color: #222;
      color: white;
      border: none;
      border-radius: 5px;
      margin-top: 20px;
      font-size: 16px;
    }

    footer {
            background-color: #43d02b;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
        }
    
  </style>
</head>

<div class="navbar">
        <div class="navbar-left">
            <img src="img/logo zari.png" alt="Logo">
            <span>ZARI PETSHOP</span>
        </div>
        <div class="navbar-right">
            <a href="dashboard.php#produk">Produk</a>
            <a href="dashboard.php#layanan">Layanan</a>
            <a href="dashboard.php#kontak">Kontak</a>
            <a href="dashboard.php#tentang">Tentang</a>
        </div>
    </div>
      
<body> 
    <?php 
    require './koneksi.php';
    
    $id = isset($_GET['id_produk']) ? intval($_GET['id_produk']) : 0; 
    $sql = 'SELECT * FROM produk WHERE id_produk = ' . $id;
    $result = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
    
    $queryPengiriman = 'SELECT * FROM pengiriman';
    $resultPengiriman = mysqli_query($koneksi, $queryPengiriman) or die(mysqli_error($koneksi));
    
    $queryPembayaran = 'SELECT * FROM metode_pembayaran';
    $resultPembayaran = mysqli_query($koneksi, $queryPembayaran) or die(mysqli_error($koneksi));
    ?>

    <?php
session_start(); // kalau pakai session login
require './koneksi.php';

if (isset($_POST['submit_bayar'])) {
    session_start();
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

    $id_pengiriman = $_POST['id_pengiriman'];
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
?>
<a href="admin_dashboard.php" class="btn btn-secondary btn-back">← Kembali</a>
  <center><h2>Pembayaran</h2></center>
  <div class="container">
  <?php while($product = mysqli_fetch_object($result)) { ?> 
    <div class="gambar-produk">
    <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>" width="100">
      
    </div>
    <div class="detail">


            <p><?php echo $product->nama; ?></p>
            <p><?php echo $product->deskripsi; ?></p>
      <p class="harga">
      <p><strong>Rp.<?php echo $product->harga; ?></strong></p>
      </p>

          <div>
            <form method="post" action="">
                <input type="hidden" name="nama" value="<?= $product->nama ?>">
                <input type="hidden" name="harga" value="<?= $product->harga ?>">
                <input type="hidden" name="gambar" value="<?= $product->gambar ?>">
                <input type="hidden" name="angka" value="<?= $angka ?>">

                <label>Jumlah:</label><br>
                <button type="submit" name="kurang">-</button>
                <input type="text" name="angka" value="<?= $angka ?>" readonly>
                <button type="submit" name="tambah">+</button>
            </form>
            <form method="post" action="">
                <input type="hidden" name="id_produk" value="<?= $product->id_produk ?>">
                <input type="hidden" name="harga" value="<?= $product->harga ?>">
                <input type="hidden" name="angka" value="<?= $angka ?>">

                <label>Nama Lengkap:</label><br>
                <input type="text" name="nama_lengkap" required><br><br>

                <label>Alamat:</label><br>
                <input type="text" name="alamat" required><br><br>

                <label>Nomor Telepon:</label><br>
                <input type="number" name="nomor_telp" required><br><br>

                <label>Metode Pengiriman:</label><br>
                <select name="id_pengiriman">
                    <?php mysqli_data_seek($resultPengiriman, 0); while($pengiriman = mysqli_fetch_object($resultPengiriman)) { ?> 
                        <option value="<?= $pengiriman->id_pengiriman ?>"><?= $pengiriman->nama_jasa ?></option>
                    <?php } ?>
                </select><br><br>

                <label>Metode Pembayaran:</label><br>
                <select name="id_metode_bayar">
                    <?php mysqli_data_seek($resultPembayaran, 0); while($pembayaran = mysqli_fetch_object($resultPembayaran)) { ?> 
                        <option value="<?= $pembayaran->id_pembayaran ?>"><?= $pembayaran->metode ?></option>
                    <?php } ?>
                </select><br><br>

                <input type="submit" name="submit_bayar" value="Pesan" class="btn-bayar">
            </form>

          </div>
          
    </div>
  </div>
  <?php } ?>

  <footer>
        &copy; 2025 Zari Petshop. All rights reserved.
    </footer>

</body>
</html>