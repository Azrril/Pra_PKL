<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembayaran = intval($_POST['id_pembayaran']);

    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['bukti_pembayaran']['name']);
        $upload_dir = 'Bukti_pembayaran/';

        // Buat folder jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Simpan nama file ke database (bukan path lengkap)
            $query = "UPDATE pembayaran SET bukti_pembayaran = '$file_name' WHERE id_pembayaran = $id_pembayaran";
            if (mysqli_query($koneksi, $query)) {
                header("Location: struk.php?id_pembayaran=$id_pembayaran");
                exit();
            } else {
                echo "Gagal update database: " . mysqli_error($koneksi);
            }
        } else {
            echo "Gagal upload file.";
        }
    } else {
        echo "File tidak ditemukan atau error saat upload.";
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
