<?php
session_start();
include 'koneksi.php';

if (isset($_POST['FotoID'])) {
    $fotoID = $_POST['FotoID'];
    $userID = $_SESSION['UserID'];

    // Cek apakah user sudah memberi like pada foto ini
    $checkLikeQuery = "SELECT * FROM likefoto WHERE FotoID = '$fotoID' AND UserID = '$userID'";
    $checkResult = mysqli_query($con, $checkLikeQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // User belum memberi like, jadi tambahkan like baru
        $insertLikeQuery = "INSERT INTO likefoto (FotoID, UserID) VALUES ('$fotoID', '$userID')";
        $insertResult = mysqli_query($con, $insertLikeQuery);

        if ($insertResult) {
            // Mengembalikan jumlah like terbaru
            $updateLikeCount = "SELECT COUNT(*) AS JumlahLike FROM likefoto WHERE FotoID = '$fotoID'";
            $likeCountResult = mysqli_query($con, $updateLikeCount);
            $likeCount = mysqli_fetch_assoc($likeCountResult);

            // Kirimkan jumlah like baru tanpa pesan apapun
            echo $likeCount['JumlahLike'];
        }
    }
}
