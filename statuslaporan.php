<?php
require_once("private/database.php");
session_start();

// Debug isi session


// Ambil ID User dari session
if (isset($_SESSION['user']['id_user'])) {
    $id_user = $_SESSION['user']['id_user'];
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Anda harus login untuk mengirim laporan.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php'; // Ganti dengan URL login Anda
            }
        });
    </script>";
    exit();
}

include('header.php');
include('navbar.php');

// Validasi apakah id_user valid
if (!$id_user || !is_numeric($id_user)) {
    echo "ID pengguna tidak valid. Silakan login ulang.";
    exit();
}

// Ambil laporan berdasarkan ID pengguna
try {
    $stmt = $db->prepare("
        SELECT 
            id_laporan, nama_plp, no_hp_plp, kls_plp, 
            nama_krb, no_hp_krb, kls_krb, 
            nama_plk, no_hp_plk, 
            tanggal_pengaduan, tanggal_kejadian, tempat_kejadian, 
            kategori_kekerasan, subjek_pengaduan, kronologi_kejadian, bukti_kekerasan, status
        FROM laporan 
        WHERE id_user = :id_user
        ORDER BY tanggal_pengaduan DESC
    ");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Terjadi kesalahan saat mengambil data laporan: " . $e->getMessage();
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 120px;
            background-color: #f5f5f5;
            color: #333;
        
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #4CAF50;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .table-container {
            width: 100%;
            max-width: 12000px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #4CAF50;
            color: white;
        }

        thead th {
            padding: 12px;
            font-size: 14px;
            text-align: center;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody td {
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }

        tbody td a {
            color: #4CAF50;
            text-decoration: none;
        }

        tbody td a:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 16px;
        }
    </style>
    <script>
        // JavaScript function for search
        function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const table = document.getElementById("reportTable");
            const rows = table.getElementsByTagName("tr");

            // Loop through all table rows except the header
            for (let i = 1; i < rows.length; i++) {
                let match = false;
                const cells = rows[i].getElementsByTagName("td");
                for (let j = 0; j < cells.length; j++) {
                    const cellValue = cells[j].textContent || cells[j].innerText;
                    if (cellValue.toLowerCase().indexOf(input) > -1) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
        }
    </script>
</head>
<body>
    <h1>Status Laporan Anda</h1>
    <div class="search-container">
        <input 
            type="text" 
            id="searchInput" 
            onkeyup="searchTable()" 
            placeholder="Cari berdasarkan nama, ID laporan, atau lainnya..."
        />
    </div>
    <div class="table-container">
        <?php if (empty($laporan)): ?>
            <div class="no-data">Tidak ada laporan yang ditemukan.</div>
        <?php else: ?>
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>ID Laporan</th>
                        <th>Nama Pelapor</th>
                        <th>No. HP Pelapor</th>
                        <th>Kelas Pelapor</th>
                        <th>Nama Korban</th>
                        <th>No. HP Korban</th>
                        <th>Kelas Korban</th>
                        <th>Nama Pelaku</th>
                        <th>No. HP Pelaku</th>
                        <th>Tanggal Pengaduan</th>
                        <th>Tanggal Kejadian</th>
                        <th>Tempat Kejadian</th>
                        <th>Kategori Kekerasan</th>
                        <th>Subjek Pengaduan</th>
                        <th>Kronologi Kejadian</th>
                        <th>Bukti Kekerasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $lapor): ?>
                        <tr>
                            <td><?= htmlspecialchars($lapor['id_laporan']) ?></td>
                            <td><?= htmlspecialchars($lapor['nama_plp']) ?></td>
                            <td><?= htmlspecialchars($lapor['no_hp_plp']) ?></td>
                            <td><?= htmlspecialchars($lapor['kls_plp']) ?></td>
                            <td><?= htmlspecialchars($lapor['nama_krb']) ?></td>
                            <td><?= htmlspecialchars($lapor['no_hp_krb']) ?></td>
                            <td><?= htmlspecialchars($lapor['kls_krb']) ?></td>
                            <td><?= htmlspecialchars($lapor['nama_plk']) ?></td>
                            <td><?= htmlspecialchars($lapor['no_hp_plk']) ?></td>
                            <td><?= htmlspecialchars($lapor['tanggal_pengaduan']) ?></td>
                            <td><?= htmlspecialchars($lapor['tanggal_kejadian']) ?></td>
                            <td><?= htmlspecialchars($lapor['tempat_kejadian']) ?></td>
                            <td><?= htmlspecialchars($lapor['kategori_kekerasan']) ?></td>
                            <td><?= htmlspecialchars($lapor['subjek_pengaduan']) ?></td>
                            <td><?= nl2br(htmlspecialchars($lapor['kronologi_kejadian'])) ?></td>
                            <td>
                                <?php if (!empty($lapor['bukti_kekerasan'])): ?>
                                    <a href="uploads/<?= htmlspecialchars($lapor['bukti_kekerasan']) ?>" target="_blank">Lihat</a>
                                <?php else: ?>
                                    Tidak ada bukti
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($lapor['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>


