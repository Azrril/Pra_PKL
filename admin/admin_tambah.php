<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php?pesan=logindulu");
    exit;   
}

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $id_brand = $_POST['id_brand'];

    
$username = $_SESSION['username'];
$result = mysqli_query($koneksi, "SELECT id_admin FROM admin WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);
$id_admin = $row['id_admin'];

$gambarPath = '';
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $namaFile = time() . '_' . basename($_FILES['gambar']['name']);

    // Ambil nama brand berdasarkan id_brand
    $brandQuery = mysqli_query($koneksi, "SELECT nama_brand FROM brand WHERE id_brand = '$id_brand'");
    $brandData = mysqli_fetch_assoc($brandQuery);
    $namaBrand = strtolower(trim($brandData['nama_brand'])); // lowercase + trim
    $namaBrand = preg_replace('/[^a-z0-9]/', '_', $namaBrand); // Ganti spasi/simbol dengan underscore

    // Folder tujuan berdasarkan brand
    $targetDir = "../$namaBrand/";

    // Cek dan buat folder jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $targetFile = $targetDir . $namaFile;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
        $gambarPath = $namaBrand . '/' . $namaFile; // Simpan path relatif
    }
}



    $query = "INSERT INTO produk (nama, harga, id_brand, id_admin, gambar) 
              VALUES ('$nama', '$harga', '$id_brand', '$id_admin', '$gambarPath')";
    mysqli_query($koneksi, $query);
    echo "<script>alert('Produk \"$nama\" berhasil ditambahkan!'); window.location.href='admin_tambah.php';</script>";
}

// Ambil semua produk
$produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Zari Petshop</title>
    <style>
                  *{
      font-weight: bold;
    }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4CAF50;
            padding: 1px 20px;
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
        
        h2 {
            font-size: 30px;
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

        .btn {
            background-color: #03a9f4;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .form-tambah {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 40px auto;
        }

        .form-tambah input,
        .form-tambah select {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
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
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
                </div>
            </ul>
        </nav>
    </header>
    <!-- Form Tambah Produk -->
<div class="form-tambah">
    <h3>Tambahkan Produk Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <id_brand>Nama Produk</id_brand>
        <input type="text" name="nama" required>

        <id_brand>Deskripsi</id_brand>
        <input type="text" name="deskripsi" required>

        <id_brand>Harga Produk</id_brand>
        <input type="number" name="harga" required>

        <id_brand>Stok</id_brand>
        <input type="number" name="stok" required>

        <id_brand>Brand</id_brand>
        <select name="id_brand" required>
            <option value="1">Bolt</option>
            <option value="2">Whiskas</option>
            <option value="3">Hills</option>
            <option value="4">Friskies</option>
        </select>

        <id_brand>Gambar Produk</id_brand>
        <input type="file" name="gambar" accept="image/*">

        <a href="admin_proses_tambah.php"><button type="submit" class="btn">Tambahkan</button></a>
    </form>
</div>
<div class="container">
    <h2>Produk Tersedia</h2>
    
</div>
          <table border="1">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Brand</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($produk)) { ?>
                <tr>
                    <td>
                     <img src="../<?= $row['gambar'] ?>" alt="" width="100">
                    </td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td><?= $row['id_brand'] ?></td>
                    <td>
                     <?= $row['harga'] ?>
                    </td>
                    <td><?= $row['stok'] ?></td>

                    <td>
    <a href="edit_produk.php?id_produk=<?= $row['id_produk'] ?>">
        <button style="background-color: #03a9f4; color: white;">Edit</button>
    </a>
    <a href="admin_hapus_produk.php?id_produk=<?= $row['id_produk'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">
        <button style="background-color: red; color: white;">Hapus</button>
    </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<div class="footer">
    © 2025 Zari Petshop. All rights reserved.
</div>

</body>
</html>
