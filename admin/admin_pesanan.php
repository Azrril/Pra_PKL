<?php
include '../koneksi.php'; // sesuaikan path koneksi

$query = "
SELECT 
    pembayaran.id_pembayaran,
    pembayaran.status,
    pembayaran.nama_lengkap AS nama_pelanggan,
    pembayaran.alamat,
    pembayaran.bukti_pembayaran,
    produk.nama AS nama_produk,
    produk.harga,
    produk.gambar,
    pembayaran.total_akhir
FROM pembayaran
JOIN users ON pembayaran.id_user = users.id_user
JOIN produk ON pembayaran.id_produk = produk.id_produk
";


$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pesanan Masuk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
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
            height: 50px;
            margin-right: 10px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="admin_dashboard.php"><img src="../img/logo zari.png" alt="Zari Petshop"></a>
            <h2>ZARI PETSHOP - Admin</h2>
        </div>
        <nav>
            <ul>
                <li><a href="admin_logout.php">Logout</a></li>
                </div>
            </ul>
        </nav>
    </header>
    <h2>Daftar Pesanan Masuk</h2>
    <table>
        <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>Pelanggan</th>
                <th>Alamat</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $jumlah=1;
            while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $jumlah ?></td>
                    <td><?= $row['nama_pelanggan'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td>
                     <img src="../<?= $row['gambar'] ?>" alt="" width="40">
                    <?= $row['nama_produk'] ?>
                    </td>
                    <td>
                     <?= $row['harga'] ?>
                    </td>
                    <td>
                     <span>Rp. <?= number_format($row['total_akhir'], 0, ',', '.') ?></span>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBukti<?= $row['id_pembayaran'] ?>">
    Lihat Bukti Pembayaran
</button>

<div class="modal fade" id="modalBukti<?= $row['id_pembayaran'] ?>" tabindex="-1" role="dialog" aria-labelledby="labelBukti<?= $row['id_pembayaran'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelBukti<?= $row['id_pembayaran'] ?>">Bukti Pembayaran - ID <?= $jumlah ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img src="../Bukti_pembayaran/<?= $row['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" style="max-width:100%; height:auto;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


                    </td>  <?php $jumlah++; ?>
                    <td>
    <select class="form-control status-select" data-id="<?= $row['id_pembayaran'] ?>">
        <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="di kemas" <?= $row['status'] == 'di kemas' ? 'selected' : '' ?>>Di kemas</option>
        <option value="di kirim" <?= $row['status'] == 'di kirim' ? 'selected' : '' ?>>Dikirim</option>
        <option value="di terima" <?= $row['status'] == 'di terima' ? 'selected' : '' ?>>Diterima</option>
    </select>
</td>

                    <td><a href="hapus_transaksi.php?id_pembayaran=<?= $row['id_pembayaran'] ?>"><button>Hapus</button></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
    $(document).ready(function() {
        $('.status-select').change(function() {
            const id = $(this).data('id');
            const status = $(this).val();

            $.ajax({
                url: 'update_status.php',
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    alert(response); // Bisa diganti dengan notifikasi lain
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupdate status.');
                }
            });
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
