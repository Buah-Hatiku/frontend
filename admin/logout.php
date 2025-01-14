<?php
session_start();
session_unset(); // Menghapus semua data session
session_destroy(); // Menghancurkan session
header("Location:../dashboard/index.php"); // Redirect ke halaman login setelah logout
exit();
?>