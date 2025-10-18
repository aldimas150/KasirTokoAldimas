<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config/database.php';
include 'includes/header.php';

// --- DATA UNTUK CARD ---
$jumlah_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM products"))['total'];
$jumlah_transaksi_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM sales WHERE DATE(tanggal_transaksi) = CURDATE()"))['total'];
$total_pendapatan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM sales WHERE DATE(tanggal_transaksi) = CURDATE()"))['total'] ?? 0;

// --- DATA UNTUK 5 TRANSAKSI TERAKHIR ---
$sql_recent = "SELECT s.kode_transaksi, s.total_bayar, u.nama_lengkap 
               FROM sales s JOIN users u ON s.id_user = u.id 
               ORDER BY s.tanggal_transaksi DESC LIMIT 5";
$recent_transactions = mysqli_query($conn, $sql_recent);

// --- DATA UNTUK CHART PENJUALAN 7 HARI TERAKHIR ---
$sales_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $sql_chart = "SELECT SUM(total_bayar) as total FROM sales WHERE DATE(tanggal_transaksi) = '$date'";
    $result_chart = mysqli_fetch_assoc(mysqli_query($conn, $sql_chart));
    $sales_data['labels'][] = date('d M', strtotime($date));
    $sales_data['data'][] = $result_chart['total'] ?? 0;
}
$sales_data_json = json_encode($sales_data);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card text-bg-primary shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Produk</h5>
                        <p class="card-text fs-1 fw-bold"><?php echo $jumlah_produk; ?></p>
                    </div>
                    <i class="fas fa-box card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card text-bg-success shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Transaksi Hari Ini</h5>
                        <p class="card-text fs-1 fw-bold"><?php echo $jumlah_transaksi_hari_ini; ?></p>
                    </div>
                    <i class="fas fa-cash-register card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card text-bg-warning shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Pendapatan Hari Ini</h5>
                        <p class="card-text fs-1 fw-bold">Rp <?php echo number_format($total_pendapatan_hari_ini); ?></p>
                    </div>
                    <i class="fas fa-dollar-sign card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Grafik Penjualan 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>5 Transaksi Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php while($row = mysqli_fetch_assoc($recent_transactions)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo $row['kode_transaksi']; ?></strong>
                                <small class="d-block text-muted">oleh <?php echo $row['nama_lengkap']; ?></small>
                            </div>
                            <span class="badge bg-primary rounded-pill">Rp <?php echo number_format($row['total_bayar']); ?></span>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = JSON.parse('<?php echo $sales_data_json; ?>');

    new Chart(ctx, {
        type: 'line', // Tipe chart: line, bar, pie, dll.
        data: {
            labels: salesData.labels,
            datasets: [{
                label: 'Pendapatan',
                data: salesData.data,
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>