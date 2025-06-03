<?php
include "koneksi.php";

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];


$sql = "insert into users(email,username,password) values ('$email','$username',md5('$password'))";
$query = mysqli_query($koneksi,$sql);

if ($query) {
    header("location:login.php?Tambah=S");
    exit;
} else {
    header("location:login.php?Tambah=G");
    exit;
}
?>