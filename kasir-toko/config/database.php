<?php
$host = "localhost";
$user = "root";
$pass = ""; // Sesuaikan dengan password database Anda
$db   = "db_kasir_toko";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>