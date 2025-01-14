<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
}

// Inisialisasi variabel
$product = [];
$quantity = 0;
$totalPrice = 0;
$user = [];

// Ambil data produk dan pengguna jika ada `product_id`
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    try {
        $pdo = connectDB();

        // Ambil data produk
        $query = "SELECT id, name, price FROM products WHERE id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ambil jumlah yang dikirim dari form
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

        // Ambil data pengguna berdasarkan user_id di session
        $userId = $_SESSION['user_id'];
        $userQuery = "SELECT username, address, phone_number FROM users WHERE id = :user_id";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $userStmt->execute();
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        // Hitung total harga jika produk ditemukan dan quantity valid
        if ($product && $quantity > 0) {
            $totalPrice = ($product['price'] * $quantity) + 8000 + 1500; // Harga + ongkir + biaya penanganan
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="stylesheet" href="../css/font-awesome.min.css" />
    <link rel="stylesheet" href="../css/themify-icons.css" />
    <link rel="stylesheet" href="../css/flaticon.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/responsive.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../vendors/linericon/style.css" />
    <link rel="stylesheet" href="../vendors/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="../vendors/lightbox/simpleLightbox.css" />
    <link rel="stylesheet" href="../vendors/nice-select/css/nice-select.css" />
    <link rel="stylesheet" href="../vendors/animate-css/animate.css" />
    <link rel="stylesheet" href="../vendors/jquery-ui/jquery-ui.css" />
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
            <p><?php echo htmlspecialchars($user['username']); ?> (<i><?php echo htmlspecialchars($user['phone_number']); ?></i>)</p>
            <p><?php echo nl2br(htmlspecialchars($user['address'])); ?></p>
            <a href="edit_profile.php" class="text-primary">Ubah</a>
        </div>

        <!-- Rincian Harga -->
        <div class="mb-3">
            <div class="section-title">Rincian Harga</div>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total Belanja</td>
                        <td class="text-end">Rp <?php echo isset($product['price']) ? number_format($product['price'] * $quantity, 0, ',', '.') : '0'; ?></td>
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
                        <td class="text-end text-success"><strong>Rp <?php echo number_format($totalPrice ?? 0, 0, ',', '.'); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Informasi Rekening -->
        <div class="mb-3">
            <div class="section-title">Transfer ke Rekening</div>
            <p>BSI A/N: 7207770005 A/N Miftahul Janah</p>
        </div>

        <!-- Form Upload Bukti Pembayaran -->
        <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
            <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">
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