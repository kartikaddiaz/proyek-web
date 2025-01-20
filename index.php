<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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

        .login-container {
            background-color: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-form {
            width: 100%;
        }

        .login-form h2 {
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
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input class="form-control" type="text" name="Username" id="Username" placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input class="form-control" type="password" name="Password" id="Password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-submit">Login</button>
            </form>
            <p class="text-center">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>

</html>
