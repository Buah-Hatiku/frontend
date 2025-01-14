<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php'; // Gunakan include_once untuk menghindari redeklarasi

session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
}

// Fungsi untuk mengambil kategori
function getCategories($pdo)
{
  $query = "SELECT * FROM categories";
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengambil produk
function getProducts($pdo)
{
  $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 6"; // Mengambil 6 produk terbaru
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Koneksi ke database
$pdo = connectDB();
$categories = getCategories($pdo);
$products = getProducts($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="img/favicon.png" type="image/png" />
  <title>Buahhatiku</title>
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
              <br>Belanja Mudah dan Terpercaya untuk Kebutuhan Anda!f </h4>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->

 <!-- Start feature Area -->
 <section class="feature-area section_gap_bottom_custom">
    <div class="container text-center">
      <div class="col-lg-12">
        <div class="main_title">
          <h2><span>Kategori</span></h2>
        </div>
        <div class="row">
          <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
              <div class="col-lg-4 col-md-6 mb-4">
                <div class="single-feature interactive-card">
                  <h3 class="feature-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                  <a href="#">
                    <img src="../uploads/<?php echo htmlspecialchars($category['image']); ?>"
                      alt="<?php echo htmlspecialchars($category['name']); ?>"
                      class="img-fluid feature-image">
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Tidak ada kategori yang tersedia.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- End feature Area -->

  <!--================ Inspired Product Area =================-->
<section class="inspired_product_area section_gap_bottom_custom">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="main_title">
          <h2><span>Rekomendasi Untuk Anda</span></h2>
        </div>
      </div>
    </div>

    <div class="row">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="single-product">
              <div class="product-img">
                <img class="img-fluid w-100" src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" />
                
                  <a href="detail.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                    <i class="ti-eye"></i>
                  </a>
                  
              </div>
              <div class="product-btm">
                <a href="detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="d-block">
                  <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                </a>
                <div class="mt-3">
                  <span class="mr-4">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                  <?php if (!empty($product['original_price'])): ?>
                    <del>Rp <?php echo number_format($product['original_price'], 0, ',', '.'); ?></del>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center w-100">Tidak ada produk yang tersedia.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<!--================ End Inspired Product Area =================-->

  
  
  <?php include '../config/footer.php'; ?>


</html>