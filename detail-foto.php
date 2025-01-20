<?php
include "koneksi.php";

// Cek apakah ada ID foto yang diberikan
if (isset($_GET['id'])) {
    $fotoID = $_GET['id'];

    // Ambil detail foto dari database
    $query = "SELECT foto.*, album.NamaAlbum FROM foto 
              INNER JOIN album ON foto.AlbumID = album.AlbumID
              WHERE foto.FotoID = '$fotoID'";
    $result = mysqli_query($con, $query);
    $foto = mysqli_fetch_assoc($result);

    if (!$foto) {
        echo "Foto tidak ditemukan!";
        exit();
    }
} else {
    echo "ID foto tidak diberikan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #000;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header p {
            margin: 0;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .details {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Detail Foto</h1>
    </div>

    <div class="container">
        <img src="uploads/<?php echo htmlspecialchars($foto['LokasiFoto']); ?>" alt="<?php echo htmlspecialchars($foto['JudulFoto']); ?>">
        <h2><?php echo htmlspecialchars($foto['JudulFoto']); ?></h2>
        <p class="details"><?php echo nl2br(htmlspecialchars($foto['DeskripsiFoto'])); ?></p>
        <p class="details"><strong>Album:</strong> <?php echo htmlspecialchars($foto['NamaAlbum']); ?></p>
        <a href="javascript:history.back()" class="back-btn">Kembali</a>
    </div>

</body>
</html>
