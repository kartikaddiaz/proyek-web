<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: index-foto.php");
    exit();
}
include "koneksi.php";

$fotoID = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['komentar']) && $fotoID) {
    $komentar = $_POST['komentar'];
    $userID = $_SESSION['UserID'];

    // Simpan komentar ke database
    $query = "INSERT INTO komentarfoto (FotoID, UserID, Komentar) VALUES ('$fotoID', '$userID', '$komentar')";
    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: detail.php?id=$fotoID"); // Refresh halaman setelah komentar ditambahkan
        exit();
    } else {
        $error = 'Gagal menambahkan komentar';
    }
}

$query = "SELECT 
            foto.FotoID, 
            foto.JudulFoto, 
            foto.DeskripsiFoto, 
            foto.LokasiFoto, 
            album.NamaAlbum, 
            (SELECT COUNT(*) FROM likefoto WHERE likefoto.FotoID = foto.FotoID) AS JumlahLike,
            (SELECT COUNT(*) FROM komentarfoto WHERE komentarfoto.FotoID = foto.FotoID) AS JumlahKomentar
          FROM foto
          INNER JOIN album ON foto.AlbumID = album.AlbumID
          WHERE foto.FotoID = '$fotoID'";

$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #000;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header p {
            margin: 0;
        }
        .header .actions {
            display: flex;
            gap: 10px;
        }
        .header .actions a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .header .actions a:hover {
            background-color: #0056b3;
        }
        .foto-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .foto-container img {
            max-width: 80%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .foto-info {
            margin-top: 20px;
            text-align: center;
        }
        .foto-info h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .foto-info p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .foto-info .actions {
            margin-top: 10px;
        }
        .foto-info .actions a {
            margin: 0 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .foto-info .actions a:hover {
            text-decoration: underline;
        }
        .comment-section {
            margin-top: 30px;
            width: 80%;
            max-width: 600px;
        }
        .comment-list {
            margin-top: 10px;
        }
        .comment-list p {
            margin: 5px 0;
            font-size: 16px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .comment-form button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .comment-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Selamat Datang, <?php echo htmlspecialchars($_SESSION['Username']); ?></p>
        <div class="actions">
            <a href="javascript:history.back()">Back</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="foto-container">
        <img src="uploads/<?php echo $row['LokasiFoto']; ?>" alt="<?php echo $row['JudulFoto']; ?>">
        <div class="foto-info">
            <h2><?php echo $row['JudulFoto']; ?></h2>
            <p><?php echo $row['DeskripsiFoto']; ?></p>
            <p><strong>Album:</strong> <?php echo $row['NamaAlbum']; ?></p>
            <p><strong>Like:</strong> <?php echo $row['JumlahLike']; ?> | <strong>Komentar:</strong> <?php echo $row['JumlahKomentar']; ?></p>
            <div class="actions">
                <a href="edit.php?id=<?php echo $row['FotoID']; ?>">Edit</a>
                <a href="delete.php?id=<?php echo $row['FotoID']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">Delete</a>
            </div>
        </div>
    </div>

    <div class="comment-section">
        <h3>Komentar</h3>
        <div class="comment-list">
            <?php
            $commentQuery = "SELECT Username, Komentar FROM komentarfoto JOIN users ON komentarfoto.UserID = users.UserID WHERE FotoID = '$fotoID' ORDER BY KomentarID DESC";
            $commentResult = mysqli_query($con, $commentQuery);
            while ($comment = mysqli_fetch_assoc($commentResult)) {
                echo "<p><strong>{$comment['Username']}</strong>: {$comment['Komentar']}</p>";
            }
            ?>
        </div>

        <div class="comment-form">
            <form method="POST">
                <textarea name="komentar" rows="4" placeholder="Tulis komentar..." required></textarea><br>
                <button type="submit">Kirim Komentar</button>
            </form>
            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        </div>
    </div>
</body>
</html>
