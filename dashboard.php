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
    overflow-x: auto;
    gap: 10;
    padding: 10px 0;
    scroll-snap-type: x mandatory;
    -ms-overflow-style: none; /* IE & Edge */
    scrollbar-width: none; /* Firefox */
    display: flex;
    justify-content: center;
}

.brand-container::-webkit-scrollbar {
    display: flex;
    justify-content: center;
}

.brand-container a {
    flex: 0 0 auto;
    scroll-snap-align: start;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    padding: 0.5rem;
    background-color: white;
    margin: 0 20px;
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

    footer {
  width: 96.3vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
}


    </style>
</head>
<body>

    <header>
        <div class="logo">
            <img src="img/logo_zari.png" alt="Zari Petshop">
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
        <img src="img/banner.png" alt="banner" class="hero-image">
        <h2>Welcome to ZARI PETSHOP</h2>
        <p><strong>Tersedia Lebih Dari 30 Produk Berkualitas Di Etalase Kami</strong></p>
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
        
   
    <!-- Footer -->
<footer style="background-color: #4CAF50; padding: 20px 20px 20px 20px; font-family: Arial, sans-serif;">
  <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 30px;">

    <!-- Logo & Deskripsi -->
    <div style="flex: 1; min-width: 250px;">
      <img src="img/logo_zari.png" alt="Zari Petshop Logo" style="max-width: 200px; margin-bottom: 15px;">
      <p style="font-size: 16px; line-height: 1.6;">
        Zari Petshop bergerak dalam bidang retail petfood, aksesoris, kandang dan perlengkapan kebutuhan hewan kesayangan Anda.
      </p>
    </div>

    <!-- Hubungi Kami -->
    <div style="flex: 1.2; min-width: 250px;">
      <h3 style="color: #2d2b75; margin-bottom: 15px;">Hubungi Kami</h3>
      <p style="margin: 8px 0;">📱 +62 858-1614-2460</p>
      <p style="margin: 8px 0;">📧 zaripetshop_official@gmail.com</p>
      <p style="margin: 8px 0;">📍 Jl. Mayor Jend. Sungkono No.34, Selabaya, Kec. Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371</p>
    </div>

    <!-- Social Media -->
    <div style="flex: 0.8; min-width: 150px;">
      <h3 style="color: #2d2b75; margin-bottom: 15px;">Social Media</h3>
      <div style="font-size: 24px;">
        <a href="https://instagram.com/zaripetshop_pbg" style="color: black; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61575280174667" style="color: black; margin-right: 15px;"><i class="fab fa-facebook-square"></i></a>
        <a href="https://api.whatsapp.com/send/?phone=6287837148304&text&type=phone_number&app_absent=0" style="color: black;"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>

  </div>
</footer>

<!-- Tambahkan ini sebelum </body> -->
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

</body>
</html>
