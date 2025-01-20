<?php
// Mulai sesi
session_start();

// Menyertakan koneksi ke database
include "koneksi.php";

// Proses saat tombol "register" ditekan
if (isset($_POST['register'])) {
    // Mengambil data dari form
    $NamaLengkap = mysqli_real_escape_string($con, $_POST['NamaLengkap']);
    $Username = mysqli_real_escape_string($con, $_POST['Username']);
    $Email = mysqli_real_escape_string($con, $_POST['Email']);
    $Password = isset($_POST["Password"]) ? password_hash($_POST["Password"], PASSWORD_DEFAULT) : null;
    $Alamat = mysqli_real_escape_string($con, $_POST['Alamat']);
    $hashed_password = md5($Password);

    // Validasi password
    if ($Password === null) {
        die("Password tidak boleh kosong!");
    }

    // Query untuk memasukkan data ke database
    $sql = "INSERT INTO user (NamaLengkap, Username, Email, Password, Alamat) 
            VALUES ('$NamaLengkap', '$Username', '$Email', '$Password', '$Alamat')";

    // Menjalankan query
    if (mysqli_query($con, $sql)) {
        $_SESSION['message'] = "Akun berhasil dibuat!";
        header("Location: index.php"); // Redirect ke halaman lain setelah sukses
        exit();
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <!-- Menambahkan link untuk font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .register-form {
            width: 100%;
        }

        .register-form h2 {
            margin-bottom: 20px;
            color: #000;
            font-size: 24px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f8f8;
            color: #333;
            font-size: 14px;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-submit {
            background-color: #000;
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #333;
        }

        .text-center {
            margin-top: 10px;
        }

        .text-center a {
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <form class="register-form" action="" method="POST">
            <h2>Register</h2>
            <div class="form-group">
                <label for="NamaLengkap">Nama Lengkap</label>
                <input class="form-control" type="text" name="NamaLengkap" id="NamaLengkap" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label for="Username">Username</label>
                <input class="form-control" type="text" name="Username" id="Username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="Email">Email</label>
                <input class="form-control" type="email" name="Email" id="Email" placeholder="Alamat Email" required>
            </div>
            <div class="form-group">
                <label for="Password">Password</label>
                <input class="form-control" type="password" name="Password" id="Password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="Alamat">Alamat</label>
                <textarea class="form-control" name="Alamat" id="Alamat" placeholder="Alamat Lengkap" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn-submit" name="register">Daftar</button>
        </form>
        <p class="text-center">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>

</html>
