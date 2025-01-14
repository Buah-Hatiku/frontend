<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php';

session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $pdo = connectDB(); // Koneksi database
    $product = getProductById($pdo, $productId); // Ambil detail produk berdasarkan ID

    if (!$product) {
        echo "Produk tidak ditemukan!";
        exit;
    }

    if ($quantity < 1 || $quantity > $product['stock']) {
        echo "Jumlah pesanan tidak valid!";
        exit;
    }

    // Hitung total harga
    $totalPrice = $quantity * $product['price'] + 8000 + 1500; // Total harga + ongkos kirim + biaya penanganan
} else {
    header('Location: index.php'); // Redirect jika akses tidak valid
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>

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
</head>

<body>
    <?php include '../config/header.php'; ?>

    <div class="container mt-5">
        <h3 class="text-center mb-4">Pemesanan Anda</h3>
        <p class="text-center text-muted">Isi data Anda dan review pesanan Anda.</p>

        <!-- Order Summary -->
        <div class="card">
            <div class="card-body">
                <!-- Product Information -->
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100px; height: auto; border-radius: 5px;">
                        <div class="ms-3">
                            <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p>Harga Satuan: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    <div class="text-end">
                        <p>Jumlah: <?php echo $quantity; ?></p>
                        <p>Total Harga: <strong>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong></p>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="mt-3 p-3 bg-light">
                    <h6>Opsi Pengiriman</h6>
                    <p class="mb-0">Pengiriman Reguler</p>
                    <small>Estimasi tiba: 3-5 hari kerja</small>
                </div>

                <!-- Total Summary -->
                <div class="mt-3">
                    <h5 class="d-flex justify-content-between">
                        <span>Total Pesanan:</span>
                        <span class="text-primary">Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
                    </h5>
                    <small>Termasuk biaya pengiriman Rp 8.000 dan biaya penanganan Rp 1.500</small>
                </div>
            </div>
        </div>

        <!-- Continue Button -->
        <form action="payment.php" method="POST" class="mt-4">
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
            <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
            <button type="submit" class="btn btn-primary w-100">Lanjutkan Pembayaran</button>
        </form>
    </div>

    <?php include '../config/footer.php'; ?>
</body>

</html>
