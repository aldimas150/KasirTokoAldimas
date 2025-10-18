<?php
session_start();
// PERIKSA HAK AKSES
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Jika bukan admin, tendang ke dasbor
    header("Location: /kasir-toko/dashboard.php");
    exit();
}

require '../../config/database.php';
include '../../includes/header.php';

// Cek apakah request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $password_plain = $_POST['password'];
    $role = $_POST['role'];

    // Validasi dasar (misal: password tidak boleh kosong)
    if (empty($password_plain)) {
        die("Password tidak boleh kosong.");
    }

    // Amankan password dengan hashing
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    // Gunakan prepared statements untuk keamanan dari SQL Injection
    $sql = "INSERT INTO users (username, nama_lengkap, password, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameter ke statement
    mysqli_stmt_bind_param($stmt, "ssss", $username, $nama_lengkap, $password_hashed, $role);

    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect kembali ke halaman daftar pengguna
        header("Location: index.php?status=tambah_sukses");
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    // Jika bukan request POST, redirect ke halaman utama
    header("Location: index.php");
}
?>