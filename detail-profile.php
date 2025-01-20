<?php
// Mulai session
session_start();

// Periksa apakah session untuk data profil sudah ada, jika tidak set data default
$username = isset($_SESSION['Username']) ? $_SESSION['Username'] : "Username123";
$email = isset($_SESSION['Email']) ? $_SESSION['Email'] : "email@example.com";
$profileImage = isset($_SESSION['ProfileImage']) ? $_SESSION['ProfileImage'] : "profile.jpg";  // Gambar default
$pronouns = isset($_SESSION['Pronouns']) ? $_SESSION['Pronouns'] : "They/Them";  // Pronouns default

// Periksa jika form edit telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update session dengan data yang baru
    $_SESSION['Username'] = $_POST['Username'];
    $_SESSION['Email'] = $_POST['Email'];
    $_SESSION['ProfileImage'] = $_FILES['ProfileImage']['name']; // Menyimpan nama gambar yang di-upload
    $_SESSION['Pronouns'] = $_POST['Pronouns'];

    // Pindahkan gambar ke folder yang sesuai
    if ($_FILES['ProfileImage']['name'] != '') {
        move_uploaded_file($_FILES['ProfileImage']['tmp_name'], 'uploads/' . $_FILES['ProfileImage']['name']);
    }

    // Update data di variabel setelah diubah
    $username = $_SESSION['Username'];
    $email = $_SESSION['Email'];
    $profileImage = 'uploads/' . $_SESSION['ProfileImage'];
    $pronouns = $_SESSION['Pronouns'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Profil</title>
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
      padding: 20px;
    }

    .profile-detail {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      text-align: center;
    }

    .profile-header {
      margin-bottom: 20px;
    }

    .profile-header .profile-img {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile-info {
      font-size: 18px;
    }

    .profile-info h2 {
      font-size: 28px;
      font-weight: 600;
    }

    .profile-info p {
      font-size: 16px;
      color: #777;
      margin-top: 10px;
    }

    .pronouns {
      font-size: 16px;
      color: #777;
      margin-top: 10px;
    }

    .edit-form {
      margin-top: 20px;
      text-align: left;
    }

    .edit-form input, .edit-form textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .edit-form button {
      background-color: #333;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="profile-detail">
    <div class="profile-header">
      <img class="profile-img" src="<?php echo $profileImage; ?>" alt="Foto Profil">
    </div>
    <div class="profile-info">
      <h2><?php echo $username; ?></h2>
      <p>Email: <?php echo $email; ?></p>
      <p class="pronouns">Pronouns: <?php echo $pronouns; ?></p>
    </div>

    <!-- Formulir untuk mengedit profil -->
    <div class="edit-form">
      <form action="" method="POST" enctype="multipart/form-data">
        <label for="Username">Username:</label>
        <input type="text" id="Username" name="Username" value="<?php echo $username; ?>" required>

        <label for="Email">Email:</label>
        <input type="email" id="Email" name="Email" value="<?php echo $email; ?>" required>

        <label for="Pronouns">Pronouns:</label>
        <input type="text" id="Pronouns" name="Pronouns" value="<?php echo $pronouns; ?>" required>

        <label for="ProfileImage">Foto Profil:</label>
        <input type="file" id="ProfileImage" name="ProfileImage">

        <button type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</body>
</html> 