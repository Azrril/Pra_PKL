<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location:login.php?AndaBelumLogin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Zari Petshop</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

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
            height: 100px;
            margin-right: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a, .dropbtn {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            top: 40px;
            z-index: 1;
        }

        .dropdown-content li a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .brand-container {
            overflow-x: auto;
            padding: 10px 0;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; /* IE & Edge */
            scrollbar-width: none; /* Firefox */
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .custom-navbar {
            background-color: #4CAF50 !important; /* warna hijau yang kamu mau */
          }

          .custom-navbar .nav-link,
          .custom-navbar .navbar-brand {
            color: white; /* biar tulisannya putih */
          }

          .custom-navbar .nav-link:hover,
          .custom-navbar .nav-link:focus {
            color: #c8e6c9; /* warna hover yang lebih muda */
          }

          .custom-navbar .dropdown-menu {
            background-color: #4CAF50; /* dropdown juga hijau */
          }

          .custom-navbar .dropdown-item {
            color: white;
          }

          .custom-navbar .dropdown-item:hover {
            background-color: #81c784;
            color: black;
          }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .brand-container {
            overflow-x: auto;
            padding: 10px 0;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; /* IE & Edge */
            scrollbar-width: none; /* Firefox */
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .brand-container::-webkit-scrollbar {
            display: none;
        }

        .brand-container a {
            flex: 0 0 auto;
            scroll-snap-align: start;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            padding: 0.5rem;
            background-color: white;
        }

        .brand-container a:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .brand-container a.clicked {
            transform: scale(0.95);
            box-shadow: none;
        }

        .brand-container img {
            height: 100px;
            object-fit: contain;
        }

        .hero {
            text-align: center;
        }

        .hero-image {
            width: 100%;
            height: auto;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #ff8000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }

        .produk {
            padding: 30px 20px;
            text-align: center;
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
<header>
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
          <a class="nav-link" href="#produk">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#tentang">Tentang</a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="pesanan.php">Pesanan</a>
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
</header>
<br>
<br>

<section class="hero">
    <img src="img/banner.png" alt="banner" class="hero-image" />
    <br>
    <br>
    <br>
    <h2><strong>WELCOME TO ZARI PETSHOP</strong></h2>
    <p><strong>Tersedia Lebih Dari 30 Produk Berkualitas Di Etalase Kami</strong></p>
    <a href="semua_produk.php" class="button">SHOP NOW</a>
</section>
<br>

<section id="produk" class="produk">
    <h3><strong>Produk</strong></h3>
    <div class="brand-container">
        <a href="produk_bolt.php"><img src="bolt/bolt-Logo.png" alt="Bolt" /></a>
        <a href="produk_hills.php"><img src="hills/hills-logo.png" alt="Hills" /></a>
        <a href="produk_friskies.php"><img src="frieskes/Friskies-Logo.png" alt="Friskies" /></a>
        <a href="produk-whiskas.php"><img src="whiskas/logo_whiskas.png" alt="Whiskas" /></a>
    </div>
</section>
<br>
<br>
<br>

<!-- FOOTER -->
<footer>
  <div>
    <div>
      <img src="img/logo_zari.png" alt="Zari Petshop Logo">
      <p>Zari Petshop bergerak dalam bidang retail petfood, aksesoris, kandang dan perlengkapan kebutuhan hewan kesayangan Anda.</p>
    </div>

    <div>
      <h3>Hubungi Kami</h3>
      <p>📱 +62 858-1614-2460</p>
      <p>📧 zaripetshop_official@gmail.com</p>
      <p>📍 Jl. Mayor Jend. Sungkono No.34, Selabaya, Kalimanah, Purbalingga</p>
    </div>

    <div>
      <h3>Social Media</h3>
      <div class="social-icons">
        <a href="https://instagram.com/zaripetshop_pbg"><i class="fab fa-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61575280174667"><i class="fab fa-facebook-square"></i></a>
        <a href="https://api.whatsapp.com/send/?phone=6287837148304"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

<script>
  // Simulasi: anggap user berhasil login
  const isLoggedIn = true; // Ganti sesuai kondisi sesungguhnya
  const userName = "profil"; // Atau ambil dari session, AJAX, dll

  if (isLoggedIn) {
    const loginBtn = document.getElementById("login-btn");
    if (loginBtn) {
      loginBtn.textContent = userName; // Ganti "Login" jadi nama user atau "Profile"
    }
  }
</script>

<script>
  document.querySelectorAll('.brand-container a').forEach(link => {
    link.addEventListener('click', () => {
      link.classList.add('clicked');
      setTimeout(() => link.classList.remove('clicked'), 300);
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

</body>
</html>
