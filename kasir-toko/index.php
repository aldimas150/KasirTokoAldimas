<?php
// Mulai sesi untuk memeriksa status login
session_start();

// Cek apakah pengguna sudah login (memiliki sesi 'username')
if (isset($_SESSION['username'])) {
    // Jika sudah login, arahkan ke halaman dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>