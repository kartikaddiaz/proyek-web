<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fotoID = $_POST['FotoID'];
    $komentar = $_POST['komentar'];
    $userID = $_SESSION['UserID'];

    // Simpan komentar ke database
    $query = "INSERT INTO komentarfoto (FotoID, UserID, Komentar) VALUES ('$fotoID', '$userID', '$komentar')";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        // Ambil data komentar yang baru ditambahkan
        $query = "SELECT Username, Komentar FROM komentarfoto JOIN users ON komentarfoto.UserID = users.UserID WHERE FotoID = '$fotoID' ORDER BY KomentarID DESC LIMIT 1";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Gagal menambahkan komentar']);
    }
}
?>