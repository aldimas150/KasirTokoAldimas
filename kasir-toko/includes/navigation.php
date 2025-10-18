<div class="sidebar sidebar-dark">
    <h3 class="text-white text-center mb-4">KasirApp</h3>
    <a href="/kasir-toko/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
    <a href="/kasir-toko/modules/products/index.php"><i class="fas fa-box me-2"></i>Produk</a>
    <a href="/kasir-toko/modules/sales/index.php"><i class="fas fa-cash-register me-2"></i>Kasir</a>
    <a href="/kasir-toko/modules/reports/daily.php"><i class="fas fa-chart-line me-2"></i>Laporan</a>
    <hr class="text-white">
    <div class="sidebar sidebar-dark">
    <h3 class="text-white text-center mb-4">KasirApp</h3>
    <a href="/kasir-toko/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
    <a href="/kasir-toko/modules/products/index.php"><i class="fas fa-box me-2"></i>Produk</a>
    <a href="/kasir-toko/modules/sales/index.php"><i class="fas fa-cash-register me-2"></i>Kasir</a>
    <a href="/kasir-toko/modules/reports/daily.php"><i class="fas fa-chart-line me-2"></i>Laporan</a>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
    <a href="/kasir-toko/modules/users/index.php"><i class="fas fa-users-cog me-2"></i>Manajemen Pengguna</a>
<?php endif; ?>

    <hr class="text-white">
    <a href="/kasir-toko/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>
    <a href="/kasir-toko/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>