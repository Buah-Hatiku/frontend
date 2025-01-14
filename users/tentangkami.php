<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php'; // Gunakan include_once untuk menghindari redeklarasi

session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
}
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
              <br>Belanja Mudah dan Terpercaya untuk Kebutuhan Anda!f
            </h4>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->

  <!--================ Tentang Kami Area =================-->
  <section class="about_us_area section_gap">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h1 class="main_title">Tentang Kami</h1>
          <p>
            Selamat datang di Buahhatiku, platform jasa titip terpercaya yang didedikasikan untuk para ibu dan keluarga.
            Kami hadir untuk memenuhi dan mempermudah kebutuhan Anda dalam mendapatkan produk berkualitas dari berbagai tempat tanpa perlu repot.
          </p>
        </div>
        <div class="col-lg-6">
          <h1 class="main_title">Misi Kami</h1>
          <p>
            Membantu para ibu untuk mendapatkan produk terbaik bagi keluarga, dengan pengalaman belanja yang cepat, aman, dan transparan.
            Kepuasan pelanggan adalah prioritas kami.
          </p>
        </div>
      </div>
</section>
<section  class="row mt-5">
        <div class="col-lg-12 text-center">
          <h2 class="sub_title text-center">Kenapa Memilih Kami?</h2>
          <p>Kami berfokus pada kebutuhan ibu, anak, dan kecantikan, menawarkan produk yang relevan bagi keluarga,
            Pelayanan kami cepat, ramah, dan transparan tanpa biaya tersembunyi, memastikan nilai terbaik di setiap transaksi.
            Buahhatiku juga menjadi komunitas ibu dan wanita untuk berbagi pengalaman dan inspirasi, menjadikan kami lebih dari
            sekadar layanan jastip.</p>
        </div>
        <div class="col-lg-4 text-center">
          <img src="../img/produk.png" alt="Produk" class="img-fluid mb-3">
          <h3>Produk</h3>
          <p>
            Semua produk pilihan hadir dengan cermat untuk memenuhi kebutuhan Anda dan keluarga tanpa kekurangan.
          </p>
        </div>
        <div class="col-lg-4 text-center">
          <img src="../img/pelayanan.png" alt="Pelayanan" class="img-fluid mb-3">
          <h3>Pelayanan</h3>
          <p>
            Memberikan kemudahan dan kepuasan pelanggan dengan layanan cepat, aman, dan transparan dari awal hingga akhir.
          </p>
        </div>
        <div class="col-lg-4 text-center">
          <img src="../img/aman.png" alt="Aman & Terpercaya" class="img-fluid mb-3">
          <h3>Aman & Terpercaya</h3>
          <p>
            Menjamin keamanan dan kepercayaan dalam setiap transaksi dengan tingkat keamanan tinggi.
          </p>
        </div>
      </>
    </div>
  </section>
  <!--================ End Tentang Kami Area =================-->

  <!--================ Kontak Kami Area =================-->
  <section class="contact_us_area section_gap">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="main_title text-center">Kontak Kami</h1>
          <p class="text-center">
            Hubungi kami untuk informasi lebih lanjut:
          </p>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-4 text-center">
          <i class="fa fa-phone fa-2x mb-3"></i>
          <p>+62 851-2810-3281</p>
        </div>
        <div class="col-lg-4 text-center">
          <i class="fa fa-envelope fa-2x mb-3"></i>
          <p>buahhatiku@gmail.com</p>
        </div>
        <div class="col-lg-4 text-center">
          <i class="fa fa-map-marker fa-2x mb-3"></i>
          <p>Golden Street, The Ngasal Avenue, Jakarta, Indonesia</p>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12 text-center">
          <p>Follow us on:</p>
            <a href="https://www.instagram.com/buahatikuofficial?igsh=aHo4MGQxeG1xYXVj" target="_blank" class="mr-3">
              <img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="Instagram" style="width: 40px; height: 40px;">
            </a>
            <a href="https://www.tiktok.com/@buahatikuofficial?_t=ZS-8suRt2nZO2j&_r=1" target="_blank">
              <img src="https://cdn-icons-png.flaticon.com/512/3046/3046126.png" alt="TikTok" style="width: 40px; height: 40px;">
            </a>
        </div>
      </div>
    </div>
  </section>

  <?php include '../config/footer.php'; ?>

  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>

</html>
?>
