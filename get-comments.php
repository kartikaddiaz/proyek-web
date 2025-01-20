<?php
include 'koneksi.php';

if (isset($_GET['FotoID'])) {
    $fotoID = $_GET['FotoID'];

    // Ambil semua komentar dari foto
    $query = "SELECT users.Username, komentarfoto.Komentar FROM komentarfoto JOIN users ON komentarfoto.UserID = users.UserID WHERE FotoID = '$fotoID' ORDER BY KomentarID DESC";
    $result = mysqli_query($con, $query);
    
    $comments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }

    echo json_encode($comments);
}
?>
