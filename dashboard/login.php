<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect sesuai role jika sudah login
    if ($_SESSION['role'] === 'admin') {
        header('Location: ../admin/index.php');
    } elseif ($_SESSION['role'] === 'user') {
        header('Location: ../users/index.php');
    }
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../konfigurasi.php'; // Pastikan file konfigurasi database Anda benar

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Menyimpan username di session
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect sesuai role
            if ($user['role'] === 'admin') {
                header('Location: ../admin/index.php');
            } elseif ($user['role'] === 'user') {
                header('Location: ../users/index.php');
            }
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    } catch (PDOException $e) {
        $error = 'Kesalahan: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url('../img/loginbg.png') no-repeat center center fixed;
      background-size: cover;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-form {
      background: #fff;
      border-radius: 10px;
      padding: 20px 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-form h2 {
      color: #007bff;
      margin-bottom: 10px;
    }

    .login-form p {
      margin-bottom: 20px;
      font-size: 14px;
      color: #555;
    }

    .login-form input[type="email"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .login-form button:hover {
      background-color: #0056b3;
    }

    .login-form .options {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      color: #007bff;
    }

    .login-form a {
      text-decoration: none;
      color: #007bff;
    }

    .login-form a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-form">
      <h2>Masuk</h2>
      <p>Selamat Datang!</p>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="options">

        </div>
        <button type="submit">Masuk</button>
      </form>
      <p>Belum mempunyai akun? <a href="daftar.php">Daftar</a>.</p>
    </div>
  </div>
</body>
</html>
