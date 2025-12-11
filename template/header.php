<?php
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : '';

if(empty($_SESSION['id_user'])or empty($_SESSION['username'])){
     echo "<script>alert('Maaf, Untuk masuk gunakan akun atau hubungi admin'); window.location='index.php';</script>";
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Arsip | PPE</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet"> <!-- added custom styles -->
  </head>
  <body>

  
<!--awal nav untuk menu-->
<nav class="navbar navbar-expand-lg navbar-pkt sticky-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="?">
      <img src="assets/img/logo-pkt.png" alt="PKT" class="brand-logo me-2" onerror="this.style.display='none'">
      <span class="brand-title">E-ARSIP <small class="text-muted d-block">PPE</small></span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
    <a class="nav-link <?= $halaman == '' ? 'active':"" ?>" href="?">Beranda</a>
</li>

<li class="nav-item">
    <a class="nav-link <?= $halaman == 'departemen' ? 'active' : '' ?>" href="?halaman=departemen">Data Departemen</a>
</li>

<li class="nav-item">
    <a class="nav-link <?= $halaman == 'pengirim_surat' ? 'active' : '' ?>" href="?halaman=pengirim_surat">Data Pengirim Surat</a>
</li>

<li class="nav-item">
    <a class="nav-link <?= $halaman == 'arsip_surat' ? 'active' : '' ?>" href="?halaman=arsip_surat">Data Arsip Surat</a>
</li>
      </ul>

       

      <div class="dropdown">
        <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" href="#">
          <div class="avatar">PK</div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
         <li>
    <a class="dropdown-item text-danger" href="#" onclick="return konfirmasiLogout()">Logout</a>
</li>

<script>
function konfirmasiLogout() {
    if (confirm("Apakah Anda yakin ingin logout?")) {
        window.location.href = "logout.php";
    }
    return false; 
}
</script>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!--akhir nav untuk menu-->
<!--awal container-->
<div class="container">