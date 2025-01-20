<?php
session_start();
if (!isset($_SESSION['UserID']) || !is_numeric($_SESSION['UserID'])) {
    echo "UserID tidak valid.";
    exit();  // Hentikan proses jika UserID tidak valid
}
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = isset($_POST['judul']) ? mysqli_real_escape_string($con, $_POST['judul']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($con, $_POST['deskripsi']) : '';
    $tanggal = isset($_POST['tanggal']) && !empty($_POST['tanggal']) ? mysqli_real_escape_string($con, $_POST['tanggal']) : date('Y-m-d');
    $albumID = isset($_POST['album']) ? $_POST['album'] : '';
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;
    $userID = $_SESSION['UserID'];  // Pastikan UserID valid

    // Validasi input
    if (empty($judul) || empty($deskripsi) || empty($albumID) || !$foto) {
        echo "Semua field harus diisi.";
        exit();
    }

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($foto["name"]);

    if (move_uploaded_file($foto["tmp_name"], $targetFile)) {
        // Memastikan UserID adalah integer
        if (!is_numeric($userID)) {
            echo "UserID tidak valid.";
            exit();
        }

        // Jika lokasi tidak diisi, gunakan nilai default kosong
        $lokasiFoto = !empty($foto['name']) ? $foto['name'] : NULL;

        $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFoto, AlbumID, UserID) 
                  VALUES ('$judul', '$deskripsi', '$tanggal', '$lokasiFoto', '$albumID', '$userID')";

        if (mysqli_query($con, $query)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Terjadi kesalahan: " . mysqli_error($con);
        }
    } else {
        echo "Gagal mengupload foto.";
    }
}
