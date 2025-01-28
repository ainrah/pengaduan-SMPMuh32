<?php

require_once("private/database.php");
session_start();

// Debug isi session


// Ambil ID User dari session
if (isset($_SESSION['user']['id_user'])) {
    $id_user = $_SESSION['user']['id_user'];
}

// Fetch max ID logic
$statement = $db->query("SELECT id_laporan FROM `laporan` ORDER BY id_laporan DESC LIMIT 1");

if ($statement->rowCount() > 0) {
    $last_id = $statement->fetch(PDO::FETCH_ASSOC)['id_laporan'];
    $urut = (int)substr($last_id, -5) + 1; // Ambil 5 digit terakhir dan tambahkan 1
} else {
    $urut = 1; // Nomor urut pertama jika belum ada data
}

// Format nomor urut dengan padding nol
$urut_padded = str_pad($urut, 5, '0', STR_PAD_LEFT);

// Ambil tanggal saat ini dalam format YYYYMMDD
$tanggal = date('Ymd');

// Gabungkan menjadi ID laporan
$id_laporan = "IDL-{$tanggal}-{$urut_padded}";
// Include header and navbar
include('header.php');
include('navbar.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_plp = $_POST['nama_pelapor'];
    $no_hp_plp = $_POST['nomor_pelapor'];
    $kls_plp = $_POST['kelas_pelapor'];

    $nama_krb = $_POST['nama_korban'];
    $no_hp_krb = $_POST['nomor_korban'];
    $kls_krb = $_POST['kelas_korban'];

    $nama_plk = $_POST['nama_pelaku'];
    $no_hp_plk = $_POST['nomor_pelaku'];

    $tanggal_pengaduan = $_POST['tanggal_pengaduan'];
    $tanggal_kejadian = $_POST['tanggal_kejadian'];
    $tempat_kejadian = $_POST['tempat_kejadian'];
    $kategori_kekerasan = $_POST['kategori_kekerasan'];
    $subjek_pengaduan = $_POST['subjek_pengaduan'];
    $kronologi_kejadian = $_POST['kronologi_kejadian'];

    // Proses file bukti kekerasan
    if (isset($_FILES['bukti_kekerasan']) && $_FILES['bukti_kekerasan']['error'] == 0) {
        // Validasi file upload
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $file_extension = pathinfo($_FILES['bukti_kekerasan']['name'], PATHINFO_EXTENSION);
    
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo json_encode([
                'success' => false,
                'message' => 'Hanya file dengan ekstensi jpg, jpeg, png, atau pdf yang diperbolehkan.'
            ]);
            exit;
        }
    
        // Tentukan nama file baru
        $upload_dir = 'admin/images/';
        $file_name = uniqid('bukti_', true) . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
    
        // Pindahkan file ke folder upload
        if (!move_uploaded_file($_FILES['bukti_kekerasan']['tmp_name'], $file_path)) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengunggah file.'
            ]);
            exit;
        }
    
        // Simpan hanya nama file ke database
        $bukti_kekerasan = $file_name;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Bukti kekerasan tidak diunggah.'
        ]);
        exit;
    }
    

    try {
        // Siapkan query SQL untuk menyimpan data
        $query = "INSERT INTO laporan (
            id_laporan, id_user,
            nama_plp, no_hp_plp, kls_plp,
            nama_krb, no_hp_krb, kls_krb,
            nama_plk, no_hp_plk,
            tanggal_pengaduan, tanggal_kejadian, tempat_kejadian,
            kategori_kekerasan, subjek_pengaduan, kronologi_kejadian, bukti_kekerasan, status
        ) VALUES (
            :id_laporan, :id_user,
            :nama_plp, :no_hp_plp, :kls_plp,
            :nama_krb, :no_hp_krb, :kls_krb,
            :nama_plk, :no_hp_plk,
            :tanggal_pengaduan, :tanggal_kejadian, :tempat_kejadian,
            :kategori_kekerasan, :subjek_pengaduan, :kronologi_kejadian, :bukti_kekerasan, 'Menunggu'
        )";

        $statement = $db->prepare($query);

        // Bind parameter
        $statement->bindParam(':id_laporan', $id_laporan);
        $statement->bindParam(':id_user', $id_user);
        $statement->bindParam(':nama_plp', $nama_plp);
        $statement->bindParam(':no_hp_plp', $no_hp_plp);
        $statement->bindParam(':kls_plp', $kls_plp);
        $statement->bindParam(':nama_krb', $nama_krb);
        $statement->bindParam(':no_hp_krb', $no_hp_krb);
        $statement->bindParam(':kls_krb', $kls_krb);
        $statement->bindParam(':nama_plk', $nama_plk);
        $statement->bindParam(':no_hp_plk', $no_hp_plk);
        $statement->bindParam(':tanggal_pengaduan', $tanggal_pengaduan);
        $statement->bindParam(':tanggal_kejadian', $tanggal_kejadian);
        $statement->bindParam(':tempat_kejadian', $tempat_kejadian);
        $statement->bindParam(':kategori_kekerasan', $kategori_kekerasan);
        $statement->bindParam(':subjek_pengaduan', $subjek_pengaduan);
        $statement->bindParam(':kronologi_kejadian', $kronologi_kejadian);
        $statement->bindParam(':bukti_kekerasan', $bukti_kekerasan);

        // Eksekusi query
        $statement->execute();

        echo json_encode([
            'success' => true,
            'message' => "Laporan berhasil dikirim dengan ID: $id_laporan."
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
    exit;
}

?>

<!-- Tambahkan SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengaduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .form-container {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        .form-section {
            margin-bottom: 20px;
            border: 1px solid #007BFF;
            border-radius: 8px;
            padding: 15px;
        }

        .form-section h3 {
            margin-top: 0;
            color: #007BFF;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .submit-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="main-content container">
<div class="form-container">
    <h2>FORM PENGADUAN</h2>
    <form method="POST" action="">
        <div class="form-section">
            <h3>DATA PELAPOR</h3>

            <div class="form-group">
                <label for="id-laporan">ID Laporan</label>
                <input type="text" id="id-laporan" name="id_laporan" value="<?php echo $id_laporan; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama-pelapor">Nama Pelapor</label>
                <input type="text" id="nama-pelapor" name="nama_pelapor" required>
            </div>
            <div class="form-group">
                <label for="nomor-pelapor">Nomor Handphone Pelapor</label>
                <input type="text" id="nomor-pelapor" name="nomor_pelapor" required>
            </div>
            <div class="form-group">
                <label for="kelas-pelapor">Kelas Pelapor</label>
                <input type="text" id="kelas-pelapor" name="kelas_pelapor" required>
            </div>
        </div>

        <div class="form-section">
            <h3>DATA KORBAN</h3>
            <div class="form-group">
                <label for="nama-korban">Nama Korban</label>
                <input type="text" id="nama-korban" name="nama_korban" required>
            </div>
            <div class="form-group">
                <label for="kelas-korban">Kelas Korban</label>
                <input type="text" id="kelas-korban" name="kelas_korban" required>
            </div>
            <div class="form-group">
                <label for="nomor-korban">Nomor Handphone Korban</label>
                <input type="text" id="nomor-korban" name="nomor_korban" required>
            </div>
        </div>

        <div class="form-section">
            <h3>DATA PELAKU</h3>
            <div class="form-group">
                <label for="nama-pelaku">Nama Pelaku</label>
                <input type="text" id="nama-pelaku" name="nama_pelaku" required>
            </div>
            <div class="form-group">
                <label for="kelas-pelaku">Kelas Pelaku</label>
                <input type="text" id="kelas-pelaku" name="kelas_pelaku" required>
            </div>
            <div class="form-group">
                <label for="nomor-pelaku">Nomor Handphone Pelaku</label>
                <input type="text" id="nomor-pelaku" name="nomor_pelaku" required>
            </div>
        </div>

        <div class="form-section">
            <h3>DATA PENGADUAN</h3>
            <div class="form-group">
                <label for="tanggal-pengaduan">Tanggal Pengaduan</label>
                <input type="date" id="tanggal-pengaduan" name="tanggal_pengaduan" required>
            </div>
            <div class="form-group">
                <label for="tanggal-kejadian">Tanggal Kejadian</label>
                <input type="date" id="tanggal-kejadian" name="tanggal_kejadian" required>
            </div>
            <div class="form-group">
                <label for="tempat-kejadian">Tempat Kejadian</label>
                <input type="text" id="tempat-kejadian" name="tempat_kejadian" required>
            </div>
            <div class="form-group">
                <label for="kategori-kekerasan">Kategori Kekerasan</label>
                <input type="text" id="kategori-kekerasan" name="kategori_kekerasan" required>
            </div>
            <div class="form-group">
                <label for="subjek-pengaduan">Subjek Pengaduan</label>
                <input type="text" id="subjek-pengaduan" name="subjek_pengaduan" required>
            </div>
            <div class="form-group">
                <label for="kronologi-kejadian">Kronologi Kejadian</label>
                <input type="text" id="kronologi-kejadian" name="kronologi_kejadian" required>
            </div>
            <div class="form-group">
                <label for="bukti-kekerasan">Bukti Kekerasan</label>
                <input type="file" id="bukti-kekerasan" name="bukti_kekerasan" required>
            </div>
        </div>

        <button type="submit" class="submit-button">Kirim Pengaduan</button>
    </form>
</div>

<?php
// Include footer
include('footer.php');
?>
<script>
               document.querySelector("form").addEventListener("submit", function (e) {
    e.preventDefault(); // Mencegah submit form default

    const formData = new FormData(this);

    fetch("", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    title: "Berhasil!",
                    text: data.message,
                    icon: "success",
                    confirmButtonText: "OK",
                }).then(() => {
                    window.location.href = "laporan.php"; // Redirect
                });
            } else {
                Swal.fire({
                    title: "Gagal!",
                    text: data.message,
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch((error) => {
            Swal.fire({
                title: "Berhasil",
                text: "Data Laporan Berhasil dikirim",
                icon: "Succes",
                confirmButtonText: "OK",
            }).then(() => {
                window.location.href = "lapor"; // Redirect
            });
        });
});
            </script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>