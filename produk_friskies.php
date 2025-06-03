<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zari Petshop - BOLT</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        /* NAVBAR */
        .navbar {
            background-color: #4CAF50;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-left span {
            font-weight: bold;
            color: white;
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            gap: 30px;
        }

        .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }

        /* KONTEN */
        .content {
     position: relative;
     height: 100px; /* atur sesuai kebutuhan */
    }

    .back-arrow,
    .next-arrow {
        position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    padding: 10px;
    text-decoration: none;
    color: black;
    cursor: pointer;
    background-color: #eee;
    border-radius: 50%;
    }

    .back-arrow {
    left: 10px;
    }

    .next-arrow {
    right: 10px;
    }   

        .back-arrow {
            font-size: 24px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .logo-section {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .logo-section img {
            height: 60px;
            margin-left: 20px;
        }

        .logo-section p {
            max-width: 500px;
        }

        .produk-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .produk-item {
            background-color: #fff;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .produk-item img {
            height: 150px;
        }

        .produk-item p {
            margin: 5px 0;
        }

        .btn-beli {
            background-color: #00aaff;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .keunggulan {
            background-color: #e0ffe0;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            margin-top: 30px;
            gap: 20px;
        }

        .keunggulan img {
            height: 100px;
        }

        footer {
            background-color: #43d02b;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <?php 
    require './koneksi.php';
    $sql = 'SELECT * FROM produk WHERE id_brand = "4"';
    $result = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
    ?>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="navbar-left">
            <img src="img/logo zari.png" alt="Logo">
            <span>ZARI PETSHOP</span>
        </div>
        <div class="navbar-right">
            <a href="dashboard.php#produk">Produk</a>
            <a href="dashboard.php#layanan">Layanan</a>
            <a href="dashboard.php#kontak">Kontak</a>
            <a href="dashboard.php#tentang">Tentang</a>
        </div>
    </div>

    <!-- ISI UTAMA -->
    <div class="content">
    <a href="produk_bolt.php"><div class="back-arrow">&larr;</div></a>
    <a href="produk_hills.php"><div class="next-arrow">&rarr;</div></a>
</div>

        <div class="logo-section">
            <img src="frieskes/Friskies-Logo.png" alt="FRISKIES">
            <p><strong>Friskies – Lezat, Bergizi, dan Penuh Petualangan!</strong><br>Friskies adalah makanan kucing berkualitas yang diperkaya dengan kandungan vitamin dan mineral yang sangat menunjang kesehatan dan kelincahan kucing Anda!</p>
        </div>

        <h3 class="produk-container">Varian Produk</h3>
        <div class="produk-container">
            <?php while($product = mysqli_fetch_object($result)) { ?> 
            <div class="produk-item">
            <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>" width="100">

                <p><?php echo $product->nama; ?></p>
                <p><strong>Rp.<?php echo $product->harga; ?></strong></p>
                <a href="pembayaran.php?id_produk=<?php echo $product->id_produk; ?>">
                    <button class="btn-beli">BELI</button>
                </a>
            </div>
            <?php } ?>
        </div>

        <div class="keunggulan">
            <img src="bahan/friskies kata kata.png" alt="">
            <div>
                <h3>Friskies – Lezat, Bergizi, dan Penuh Petualangan!</h3>
                <p>Di Zari Petshop, kami menyediakan Friskies, makanan kucing lezat dan bergizi yang dibuat dengan bahan berkualitas tinggi. Dengan protein dari ikan, ayam, atau daging, serta diperkaya vitamin, mineral, dan serat, Friskies membantu menjaga energi, pencernaan, dan kesehatan bulu kucing kesayangan Anda. Tersedia dalam berbagai rasa dalam bentuk makanan kering dan basah, Friskies adalah pilihan sempurna untuk memberi kucing Anda makanan yang nikmat dan seimbang!</p>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 Zari Petshop. All rights reserved.
    </footer>

</body>
</html>
