<?php
session_start();
include 'config/koneksi.php'; // ✅ path relatif yang benar

// Cek jika sudah login
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Query ke database pakai $koneksi
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Cek password
        if ($user['password'] === $password) {
            $_SESSION['user'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['id'] = $user['id'];

            // Redirect sesuai role
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Dashboard Kue ChocoLove</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    margin: 0; padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: url('https://your-image-url.com/background.jpg'); /* Ganti dengan URL gambar latar belakang */
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: relative;
}

/* Overlay agar teks tetap jelas */
body::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 0;
}

.login-card {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, 0.9);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
    animation: fadeIn 1s ease;
}

h2 {
    color: #FF4D6D;
    margin-bottom: 30px;
    font-size: 28px;
}

input {
    width: 100%;
    padding: 14px 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

input:focus {
    border-color: #FF4D6D;
    box-shadow: 0 0 5px rgba(255,77,109,0.5);
    outline: none;
}

button {
    width: 100%;
    padding: 15px;
    background: #FF4D6D;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
    background: #e0405b;
    transform: translateY(-2px);
}

.error {
    color: red;
    margin-bottom: 15px;
    font-weight: 600;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

@media(max-width:480px){
    .login-card{padding:30px 20px;}
    h2{font-size:24px;}
}
</style>
</head>
<body>

<div class="login-card">
    <h2>Login Dashboard Kue ChocoLove</h2>
    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>