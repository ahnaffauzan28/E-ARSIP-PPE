<?php
session_start();
include "config/koneksi.php";

@$pass = md5($_POST['password']);
@$username = mysqli_escape_string($koneksi, $_POST['username']);
@$password = mysqli_escape_string($koneksi, $pass);

$login = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
$data = mysqli_fetch_assoc($login);

if($data){
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role']; // pastikan kolom role ada

    if($data['role'] == 'admin'){
        header("location: admin.php");
        exit;
    } else if($data['role'] == 'karyawan'){
        header("location: employee.php");
        exit;
    }
} else {
    echo "<script>alert('Login gagal'); window.location='index.php';</script>";
}