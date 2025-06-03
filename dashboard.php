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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zari Petshop</title>
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

        .profile-logo img{
            height: 20px;

            /* margin-right: 10px; */
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

        .produk, .layanan, .kontak, .tentang {
            padding: 30px 20px;
            text-align: center;
        }

        .brand-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .brand-container img {
            width: 100px;
        }

        .layanan-card {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .layanan-card img {
            width: 220px;
            border-radius: 10px;
        }

        .promo {
            margin: 30px auto;
            text-align: center;
        }

        .promo img {
            width: 340px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .kontak-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }

        .kontak-item, .kontak-map {
            max-width: 300px;
            text-align: left;
        }

        .kontak-item p {
            margin: 5px 0;
        }

        .tentang-content {
            background: linear-gradient(to right, #b2f0e7, #ffe3a3);
            padding: 30px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .tentang-content img {
            width: 700px;
            border-radius: 15px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #4CAF50;
            color: white;
        }

            ul {
    list-style: none;
    display: flex;
    gap: 20px;
    padding: 10px;
    }

    ul li {
    position: relative;
    }

    ul li a, .dropbtn {
    color: white;
    text-decoration: none;
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

    </style>
</head>
<body>

    <header>
        <div class="logo">
            <img src="img/logo zari.png" alt="Zari Petshop">
            <h2>ZARI PETSHOP</h2>
        </div>
        <nav>
            
          <ul>
  <li><a href="#produk">Produk</a></li>
  <li><a href="#layanan">Layanan</a></li>
  <li><a href="#kontak">Kontak</a></li>
  <li><a href="#tentang">Tentang</a></li>

  <!-- Dropdown Akun -->
  <li class="dropdown">
    <a href="#" class="dropbtn"><Button class="btn btn-prymary" id="login-btn">Login</Button><i class="bi bi-caret-down-fill"></i></a>
    <ul class="dropdown-content">
      <li><a href="login.php">Login</a></li>
      <li><a href="profil.php">Edit Profile</a></li>
      <li><a href="logout.php">Log Out</a></li>
    </ul>
  </li>
</ul>

        </nav>
    </header>

    <section class="hero">
        <img src="img/banner.jpg" alt="banner" class="hero-image">
        <h2>Welcome to ZARI PETSHOP</h2>
        <p>Tersedia diskon hingga <strong>20%</strong></p>
        <a href="produk_bolt.php" class="button">SHOP NOW</a>
    </section>

    <section id="produk" class="produk">
        <h3>Produk</h3>
        <div class="brand-container">
            <a href="produk_bolt.php"><img src="bolt/bolt-Logo.png" alt="Bolt"></a>
            <a href="produk_hills.php"><img src="hills/hills-logo.png" alt="Hills"></a>
            <a href="produk_friskies.php"><img src="frieskes/Friskies-Logo.png" alt="Friskies"></a>
            <a href="produk-whiskas.php"><img src="whiskas/logo_whiskas.png" alt="Whiskas"></a>
        </div>
    </section>

    <section id="layanan" class="layanan">
        <h3>Layanan</h3>
        <div class="layanan-card">
            <img src="bahan/1.png" alt="Layanan Grooming Kucing">
            <img src="bahan/2.png" alt="Layanan Inap Kucing">
            <img src="bahan/3.png" alt="Layanan Kesehatan Kucing">
        </div>
        <div class="promo">
            <img src="bahan/promo.png" alt="Promo Member Setia">
        </div>
    </section>

    <section id="kontak" class="kontak">
        <h3>Kontak</h3>
        <div class="kontak-content">
            <div class="kontak-item">
                <h4>Hubungi Kami
                <a href="https://api.whatsapp.com/send/?phone=6287837148304&text&type=phone_number&app_absent=0">📱 WhatsApp: 087837148304</a>
                <p><a href="https://api.whatsapp.com/send/?phone=6285816142460&text&type=phone_number&app_absent=0">☎️ Telp: 08561624280</a></p>
                <p><a href="https://mail.google.com/mail/?view=cm&fs=1&to=ahdzaahdza911@gmail.com">✉️ Email: zaripetshop@gmail.com</a></p>
                <p><a href="https://instagram.com/zaripetshop_pbg">📷 Instagram: zaripetshop_pbg</a></p>
                <p><a href="https://www.facebook.com/profile.php?id=61575280174667">📘 Facebook: Zari Petshop</a></p>
            </div>
            <div class="kontak-map">
                <h4>Visit Us!</h4>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d989.1475816443891!2d109.3393173!3d-7.3997151!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6559b9ff8d3795%3A0xa58daaef273f4e44!2sSMKN%201%20Purbalingga!5e0!3m2!1sen!2sid!4v1747127123588!5m2!1sen!2sid" width="350" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <section id="tentang" class="tentang">
        <h3>Tentang Website Kami</h3>
        <div class="tentang-content">
            <img src="bahan/tentang.png" alt="Kucing Ilustrasi">
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Zari Petshop. All Rights Reserved.</p>
    </footer>

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

</body>
</html>
