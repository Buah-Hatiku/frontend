<?php
session_start();
include '../konfigurasi.php';
require_once('../TCPDF-main/tcpdf.php');

// Fungsi untuk mengambil laporan berdasarkan periode dan tanggal/bulan yang dipilih
function getSalesReport($pdo, $periode, $tanggal = null, $bulan = null, $minggu = null, $tahun = null)
{
    $whereClause = '';

    if ($periode == 'hari') {
        if ($tanggal) {
            $whereClause = "pp.created_at BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59'";
        } else {
            $whereClause = "pp.created_at = CURDATE()";
        }
    } elseif ($periode == 'minggu') {
        if ($minggu) {
            $weekStartDate = date('Y-m-d', strtotime($minggu . '-1'));
            $whereClause = "YEAR(pp.created_at) = YEAR('$weekStartDate') AND WEEK(pp.created_at, 1) = WEEK('$weekStartDate', 1)";
        } else {
            $whereClause = "pp.created_at >= CURDATE() - INTERVAL 1 WEEK";
        }
    } elseif ($periode == 'bulan') {
        if ($bulan) {
            $whereClause = "MONTH(pp.created_at) = MONTH('$bulan-01') AND YEAR(pp.created_at) = YEAR('$bulan-01')";
        } else {
            $whereClause = "pp.created_at >= CURDATE() - INTERVAL 1 MONTH";
        }
    } elseif ($periode == 'tahun') {
        if ($tahun) {
            $whereClause = "YEAR(pp.created_at) = $tahun";
        } else {
            $whereClause = "pp.created_at >= CURDATE() - INTERVAL 1 YEAR";
        }
    }

    try {
        // Query data transaksi
        $query = "SELECT pp.*, p.name AS nama_produk 
                  FROM payment_proofs pp
                  JOIN products p ON pp.product_id = p.id
                  WHERE $whereClause";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query statistik
        $queryStats = "SELECT 
                          SUM(pp.total_price) AS total_penjualan,
                          COUNT(pp.id) AS jumlah_transaksi,
                          AVG(pp.total_price) AS rata_rata_harga
                       FROM payment_proofs pp
                       WHERE $whereClause";
        $stmtStats = $pdo->prepare($queryStats);
        $stmtStats->execute();
        $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

        return [
            'reportData' => $reportData,
            'stats' => $stats
        ];
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Proses jika tombol 'Unduh Laporan' diklik
if (isset($_POST['download_report'])) {
    $periode = $_POST['periode'];
    $tanggal = $_POST['tanggal'] ?? null;
    $bulan = $_POST['bulan'] ?? null;
    $minggu = $_POST['minggu'] ?? null;
    $tahun = $_POST['tahun'] ?? null;
    $pdo = connectDB();
    $reportData = getSalesReport($pdo, $periode, $tanggal, $bulan, $minggu, $tahun);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Judul laporan
    $pdf->Cell(0, 10, 'Laporan Penjualan ' . ucfirst($periode), 0, 1, 'C');
    $pdf->Ln(10);

    // Tambahkan Statistik
    $pdf->Cell(0, 10, 'Statistik:', 0, 1);
    $pdf->Cell(40, 10, 'Total Penjualan: Rp ' . number_format($reportData['stats']['total_penjualan'], 2, ',', '.'), 0, 1);
    $pdf->Cell(40, 10, 'Jumlah Transaksi: ' . $reportData['stats']['jumlah_transaksi'], 0, 1);
    $pdf->Cell(40, 10, 'Rata-rata Harga: Rp ' . number_format($reportData['stats']['rata_rata_harga'], 2, ',', '.'), 0, 1);
    $pdf->Ln(10);

    // Tampilkan periode yang dipilih
    $pdf->Cell(0, 10, 'Periode: ' . ucfirst($periode), 0, 1);
    if ($periode == 'hari' && $tanggal) {
        $pdf->Cell(0, 10, 'Tanggal: ' . date('d-m-Y', strtotime($tanggal)), 0, 1);
    } elseif (($periode == 'bulan' || $periode == 'tahun') && $bulan) {
        $pdf->Cell(0, 10, 'Bulan/Tahun: ' . date('F Y', strtotime($bulan . '-01')), 0, 1);
    } elseif ($periode == 'minggu' && $minggu) {
        $pdf->Cell(0, 10, 'Minggu: ' . $minggu, 0, 1);
    }
    $pdf->Ln(10);

    // Tabel Header
    $pdf->Cell(40, 10, 'Nama Produk', 1);
    $pdf->Cell(40, 10, 'Harga', 1);
    $pdf->Cell(40, 10, 'Tanggal Pembelian', 1);
    $pdf->Cell(40, 10, 'Status', 1);
    $pdf->Ln();

    // Tabel Data
    foreach ($reportData['reportData'] as $row) {
        $status = $row['status'] === 'approved' ? 'Disetujui' : ($row['status'] === 'process' ? 'Proses' : ($row['status'] === 'rejected' ? 'Ditolak' : ucfirst($row['status'])));
        $pdf->Cell(40, 10, $row['nama_produk'], 1);
        $pdf->Cell(40, 10, 'Rp ' . number_format($row['total_price'], 2, ',', '.'), 1);
        $pdf->Cell(40, 10, date('d-m-Y', strtotime($row['created_at'])), 1);
        $pdf->Cell(40, 10, $status, 1);
        $pdf->Ln();
    }

    // Output file PDF
    $pdf->Output('laporan_penjualan_' . $periode . '.pdf', 'D');
    exit();
}

// Ambil data laporan berdasarkan periode default
$periode = isset($_POST['periode']) ? $_POST['periode'] : 'hari';
$tanggal = $_POST['tanggal'] ?? null;
$bulan = $_POST['bulan'] ?? null;
$minggu = $_POST['minggu'] ?? null;
$tahun = $_POST['tahun'] ?? null;
$pdo = connectDB();
$reportData = getSalesReport($pdo, $periode, $tanggal, $bulan, $minggu, $tahun);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Manage Laporan Penjualan</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <form action="index.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>

        <!-- Form Pilih Periode -->
        <form method="POST" action="manage_laporan.php">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="periode" class="form-label">Pilih Periode</label>
                    <select name="periode" id="periode" class="form-control">
                        <option value="hari" <?= ($periode == 'hari') ? 'selected' : '' ?>>Perhari</option>
                        <option value="minggu" <?= ($periode == 'minggu') ? 'selected' : '' ?>>Perminggu</option>
                        <option value="bulan" <?= ($periode == 'bulan') ? 'selected' : '' ?>>Perbulan</option>
                        <option value="tahun" <?= ($periode == 'tahun') ? 'selected' : '' ?>>Pertahun</option>
                    </select>
                </div>
                <div class="col-md-6" id="tanggal-container" style="display: <?= ($periode == 'hari') ? 'block' : 'none' ?>;">
                    <label for="tanggal" class="form-label">Pilih Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $tanggal ?>">
                </div>
                <div class="col-md-6" id="minggu-container" style="display: <?= ($periode == 'minggu') ? 'block' : 'none' ?>;">
                    <label for="minggu" class="form-label">Pilih Minggu</label>
                    <input type="week" name="minggu" id="minggu" class="form-control" value="<?= $minggu ?? '' ?>">
                </div>
                <div class="col-md-6" id="tahun-container" style="display: <?= ($periode == 'tahun') ? 'block' : 'none' ?>;">
                    <label for="tahun" class="form-label">Pilih Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="<?= $tahun ?? '' ?>" min="2000" max="2100">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="tampilkan_laporan">Tampilkan Laporan</button>
            <button type="submit" class="btn btn-success" name="download_report" value="pdf">Unduh Laporan (PDF)</button>
        </form>

        <!-- Tabel Laporan -->
        <?php if (isset($_POST['tampilkan_laporan']) || isset($_POST['download_report'])): ?>
            <h3 class="my-4">Data Laporan</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Tanggal Pembelian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportData['reportData'])): ?>
                        <?php foreach ($reportData['reportData'] as $row): ?>
                            <tr>
                                <td><?= $row['nama_produk'] ?></td>
                                <td>Rp <?= number_format($row['total_price'], 2, ',', '.') ?></td>
                                <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <?php 
                                    switch ($row['status']) {
                                        case 'approved':
                                            echo 'Disetujui';
                                            break;
                                        case 'process':
                                            echo 'Proses';
                                            break;
                                        case 'rejected':
                                            echo 'Ditolak';
                                            break;
                                        default:
                                            echo ucfirst($row['status']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data yang ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <script>
        document.getElementById('periode').addEventListener('change', function() {
            const periode = this.value;
            document.getElementById('tanggal-container').style.display = periode === 'hari' ? 'block' : 'none';
            document.getElementById('minggu-container').style.display = periode === 'minggu' ? 'block' : 'none';
            document.getElementById('tahun-container').style.display = periode === 'tahun' ? 'block' : 'none';
        });
    </script>
</body>

</html>
