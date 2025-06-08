<?php
include 'koneksi.php'; 

$id_pembayaran = isset($_GET['id_pembayaran']) ? intval($_GET['id_pembayaran']) : 0;

$query = "
SELECT 
    pembayaran.*, 
    pengiriman.nama_jasa, 
    pengiriman.harga AS harga_pengiriman,
    produk.nama AS nama_produk,
    produk.harga AS harga_produk,
    metode_pembayaran.metode,
    metode_pembayaran.nomor_akun
FROM pembayaran 
JOIN pengiriman ON pembayaran.id_pengiriman = pengiriman.id_pengiriman 
JOIN produk ON pembayaran.id_produk = produk.id_produk
JOIN metode_pembayaran ON pembayaran.id_metode_bayar = metode_pembayaran.id_pembayaran
WHERE pembayaran.id_pembayaran = $id_pembayaran
";


$result = mysqli_query($koneksi, $query);

$data = mysqli_fetch_assoc($result);

$total_bayar = ($data['harga_produk'] * $data['Qty']) + $data['harga_pengiriman'];

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <?php $back_link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'produk.php'; ?>
 <a href="' . $back_link . '">← Kembali</a> 
 <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #f9f9f9;
            padding: 30px;
        }
        .struk {
            width: 400px;
            margin: auto;
            background: white;
            border: 1px dashed #333;
            padding: 20px;
            text-align: left;
        }
        .struk h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .struk p {
            margin: 5px 0;
        }
        .struk .total {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .struk .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #555;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk">
    <h2>STRUK PEMBAYARAN</h2>
    <p><strong>Nama:</strong> <?= $data['nama_lengkap'] ?></p>
    <p><strong>Alamat:</strong> <?= $data['alamat'] ?></p>
    <p><strong>No. Telepon:</strong> <?= $data['nomor_telp'] ?></p>
    <p><strong>Status:</strong> <?= ucfirst($data['status']) ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= $data['metode'] ?>(</strong> <?= $data['nomor_akun'] ?>)</p>
    <p><strong>Nama Produk:</strong> <?= $data['nama_produk'] ?></p>
    <p><strong>Harga Produk:</strong> Rp<?= number_format($data['harga_produk']) ?></p>
    <p><strong>Qty:</strong> <?= $data['Qty'] ?></p>
    <p><strong>Kurir:</strong> <?= $data['nama_jasa'] ?></p>
    <p><strong>Total Produk (<?= $data['Qty'] ?> × Rp<?= number_format($data['harga_produk']) ?>):</strong> Rp<?= number_format($data['Qty'] * $data['harga_produk']) ?></p>
    <p><strong>Ongkos Kirim:</strong> Rp<?= number_format($data['harga_pengiriman']) ?></p>


    <p><strong>Bukti Bayar:</strong><br>
        <?php if ($data['bukti_pembayaran']): ?>
            <img src="Bukti_pembayaran/<?= $data['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" width="100%">
        <?php else: ?>
            <em>Tidak ada bukti terlampir.</em>
        <?php endif; ?>
    </p>
    <p class="total">Total Bayar: Rp<?= number_format($data['total_akhir']) ?></p>
    <div class="footer">
        Terima kasih telah melakukan pembayaran!<br>
        Simpan struk ini sebagai bukti valid.
    </div>
</div>

<div style="text-align:center; margin-top: 20px;">
    <button onclick="window.print()">🖨 Cetak Struk</button>
    <!-- Button trigger modal -->

    <?php if (!$data['bukti_pembayaran']): ?>
        <!-- Jika belum upload -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Bayar
        </button>
    <?php else: ?>
        <!-- Jika sudah upload -->
        <a href="dashboard.php"><button class="btn btn-success">Selesai ✅</button></a>
    <?php endif; ?>
    


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="upload_bukti.php" method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Bukti Bayar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <p>
            Mohon lakukan transaksi sesuai nominal yang sudah ditentukan di rekening bank yang anda pilih. 
        </p>
        <h2><?= $data['metode'] ?></h2>
        <h2><?= $data['nomor_akun'] ?></h2>
      <div class="modal-body">
        <input type="hidden" name="id_pembayaran" value="<?= $data['id_pembayaran'] ?>">
        <input type="file" name="bukti_pembayaran" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </div>
    </form>
  </div>
</div>

</div>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
