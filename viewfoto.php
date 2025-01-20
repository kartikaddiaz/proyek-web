<?php
session_start();
if (!isset($_SESSION['UserID']) || !is_numeric($_SESSION['UserID'])) {
    echo "UserID tidak valid.";
    exit();
}
include "koneksi.php";

// Ambil foto berdasarkan parameter 'foto' yang dikirimkan dari URL
$foto = isset($_GET['foto']) ? $_GET['foto'] : null;

// Validasi apakah foto ada
if ($foto && file_exists($foto)) {
    $fotoPath = $foto;  // Foto yang dipilih dari URL
} else {
    $fotoPath = "img/portfolio/default.jpg";  // Foto default jika tidak ada
}

// Ambil deskripsi foto dari database
$query = "SELECT Deskripsi, Likes FROM foto WHERE LokasiFoto = '$foto'";
$result = mysqli_query($con, $query);
$photoDetail = mysqli_fetch_assoc($result);
$description = $photoDetail['Deskripsi'] ?? 'No description available.';
$likes = $photoDetail['Likes'] ?? 0;

// Proses penambahan like
if (isset($_POST['like'])) {
    $newLikes = $likes + 1;
    $updateQuery = "UPDATE foto SET Likes = $newLikes WHERE LokasiFoto = '$foto'";
    mysqli_query($con, $updateQuery);
    $likes = $newLikes;
}

// Menangani pengiriman komentar
if (isset($_POST['comment'])) {
    // Ambil UserID dari session
    $userID = $_SESSION['UserID'];

    // Cek apakah UserID ada di tabel users
    $queryCheckUser = "SELECT UserID FROM users WHERE UserID = '$userID'";
    $resultCheckUser = mysqli_query($con, $queryCheckUser);

    if (mysqli_num_rows($resultCheckUser) > 0) {
        // Jika UserID valid, lanjutkan untuk menyimpan komentar
        $comment = mysqli_real_escape_string($con, $_POST['comment']);  // Hindari SQL injection

        // Simpan komentar ke database
        $insertQuery = "INSERT INTO komentar (UserID, LokasiFoto, Komentar) VALUES ('$userID', '$foto', '$comment')";
        mysqli_query($con, $insertQuery);
    } else {
        // Jika UserID tidak ditemukan
        echo "UserID tidak valid.";
        exit();
    }
}

// Ambil komentar yang ada untuk foto ini (mengurutkan berdasarkan tanggal)
$commentQuery = "SELECT komentar.Komentar, users.Username FROM komentar JOIN users ON komentar.UserID = users.UserID WHERE LokasiFoto = '$foto' ORDER BY komentar.TanggalKomentar DESC";
$commentsResult = mysqli_query($con, $commentQuery);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>View Foto</title>
    <meta charset="UTF-8">
    <meta name="description" content="View Foto Detail">
    <meta name="keywords" content="photographer, viewfoto">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/favicon.ico" rel="shortcut icon" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .photo-detail-image {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        h3 {
            font-weight: 600;
            text-align: center;
        }

        .like-btn {
            background-color: #f04e23;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .like-btn:hover {
            background-color: #e04b1b;
        }

        .comment-section {
            margin-top: 30px;
        }

        .comment-box {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .comment {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<header class="header-section">
    <a href="index-foto.php" class="site-logo">
        <img src="img/logo2.png" alt="logo" style="width: 185px; height: auto;">
    </a>
    <div class="header-controls">
        <button class="nav-switch-btn"><i class="fa fa-bars"></i></button>
        <button class="search-btn"><i class="fa fa-search"></i></button>
    </div>
    <ul class="main-menu">
        <li><a href="index-foto.php">Home</a></li>
        <li><a href="dashboard.php">Upload</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li class="search-mobile">
            <button class="search-btn"><i class="fa fa-search"></i></button>
        </li>
    </ul>
</header>

    <div class="view-foto-section">
        <div class="container">
            <br>
             <br><h3>Foto Details</h3></br>
            <div class="row">
                <div class="col-md-12">
                    <img src="<?php echo $fotoPath; ?>" alt="Portfolio Image" class="photo-detail-image">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <br>
                    <p><strong>Description:</strong> <?php echo $description; ?></p>
                    <p><strong>Likes:</strong> <?php echo $likes; ?></p>
                    <form method="POST">
                        <button type="submit" name="like" class="like-btn">Like</button>
                    </form>
                </div>
            </div>

            <!-- Comment Section -->
            <div class="row comment-section">
                <div class="col-md-12">
                    <h4>Leave a Comment</h4>
                    <form method="POST">
                        <textarea class="comment-box" name="comment" placeholder="Write your comment here..."></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Display Comments -->
            <div class="row">
                <div class="col-md-12">
                    <h4>Comments:</h4>
                    <?php
                    if (mysqli_num_rows($commentsResult) > 0) {
                        while ($comment = mysqli_fetch_assoc($commentsResult)) {
                            echo "<div class='comment'>";
                            echo "<strong>" . htmlspecialchars($comment['Username']) . ":</strong> ";
                            echo "<p>" . htmlspecialchars($comment['Komentar']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 order-1 order-md-2">
                    <div class="footer-social-links">
                        <a href=""><i class="fa fa-pinterest"></i></a>
                        <a href=""><i class="fa fa-facebook"></i></a>
                        <a href=""><i class="fa fa-twitter"></i></a>
                        <a href=""><i class="fa fa-dribbble"></i></a>
                        <a href=""><i class="fa fa-behance"></i></a>
                    </div>
                </div>
                <div class="col-md-6 order-2 order-md-1">
                    <div class="copyright">
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
