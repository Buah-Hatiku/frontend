<?php
session_start();
include '../konfigurasi.php';

// Fungsi untuk mengambil data pembayaran
function getPaymentProofs($pdo)
{
    $query = "SELECT pp.id, u.username, p.name AS product_name, pp.payment_proof_file, pp.total_price, pp.status, pp.created_at, pp.updated_at
              FROM payment_proofs pp
              JOIN users u ON pp.user_id = u.id
              JOIN products p ON pp.product_id = p.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengubah status pembayaran
function updatePaymentProofStatus($pdo, $id, $status)
{
    try {
        // Pastikan status valid
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            throw new Exception('Status tidak valid');
        }

        $query = "UPDATE payment_proofs SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Proses jika status diubah
$message = '';
if (isset($_POST['update_status'])) {
    $id = intval($_POST['transaction_id']);
    $status = htmlspecialchars($_POST['status']);
    $pdo = connectDB();
    if (updatePaymentProofStatus($pdo, $id, $status)) {
        $message = "Status pembayaran berhasil diperbarui!";
    } else {
        $message = "Gagal memperbarui status pembayaran.";
    }
}

// Ambil data pembayaran
$pdo = connectDB();
$paymentProofs = getPaymentProofs($pdo);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Kelola Transaksi</h1>

        <!-- Pesan Feedback -->
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, 'berhasil') !== false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>

        <!-- Tabel Pembayaran -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Produk</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Bukti Pembayaran</th>
                        <th>Dibuat Pada</th>
                        <th>Diperbarui Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paymentProofs as $paymentProof): ?>
                        <tr>
                            <td><?= $paymentProof['id'] ?></td>
                            <td><?= htmlspecialchars($paymentProof['username']) ?></td>
                            <td><?= htmlspecialchars($paymentProof['product_name']) ?></td>
                            <td>Rp<?= number_format($paymentProof['total_price'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($paymentProof['status']) ?></td>
                            <td>
                                <?php if ($paymentProof['payment_proof_file']): ?>
                                    <a href="../uploads/<?= htmlspecialchars($paymentProof['payment_proof_file']) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    Tidak Ada
                                <?php endif; ?>
                            </td>
                            <td><?= $paymentProof['created_at'] ?></td>
                            <td><?= $paymentProof['updated_at'] ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="transaction_id" value="<?= $paymentProof['id'] ?>">
                                    <select name="status" class="form-select form-select-sm mb-2" required>
                                        <option value="pending" <?= $paymentProof['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $paymentProof['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $paymentProof['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
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
