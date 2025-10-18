<?php
require 'config/database.php';

$username = 'admin_aman';
$password_plain = 'admin123'; // Password yang akan kita gunakan
$nama_lengkap = 'Admin Secure';
$role = 'admin';

// --- INI BAGIAN PENTINGNYA ---
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password_hashed', '$nama_lengkap', '$role')";

if (mysqli_query($conn, $sql)) {
    echo "User admin aman berhasil dibuat!<br>";
    echo "Username: admin_aman<br>";
    echo "Password: admin123<br>";
    echo "Silakan hapus file ini setelah selesai.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>