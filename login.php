<?php
require_once("private/database.php");
session_start();



// Proses form ketika data dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi data input
    $errors = [];
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Alamat email tidak valid.";
    }
    if (empty($password)) {
        $errors['password'] = "Password wajib diisi.";
    }

    // Jika tidak ada error validasi
    if (empty($errors)) {
        try {
            // Cek keberadaan pengguna berdasarkan email
            $stmt = $db->prepare("SELECT id_user, nama_user, password FROM user WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validasi pengguna dan password
            if ($user && password_verify($password, $user['password'])) {
                // Set session untuk pengguna yang berhasil login
                $_SESSION['user'] = [
                    'id_user' => $user['id_user'],
                    'email' => $email,
                    'nama_user' => $user['nama_user']
                ];

                // Redirect ke dashboard atau halaman utama
                header("Location: index.php");
                exit;
            } else {
                $errors['login'] = "Email atau password salah.";
            }
        } catch (PDOException $e) {
            // Tangani error database
            $errors['database'] = "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SMP MUHAMMADIYAH 32 Jakarta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 500px;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-bottom: 15px;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>LOGIN</h2>
        <form action="login.php" method="POST">
            <!-- Tampilkan error login -->
            <?php if (!empty($errors['login'])): ?>
                <div class="alert alert-danger"><?php echo $errors['login']; ?></div>
            <?php endif; ?>
            
            <!-- Input Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <?php if (!empty($errors['email'])): ?>
                    <small class="text-danger"><?php echo $errors['email']; ?></small>
                <?php endif; ?>
            </div>
            
            <!-- Input Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php if (!empty($errors['password'])): ?>
                    <small class="text-danger"><?php echo $errors['password']; ?></small>
                <?php endif; ?>
            </div>
            
            <!-- Tombol Login -->
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>

        <!-- Link Registrasi -->
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</div>
</body>
</html>
