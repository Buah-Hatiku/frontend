<!--================Header Menu Area =================-->
<header class="header_area">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light w-100">
            <!-- Brand and toggle get grouped for better mobile display -->
            <a class="navbar-brand logo_h" href="index.php">
                <img src="../img/logo.png" alt="" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse offset w-100" id="navbarSupportedContent">
                <div class="row w-100 mr-0">
                    <div class="col-lg-7 pr-0">
                        <ul class="nav navbar-nav center_nav pull-right">
                            <li
                                class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                                <a class="nav-link" href="index.php">Beranda</a>
                            </li>
                            <li
                                class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'barangfavorit.php' ? 'active' : ''; ?>">
                                <a class="nav-link" href="../users/barangfavorit.php">Barang Favorit</a>
                            </li>
                            <li
                                class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'tentangkami.php' ? 'active' : ''; ?>">
                                <a class="nav-link" href="tentangkami.php">Tentang Kami</a>
                            </li>
                    </div>

                    <?php
// Pastikan session sudah dimulai
                    ?>
                    <div class="col-lg-5 pr-0">
                        <ul class="nav navbar-nav navbar-right right_nav pull-right">
                            <?php
                            if (isset($_SESSION['user_id'])): // Cek jika pengguna sudah login
                                echo '<li class="nav-item dropdown">';
                                echo '<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); // Tampilkan nama pengguna
                                echo '</a>';
                                echo '<div class="dropdown-menu">';
                                echo '<a class="dropdown-item" href="../users/profile.php">Lihat Profil</a>';
                                echo '<a class="dropdown-item" href="../admin/logout.php">Logout</a>';
                                echo '</div>';
                                echo '</li>';
                            else:
                                echo '<li class="nav-item"><a href="login.php" class="nav-link">Masuk</a></li>';
                                echo '<li class="nav-item"><a href="daftar.php" class="nav-link">Daftar</a></li>';
                            endif;
                            ?>
                        </ul>
                    </div>

                </div>
            </div>
        </nav>
    </div>
    </div>
</header>