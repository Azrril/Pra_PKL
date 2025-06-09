<?php 
    require 'koneksi.php';
    $sql = 'SELECT * FROM produk WHERE id_brand = "1"';
    $result = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));


    $sql = 'SELECT * FROM produk WHERE id_brand = "4"';
    $frieskies = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));

    $sql = 'SELECT * FROM produk WHERE id_brand = "3"';
    $hills = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));

    $sql = 'SELECT * FROM produk WHERE id_brand = "2"';
    $whiskas = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zari Petshop - Semua Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      padding-top: 80px;
    }

    /* NAVBAR */
    .custom-navbar {
      background-color: #4CAF50 !important;
    }

    .custom-navbar .nav-link,
    .custom-navbar .navbar-brand {
      color: white;
    }

    .custom-navbar .nav-link:hover,
    .custom-navbar .nav-link:focus {
      color: #c8e6c9;
    }

    .custom-navbar .dropdown-menu {
      background-color: #4CAF50;
    }

    .custom-navbar .dropdown-item {
      color: white;
    }

    .custom-navbar .dropdown-item:hover {
      background-color: #81c784;
      color: black;
    }

    /* BRAND FILTER */


    .brand-filter h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .filter-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .filter-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: bold;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background-color: #45a049;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* BRAND SECTIONS */
    

    .brand-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
      padding: 20px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .brand-header img {
      height: 80px;
      object-fit: contain;
    }

    .brand-info h3 {
      margin: 0;
      color: #333;
      font-size: 24px;
    }

    .brand-info p {
      margin: 5px 0 0 0;
      color: #666;
      font-size: 14px;
    }

    

    /* Responsive */
    @media (max-width: 768px) {
      .brand-header {
        flex-direction: column;
        text-align: center;
      }
      
      .filter-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .filter-btn {
        width: 200px;
      }
    }

    /* ISI UTAMA */
    .content {
      height: 100px;
      position: relative;
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
      background-color: #eee;
      border-radius: 50%;
      cursor: pointer;
    }

    .back-arrow {
      left: 10px;
    }

    .next-arrow {
      right: 10px;
    }

    .logo-section {
      display: flex;
      gap: 20px;
      align-items: center;
      margin: 0 20px;
    }

    .logo-section img {
      height: 60px;
    }

    .logo-section p {
      max-width: 500px;
    }

    h3.produk-container {
      text-align: center;
      font-size: 28px;
      margin-bottom: 30px;
    }

    .produk-container {
      display: flex;
      flex-wrap: wrap;
      gap: 24px;
      justify-content: center;
      padding: 0 20px;
    }

    .produk-item {
      width: 200px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      padding: 16px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .produk-item:hover {
      transform: translateY(-5px);
    }

    .produk-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }

    .produk-item p {
      margin: 10px 0 5px;
      font-size: 15px;
    }

    .produk-item p strong {
      color: #28a745;
      font-size: 16px;
    }

    .btn-beli {
      background-color: #00aaff;
      color: white;
      border: none;
      padding: 8px 18px;
      border-radius: 5px;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      margin-top: 8px;
    }

    .btn-beli:hover {
      background-color: #008ecc;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-beli:active {
      transform: scale(0.95);
      box-shadow: none;
    }

    footer {
      background-color: #4CAF50;
      padding: 40px 20px 50px 20px;
      font-family: Arial, sans-serif;
    }

    footer img {
      max-width: 140px;
      margin-bottom: 10px;
    }

    footer p {
      font-size: 14px;
      line-height: 1.5;
      margin: 5px 0;
    }

    footer h3 {
      font-size: 16px;
      margin-bottom: 10px;
      color: #2d2b75;
    }

    footer a {
      color: black;
      margin-right: 15px;
    }

    footer .social-icons {
      font-size: 24px;
    }

    footer > div {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 30px;
    }

    footer > div > div {
      flex: 1;
      min-width: 200px;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logo_zari.png" alt="Zari Petshop" width="50" class="me-3">
      <strong>ZARI PETSHOP</strong>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php#produk">Tentang</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button" data-bs-toggle="dropdown">
            Kategori Brand
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="produk_bolt.php" onclick="filterBrand('bolt')">BOLT</a></li>
            <li><a class="dropdown-item" href="produk_friskies.php" onclick="filterBrand('friskies')">Friskies</a></li>
            <li><a class="dropdown-item" href="produk_hills.php" onclick="filterBrand('hills')">Hill's</a></li>
            <li><a class="dropdown-item" href="produk-whiskas.php" onclick="filterBrand('whiskas')">Whiskas</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown">
            Akun
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="Profil.php">Edit Profil</a></li>
            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- BRAND FILTER -->
<div class="brand-filter" id="produk">
  <h2><strong><center>Pilih Brand Favorit Anda</center></strong></h2>
</div>
<br>
<br>

<!-- BOLT SECTION -->
<div class="brand-section" id="bolt-section">
  <div class="brand-header">
    <img src="bolt/bolt-logo.png" alt="BOLT">
    <div class="brand-info">
      <h3>BOLT - Best Care For Your Pets</h3>
      <p>BOLT adalah makanan kucing berkualitas tinggi dengan berbagai pilihan varian. Mengandung nutrisi penting yang cocok untuk semua usia dan ras kucing.</p>
    </div>
  </div>
  

<div class="produk-container">
  <?php while($product = mysqli_fetch_object($result)) { ?> 
  <div class="produk-item">
    <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>">
    <p><?php echo $product->nama; ?></p>
    <p><strong>Rp.<?php echo $product->harga; ?></strong></p>
    <a href="pembayaran.php?id_produk=<?php echo $product->id_produk; ?>">
      <button class="btn-beli">BELI</button>
    </a>
  </div>
  <?php } ?>
</div>
<br>
<br>

<!-- FRISKIES SECTION -->
<div class="brand-section" id="friskies-section">
  <div class="brand-header">
    <img src="frieskes/Friskies-Logo.png" alt="Friskies">
    <div class="brand-info">
      <h3>Friskies - Playful Nutrition</h3>
      <p>Friskies memberikan nutrisi lengkap dan seimbang untuk kucing aktif. Dengan berbagai rasa yang disukai kucing untuk kehidupan yang lebih sehat dan bahagia.</p>
    </div>
  </div>
  
  <div class="produk-container">
    <?php while($product = mysqli_fetch_object($frieskies)) { ?> 
      <div class="produk-item">
        <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>" width="100" />
        <p><?php echo $product->nama; ?></p>
        <p><strong>Rp.<?php echo $product->harga; ?></strong></p>
        <a href="pembayaran.php?id_produk=<?php echo $product->id_produk; ?>">
          <button class="btn-beli">BELI</button>
        </a>
      </div>
    <?php } ?>
  </div>
  <br>
  <br>

<!-- HILLS SECTION -->
<div class="brand-section" id="hills-section">
  <div class="brand-header">
    <img src="hills/hills-logo.png" alt="Hills">
    <div class="brand-info">
      <h3>Hill's Science Diet - Precisely Balanced Nutrition</h3>
      <p>Hill's Science Diet diformulasikan oleh dokter hewan dan ahli nutrisi hewan untuk memberikan nutrisi yang tepat sesuai kebutuhan kucing di setiap tahap kehidupan.</p>
    </div>
  </div>
  
 <div class="produk-container">
        <?php while($product = mysqli_fetch_object($hills)) { ?> 
        <div class="produk-item">
            <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>" />
            <p><?php echo $product->nama; ?></p>
            <p><strong>Rp.<?php echo $product->harga; ?></strong></p>
            <a href="pembayaran.php?id_produk=<?php echo $product->id_produk; ?>">
                <button class="btn-beli">BELI</button>
            </a>
        </div>
        <?php } ?>
    </div>
    <br>
    <br>

<!-- WHISKAS SECTION -->
<div class="brand-section" id="whiskas-section">
  <div class="brand-header">
    <img src="whiskas/logo_whiskas.png" alt="Whiskas">
    <div class="brand-info">
      <h3>Whiskas - Good Food For Cats</h3>
      <p>Whiskas memahami apa yang dibutuhkan kucing untuk hidup sehat dan bahagia. Dengan nutrisi lengkap dan rasa yang lezat untuk kucing kesayangan Anda.</p>
    </div>
  </div>
  
   <div class="produk-container">
        <?php while($product = mysqli_fetch_object($whiskas)) { ?> 
            <div class="produk-item">
                <img src="<?php echo $product->gambar; ?>" alt="<?php echo $product->nama; ?>" title="<?php echo $product->nama; ?>" />
                <p><?php echo $product->nama; ?></p>
                <p><strong>Rp.<?php echo number_format($product->harga,0,',','.'); ?></strong></p>
                <a href="pembayaran.php?id_produk=<?php echo $product->id_produk; ?>">
                    <button class="btn-beli">BELI</button>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
<br>
<br>
   
  <!-- Footer -->
  <footer>
    <div>
      <!-- Logo & Deskripsi -->
      <div>
        <img src="img/logo_zari.png" alt="Zari Petshop Logo" />
        <p>
          Zari Petshop bergerak dalam bidang retail petfood, aksesoris, kandang dan perlengkapan kebutuhan hewan kesayangan Anda.
        </p>
      </div>

      <!-- Hubungi Kami -->
      <div>
        <h3>Hubungi Kami</h3>
        <p>📱 +62 858-1614-2460</p>
        <p>📧 zaripetshop_official@gmail.com</p>
        <p>📍 Jl. Mayor Jend. Sungkono No.34, Selabaya, Kec. Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371</p>
      </div>

      <!-- Social Media -->
      <div>
        <h3>Social Media</h3>
        <div class="social-icons">
          <a href="https://instagram.com/zaripetshop_pbg"><i class="fab fa-instagram"></i></a>
          <a href="https://www.facebook.com/profile.php?id=61575280174667"><i class="fab fa-facebook-square"></i></a>
          <a href="https://api.whatsapp.com/send/?phone=6287837148304&text&type=phone_number&app_absent=0"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

<script>
function filterBrand(brand) {
  // Remove active class from all buttons
  const buttons = document.querySelectorAll('.filter-btn');
  buttons.forEach(btn => btn.classList.remove('active'));
  
  // Add active class to clicked button
  event.target.classList.add('active');
  
  // Show/hide sections based on filter
  const sections = document.querySelectorAll('.brand-section');
  
  if (brand === 'all') {
    sections.forEach(section => {
      if (section.id !== 'tentang') {
        section.classList.remove('hidden');
      }
    });
  } else {
    sections.forEach(section => {
      if (section.id === brand + '-section') {
        section.classList.remove('hidden');
      } else if (section.id !== 'tentang') {
        section.classList.add('hidden');
      }
    });
  }
}

function beliProduk(productId) {
  // Redirect to payment page or handle purchase
  alert('Redirecting to payment for product: ' + productId);
  // window.location.href = 'pembayaran.php?product=' + productId;
}

// Initialize page - show all brands by default
document.addEventListener('DOMContentLoaded', function() {
  filterBrand('all');
});
</script>

</body>
</html>