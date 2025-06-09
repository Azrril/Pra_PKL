<?php
session_start();
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Zari Petshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        * {
            box-sizing: border-box;
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

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* full layar */
    position: relative;
    font-family: 'Segoe UI', sans-serif;
    z-index: 1;
}

.container::before {
    content: "";
    position: fixed; /* ini penting */
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: url('bahan/halaman login.png') no-repeat center center/cover;
    filter: brightness(0.6);
    z-index: -1;
}


        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 300px;
            text-align: center;

            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-box h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-box label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .login-box input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        .login-box input[type="submit"]:hover {
            background-color: #333;
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
          <a class="nav-link" href="dashboard.php">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="produk.php">Tentang</a>
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

    <div class="container">

    <div class="login-box">
        <h1>Login</h1>
        <form action="proses_login.php" method="post">

            <label for="email">Username</label>
            <input type="text" name="username" id="username">

            <label for="password">Password</label>
            <input type="password" name="password" id="password">

            <input type="submit" value="Login">
        </form>

        
        <a href="register.php">Register</a> <br>
        
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

</body>
</html>
