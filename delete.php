<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

include "koneksi.php";

if (isset($_GET['id'])) {
    $fotoID = intval($_GET['id']);

    // Ambil lokasi file untuk menghapus foto dari folder
    $query = "SELECT LokasiFoto FROM foto WHERE FotoID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $fotoID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $lokasiFoto);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($lokasiFoto && file_exists("uploads/$lokasiFoto")) {
        unlink("uploads/$lokasiFoto"); // Hapus file foto
    }

    // Hapus data dari database
    $query = "DELETE FROM foto WHERE FotoID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $fotoID);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($success) {
        $_SESSION['message'] = "Foto berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus foto.";
    }
} else {
    $_SESSION['message'] = "ID foto tidak ditemukan.";
}

header("Location: dashboard.php");
exit();
?>