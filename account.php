<?php
// Data akun (ini bisa diganti dengan data dari database atau sesi pengguna)
$username = "luckywargod";
$email = "luckywargod@gmail";
$profileImage = "profile.jpg"; // Ganti dengan path gambar profil yang sesuai
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informasi Akun</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f7f7f7;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .profile-card {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      width: 300px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
      width: 100%;
      height: 200px;
      background-color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .profile-header .profile-img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile-info {
      padding: 20px;
      text-align: center;
    }

    .username {
      font-size: 24px;
      font-weight: 600;
      color: #333;
    }

    .email {
      font-size: 16px;
      color: #777;
    }

    .profile-link {
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="profile-card">
      <!-- Menambahkan link untuk menuju ke halaman detail profil -->
      <a href="detail-profile.php" class="profile-link">
        <div class="profile-header">
          <img class="profile-img" src="<?php echo $profileImage; ?>" alt="Foto Profil">
        </div>
        <div class="profile-info">
          <h2 class="username"><?php echo $username; ?></h2>
          <p class="email"><?php echo $email; ?></p>
        </div>
      </a>
    </div>
  </div>
</body>
</html>
