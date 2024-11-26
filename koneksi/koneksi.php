<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_laundry-transaksi";

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>