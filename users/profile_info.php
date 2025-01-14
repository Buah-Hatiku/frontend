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
  <div class="container my-5">
    <h2>Informasi Akun</h2>
    <table class="table table-bordered">
      <tbody>
        <tr>
          <th>Username</th>
          <td><?= htmlspecialchars($user['username']) ?></td>
        </tr>
        <tr>
          <th>Nama Lengkap</th>
          <td><?= htmlspecialchars($user['full_name'] ?? 'Tidak diisi') ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <tr>
          <th>No. Telepon</th>
          <td><?= htmlspecialchars($user['phone_number'] ?? 'Tidak diisi') ?></td>
        </tr>
        <tr>
          <th>Alamat</th>
          <td><?= htmlspecialchars($user['address'] ?? 'Tidak diisi') ?></td>
        </tr>
      </tbody>
    </table>
    <!-- Tombol Edit -->
    <a href="edit_profile.php" class="btn btn-primary">Edit Profil</a>
  </div>
</body>

</html>
