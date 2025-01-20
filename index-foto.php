<?php
session_start();
if (!isset($_SESSION['UserID']) || !is_numeric($_SESSION['UserID'])) {
    echo "UserID tidak valid.";
    exit();  // Hentikan proses jika UserID tidak valid
}
include "koneksi.php";

// Ambil data pencarian kategori dari form (misalnya kategori "Pemandangan")
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_condition = '';
if (!empty($search)) {
    $category_condition = "AND Kategori LIKE '%" . mysqli_real_escape_string($con, $search) . "%'";
}

// Query untuk mengambil foto berdasarkan kategori yang dicari
$query = "SELECT LokasiFoto FROM foto WHERE UserID = '" . $_SESSION['UserID'] . "' $category_condition ORDER BY TanggalUnggah DESC LIMIT 5";
$result = mysqli_query($con, $query);

// Menyimpan hasil foto dalam array
$photos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $photos[] = "uploads/" . $row['LokasiFoto']; // Lokasi gambar yang di-upload
}

// Hapus duplikat
$photos = array_unique($photos);

if (empty($photos)) {
    $photos[] = "img/portfolio/default.jpg"; // Gambar default jika tidak ada foto yang di-upload
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Eternal Focus | Menu </title>
    <meta charset="UTF-8">
    <meta name="description" content="Photographer html template">
    <meta name="keywords" content="photographer, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="shortcut icon" />

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/magnific-popup.css" />
    <link rel="stylesheet" href="css/slicknav.min.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />

    <!-- Main Stylesheets -->
    <link rel="stylesheet" href="css/style.css" />

    <style>
        .hero-item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            object-position: center;
        }

        .hero-slider .owl-item {
            position: relative;
            overflow: hidden;
        }

        .hero-slider .hero-item {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 500px;
        }

        .hero-item.empty-slide {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero-item.empty-slide img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            object-position: center;
        }

        .hero-item.empty-slide h2 {
            font-size: 24px;
            color: #555;
            font-weight: 600;
        }

        .search-model {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .search-model-form {
            display: flex;
            justify-content: center;
        }

        #search-input {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        .search-close-switch {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header section -->
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
        <li><a href="account.php">Account</a></li>
        <li class="search-mobile">
            <button class="search-btn"><i class="fa fa-search"></i></button>
        </li>
        <!-- Pencarian di navigasi -->
        <li>
            <form action="index-foto.php" method="get" class="navbar-search">
                <input type="text" name="search" id="search-input" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </li>
    </ul>
</header>
<div class="clearfix"></div>
<!-- Header section end -->

    <div class="hero-section">
        <div class="hero-slider owl-carousel">
            <?php foreach ($photos as $photo): ?>
                <div class="hero-item portfolio-item">
                    <img src="<?php echo $photo; ?>" alt="Portfolio Image">
                    <a href="viewfoto.php?foto=<?php echo urlencode($photo); ?>" class="hero-link">
                        <h2>Take a look at my Portfolio</h2>
                    </a>
                </div>
            <?php endforeach; ?>

            <!-- Menambahkan item kosong -->
            <div class="hero-item portfolio-item empty-slide">
                <img src="img/portfolio/empty.jpg" alt="Empty">
                <a href="javascript:void(0);" class="hero-link">
                    <h2>This space is empty</h2>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer section -->
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
                        </script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer section end -->

    <!-- Search model -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!--====== Javascripts & Jquery ======-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.slicknav.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/circle-progress.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/instafeed.min.js"></script>
    <script src="js/masonry.pkgd.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true
            });

            // Menampilkan form pencarian saat tombol pencarian diklik
            $(".search-btn").on("click", function() {
                $(".search-model").fadeIn();
            });

            // Menutup form pencarian saat tombol close diklik
            $(".search-close-switch").on("click", function() {
                $(".search-model").fadeOut();
            });
        });
    </script>
</body>

</html>
