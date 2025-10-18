<?php
require '../../config/database.php';

// Cek apakah ID transaksi ada di URL
if (!isset($_GET['id'])) {
    echo "ID Transaksi tidak ditemukan.";
    exit();
}

$id_sale = $_GET['id'];

// --- Query untuk mengambil info utama transaksi ---
$sql_sale = "SELECT 
                s.kode_transaksi, 
                s.tanggal_transaksi,
                s.total_bayar,
                u.nama_lengkap as nama_kasir
             FROM sales s
             JOIN users u ON s.id_user = u.id
             WHERE s.id = $id_sale";
$result_sale = mysqli_query($conn, $sql_sale);
$sale = mysqli_fetch_assoc($result_sale);

// Cek jika transaksi tidak ditemukan
if (!$sale) {
    echo "Transaksi tidak ditemukan.";
    exit();
}

// --- Query untuk mengambil detail item transaksi ---
$sql_details = "SELECT
                   p.nama_produk,
                   sd.jumlah,
                   p.harga,
                   sd.subtotal
                FROM sale_details sd
                JOIN products p ON sd.id_product = p.id
                WHERE sd.id_sale = $id_sale";
$result_details = mysqli_query($conn, $sql_details);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struk Belanja - <?php echo $sale['kode_transaksi']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px; /* Lebar struk thermal printer */
            margin: 0 auto;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h3 {
            margin: 0;
        }
        .info {
            margin-bottom: 10px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table td {
            padding: 2px 0;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .total-section {
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h3>NAMA TOKO ANDA</h3>
            <p>Jalan Alamat Toko No. 123</p>
        </div>
        
        <div class="separator"></div>

        <div class="info">
            <div class="info-item">
                <span>No Struk:</span>
                <span><?php echo $sale['kode_transaksi']; ?></span>
            </div>
            <div class="info-item">
                <span>Tanggal:</span>
                <span><?php echo date("d/m/Y H:i", strtotime($sale['tanggal_transaksi'])); ?></span>
            </div>
             <div class="info-item">
                <span>Kasir:</span>
                <span><?php echo $sale['nama_kasir']; ?></span>
            </div>
        </div>

        <div class="separator"></div>

        <table class="items-table">
            <?php while($item = mysqli_fetch_assoc($result_details)): ?>
            <tr>
                <td colspan="3"><?php echo $item['nama_produk']; ?></td>
            </tr>
            <tr>
                <td><?php echo $item['jumlah']; ?> x</td>
                <td style="text-align: right;"><?php echo number_format($item['harga']); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['subtotal']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="separator"></div>

        <div class="total-section">
             <div class="info-item">
                <strong><span>TOTAL</span></strong>
                <strong><span>Rp <?php echo number_format($sale['total_bayar']); ?></span></strong>
            </div>
            </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>
    
    <div class="no-print">
        <button onclick="window.print()">Cetak Struk</button>
        <br><br>
        <a href="index.php">Kembali ke Halaman Kasir</a>
    </div>

</body>
</html>