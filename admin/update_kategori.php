<?php
include '../konfigurasi.php'; // Menyertakan file konfigurasi untuk koneksi database

// Proses jika tombol 'Update Category' diklik
if (isset($_POST['update_category'])) {
    $id = $_POST['id'];  // ID kategori yang akan diperbarui
    $name = $_POST['name'];
    $image = $_POST['image']; // Gambar baru (jika ada perubahan)
    $description = $_POST['description'];

    // Proses upload gambar jika ada perubahan gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Tentukan folder tempat gambar akan disimpan
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        // Cek apakah file gambar valid dan berhasil diupload
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "File gambar berhasil diupload.";
        } else {
            echo "Terjadi kesalahan saat mengupload gambar.";
        }
        // Jika gambar berhasil diupload, gunakan path baru
        $image = basename($_FILES["image"]["name"]);
    } else {
        // Jika gambar tidak diubah, gunakan gambar lama
        // Ambil gambar lama dari database
        $pdo = connectDB();
        $query = "SELECT image FROM categories WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $image = $category['image'];  // Gambar lama tetap digunakan
    }

    // Update data kategori di database
    try {
        $pdo = connectDB();
        $query = "UPDATE categories SET name = :name, image = :image, description = :description, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirect ke halaman manage_kategori.php setelah berhasil diperbarui
        header("Location: manage_kategori.php");
        exit;  // Pastikan exit setelah redirect
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    // Jika form pertama kali dibuka, ambil data kategori berdasarkan ID
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Ambil id kategori dari URL
        try {
            $pdo = connectDB();
            $query = "SELECT * FROM categories WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cek jika data kategori ditemukan
            if (!$category) {
                echo "Kategori tidak ditemukan!";
                exit;
            }
        } catch (PDOException $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        echo "ID kategori tidak ditemukan!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Update Kategori</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="manage_kategori.php" class="btn btn-secondary">Kembali</a>
        </div>

        <!-- Form untuk memperbarui kategori -->
        <div class="card">
            <div class="card-header">Form Update Kategori</div>
            <div class="card-body">
                <form method="POST" action="update_kategori.php" enctype="multipart/form-data">
                    <!-- Input ID Kategori -->
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

                    <!-- Nama Kategori -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                    </div>

                    <!-- Gambar -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar (upload gambar baru)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img src="../uploads/<?php echo htmlspecialchars($category['image']); ?>" alt="Gambar Kategori" class="img-thumbnail" width="150">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($category['description']); ?></textarea>
                    </div>

                    <!-- Tombol Update -->
                    <button type="submit" name="update_category" class="btn btn-primary">Perbarui Kategori</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
