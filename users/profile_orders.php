<?php
// Include database configuration
require_once '../konfigurasi.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
  exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

try {
  // Get PDO connection
  $pdo = connectDB();

  // Fetch purchase history including status from payment_proofs
  $query = "SELECT pp.id AS transaksi_id, p.name AS nama_produk, pp.total_price AS harga, 
                     pp.created_at AS tanggal_pembelian, pp.status 
              FROM payment_proofs pp
              JOIN products p ON pp.product_id = p.id
              WHERE pp.user_id = :user_id
              ORDER BY pp.created_at DESC";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Terjadi kesalahan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="img/favicon.png" type="image/png" />
  <title>Buahhatiku - Riwayat Pembelian</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/bootstrap.css" />
  <link rel="stylesheet" href="../css/font-awesome.min.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <div class="container mt-5">
    <h2>Riwayat Pembelian</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($results): ?>
          <?php $counter = 1; ?>
          <?php foreach ($results as $row): ?>
            <tr>
              <td><?= $counter++ ?></td>
              <td><?= htmlspecialchars($row['nama_produk']) ?></td>
              <td>Rp<?= number_format($row['harga'], 2, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['tanggal_pembelian']) ?></td>
              <td>
                <?php 
                // Menentukan status berdasarkan status di database
                if ($row['status'] == 'approved') {
                  $statusLabel = 'Disetujui';
                  $badgeClass = 'bg-success';
                } elseif ($row['status'] == 'rejected') {
                  $statusLabel = 'Ditolak';
                  $badgeClass = 'bg-danger';
                } else {
                  $statusLabel = 'Diproses';
                  $badgeClass = 'bg-warning';
                }
                ?>
                <span class="badge <?= $badgeClass ?>" style="color: white;">
                  <?= $statusLabel ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">Tidak ada riwayat pembelian</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>
