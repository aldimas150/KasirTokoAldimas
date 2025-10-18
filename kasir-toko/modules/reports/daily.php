<?php
require '../../config/database.php';
include '../../includes/header.php';

// Ambil tanggal hari ini
$today = date("Y-m-d");

// Query SQL untuk mengambil data penjualan hari ini
$sql = "SELECT 
            s.kode_transaksi, 
            s.tanggal_transaksi,
            p.nama_produk,
            sd.jumlah,
            sd.subtotal,
            u.nama_lengkap as nama_kasir
        FROM sales s
        JOIN sale_details sd ON s.id = sd.id_sale
        JOIN products p ON sd.id_product = p.id
        JOIN users u ON s.id_user = u.id
        WHERE DATE(s.tanggal_transaksi) = '$today'
        ORDER BY s.tanggal_transaksi DESC";

$result = mysqli_query($conn, $sql);
$total_pendapatan = 0;
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Laporan Penjualan Harian (<?php echo date("d F Y"); ?>)
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal (Rp)</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo date("H:i:s", strtotime($row['tanggal_transaksi'])); ?></td>
                                    <td><?php echo $row['kode_transaksi']; ?></td>
                                    <td><?php echo $row['nama_produk']; ?></td>
                                    <td class="text-center"><?php echo $row['jumlah']; ?></td>
                                    <td class="text-end"><?php echo number_format($row['subtotal']); ?></td>
                                    <td><?php echo $row['nama_kasir']; ?></td>
                                </tr>
                                <?php $total_pendapatan += $row['subtotal']; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada transaksi hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-success mt-4 text-end" role="alert">
                <h4 class="alert-heading fw-bold mb-0">
                    Total Pendapatan Hari Ini: Rp <?php echo number_format($total_pendapatan); ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>