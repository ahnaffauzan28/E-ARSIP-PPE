<?php
// Buat koneksi ke database
$server   = "localhost"; // nama server
$user     = "root";      // username database
$pass     = "";          // password database
$Database = "dbarsip";   // nama database

// Koneksi database
$koneksi = mysqli_connect($server, $user, $pass, $Database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>