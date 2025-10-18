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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    $password_plain = $_POST['password'];

    // Cek apakah password diisi atau tidak
    if (!empty($password_plain)) {
        // Jika password diisi, hash password baru dan update ke database
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, nama_lengkap=?, password=?, role=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $nama_lengkap, $password_hashed, $role, $id);
    } else {
        // Jika password kosong, jangan update kolom password
        $sql = "UPDATE users SET username=?, nama_lengkap=?, role=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $nama_lengkap, $role, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?status=update_sukses");
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
}
?>