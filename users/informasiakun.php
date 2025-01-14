<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="img/favicon.png" type="image/png" />
  <title>Buahhatiku</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.css" />
  <link rel="stylesheet" href="vendors/linericon/style.css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" />
  <link rel="stylesheet" href="css/themify-icons.css" />
  <link rel="stylesheet" href="css/flaticon.css" />
  <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css" />
  <link rel="stylesheet" href="vendors/lightbox/simpleLightbox.css" />
  <link rel="stylesheet" href="vendors/nice-select/css/nice-select.css" />
  <link rel="stylesheet" href="vendors/animate-css/animate.css" />
  <link rel="stylesheet" href="vendors/jquery-ui/jquery-ui.css" />
  <!-- main css -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/responsive.css" />
</head>

<body>
<?php include 'header2.php';?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar-box">
      <ul class="sidebar">
      <h2>Profile</h2>
        <li><a href="informasiakun.php" class="active">Informasi Akun</a></li>
        <li><a href="#">Riwayat Pembelian</a></li>
        <li><a href="#">Keluar dari Akun</a></li>
      </ul>
    </div>

    <!-- Formulir -->
    <div class="form-box">
      <h2>Informasi Akun</h2>
      <form action="update_profile.php" method="POST">
        <div class="form-group">
          <label for="nickname">Nama Panggilan</label>
          <input type="text" id="nickname" name="nickname" value="MJ" required>
        </div>
        <div class="form-group">
          <label for="fullname">Nama Lengkap</label>
          <input type="text" id="fullname" name="fullname" value="Test" required>
        </div>
        <div class="form-group">
          <label for="phone">No. Telepon</label>
          <input type="text" id="phone" name="phone" value="081234567810" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="alip@gmail.com" required>
        </div>
        <div class="form-group">
          <label for="address">Alamat</label>
          <input type="text" id="address" name="address" value="Taman Palem" required>
        </div>
        <div class="buttons">
          <button type="reset" class="cancel">Batal</button>
          <button type="submit" class="update">Perbarui</button>
        </div>
      </form>
    </div>
  </div>
</body>

<!--================ start footer Area  =================-->
<footer class="footer-area section_gap" style="background-color: #333; color: #fff; padding: 40px 0;">
    <div class="container">
      <div class="row">
        <!-- Payment Partners Section -->
        <div class="col-lg-4 col-md-6">
          <h4 style="color: #fff;">Payment Partners</h4>
          <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <img src="img/paymentparnters/bsi.png" style="width: 60px;">
          </div>
        </div>


  
        <!-- Contact Information Section -->
        <div class="col-lg-4 col-md-6">
          <h4 style="color: #fff;">Informasi Kontak</h4>
          <ul class="contact-info" style="list-style: none; padding: 0;">
            <li>
              <i class="fa fa-phone"></i>
              <span>Telephone: +62 851-5657-0737</span>
            </li>
            <li>
              <i class="fa fa-whatsapp"></i>
              <span>WhatsApp: +62 851-5657-0737</span>
            </li>
            <li>
              <i class="fa fa-envelope"></i>
              <span>E-mail: buahhatiku@gmail.com</span>
            </li>
            <li>
              <i class="fa fa-map-marker"></i>
              <span>Location: Jl. Mampang Prapatan IV, Jakarta Selatan</span>
            </li>
          </ul>
        </div>
        
  
        <!-- Quick Links Section -->
        <div class="col-lg-2 col-md-6">
          <h4 style="color: #fff;">Tautan Cepat</h4>
          <ul style="list-style: none; padding: 0;">
            <li><a href="index.html" style="color: #fff; text-decoration: none;">Beranda</a></li>
            <li><a href="barangfavorit.html" style="color: #fff; text-decoration: none;">Barang Favorit</a></li>
            <li><a href="contact.html" style="color: #fff; text-decoration: none;">Tentang Kami</a></li>
          </ul>
        </div>
  
        <!-- Social Media Section -->
        <div class="col-lg-2 col-md-6">
          <h4 style="color: #fff;">Follow us on</h4>
          <div style="display: flex; gap: 10px;">
            <a href="#" style="color: #fff; font-size: 24px;"><i class="fa fa-instagram"></i></a>
            <a href="#" style="color: #fff; font-size: 24px;"><i class="fa fa-facebook"></i></a>
            <a href="#" style="color: #fff; font-size: 24px;"><i class="fa fa-youtube"></i></a>
            <a href="#" style="color: #fff; font-size: 24px;"><i class="fa fa-twitter"></i></a>
          </div>
        </div>
      </div>
  
      <div class="row mt-4">
        <div class="col-12 text-center">
          <p style="margin: 0; color: #fff;">Copyright &copy; 2024 Buahhatiku. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>  
  <!--================ End footer Area  =================-->
</html>