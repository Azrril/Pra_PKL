<?php
session_start();
include 'koneksi.php'; // koneksi ke database

// Cek apakah user sudah login


// Ambil data user dari database

$query = mysqli_query($koneksi, "SELECT * FROM users");
$user = mysqli_fetch_assoc($query);

// Gunakan foto default jika belum ada
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <style>
body {
  background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0)), url('img/BG.png');
  background-size: cover;         /* ⬅️ ini penting: agar gambar menutupi layar dengan proporsional */
  background-repeat: no-repeat;
  background-position: center;    /* ⬅️ agar posisi fokus di tengah */
  height: 100dvh;                  /* ⬅️ pastikan tingginya 1 layar penuh */
  margin: 0;
  overflow: hidden;
  font-family: Arial, sans-serif;
}


    .profile-container {
      background-color:rgb(250, 167, 33);
      color: white;
      width: 600px;
      margin: 50px auto;
      padding: 30px;
      display: flex;
      gap: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    .profile-image {
      width: 150px;
      height: 150px;
      background-color: white;
      border-radius: 5px;
      overflow: hidden;
    }

    .profile-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-info {
      flex: 1;
    }

    .info-group {
      margin-bottom: 15px;
    }

    .info-group label {
      font-weight: bold;
      display: block;
      font-size: 14px;
    }

    .info-group p {
      margin: 5px 0 0 0;
    }

    .edit-btn {
      background-color: #00aaff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
    }

    .edit-btn:hover {
      background-color: #008fcc;
    }
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
  </style>
</head>
<body>
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
  <div class="profile-container">
    <div class="profile-image">
        <img src="img/profil.png" alt="">
    </div>
    <div class="profile-info">
      <div class="info-group">
        <label>Name:</label>
        <p><?= htmlspecialchars($user['username']) ?></p>
      </div>
      <div class="info-group">
        <label>Email:</label>
        <p><?= htmlspecialchars($user['email']) ?></p>
      </div>
      <div class="info-group">
        <label>Password:</label>
        <p><?= htmlspecialchars($user['password']) ?></p>
      </div>
      <a href="edit-profile.php" class="edit-btn">Edit Profile</a>
    </div>
  </div>
</body>
</html>
