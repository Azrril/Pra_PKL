<?php
include '../koneksi.php'; // Pastikan path ini sesuai

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari form
    $id_produk  = $_POST['id_produk'];
    $nama       = $_POST['nama'];
    $id_brand   = $_POST['id_brand'];
    $harga      = $_POST['harga'];
    $deskripsi  = $_POST['deskripsi'];
    $stok       = $_POST['stok'];

    // Query update
    $sql = "UPDATE produk SET 
                nama = '$nama', 
                id_brand = '$id_brand', 
                harga = '$harga', 
                deskripsi = '$deskripsi', 
                stok = '$stok' 
            WHERE id_produk = '$id_produk'";

    // Eksekusi query
    if (mysqli_query($koneksi, $sql)) {
        // Redirect ke halaman admin produk
        header("Location: admin_tambah.php?status=berhasil_update");
        exit;
    } else {
        // Gagal update
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
} else {
    // Jika bukan metode POST
    echo "Akses tidak diizinkan.";
}
?>
