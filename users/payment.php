<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php';

session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
}

// Fungsi untuk mendapatkan detail pengguna berdasarkan user_id
function getUserDetails($pdo, $userId)
{
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan detail produk berdasarkan ID
function getProductById($pdo, $productId)
{
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Validasi input dari halaman sebelumnya
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'], $_POST['total_price'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $totalPrice = floatval($_POST['total_price']);

    $pdo = connectDB(); // Koneksi database
    $user = getUserDetails($pdo, $_SESSION['user_id']); // Ambil detail pengguna
    $product = getProductById($pdo, $productId); // Ambil detail produk

    if (!$user || !$product) {
        echo "Data pengguna atau produk tidak ditemukan!";
        exit;
    }

    // Tangani nilai NULL pada kolom pengguna
    $userName = $user['full_name'] ?? 'Nama belum diatur';
    $userAddress = $user['address'] ?? 'Alamat belum diatur';
    $userPhone = $user['phone_number'] ?? 'Nomor telepon belum diatur';
} else {
    header('Location: order_summary.php'); // Redirect jika akses tidak valid
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .btn-upload {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            display: block;
            width: 100%;
            text-align: center;
        }
        .btn-upload:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include '../config/header.php'; ?>

    <div class="payment-container">
        <h3 class="text-center mb-4">Konfirmasi Pembayaran</h3>

        <!-- Alamat Pengiriman -->
        <div class="mb-3">
            <div class="section-title">Alamat Pengiriman</div>
            <p><?php echo htmlspecialchars($userName); ?> (<i><?php echo htmlspecialchars($userPhone); ?></i>)</p>
            <p><?php echo nl2br(htmlspecialchars($userAddress)); ?></p>
            <a href="edit_profile.php" class="text-primary">Ubah</a>
        </div>

        <!-- Detail Pengiriman -->
        <div class="mb-3">
            <div class="section-title">Pengiriman</div>
            <p>JNE</p>
        </div>

        <!-- Rincian Harga -->
        <div class="mb-3">
            <div class="section-title">Rincian Harga</div>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total Belanja</td>
                        <td class="text-end">Rp <?php echo number_format($product['price'] * $quantity, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td>Total Ongkos Kirim</td>
                        <td class="text-end">Rp 8.000</td>
                    </tr>
                    <tr>
                        <td>Biaya Penanganan</td>
                        <td class="text-end">Rp 1.500</td>
                    </tr>
                    <tr>
                        <td><strong>Total Pembayaran</strong></td>
                        <td class="text-end text-success"><strong>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Informasi Rekening -->
        <div class="mb-3">
            <div class="section-title">Transfer ke Rekening</div>
            <p>BSI A/N: 7207770005</p>
        </div>

        <!-- Form Upload Bukti Pembayaran -->
        <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
            <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
            <div class="mb-3">
                <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                <input type="file" class="form-control" id="payment_proof" name="payment_proof" required>
            </div>
            <button type="submit" class="btn-upload">Upload Bukti Pembayaran</button>
        </form>
    </div>

    <?php include '../config/footer.php'; ?>
</body>

</html>
