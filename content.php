<?php
$halaman = $_GET['halaman'] ?? 'beranda';

if ($halaman == "departemen") {
    include "modul/departemen/departemen.php";
    //echo "Tampilan halaman departemen";
} elseif ($halaman == "pengirim_surat") {
    include "modul/pengirim_surat/pengirim_surat.php";
   //echo "Tampilan halaman pengirim";
} elseif ($halaman == "arsip_surat") {
     include "modul/arsip/arsip.php";
    //echo "Tampilan halaman arsip surat";
 } elseif ($halaman == "arsip_form") {
     include "modul/arsip/form.php";
    //echo "Tampilan halaman arsip surat";
} else {
    //echo "Tampilan halaman beranda";
    include "modul/home.php";
}

?>

<style>
    .content-wrapper {
        padding-top: 40px;   /* jarak dari header */
        padding-bottom: 40px; /* jarak ke footer */
        min-height: 70vh; /* agar konten tidak terlalu mepet ketika sedikit */
    }
</style>
