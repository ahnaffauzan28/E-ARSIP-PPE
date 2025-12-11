<?php session_start();

if (empty($_SESSION['id_user']) || empty($_SESSION['username'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location='index.php';</script>";
    exit;
}

// CEK ROLE
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Anda bukan admin'); window.location='employee.php';</script>";
    exit;
}?>

<?php include "config/koneksi.php"?>
<?php include "template/header.php"?>

<div class="main-content">
    <?php include "content.php"?>
</div>

<?php include "template/footer.php"?>