<?php
session_start();
include "../koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("location:login.php?pesan=logindulu");
    exit;
}

// Ambil id produk dari URL
$id_produk = $_GET['id_produk'];

// Cek apakah produk digunakan dalam pembayaran
$cekPembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_produk = '$id_produk'");
if (mysqli_num_rows($cekPembayaran) > 0) {
    echo "<script>alert('Produk tidak bisa dihapus karena sudah digunakan dalam transaksi pembayaran.'); window.location.href='admin_tambah.php';</script>";
    exit;
}

// Ambil path gambar produk
$getGambar = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id_produk = '$id_produk'");
$data = mysqli_fetch_assoc($getGambar);
$gambarPath = '../' . $data['gambar'];

// Hapus file gambar jika ada
if (file_exists($gambarPath)) {
    unlink($gambarPath);
}

// Hapus produk dari database
$sql = "DELETE FROM produk WHERE id_produk = '$id_produk'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    header("location:admin_tambah.php?hapus=sukses");
    exit;
} else {
    header("location:admin_tambah.php?hapus=gagal");
    exit;
}
?>
