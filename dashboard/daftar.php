<?php
// Sertakan konfigurasi database
require_once '../konfigurasi.php';

// Inisialisasi pesan error dan success
$error_message = '';
$success_message = '';

// Proses form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error_message = "Username hanya boleh mengandung huruf, angka, dan underscore (_).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak sama.";
    } else {
        try {
            // Cek apakah username atau email sudah terdaftar
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user_count = $stmt->fetchColumn();

            if ($user_count > 0) {
                $error_message = "Username atau Email sudah terdaftar. Silakan gunakan yang lain.";
            } else {
                // Simpan data ke database tanpa hashing password
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (:username, :email, :password, :full_name, 'user')");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password); // Password disimpan langsung
                $stmt->bindParam(':full_name', $full_name);
                $stmt->execute();

                // Redirect ke login.php setelah berhasil
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            $error_message = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url('../img/loginbg.png') no-repeat center center fixed;
      background-size: cover;
    }

    .register-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-form {
      background: #fff;
      border-radius: 10px;
      padding: 20px 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .register-form h2 {
      color: #007bff;
      margin-bottom: 10px;
    }

    .register-form p {
      margin-bottom: 20px;
      font-size: 14px;
      color: #555;
    }

    .register-form input[type="text"],
    .register-form input[type="email"],
    .register-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .register-form button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .register-form button:hover {
      background-color: #0056b3;
    }

    .register-form a {
      text-decoration: none;
      color: #007bff;
      font-size: 14px;
    }

    .register-form a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="register-container">
    <div class="register-form">
        <h2>Daftar</h2>
        <p>Buat akun baru Anda!</p>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <form action="daftar.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="full_name" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah mempunyai akun? <a href="login.php">Masuk</a>.</p>
    </div>
</div>
</body>
</html>
