<?php
$host = "localhost"; // Host database
$user = "root";      // Username database
$pass = "";          // Password database
$db   = "gallerydb_plusdummy"; // Nama database

// Koneksi ke database
$con = mysqli_connect($host, $user, $pass, $db);

// Periksa koneksi
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
