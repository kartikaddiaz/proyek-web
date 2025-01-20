<?php
// Include file koneksi
include "koneksi.php";

// Ambil data dari form
$Username = mysqli_real_escape_string($con, $_POST['Username']);
$Password = md5($_POST['Password']); // Pastikan hashing sesuai dengan yang digunakan saat pendaftaran

// Query untuk memeriksa login
$query = "SELECT * FROM user WHERE Username = '$Username' AND Password = '$Password'";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($con));
}

// Periksa apakah pengguna ditemukan
if (mysqli_num_rows($result) == 1) {
    session_start();
    $row = mysqli_fetch_assoc($result);

    // Simpan data ke session
    $_SESSION['Username'] = $row['Username'];
    $_SESSION['UserID'] = $row['UserID'];

    // Redirect ke dashboard
    header("Location: index-foto.php");
    exit();
} else {
    // Jika login gagal, masih tetap set session untuk mengabaikan password yang salah
    session_start();
    $_SESSION['Username'] = $Username; // Set Username walaupun password salah
    $_SESSION['UserID'] = 'default'; // Atau bisa set ID yang tidak valid

    // Redirect ke dashboard meskipun username/password salah
    header("Location: index-foto.php");
    exit();
}
