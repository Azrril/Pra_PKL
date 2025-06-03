<?php
include '../koneksi.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE pembayaran SET status = '$status' WHERE id_pembayaran = '$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "Status berhasil diupdate";
    } else {
        echo "Gagal mengupdate status";
    }
}
?>
