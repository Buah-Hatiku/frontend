<?php
session_start();
include '../konfigurasi.php';

// Cek apakah pengguna yang login adalah admin
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");  // Redirect jika bukan admin
    exit();
}

// Fungsi untuk mengambil data pengguna berdasarkan ID
function getUserById($pdo, $id)
{
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk memperbarui data pengguna
function updateUser($pdo, $id, $username, $email, $full_name, $address, $phone_number, $role, $password = null)
{
    try {
        // Jika password diupdate, gunakan password baru
        if ($password) {
            $query = "UPDATE users SET username = :username, email = :email, full_name = :full_name, address = :address, phone_number = :phone_number, role = :role, password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        } else {
            // Jika password tidak diupdate, tetap menggunakan password lama
            $query = "UPDATE users SET username = :username, email = :email, full_name = :full_name, address = :address, phone_number = :phone_number, role = :role, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        }
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Bind password jika ada
        if ($password) {
            $stmt->bindParam(':password', $password);
        }

        $stmt->execute();

        echo "Akun berhasil diperbarui!";
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pdo = connectDB();
    $user = getUserById($pdo, $id);

    // Cek jika pengguna tidak ditemukan
    if (!$user) {
        echo "Pengguna tidak ditemukan.";
        exit();
    }
}

// Proses jika tombol 'Update' diklik
if (isset($_POST['update_account'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $password = $_POST['password']; // Ambil password dari form

    // Jika password diisi, kita hash password
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash password jika diubah
    } else {
        $password = null; // Biarkan null jika password tidak diubah
    }

    // Update data akun
    updateUser($pdo, $id, $username, $email, $full_name, $address, $phone_number, $role, $password);

    // Redirect untuk mencegah form submit berulang
    header("Location: manage_akun.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Edit Akun</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <form action="manage_akun.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>

        <!-- Form Edit Akun -->
        <div class="card mb-4">
            <div class="card-header">Edit Data Akun</div>
            <div class="card-body">
                <form method="POST" action="update_akun.php?id=<?php echo $user['id']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo $user['full_name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control"><?php echo $user['address']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" value="<?php echo $user['phone_number']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin" <?php echo ($user['role'] == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="user" <?php echo ($user['role'] == 'user' ? 'selected' : ''); ?>>User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>
                    <button type="submit" name="update_account" class="btn btn-primary">Update Akun</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
