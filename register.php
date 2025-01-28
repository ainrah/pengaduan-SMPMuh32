<?php
require_once("private/database.php");
session_start();
// Proses form ketika data dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = $_POST['nama_user'] ?? '';
    $email = $_POST['email'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi data
    $errors = [];
    if (empty($nama_user)) $errors['nama_user'] = "Nama wajib diisi.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Email tidak valid.";
    if (empty($no_hp) || !is_numeric($no_hp)) $errors['no_hp'] = "Nomor HP wajib diisi dengan angka.";
    if (empty($password)) $errors['password'] = "Password wajib diisi.";
    if ($password !== $password_confirm) $errors['password_confirm'] = "Konfirmasi password tidak cocok.";

    if (empty($errors)) {
        // Format tanggal
        $tanggal = date("dmY");
        $prefix = "SMPM";

        // Cari nomor urut terakhir di database untuk format tanggal dan prefix ini
        $statement = $db->query("SELECT id_user FROM user WHERE id_user LIKE '{$prefix}{$tanggal}%' ORDER BY id_user DESC LIMIT 1");
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Ambil nomor urut terakhir dan tambahkan 1
            $last_id = substr($result['id_user'], 0, 6); // Ambil bagian nomor urut
            $urut = str_pad($last_id + 1, 6, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada ID pada tanggal ini, mulai dari 000001
            $urut = '000001';
        }

        $id_user = $urut . $prefix . $tanggal;

        // Hash password sebelum menyimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data ke database
        $stmt = $db->prepare("INSERT INTO user (id_user, nama_user, email, no_hp, password) VALUES (:id_user, :nama_user, :email, :no_hp, :password)");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':nama_user', $nama_user);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':no_hp', $no_hp);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo "Registrasi berhasil!";
            header("Location: login.php");
            exit;
        } else {
            echo "Gagal menyimpan data.";
        }
    } else {
        // Tampilkan pesan error
        foreach ($errors as $field => $error) {
            echo "<p>{$field}: {$error}</p>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Lapor | SMP MUHAMMADIYAH 32 Jakarta</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Main Styles CSS -->
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width">
    <title>SMP MUHAMMADIYAH 32 Jakarta</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Main Styles CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.js"></script>
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.min.css">
    <meta charset="UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Lihat Pengaduan | SMP MUHAMMADIYAH 32 Jakarta</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Main Styles CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
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
            display: flex;
            width: 90%;
            max-width: 10000px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
            align-items: center;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .image {
            margin-right: 20px;
        }
        .form-container {
            width: 300px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            flex: 1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
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
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
    <script src="js/bootstrap.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
</head>
<body>
<div class="shadow">
        <!-- navbar -->
        <nav class="navbar navbar-inverse navbar-fixed form-shadow">
            <!-- container-fluid -->
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="">
                        <img alt="Brand" src="images/favicon-40x47.png">
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index">SMP Muhammadiyah 32 Jakarta Barat</a></li>
                <?php if (isset($_SESSION['user'])) { // Tampilkan menu ini hanya jika user sudah login ?>
                    <li class="active"><a href="lapor">LAPOR</a></li>
                    <li><a href="lihat">LIHAT PENGADUAN</a></li>
                <?php } ?>
                <li><a href="kontak">KONTAK</a></li>
            </ul>

            <!-- User Options on the Right -->
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['user'])) { // Check if the user is logged in ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['user']['nama_user']); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="statuslaporan">Status Laporan</a></li>
                            <li><a href="reset_password">Reset Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="logout">Logout</a></li>
                        </ul>
                    </li>
                <?php } else { // If the user is not logged in ?>
                    <li><a href="register">Register</a></li>
                    <li><a href="login">Masuk</a></li>
                <?php } ?>
            </ul>
        </div>

            </div>
        </nav>

        <div class="main-content">
<hr />
<div class="container">
    <div class="image">
        <img src="images/SMP.png" alt="Image" width="200">
    </div>
    <div class="form-container">
        <h2>REGISTRASI</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="nama_user">Nama Lengkap</label>
                <input type="text" id="nama_user" name="nama_user" required>
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="no_hp">Nomor Handphone</label>
                <input type="tel" id="no_hp" name="no_hp" required>
            </div>
            <div class="form-group">
                <label for="password">Password (min 8 angka)</label>
                <input type="password" id="password" name="password" minlength="8" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Ulangi Password</label>
                <input type="password" id="password_confirm" name="password_confirm" minlength="8" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrasi">
            </div>
        </form>
        <div class="login-link">
            Sudah Punya Akun? <a href="login.php">Silahkan Masuk</a>
        </div>
    </div>
</div>
    <hr>
    
     <<?php
// Include footer
include('footer.php');
?>

</body>
</html>
