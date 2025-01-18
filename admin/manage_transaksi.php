<?php
session_start();
include '../konfigurasi.php';

// Fungsi untuk mengambil data pembayaran
function getPaymentProofs($pdo)
{
    $query = "SELECT pp.id, u.username, p.name AS product_name, pp.quantity AS ordered_quantity, pp.payment_proof_file, pp.total_price, pp.status, pp.created_at, pp.updated_at
              FROM payment_proofs pp
              JOIN users u ON pp.user_id = u.id
              JOIN products p ON pp.product_id = p.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengubah status pembayaran dan mengurangi stok produk jika disetujui
function updatePaymentProofStatus($pdo, $id, $status)
{
    try {
        // Validasi status
        if (!in_array($status, ['proses', 'approved', 'rejected'])) {
            throw new Exception('Status tidak valid.');
        }

        // Ambil data pembayaran berdasarkan ID
        $query = "SELECT product_id, quantity FROM payment_proofs WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $paymentProof = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paymentProof) {
            throw new Exception('Data pembayaran tidak ditemukan.');
        }

        // Perbarui status pembayaran
        $query = "UPDATE payment_proofs SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Kurangi stok produk jika status disetujui
        if ($status === 'approved') {
            $query = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id AND stock >= :quantity";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':product_id', $paymentProof['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $paymentProof['quantity'], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('Stok produk tidak mencukupi.');
            }
        }

        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

// Proses jika status diubah
if (isset($_POST['update_status'])) {
    try {
        // Validasi ID
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            throw new Exception('ID tidak ditemukan.');
        }

        $id = intval($_POST['id']);
        $status = htmlspecialchars($_POST['status']);

        if (updatePaymentProofStatus($pdo, $id, $status)) {
            $_SESSION['message'] = "Status pembayaran berhasil diperbarui!";
        } else {
            throw new Exception('Gagal memperbarui status pembayaran.');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header('Location: manage_transaksi.php');
    exit;
}

// Ambil data pembayaran untuk ditampilkan di halaman
$paymentProofs = getPaymentProofs($pdo);

// Atur pesan berdasarkan sesi jika ada
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

// Hapus sesi setelah digunakan
unset($_SESSION['message']);
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Kelola Pembayaran</h1>

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
                        <th>Jumlah Pesanan</th>
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
                            <td><?= $paymentProof['ordered_quantity'] ?></td>
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
                                <form method="POST" action="manage_transaksi.php">
                                    <input type="hidden" name="id" value="<?= $paymentProof['id'] ?>">
                                    <select name="status" class="form-select">
                                        <option value="proses" <?= $paymentProof['status'] === 'proses' ? 'selected' : '' ?>>Proses</option>
                                        <option value="approved" <?= $paymentProof['status'] === 'approved' ? 'selected' : '' ?>>Disetujui</option>
                                        <option value="rejected" <?= $paymentProof['status'] === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Perbarui</button>
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
