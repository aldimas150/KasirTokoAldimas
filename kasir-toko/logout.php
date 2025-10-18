<?php
// 1. Mulai sesi terlebih dahulu
session_start();

// 2. Hapus semua variabel sesi
session_unset();

// 3. Hancurkan sesi secara total
session_destroy();

// 4. Arahkan pengguna kembali ke halaman login
header("Location: login.php?status=logout_sukses");
exit();
?>