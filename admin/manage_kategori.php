<?php
session_start();
include '../konfigurasi.php';


// Fungsi untuk mengambil kategori
function getCategories($pdo)
{
    $query = "SELECT * FROM categories";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk menghapus kategori berdasarkan ID
function deleteCategory($pdo, $id)
{
    try {
        // Ambil nama gambar yang terkait dengan kategori yang akan dihapus
        $query = "SELECT image FROM categories WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            // Tentukan path gambar
            $imagePath = "../uploads/" . $category['image']; // Gambar disimpan di folder uploads di luar admin

            // Hapus gambar dari server jika file ada
            if (file_exists($imagePath)) {
                unlink($imagePath); // Menghapus file gambar
            }

            // Hapus kategori dari database
            $query = "DELETE FROM categories WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo "Kategori dan gambar berhasil dihapus!";
        } else {
            echo "Kategori tidak ditemukan.";
        }
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Proses jika tombol 'Tambah Kategori' diklik
if (isset($_POST['add_category'])) {
    // Ambil data dari form
    $name = $_POST['name'];
    $image = $_FILES['image']['name']; // Nama gambar
    $description = $_POST['description'];

    // Cek apakah nama kategori sudah ada
    $pdo = connectDB();
    $query = "SELECT COUNT(*) FROM categories WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Set pesan error menggunakan session
        $_SESSION['error_message'] = "Kategori dengan nama '$name' sudah ada. Gunakan nama lain.";
    } else {
        // Proses upload gambar
        $targetDir = "../uploads/"; // Tentukan folder uploads di luar admin
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Membuat folder jika belum ada
        }
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);

        // Cek apakah file adalah gambar dan ukuran file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            try {
                $query = "INSERT INTO categories (name, image, description) VALUES (:name, :image, :description)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':image', $image);
                $stmt->bindParam(':description', $description);
                $stmt->execute();

                // Redirect untuk mencegah form submit berulang
                header("Location: manage_kategori.php");
                exit();
            } catch (PDOException $e) {
                echo "Terjadi kesalahan: " . $e->getMessage();
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah gambar.";
        }
    }
}

// Proses jika tombol 'Delete' diklik
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $pdo = connectDB();  // Membuat koneksi ke database
    deleteCategory($pdo, $deleteId);  // Menghapus kategori
}

// Ambil semua kategori
$pdo = connectDB();
$categories = getCategories($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Manage Kategori</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <form action="index.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>


        <!-- Tampilkan pesan error jika ada -->
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
        }
        ?>

        <!-- Form Tambah Kategori -->
        <div class="card mb-4">
            <div class="card-header">Tambah Kategori Baru</div>
            <div class="card-body">
                <form method="POST" action="manage_kategori.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary">Tambah Kategori</button>
                </form>
            </div>
        </div>

        <!-- Tabel Kategori -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo $category['name']; ?></td>
                            <td><img src="../uploads/<?php echo $category['image']; ?>" alt="Gambar" class="img-thumbnail" width="100"></td>
                            <td><?php echo $category['description']; ?></td>
                            <td><?php echo $category['created_at']; ?></td>
                            <td><?php echo $category['updated_at']; ?></td>
                            <td>
                                <div class="d-flex">
                                    <!-- Tombol Update -->
                                    <form method="GET" action="update_kategori.php" class="me-2">
                                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">`
                                        <button type="submit" class="btn btn-warning btn-sm">Perbarui</button>
                                    </form>

                                    <!-- Tombol Delete -->
                                    <form method="GET" action="manage_kategori.php">
                                        <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus kategori ini?');">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>