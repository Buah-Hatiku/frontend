<?php
// manage_users.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buahhatiku";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Menghapus pengguna jika ada parameter 'delete' pada URL
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $deleteSql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);

    try {
        $stmt->execute([':id' => $user_id]);
        echo "User deleted successfully.<br>";
        header("Location: manage_akun.php"); // Redirect ke manage_akun setelah menghapus
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Insert a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi data formulir
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $phone_number = trim($_POST['phone_number']);
    $role = $_POST['role'];

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Hash password
    $password = password_hash($password, PASSWORD_BCRYPT);

    // Menyusun query untuk menambah pengguna baru
    $insertSql = "INSERT INTO users (username, email, password, full_name, address, phone_number, role, created_at, updated_at) 
                  VALUES (:username, :email, :password, :fullname, :address, :phone_number, :role, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $stmt = $pdo->prepare($insertSql);

    try {
        $stmt->execute([ 
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':fullname' => $fullname,
            ':address' => $address,
            ':phone_number' => $phone_number,
            ':role' => $role
        ]);
        
        // Redirect ke halaman manage_akun setelah berhasil menambah pengguna
        header('Location: manage_akun.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all users
$usersSql = "SELECT id, username, email, full_name, address, phone_number, role, created_at, updated_at FROM users";
$stmt = $pdo->query($usersSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Manage Akun</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <form action="index.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>
        
        <h2>Tambahkan Akun Baru</h2>
        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap:</label>
                <input type="text" id="fullname" name="fullname" class="form-control">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat:</label>
                <textarea id="address" name="address" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon:</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select id="role" name="role" class="form-select">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tambahkan Pengguna</button>
        </form>

        <h2>Daftar Pengguna</h2>
        <?php
        if ($stmt->rowCount() > 0) {
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['updated_at']}</td>
                        <td>
                            <a href='update_akun.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a> 
                            <a href='?delete=true&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
