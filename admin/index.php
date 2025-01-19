<?php include 'sidebar.php'; ?>

<div class="main-panel">
  <div class="main-header">
    <div class="main-header-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
          <img
            src="assets/img/kaiadmin/logo_light.svg"
            alt="navbar brand"
            class="navbar-brand"
            height="20" />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
          </button>
          <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
          </button>
        </div>
        <button class="topbar-toggler more">
          <i class="gg-more-vertical-alt"></i>
        </button>
      </div>
      <!-- End Logo Header -->
    </div>
    <!-- Navbar Header -->
    <nav
      class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
      <div class="container-fluid">
        <nav
          class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
          <div class="input-group">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-search pe-1">
                <i class="fa fa-search search-icon"></i>
              </button>
            </div>
            <input
              type="text"
              placeholder="Search ..."
              class="form-control" />
          </div>
        </nav>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
          <li
            class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
            <a
              class="nav-link dropdown-toggle"
              data-bs-toggle="dropdown"
              href="#"
              role="button"
              aria-expanded="false"
              aria-haspopup="true">
              <i class="fa fa-search"></i>
            </a>
            <ul class="dropdown-menu dropdown-search animated fadeIn">
              <form class="navbar-left navbar-form nav-search">
                <div class="input-group">
                  <input
                    type="text"
                    placeholder="Search ..."
                    class="form-control" />
                </div>
              </form>
            </ul>
          </li>
          <li class="nav-item topbar-icon dropdown hidden-caret">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="messageDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              
            </a>
              
          <li class="nav-item topbar-user dropdown hidden-caret">
                <span class="op-7">Hi,</span>
                <span class="fw-bold">ADMIN MODE</span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-user animated fadeIn">
              <div class="dropdown-user-scroll scrollbar-outer">
                <li>
                  <div class="user-box">
                    <div class="avatar-lg">
                      <img
                        src="assets/img/profile.jpg"
                        alt="image profile"
                        class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                      <h4>Hizrian</h4>
                      <p class="text-muted">hello@example.com</p>
                      <a
                        href="profile.html"
                        class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">My Profile</a>
                  <a class="dropdown-item" href="#">My Balance</a>
                  <a class="dropdown-item" href="#">Inbox</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Account Setting</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Logout</a>
                </li>
              </div>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
  </div>

  <div class="container my-5">
    <h1 class="text-center mb-8">--------------------</h1>

    <?php
    // Database connection
    include '../konfigurasi.php';
    $pdo = connectDB();

    // Fetch data from payment_proofs
    $approvedOrders = $pdo->query("SELECT COUNT(*) FROM payment_proofs WHERE status = 'approved'")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM payment_proofs WHERE status = 'pending'")->fetchColumn();
    $canceledOrders = $pdo->query("SELECT COUNT(*) FROM payment_proofs WHERE status = 'canceled'")->fetchColumn();
    $totalSales = $pdo->query("SELECT SUM(total_price) FROM payment_proofs WHERE status = 'approved'")->fetchColumn();

    $dailySales = $pdo->query("SELECT SUM(total_price) FROM payment_proofs WHERE status = 'approved' AND DATE(created_at) = CURDATE()")->fetchColumn();

    $transactions = $pdo->query("SELECT * FROM payment_proofs WHERE status = 'approved'")->fetchAll(PDO::FETCH_ASSOC);

    $weeklySales = $pdo->query(
        "SELECT DATE(created_at) as date, SUM(total_price) as total FROM payment_proofs WHERE status = 'approved' AND created_at >= NOW() - INTERVAL 7 DAY GROUP BY DATE(created_at)"
    )->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="card-text"> <?= $pendingOrders ?? 0 ?> Pending</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Disetujui</h5>
                    <p class="card-text"> <?= $approvedOrders ?? 0 ?> Approved</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dibatalkan</h5>
                    <p class="card-text"> <?= $canceledOrders ?? 0 ?> Dibatalkan</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hasil Penjualan</h5>
                    <p class="card-text"> Rp<?= number_format($totalSales ?? 0, 2, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Penjualan Harian</h5>
            <p class="card-text"> Rp<?= number_format($dailySales ?? 0, 2, ',', '.') ?></p>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card mb-4">
        <div class="card-header">Riwayat Transaksi</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction['id'] ?></td>
                        <td><?= htmlspecialchars($transaction['user_id']) ?></td>
                        <td>Rp<?= number_format($transaction['total_price'], 2, ',', '.') ?></td>
                        <td><?= $transaction['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Weekly Sales Chart -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Penjualan Mingguan</h5>
            <canvas id="weeklySalesChart"></canvas>
        </div>
    </div>

    <script>
        const weeklySalesData = <?= json_encode($weeklySales) ?>;
        const labels = weeklySalesData.map(data => data.date);
        const sales = weeklySalesData.map(data => data.total);

        const ctx = document.getElementById('weeklySalesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: sales,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</div>
  
</div>

<!-- Custom template | don't include it in your project! -->
<div class="custom-template">
  <div class="title">Settings</div>
  <div class="custom-content">
    <div class="switcher">
      <div class="switch-block">
        <h4>Logo Header</h4>
        <div class="btnSwitch">
          <button
            type="button"
            class="selected changeLogoHeaderColor"
            data-color="dark"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="blue"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="purple"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="light-blue"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="green"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="orange"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="red"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="white"></button>
          <br />
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="dark2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="blue2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="purple2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="light-blue2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="green2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="orange2"></button>
          <button
            type="button"
            class="changeLogoHeaderColor"
            data-color="red2"></button>
        </div>
      </div>
      <div class="switch-block">
        <h4>Navbar Header</h4>
        <div class="btnSwitch">
          <button
            type="button"
            class="changeTopBarColor"
            data-color="dark"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="blue"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="purple"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="light-blue"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="green"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="orange"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="red"></button>
          <button
            type="button"
            class="selected changeTopBarColor"
            data-color="white"></button>
          <br />
          <button
            type="button"
            class="changeTopBarColor"
            data-color="dark2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="blue2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="purple2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="light-blue2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="green2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="orange2"></button>
          <button
            type="button"
            class="changeTopBarColor"
            data-color="red2"></button>
        </div>
      </div>
      <div class="switch-block">
        <h4>Sidebar</h4>
        <div class="btnSwitch">
          <button
            type="button"
            class="changeSideBarColor"
            data-color="white"></button>
          <button
            type="button"
            class="selected changeSideBarColor"
            data-color="dark"></button>
          <button
            type="button"
            class="changeSideBarColor"
            data-color="dark2"></button>
        </div>
      </div>
    </div>
  </div>
  <div class="custom-toggle">
    <i class="icon-settings"></i>
  </div>
</div>
<!-- End Custom template -->
</div>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Chart JS -->
<script src="assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="assets/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="assets/js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="assets/js/setting-demo.js"></script>
<script src="assets/js/demo.js"></script>
