<?php
include "../koneksi.php";

$id_pembayaran = isset($_GET['id_pembayaran']) ? intval($_GET['id_pembayaran']) : 0;

$sql = "delete from pembayaran where id_pembayaran = '$id_pembayaran'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    header("location:admin_pesanan.php?hapus=sukses");
    exit;
} else {
    header("location:admin_pesanan.php?hapus=gagal");
}


?>