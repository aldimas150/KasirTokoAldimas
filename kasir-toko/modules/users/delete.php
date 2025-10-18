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

$id_to_delete = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$logged_in_user_id = $_SESSION['user_id'];

// Keamanan: Pastikan ID valid dan pengguna tidak mencoba menghapus dirinya sendiri
if (!$id_to_delete) {
    die("ID tidak valid.");
}
if ($id_to_delete == $logged_in_user_id) {
    die("Error: Anda tidak dapat menghapus akun Anda sendiri.");
}

// Gunakan prepared statements untuk menghapus
$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_to_delete);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?status=hapus_sukses");
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>