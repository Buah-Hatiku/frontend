<?php
// Mulai sesi untuk mendapatkan username yang sedang login
session_start();

// Masukkan header
include '../config/header.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../dashboard/login.php");
    exit();
}

// Koneksi ke database
require '../konfigurasi.php';

try {
    // Ambil data pengguna berdasarkan username di sesi
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<p class='text-danger'>User tidak ditemukan!</p>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="?page=info" class="list-group-item list-group-item-action <?= (!isset($_GET['page']) || $_GET['page'] == 'info') ? 'active' : '' ?>">Informasi Akun</a>
                <a href="?page=orders" class="list-group-item list-group-item-action <?= (isset($_GET['page']) && $_GET['page'] == 'orders') ? 'active' : '' ?>">Riwayat Pembelian</a>
                <a href="?page=logout" class="list-group-item list-group-item-action">Keluar Akun</a>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="col-md-9">
            <?php
            // Pilih halaman berdasarkan parameter `page`
            $page = isset($_GET['page']) ? $_GET['page'] : 'info';

            switch ($page) {
                case 'info':
                    include 'profile_info.php';
                    break;
                case 'orders':
                    include 'profile_orders.php';
                    break;
                case 'logout':
                    session_destroy();
                    header("Location: ../login.php");
                    break;
                default:
                    echo "<p class='text-danger'>Halaman tidak ditemukan!</p>";
            }
            ?>
        </div>
    </div>
</div>
<?php
// Masukkan footer
include '../config/footer.php';
?>
</html


