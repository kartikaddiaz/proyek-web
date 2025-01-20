<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

include "koneksi.php";

// Ambil data foto berdasarkan ID
if (isset($_GET['id'])) {
    $fotoID = intval($_GET['id']);
    $query = "SELECT * FROM foto WHERE FotoID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $fotoID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $foto = mysqli_fetch_assoc($result);
    
    if (!$foto) {
        $_SESSION['message'] = "Foto tidak ditemukan.";
        header("Location: dashboard.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = "ID foto tidak ditemukan.";
    header("Location: dashboard.php");
    exit();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $album = intval($_POST['album']);
    
    // Handle file upload jika ada
    $lokasiFotoBaru = $foto['LokasiFoto']; // Default ke foto lama
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['foto']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Validasi tipe file
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Upload file baru
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $lokasiFotoBaru = $fileName; // Update lokasi file baru
            } else {
                $_SESSION['message'] = "Gagal mengunggah foto baru.";
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
            header("Location: dashboard.php");
            exit();
        }
    }
    
    // Update data ke database
    $query = "UPDATE foto SET JudulFoto = ?, DeskripsiFoto = ?, AlbumID = ?, LokasiFoto = ? WHERE FotoID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sssisi", $judul, $deskripsi, $album, $lokasiFotoBaru, $fotoID);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Foto berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Gagal memperbarui data foto.";
    }
    mysqli_stmt_close($stmt);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Global styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
            max-width: 100%;
        }

        h3 {
            color: #fff;
            font-size: 24px;
            background-color: #000;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: 0;
        }

        label {
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
            display: block;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        input[type="file"] {
            background-color: transparent;
            padding: 5px;
        }

        button {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #333;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            margin-top: 10px;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .foto-preview img {
            max-width: 100%;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .message {
            color: #e74c3c;
            margin-top: 10px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        select {
            cursor: pointer;
        }

        /* Styling tombol kembali */
        .button-back {
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .button-back button {
            background-color: #f1f1f1;
            color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-back button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Edit Foto</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Foto:</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($foto['JudulFoto']); ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Foto:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></textarea>
            </div>

            <div class="form-group foto-preview">
                <label for="foto">Foto Sebelumnya:</label><br>
                <img src="uploads/<?php echo htmlspecialchars($foto['LokasiFoto']); ?>" alt="Foto Sebelumnya">
            </div>

            <div class="form-group">
                <label for="foto">Upload Foto Baru (opsional):</label>
                <input type="file" id="foto" name="foto">
            </div>
            


            <div class="form-group">
                <label for="album">Album:</label>
                <select name="album" id="album" required>
                    <?php
                    $query = "SELECT * FROM album";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['AlbumID'] == $foto['AlbumID']) ? "selected" : "";
                        echo "<option value='{$row['AlbumID']}' $selected>{$row['NamaAlbum']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Simpan Perubahan</button>

            <!-- Tombol Kembali -->
            <a href="dashboard.php" class="button-back">
                <button type="button">Kembali ke Dashboard</button>
            </a>
        </form>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
