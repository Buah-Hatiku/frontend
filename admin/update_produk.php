<?php
include '../konfigurasi.php'; // Menyertakan file konfigurasi untuk koneksi database

// Fungsi untuk mengambil data produk berdasarkan ID
function getProductById($pdo, $id) {
    $query = "SELECT p.*, c.name AS category_name FROM products p
              JOIN categories c ON p.category_id = c.id
              WHERE p.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk memperbarui produk
function updateProduct($pdo, $id, $name, $image, $category_id, $price, $quantity, $description) {
    try {
        if ($image) {
            $query = "UPDATE products SET name = :name, image = :image, category_id = :category_id, 
                      price = :price, quantity = :quantity, description = :description, updated_at = NOW() 
                      WHERE id = :id";
        } else {
            $query = "UPDATE products SET name = :name, category_id = :category_id, 
                      price = :price, quantity = :quantity, description = :description, updated_at = NOW() 
                      WHERE id = :id";
        }
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);

        if ($image) {
            $stmt->bindParam(':image', $image);
        }

        $stmt->execute();
        return true;  // Data berhasil diperbarui
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
        return false; // Jika terjadi kesalahan
    }
}

// Proses jika tombol 'Perbarui Produk' diklik
if (isset($_POST['update_product'])) {
    // Ambil data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $image = $_FILES['image']['name']; // Nama gambar
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Proses upload gambar
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Membuat folder jika belum ada
    }

    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    // Cek apakah ada gambar baru yang diupload
    if ($image && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $updated = updateProduct($pdo, $id, $name, $image, $category_id, $price, $quantity, $description);
    } else {
        $updated = updateProduct($pdo, $id, $name, null, $category_id, $price, $quantity, $description);
    }

    // Jika berhasil diperbarui, redirect ke manage_produk.php
    if ($updated) {
        header("Location: manage_produk.php");  // Redirect ke halaman manage_produk.php
        exit();  // Pastikan kode di bawahnya tidak dieksekusi setelah redirect
    }
}

// Ambil ID produk dari parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pdo = connectDB();
    $product = getProductById($pdo, $id);
} else {
    echo "Produk tidak ditemukan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Perbarui Produk</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="manage_produk.php" class="btn btn-secondary">Kembali ke Daftar Produk</a>
        </div>

        <!-- Form Perbarui Produk -->
        <div class="card">
            <div class="card-header">Form Perbarui Produk</div>
            <div class="card-body">
                <form method="POST" action="update_produk.php" enctype="multipart/form-data">
                    <!-- Input ID Produk -->
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <!-- Gambar -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk (upload gambar baru)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if (!empty($product['image'])): ?>
                        <div class="mt-2">
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Gambar Produk" class="img-thumbnail" width="150">
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <?php
                            $pdo = connectDB();
                            $query = "SELECT * FROM categories";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($categories as $category) {
                                $selected = ($category['id'] == $product['category_id']) ? "selected" : "";
                                echo "<option value='" . $category['id'] . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>

                    <!-- Kuantitas -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Kuantitas</label>
                        <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" name="update_product" class="btn btn-primary">Perbarui Produk</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

