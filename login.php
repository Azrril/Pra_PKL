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
    <style>
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

        body{
            margin: 0;
            padding: 0;
        }

        .container{
            display: flex;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
        
    <header>
        <div class="logo">
            <a href="login.php"><img src="img/logo zari.png" alt="Zari Petshop"></a>
            <h2>ZARI PETSHOP</h2>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php#produk">Produk</a></li>
                <li><a href="dashboard.php#layanan">Layanan</a></li>
                <li><a href="dashboard.php#kontak">Kontak</a></li>
                <li><a href="dashboard.php#tentang">Tentang</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">|

    <div class="login-box">
        <h1>Login</h1>
        <form action="proses_login.php" method="post">

            <label for="email">Username</label>
            <input type="text" name="username" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" id="password">

            <input type="submit" value="Login">
        </form>

        
        <a href="register.php">Register</a> <br>
        
    </div>

    </div>
</body>
</html>
