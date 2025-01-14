<?php
session_start();
include '../konfigurasi.php';

// Fungsi untuk mengambil data transaksi
function getTransactions($pdo)
{
    $query = "SELECT t.*, u.username, p.name AS product_name 
              FROM transactions t
              JOIN users u ON t.user_id = u.id
              JOIN products p ON t.product_id = p.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengubah status transaksi
function updateTransactionStatus($pdo, $id, $status)
{
    try {
        $query = "UPDATE transactions SET status = :status WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Proses jika status diubah
if (isset($_POST['update_status'])) {
    $id = $_POST['transaction_id'];
    $status = $_POST['status'];
    $pdo = connectDB();
    updateTransactionStatus($pdo, $id, $status);
    header("Location: manage_transaksi.php");
    exit();
}

// Ambil data transaksi
$pdo = connectDB();
$transactions = getTransactions($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center">Kelola Transaksi</h1>
         <!-- Tombol Kembali -->
         <div class="mb-4">
            <form action="index.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Bukti Pembayaran</th>
                        <th>Dibuat Pada</th>
                        <th>Diperbarui Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= $transaction['id'] ?></td>
                            <td><?= htmlspecialchars($transaction['username']) ?></td>
                            <td><?= htmlspecialchars($transaction['product_name']) ?></td>
                            <td><?= $transaction['quantity'] ?></td>
                            <td>Rp<?= number_format($transaction['total_price'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($transaction['status']) ?></td>
                            <td>
                                <?php if ($transaction['payment_proof']): ?>
                                    <a href="../uploads/<?= htmlspecialchars($transaction['payment_proof']) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    Tidak Ada
                                <?php endif; ?>
                            </td>
                            <td><?= $transaction['created_at'] ?></td>
                            <td><?= $transaction['updated_at'] ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                                    <select name="status" class="form-select form-select-sm mb-2" required>
                                        <option value="menunggu persetujuan" <?= $transaction['status'] == 'menunggu persetujuan' ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                                        <option value="diproses" <?= $transaction['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $transaction['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="dibatalkan" <?= $transaction['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm">Perbarui</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
