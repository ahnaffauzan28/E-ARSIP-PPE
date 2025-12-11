<?php
session_start();
//hapus session yang sudad diset
unset($_SESSION['id_user']);
unset($_SESSION['username']);

session_destroy();
echo "<script>alert('Anda berhasil logout'); window.location='index.php';</script>";
?>