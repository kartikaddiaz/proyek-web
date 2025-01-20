<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['UserID'])) {
    $fotoID = intval($_POST['FotoID']);
    $userID = $_SESSION['UserID'];
    $komentar = htmlspecialchars($_POST['komentar'], ENT_QUOTES);

    $query = "INSERT INTO komentarfoto (FotoID, UserID, Komentar) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iis", $fotoID, $userID, $komentar);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "username" => $_SESSION['Username'], "komentar" => $komentar]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}
?>
