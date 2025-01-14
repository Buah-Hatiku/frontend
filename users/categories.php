<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php';

// Koneksi ke database
$pdo = connectDB();

// Ambil ID kategori dari URL
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Ambil nama kategori
$query = "SELECT name FROM Categories WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo "Kategori tidak ditemukan.";
    exit;
}

// Ambil produk berdasarkan kategori
$query = "SELECT * FROM products WHERE category_id = :category_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['category_id' => $category_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Kategori: <?php echo htmlspecialchars($category['name']); ?></title>
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
<?php include '../config/header.php'; ?>
<body>

<!--================Home Banner Area =================-->
<section class="home_banner_area mb-40">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content row">
                    <div class="col-lg-12">

                        <h3>Buahhatiku</h3>
                        <h4>Solusi Terbaik untuk Jasa Titip Produk Ibu, Anak, dan Kecantikan
                            <br>Belanja Mudah dan Terpercaya untuk Kebutuhan Anda!f
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Home Banner Area =================-->

  <div class="container mt-5">
    <h2>Produk Kategori: <?php echo htmlspecialchars($category['name']); ?></h2>
    <div class="row">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="single-product">
              <div class="product-img">
                <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="img-fluid">
                <a href="detail.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                  <i class="ti-eye"></i>
                </a>
              </div>
              <div class="product-btm">
                <a href="detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="d-block">
                  <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                </a>
                <div class="mt-3">
                  <span>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Tidak ada produk untuk kategori ini.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
<?php include '../config/footer.php'; ?>

</html>
