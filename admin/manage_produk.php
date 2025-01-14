<?php
include '../konfigurasi.php'; // Menyertakan file konfigurasi untuk koneksi database

// Fungsi untuk mengambil produk
function getProducts($pdo)
{
    $query = "SELECT p.*, c.name AS category_name FROM products p
              JOIN categories c ON p.category_id = c.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk menghapus produk berdasarkan ID
function deleteProduct($pdo, $id)
{
    try {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "Produk berhasil dihapus!";
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Proses jika tombol 'Tambah Produk' diklik
if (isset($_POST['add_product'])) {
    // Ambil data dari form
    $name = $_POST['name'];
    $image = $_FILES['image']['name']; // Nama gambar
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Proses upload gambar
    $targetDir = "../uploads/"; // Folder uploads di luar folder admin
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Membuat folder jika belum ada
    }
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    // Cek apakah file adalah gambar dan ukuran file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        try {
            $pdo = connectDB();
            $query = "INSERT INTO products (name, image, category_id, price, quantity, description) 
                    VALUES (:name, :image, :category_id, :price, :quantity, :description)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            echo "Produk berhasil ditambahkan!";

            // Redirect setelah berhasil menambahkan produk
            header("Location: manage_produk.php");
            exit();  // Pastikan proses berhenti setelah redirect
        } catch (PDOException $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah gambar.";
    }
}

// Proses jika tombol 'Delete' diklik
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    deleteProduct($pdo, $deleteId);
}

// Ambil semua produk
$pdo = connectDB();
$products = getProducts($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Manage Produk</h1>

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <form action="index.php" method="get">
                <button type="submit" class="btn btn-secondary">Kembali</button>
            </form>
        </div>

        <!-- Form Tambah Produk -->
        <div class="card mb-4">
            <div class="card-header">Tambah Produk Baru</div>
            <div class="card-body">
                <form method="POST" action="manage_produk.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                            <?php
                            // Query data kategori
                            $categories = $pdo->query("SELECT id, name FROM categories");

                            // Generate opsi dropdown
                            while ($category = $categories->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary">Tambah Produk</button>
                </form>
            </div>
        </div>

        <!-- Tabel Produk -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stock</th>
                        <th>Deskripsi</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td>
                                <img src="../uploads/<?php echo $product['image']; ?>" alt="Gambar" class="img-thumbnail" width="100">
                            </td>
                            <td><?php echo $product['category_name']; ?></td>
                            <td><?php echo $product['price']; ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td><?php echo $product['description']; ?></td>
                            <td><?php echo $product['created_at']; ?></td>
                            <td><?php echo $product['updated_at']; ?></td>
                            <td>
                                <div class="d-flex">
                                    <form method="GET" action="update_produk.php" class="me-2">
                                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Perbarui</button>
                                    </form>
                                    <form method="GET" action="manage_produk.php">
                                        <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus produk ini?');">Hapus</button>
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