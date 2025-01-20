<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: index-foto.php");
    exit();
}
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['komentar'])) {
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Foto</title>
    <!-- Menambahkan link untuk font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
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
            color: black;
            background-color:rgb(255, 255, 255);
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header .actions a:hover {
            background-color:rgb(0, 0, 0);
        }

        h3 {
            color: #111;
            text-align: center;
        }

        .album-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .album-item {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        .album-item img {
            width: 100%;
            height: auto;
            display: block;
            filter: grayscale(100%);
            transition: filter 0.3s;
        }

        .album-item img:hover {
            filter: none;
        }

        .album-item .info {
            padding: 15px;
        }

        .album-item .info h4 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #111;
        }

        .album-item .info p {
            font-size: 14px;
            color: #555;
        }

        .album-item .actions {
            margin-top: 10px;
        }

        .album-item .actions a {
            text-decoration: none;
            color:rgb(0, 0, 0);
            margin: 0 10px;
        }

        .album-item .actions a:hover {
            text-decoration: underline;
        }

        button,
        input,
        select,
        textarea {
            font-family: 'Poppins', sans-serif;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form button {
            background-color:rgb(0, 0, 0);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #00cc7a;
        }

        hr {
            margin: 20px 0;
            border: 0;
            height: 1px;
            background-color: #ccc;
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

    <h3>Unggah Foto Baru</h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="judul">Judul Foto:</label>
        <input type="text" id="judul" name="judul" placeholder="Masukkan judul foto" required><br>
        <label for="deskripsi">Deskripsi Foto:</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi foto" required></textarea><br>
        <label for="foto">Upload Foto:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required><br>
        <label for="album">Album:</label>
        <select name="album" id="album" required>
            <?php
            $query = "SELECT * FROM album";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['AlbumID']}'>{$row['NamaAlbum']}</option>";
            }
            ?>
        </select><br>
        <button type="submit">Submit</button>
    </form>
    <hr>
    <h3>Foto yang Telah Diupload</h3>
    <div class="album-container">
        <?php
        $query = "SELECT 
                foto.FotoID, 
                foto.JudulFoto, 
                foto.DeskripsiFoto, 
                foto.LokasiFoto, 
                album.NamaAlbum, 
                (SELECT COUNT(*) FROM likefoto WHERE likefoto.FotoID = foto.FotoID) AS JumlahLike,
                (SELECT COUNT(*) FROM komentarfoto WHERE komentarfoto.FotoID = foto.FotoID) AS JumlahKomentar
            FROM foto
            INNER JOIN album ON foto.AlbumID = album.AlbumID";

        $result = mysqli_query($con, $query);

        if (!$result) {
            die("Query Error: " . mysqli_error($con));
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='album-item'>
        <a href='viewfoto.php?id={$row['FotoID']}' target='_blank'>
            <img src='uploads/{$row['LokasiFoto']}' alt='{$row['JudulFoto']}'>
        </a>
        <div class='info'>
            <h4>{$row['JudulFoto']}</h4>
            <p>{$row['DeskripsiFoto']}</p>
            <small>Album: {$row['NamaAlbum']}</small>
            <div class='actions'>
                <button class='like-button' data-foto-id='{$row['FotoID']}'>👍 Like ({$row['JumlahLike']})</button>
                <button onclick='showComments({$row['FotoID']})'>💬 Komentar ({$row['JumlahKomentar']})</button>
                <a href='edit.php?id={$row['FotoID']}'>Edit</a>
                <a href='delete.php?id={$row['FotoID']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus foto ini?\")'>Delete</a>
            </div>
            <div class='comments' id='comments-{$row['FotoID']}' style='display:none; margin-top: 10px;' >
                <strong>Komentar:</strong>
                <div id='comment-list-{$row['FotoID']}'></div>
                <form action='' method='POST' style='margin-top:10px;' onsubmit='addComment({$row['FotoID']}, event)'>
                    <input type='hidden' name='FotoID' value='{$row['FotoID']}'>
                    <textarea name='komentar' rows='2' placeholder='Tulis komentar...' required></textarea>
                    <button type='submit'>Kirim</button>
                </form>
            </div>
        </div>
    </div>";
        }
        ?>
    </div>

    <script>
        // Menangani tombol Like
        document.addEventListener("DOMContentLoaded", function() {
    const likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fotoID = button.getAttribute('data-foto-id');

            fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `FotoID=${fotoID}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = `👍 Like (${data.jumlahLike})`;
                } else {
                    alert('Gagal menambahkan like');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

function showComments(fotoID) {
    const commentsDiv = document.getElementById(`comments-${fotoID}`);
    commentsDiv.style.display = (commentsDiv.style.display === 'none') ? 'block' : 'none';
}

function addComment(fotoID, event) {
    event.preventDefault();
    const komentarInput = event.target.querySelector('textarea');
    const komentar = komentarInput.value;

    fetch('komentar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `FotoID=${fotoID}&komentar=${komentar}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Gagal menambahkan komentar');
        } else {
            const commentList = document.getElementById(`comment-list-${fotoID}`);
            const newComment = document.createElement('div');
            newComment.innerHTML = `<strong>${data.Username}</strong>: ${data.Komentar}`;
            commentList.appendChild(newComment);

            komentarInput.value = '';
        }
    })
    .catch(error => console.error('Error:', error));
}
    </script>
</body>
</html>
