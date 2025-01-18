<?php
// Menyertakan file konfigurasi untuk koneksi database
include_once '../konfigurasi.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, arahkan ke halaman login
    header('Location: ../dashboard/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_proofs'])) {
    // Ambil data dari form
    $userId = $_SESSION['user_id'];
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']); // Tambahkan quantity
    $totalPrice = intval($_POST['total_price']);

    // Validasi file upload
    $file = $_FILES['payment_proofs'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // Maksimal ukuran file 5MB
                $fileNewName = uniqid('', true) . '.' . $fileExt;
                $fileDestination = '../uploads/' . $fileNewName;

                // Pindahkan file ke folder uploads
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    try {
                        // Koneksi ke database
                        $pdo = connectDB();

                        // Query untuk menyimpan data pembayaran
                        $query = "INSERT INTO payment_proofs (user_id, product_id, quantity, payment_proof_file, total_price) 
                                  VALUES (:user_id, :product_id, :quantity, :payment_proof_file, :total_price)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                        $stmt->bindParam(':payment_proof_file', $fileNewName, PDO::PARAM_STR);
                        $stmt->bindParam(':total_price', $totalPrice, PDO::PARAM_INT);

                        $stmt->execute();

                        // Redirect ke halaman profile_order.php
                        header('Location: profile.php?page=orders');
                        exit;
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Gagal mengupload file.";
                }
            } else {
                echo "File terlalu besar. Maksimal ukuran file adalah 5MB.";
            }
        } else {
            echo "Terjadi kesalahan saat mengupload file.";
        }
    } else {
        echo "Format file tidak diperbolehkan. Hanya JPG, JPEG, PNG, atau PDF.";
    }
} else {
    echo "Tidak ada file yang diupload atau metode tidak valid.";
}
?>
