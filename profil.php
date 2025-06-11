<<<<<<< HEAD
<?php
session_start();
include 'koneksi.php'; // koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Handle update password
if ($_POST && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $id_user = $_SESSION['id_user']; // Sesuaikan dengan session Anda
    
    // Validasi
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Password baru dan konfirmasi password tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $error_message = "Password baru minimal 6 karakter!";
    } else {
        // Cek password lama
        $check_query = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = '$id_user'");
        $user_data = mysqli_fetch_assoc($check_query);
        
        if (md5($current_password) !== $user_data['password']) {
            $error_message = "Password lama tidak benar!";
        } else {
            // Update password dengan MD5
            $new_password_md5 = md5($new_password);
            $update_query = mysqli_query($koneksi, "UPDATE users SET password = '$new_password_md5' WHERE id_user = '$id_user'");
            
            if ($update_query) {
                $success_message = "Password berhasil diubah!";
            } else {
                $error_message = "Gagal mengubah password. Silakan coba lagi.";
            }
        }
    }
}

// Ambil data user dari database
$id_user = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM users where id_user = $id_user"); // Sesuaikan query dengan kebutuhan
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
                  *{
      font-weight: bold;
    }
        body {
            background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0)), url('img/BG.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            background-color: rgb(250, 167, 33);
            color: white;
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .profile-image {
            width: 150px;
            height: 150px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            margin: 0 auto 20px;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-group label {
            font-weight: bold;
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .edit-btn {
            background-color: #00aaff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .edit-btn:hover {
            background-color: #008fcc;
            color: white;
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

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .modal-header {
            background-color: #4CAF50;
            color: white;
        }

        .btn-password {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }

        .btn-password:hover {
            background-color: #138496;
            color: white;
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
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#tentang">Tentang</a>
        </li>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<br>
<br>
<br>


    <div class="container">
        <div class="profile-container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="profile-image">
                        <img src="img/profil.png" alt="Profile Picture">
                    </div>
                </div>
                <div class="col-md-8">
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
                            <p>
                                ********
                                <button type="button" class="btn-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key"></i> Ubah Password
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="fas fa-key"></i> Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                            </div>
                            <div class="form-text">Password minimal 6 karakter</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update_password" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validasi password konfirmasi
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword !== newPassword && confirmPassword !== '') {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Tampilkan notifikasi jika ada
        <?php if (isset($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $success_message ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $error_message ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
=======
<?php
session_start();
include 'koneksi.php'; // koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Handle update password
if ($_POST && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $id_user = $_SESSION['id_user']; // Sesuaikan dengan session Anda
    
    // Validasi
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Password baru dan konfirmasi password tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $error_message = "Password baru minimal 6 karakter!";
    } else {
        // Cek password lama
        $check_query = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = '$id_user'");
        $user_data = mysqli_fetch_assoc($check_query);
        
        if (md5($current_password) !== $user_data['password']) {
            $error_message = "Password lama tidak benar!";
        } else {
            // Update password dengan MD5
            $new_password_md5 = md5($new_password);
            $update_query = mysqli_query($koneksi, "UPDATE users SET password = '$new_password_md5' WHERE id_user = '$id_user'");
            
            if ($update_query) {
                $success_message = "Password berhasil diubah!";
            } else {
                $error_message = "Gagal mengubah password. Silakan coba lagi.";
            }
        }
    }
}

// Ambil data user dari database
$id_user = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM users where id_user = $id_user"); // Sesuaikan query dengan kebutuhan
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
                  *{
      font-weight: bold;
    }
        body {
            background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0)), url('img/BG.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            background-color: rgb(250, 167, 33);
            color: white;
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .profile-image {
            width: 150px;
            height: 150px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            margin: 0 auto 20px;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-group label {
            font-weight: bold;
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .edit-btn {
            background-color: #00aaff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .edit-btn:hover {
            background-color: #008fcc;
            color: white;
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

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .modal-header {
            background-color: #4CAF50;
            color: white;
        }

        .btn-password {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }

        .btn-password:hover {
            background-color: #138496;
            color: white;
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
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#tentang">Tentang</a>
        </li>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<br>
<br>
<br>


    <div class="container">
        <div class="profile-container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="profile-image">
                        <img src="img/profil.png" alt="Profile Picture">
                    </div>
                </div>
                <div class="col-md-8">
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
                            <p>
                                ********
                                <button type="button" class="btn-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key"></i> Ubah Password
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="fas fa-key"></i> Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                            </div>
                            <div class="form-text">Password minimal 6 karakter</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <div class="password-toggle">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update_password" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validasi password konfirmasi
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword !== newPassword && confirmPassword !== '') {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Tampilkan notifikasi jika ada
        <?php if (isset($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $success_message ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $error_message ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
>>>>>>> b7516bf (mengubah file lama)
</html>