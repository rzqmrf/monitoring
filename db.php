<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "monitoring_toren";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// Tampilkan pesan hanya jika file ini diakses langsung
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    echo "Koneksi berhasil";
}
