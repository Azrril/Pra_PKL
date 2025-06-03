<?php
include '../koneksi.php';

if (!isset($_GET['id_produk'])) {
    echo "ID Produk tidak ditemukan.";
    exit;
}

$id_produk = $_GET['id_produk'];

// Ambil data produk dari database
$query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 40px;
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Produk</h2>
    <form action="proses_edit.php" method="post">
        <input type="hidden" name="id_produk" value="<?= $data['id_produk'] ?>">

        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
        </div>

        <div class="form-group">
            <label for="id_brand">Brand</label>
            <select name="id_brand" class="form-control" required>
                <option value="">-- Pilih Brand --</option>
                <?php
                $brand_query = mysqli_query($koneksi, "SELECT * FROM brand");
                while ($brand = mysqli_fetch_assoc($brand_query)) {
                    $selected = ($brand['id_brand'] == $data['id_brand']) ? "selected" : "";
                    echo "<option value='{$brand['id_brand']}' $selected>{$brand['nama_brand']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required><?= $data['deskripsi'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="admin_tambah.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
