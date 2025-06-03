<?php
include '../koneksi.php';

// Ambil data pengguna dari tabel users
$query = "SELECT * FROM users ORDER BY id_user DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Data Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50;
        }

        table {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        thead {
            background-color: #4CAF50;
            color: white;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard.php" class="btn btn-secondary btn-back">← Kembali ke Dashboard</a>
    <h2>Data Pengguna</h2>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>ID User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $user['id_user'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['password'] ?></td>
                    <td>
                       
                        <a href="hapus_user.php?id_user=<?= $user['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
