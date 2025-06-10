<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php?pesan=logindulu");
    exit;   
}

$username = $_SESSION['username'];
$result = mysqli_query($koneksi, "SELECT id_admin FROM admin WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);
$id_admin = $row['id_admin'];

$nama = $_GET['nama'];
$harga = $_GET['harga'];
$gambar = $_GET['gambar'];
$id_brand = $_GET['id_brand'];
$deskripsi = $_GET['deskripsi'];
$stok = $_GET['stok'];

$sql = "INSERT into produk(nama, harga, gambar, id_brand, deskripsi, stok, id_admin) values ('$nama','$harga','$gambar','$id_brand','$deskripsi','$stok','$id_admin')";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    header("location:admim/admin_tambah.php?tambah=sukses");
    exit;
} else {
    header("location:admin/admin_tambah.php?tambah=gagal");
    exit;
}


?>