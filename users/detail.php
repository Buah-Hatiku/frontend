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

// Mendapatkan ID produk dari URL
if (isset($_GET['id'])) {
  $productId = intval($_GET['id']); // Pastikan ID adalah integer
  $pdo = connectDB(); // Koneksi database
  $product = getProductById($pdo, $productId); // Ambil detail produk berdasarkan ID
} else {
  header('Location: index.php'); // Redirect ke halaman utama jika ID tidak ada
  exit;
}

if (!$product) {
  echo "Produk tidak ditemukan!";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="img/favicon.png" type="image/png" />
  <title>BuahHatiku</title>
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
  <!--================Home Banner Area =================-->
  <section class="home_banner_area mb-40">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div class="banner_content row">
          <div class="col-lg-12">
            <h3>Buahhatiku</h3>
            <h4>Solusi Terbaik untuk Jasa Titip Produk Ibu, Anak, dan Kecantikan
              <br>Belanja Mudah dan Terpercaya untuk Kebutuhan Anda!
            </h4>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->
  <div class="whatsapp-chat">
    <img src="../img/wa.png" alt="logo" class="whatsapp-logo">
    <a href="https://wa.me/+6285156570737" target="_blank">Chat dengan WhatsApp</a>
  </div>

  <!--================ Detail Produk Area =================-->
  <section class="product-detail-area section_gap">
    <div class="container">
      <div class="row">
        <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
          <div class="col-lg-6">
            <div class="product-image">
              <img class="img-fluid" src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="product-info">
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <h4>Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></h4>
              <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
              <div class="stock-info">
                <!-- Mengecek apakah stock ada sebelum menampilkan -->
                <p>Stok: <?php echo isset($product['stock']) ? htmlspecialchars($product['stock']) : 'Tidak tersedia'; ?> barang</p>
              </div>
              <form action="order_summary.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                <div class="quantity">
                  <label for="quantity">Quantity:</label>
                  <!-- Pastikan max sesuai dengan stock -->
                  <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo isset($product['stock']) ? htmlspecialchars($product['stock']) : 0; ?>">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Beli</button>
              </form>
            </div>
          </div>
        <?php else: ?>
          <div class="col-12">
            <div class="product-info">
              <p class="text-center text-danger">Produk ini tidak tersedia karena stok habis.</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!--================ End Detail Produk Area =================-->

  <?php include '../config/footer.php'; ?>
</body>

</html>
