<?php
// Include konfigurasi database
require_once 'config.php';

try {
    // Query untuk mengambil data transaksi dengan informasi tambahan dari tabel users dan produk (jika ada relasi)
    $query = "
        SELECT 
            t.id, 
            u.username, 
            t.product_id, 
            t.status, 
            t.payment_proof, 
            t.quantity, 
            t.total_price, 
            t.created_at 
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center">Kelola Transaksi</h1>

        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Product ID</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaction['id']) ?></td>
                            <td><?= htmlspecialchars($transaction['username']) ?></td>
                            <td><?= htmlspecialchars($transaction['product_id']) ?></td>
                            <td><?= htmlspecialchars($transaction['status']) ?></td>
                            <td>
                                <?php if ($transaction['payment_proof']): ?>
                                    <a href="uploads/<?= htmlspecialchars($transaction['payment_proof']) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    Tidak ada
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($transaction['quantity']) ?></td>
                            <td>Rp <?= number_format($transaction['total_price'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                            <td>
                                <a href="edit_transaction.php?id=<?= $transaction['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_transaction.php?id=<?= $transaction['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
