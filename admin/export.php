<?php
require_once("database.php");
require_once("auth.php"); // Session
logged_admin ();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Export - SMP MUHAMMADIYAH 32 Jakarta</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/admin.css" rel="stylesheet">
    <!-- Page level plugin CSS-->
    <link rel="stylesheet" type="text/css" href="vendor/datatables/extra/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/datatables/extra/buttons.dataTables.min.css">

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- export plugin JavaScript-->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/extra/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/extra/buttons.print.min.js"></script>
    <script src="vendor/datatables/extra/jszip.min.js"></script>
    <script src="vendor/datatables/extra/pdfmake.min.js"></script>
    <script src="vendor/datatables/extra/vfs_fonts.js"></script>
    <script src="vendor/datatables/extra/buttons.html5.min.js"></script>
    <script type="text/javascript"  class="init">
$(document).ready(function() {
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                title: 'Data Pengaduan',
                customize: function(win) {
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend(
                            '<img src="http://www.surabaya.bpk.go.id/wp-content/uploads/2015/07/logo-Bangkalan.png" style="opacity: 0.5; display:block;margin-left: auto; margin-top: auto; margin-right: auto; width: 100px;" />'
                        );
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'Data Pengaduan'
            },
            {
                extend: 'excel',
                title: 'Data Pengaduan'
            }
        ]
    });
});
function printAllTabs() {
    const { jsPDF } = window.jspdf;

    // Daftar tab dan judulnya
    const tabs = [
        { id: "laporan", title: "Laporan" },
        { id: "penanganan", title: "Penanganan" },
        { id: "tanggapan", title: "Tanggapan" },
        { id: "jadwal-penanganan", title: "Jadwal Penanganan" },
    ];

    tabs.forEach((tab) => {
        const table = document.querySelector(`#${tab.id} table`);
        if (table) {
            // Buat PDF untuk masing-masing tab
            const doc = new jsPDF("landscape"); // Orientasi landscape untuk tabel yang lebar

            // Tambahkan judul PDF
            doc.text(`Export Data - ${tab.title}`, 14, 10);

            // Konversi tabel ke PDF menggunakan AutoTable
            doc.autoTable({
                html: table,
                startY: 20,
                theme: "grid",
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                },
                headStyles: { fillColor: [22, 160, 133] },
            });

            // Simpan file PDF
            doc.save(`${tab.title}.pdf`);
        } else {
            console.warn(`Tabel di tab "${tab.title}" tidak ditemukan.`);
        }
    });

    alert("Semua PDF berhasil dicetak!");
}
    </script>

</head>

<body class="fixed-nav sticky-footer" id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="index">Pengaduan </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav sidebar-menu" id="exampleAccordion">

                <li class="sidebar-profile nav-item" data-toggle="tooltip" data-placement="right" title="Admin">
                    <div class="profile-main">
                        <p class="image">
                            <img alt="image" src="images/avatar1.png" width="80">
                            <span class="status"><i class="fa fa-circle text-success"></i></span>
                        </p>
                        <p>
                            <span class="">Admin</span><br>
                            <span class="user" style="font-family: monospace;"><?php echo $divisi; ?></span>
                        </p>
                    </div>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <a class="nav-link" href="index">
                        <i class="fa fa-fw fa-dashboard"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item dropdown" data-toggle="tooltip" data-placement="right" title="Penanganan">
                    <a class="nav-link dropdown-toggle" href="#" id="penangananDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-archive"></i>
                        <span class="nav-link-text">Penanganan</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="penangananDropdown">
                        <a class="dropdown-item" href="form_penanganan">Surat Penanganan</a>
                        <a class="dropdown-item" href="form_jadwal">Jadwal Penanganan</a>
                    </div>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Export">
                    <a class="nav-link" href="export">
                        <i class="fa fa-fw fa-print"></i>
                        <span class="nav-link-text">Data Arsip</span>
                    </a>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Version">
                    <a class="nav-link" href="#VersionModal" data-toggle="modal" data-target="#VersionModal">
                        <i class="fa fa-fw fa-code"></i>
                        <span class="nav-link-text">v-1.0</span>
                    </a>
                </li>
            </ul>

            </ul>
            <ul class="navbar-nav sidenav-toggler">
                <li class="nav-item">
                    <a class="nav-link text-center" id="sidenavToggler">
                        <i class="fa fa-fw fa-angle-left"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-envelope"></i>
                        <span class="d-lg-none">Messages
                            <span class="badge badge-pill badge-primary">12 New</span>
                        </span>
                        <span class="indicator text-primary d-none d-lg-block">
                            <i class="fa fa-fw fa-circle"></i>
                        </span>
                    </a>
                    <?php
                        $statement = $db->query("SELECT * FROM laporan ORDER BY laporan.id_laporan DESC LIMIT 1");
                        foreach ($statement as $key ) {
                            $mysqldate = $key['tanggal_pengaduan'];
                            $phpdate = strtotime($mysqldate);
                            $tanggal = date( 'd/m/Y', $phpdate);
                     ?>
                    <div class="dropdown-menu" aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">Laporan Baru :</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <strong><?php echo $key['nama']; ?></strong>
                            <span class="small float-right text-muted"><?php echo $key['tanggal']; ?></span>
                            <div class="dropdown-message small"><?php echo $key['isi']; ?></div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item small" href="#">View all messages</a> -->
                    </div>
                    <?php
                        }
                     ?>

                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-fw fa-sign-out"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <style>
@media print {
    @page {
        size: A4 landscape; /* Orientasi landscape */
        margin: 10mm;
    }
    body {
        font-size: 12px; /* Ukuran teks lebih kecil */
    }
    table {
        width: 100%; /* Pastikan tabel memenuhi halaman */
        border-collapse: collapse;
    }
    th, td {
        word-wrap: break-word; /* Pecah kata panjang */
        padding: 5px;
        font-size: 10px; /* Ukuran teks tabel lebih kecil */
    }
    .nav-tabs, .btn {
        display: none; /* Sembunyikan elemen yang tidak perlu saat print */
    }
    table {
    table-layout: fixed;
    width: 100%; /* Tabel selalu penuh */
}
th, td {
    font-size: 10px; /* Ukuran font lebih kecil */
    text-align: left;
    word-wrap: break-word; /* Pecah kata jika terlalu panjang */
}
}

</style>
    <!-- Body -->
    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Ekspor</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $divisi; ?></li>
            </ol>
            <div class="container my-4">
        <h1 class="text-center mb-4">Export Data</h1>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="laporan-tab" data-toggle="tab" href="#laporan" role="tab">Laporan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="penanganan-tab" data-toggle="tab" href="#penanganan" role="tab">Penanganan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="tanggapan-tab" data-toggle="tab" href="#tanggapan" role="tab">Tanggapan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="surat-tab" data-toggle="tab" href="#surat" role="tab">Surat Penanganan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="jadwal-tab" data-toggle="tab" href="#jadwal" role="tab">Jadwal Penanganan</a>
    </li>

</ul>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manajemen Laporan</h4>
    <button onclick="printAllTabs()" class="btn btn-primary">Cetak Semua Tab</button>
    <button onclick="window.print()" class="btn btn-primary">Cetak</button>
    <button onclick="generatePDF()">Unduh PDF</button>
</div>
            <!-- DataTables Card
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Cetak Laporan Masuk
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="example" width="100%">
                            <thead>
                                <tr>
                                <th>Nama Pelapor</th>
                                <th>No HP Pelapor</th>
                                <th>Kelas Pelapor</th>
                                <th>Nama Korban</th>
                                <th>No HP Korban</th>
                                <th>Kelas Korban</th>
                                <th>Nama Pelaku</th>
                                <th>No HP Pelaku</th>
                                <th>Tanggal Pengaduan</th>
                                <th>Tanggal Kejadian</th>
                                <th>Tempat Kejadian</th>
                                <th>Kategori Kekerasan</th>
                                <th>Subjek Pengaduan</th>
                                <th>Kronologi Kejadian</th>
                                <th>Bukti Kekerasan</th>
                                <th>Status</th>
                                    <th class="sorting_asc_disabled sorting_desc_disabled">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Ambil semua record dari tabel laporan
                              

                                foreach ($statement as $key) {
                                    $mysqldate = $key['tanggal_pengaduan'];
                                    $phpdate = strtotime($mysqldate);
                                    $tanggal = date('d/m/Y', $phpdate);
                                
                                    $status  = $key['status'];
                                    $style_status = ($status == "menunggu") 
                                        ? "<p style=\"background-color:#009688;color:#fff;padding-left:2px;padding-right:2px;padding-bottom:2px;margin-top:16px;font-size:15px;font-style:italic;\">Ditanggapi</p>" 
                                        : "<p style=\"background-color:#FF9800;color:#fff;padding-left:2px;padding-right:2px;padding-bottom:2px;margin-top:16px;font-size:15px;font-style:italic;\">Menunggu</p>";
                                }
                                
                                    ?>
                                    <tr>
                                    <td><?php echo $key['id_laporan']; ?></td>
                                    <td><?php echo $key['nama_plp']; ?></td>
                                    <td><?php echo $key['no_hp_plp']; ?></td>
                                    <td><?php echo $key['kls_plp']; ?></td>
                                    <td><?php echo $key['nama_krb']; ?></td>
                                    <td><?php echo $key['no_hp_krb']; ?></td>
                                    <td><?php echo $key['kls_krb']; ?></td>
                                    <td><?php echo $key['nama_plk']; ?></td>
                                    <td><?php echo $key['no_hp_plk']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($key['tanggal_pengaduan'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($key['tanggal_kejadian'])); ?></td>
                                    <td><?php echo $key['tempat_kejadian']; ?></td>
                                    <td><?php echo $key['kategori_kekerasan']; ?></td>
                                    <td><?php echo $key['subjek_pengaduan']; ?></td>
                                    <td><?php echo $key['kronologi_kejadian']; ?></td>
                                    <td><?php echo $key['status']; ?></td>
                                     
                                    </tr>
                                    <?php
                              
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
        </div>
        /.container-fluid

        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright © SMP MUHAMMADIYAH 32 Jakarta</small>
                </div>
            </div>
        </footer> -->


        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fa fa-angle-up"></i>
        </a>

  <!-- Tab Penanganan -->
  <div class="tab-content" id="myTabContent">
    <!-- Laporan Tab Content -->
    <div id="laporan" class="tab-pane fade show active" role="tabpanel" aria-labelledby="laporan-tab">
        <h3>Laporan</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                    <th>Nama Pelapor</th>
                    <th>No HP Pelapor</th>
                    <th>Kelas Pelapor</th>
                    <th>Nama Korban</th>
                    <th>No HP Korban</th>
                    <th>Kelas Korban</th>
                    <th>Nama Pelaku</th>
                    <th>No HP Pelaku</th>
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
                <tr>
                <?php
                                // Ambil semua record dari tabel laporan
                              

                                $sql = "SELECT * FROM laporan ORDER BY id_laporan DESC";
                                $statement = $db->query($sql);
                                
                                // Ambil data
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua data dalam bentuk array asosiatif
                                
                                // Jika ada data
                                if (!empty($result)) {
                                    foreach ($result as $key) {
                                        $tanggal_pengaduan = !empty($key['tanggal_pengaduan']) ? date('d/m/Y', strtotime($key['tanggal_pengaduan'])) : '-';
                                        $tanggal_kejadian = !empty($key['tanggal_kejadian']) ? date('d/m/Y', strtotime($key['tanggal_kejadian'])) : '-';
                                    }
                                }
                                    ?>

                                
        
                                   
                                     <tr>
            <td><?php echo $key['nama_plp']; ?></td>
            <td><?php echo $key['no_hp_plp']; ?></td>
            <td><?php echo $key['kls_plp']; ?></td>
            <td><?php echo $key['nama_krb']; ?></td>
            <td><?php echo $key['no_hp_krb']; ?></td>
            <td><?php echo $key['kls_krb']; ?></td>
            <td><?php echo $key['nama_plk']; ?></td>
            <td><?php echo $key['no_hp_plk']; ?></td>
            <td><?php echo $tanggal_pengaduan; ?></td>
            <td><?php echo $tanggal_kejadian; ?></td>
            <td><?php echo $key['tempat_kejadian']; ?></td>
            <td><?php echo $key['kategori_kekerasan']; ?></td>
            <td><?php echo $key['subjek_pengaduan']; ?></td>
            <td><?php echo $key['kronologi_kejadian']; ?></td>
            <td>
                        <?php 
                        $bukti = $key['bukti_kekerasan']; // Nama file dari database
                        $file_path = "images/$bukti"; // Jalur relatif untuk browser

                        if (!empty($bukti) && file_exists(__DIR__ . "/images/$bukti")) {
                            $file_extension = strtolower(pathinfo($bukti, PATHINFO_EXTENSION));

                            // Jika file adalah gambar
                            if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                                echo "<a href='$file_path' target='_blank'>
                                        <img src='$file_path' alt='Bukti Kekerasan' style='width: 100px; height: auto;'>
                                    </a>";
                            } 
                            // Jika file adalah PDF
                            elseif ($file_extension === 'pdf') {
                                echo "<a href='$file_path' target='_blank'>Lihat PDF</a>";
                            }
                        } else {
                            echo "Tidak ada bukti";
                        }
                        ?>
                    </td>
            <td><?php echo $key['status']; ?></td> <!-- Status ditampilkan tanpa gaya -->
        </tr>
                                    <?php
                              
                                ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Penanganan Tab Content -->
    <div id="penanganan" class="tab-pane fade" role="tabpanel" aria-labelledby="penanganan-tab">
        <h3>Penanganan</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                                    <th>ID Surat Penanganan</th>
                                    <th>Tanggal Surat</th>
                                    <th>Nomor Surat</th>
                                    <th>Jenis Surat</th>
                                    <th>Nama Pendamping</th>
                                    <th>File Surat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query database
                                $sql = "SELECT * FROM srt_penanganan ORDER BY id_srt_penanganan DESC";
                                $statement = $db->query($sql);

                                // Ambil data
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                // Tampilkan data
                                if (!empty($result)) {
                                    foreach ($result as $row) {
                                        $tanggal_srt = !empty($row['tanggal_srt']) ? date('d/m/Y', $row['tanggal_srt']) : '-';
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id_srt_penanganan']; ?></td>
                                            <td><?php echo $tanggal_srt; ?></td>
                                            <td><?php echo $row['nomor_surat']; ?></td>
                                            <td><?php echo $row['jenis_surat']; ?></td>
                                            <td><?php echo $row['nama_pendamping_srt']; ?></td>
                                            <td>
                                                <?php 
                                                $file_surat = $row['file_surat'];
                                                $file_path = "images/$file_surat"; // Jalur file relatif
                                                
                                                if (!empty($file_surat) && file_exists($file_path)) {
                                                    $file_extension = strtolower(pathinfo($file_surat, PATHINFO_EXTENSION));

                                                    // Jika file adalah gambar
                                                    if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                                                        echo "<a href='$file_path' target='_blank'>
                                                                <img src='$file_path' alt='File Surat' style='width: 100px; height: auto;'>
                                                            </a>";
                                                    }
                                                    // Jika file adalah PDF
                                                    elseif ($file_extension === 'pdf') {
                                                        echo "<a href='$file_path' target='_blank'>Lihat PDF</a>";
                                                    }
                                                } else {
                                                    echo "Tidak ada bukti";
                                                }
                                            }
                                        }
                                                ?>
                                                
                                            </td>
                                            <tr>
            </tbody>
        </table>
    </div>

    <!-- Tanggapan Tab Content -->
    <div id="tanggapan" class="tab-pane fade" role="tabpanel" aria-labelledby="tanggapan-tab">
        <h3>Penanganan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Surat Penanganan</th>
                <th>Tanggal Surat</th>
                <th>Nomor Surat</th>
                <th>Jenis Surat</th>
                <th>Nama Pendamping</th>
                <th>File Surat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query database
            $sql = "SELECT * FROM jdwl_penanganan ORDER BY id_jadwal DESC";
            $statement = $db->query($sql);

            // Ambil data
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Tampilkan data
            if (!empty($result)) {
                foreach ($result as $row) {
                    // Format tanggal
                    $tanggal_srt = !empty($row['tanggal_penanganan']) ? date('d/m/Y', strtotime($row['tanggal_penanganan'])) : '-';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_jadwal']); ?></td>
                        <td><?php echo htmlspecialchars($tanggal_srt); ?></td>
                        <td><?php echo htmlspecialchars($row['lokasi_penanganan']); ?></td>
                        <td><?php echo htmlspecialchars($row['unggah_surat_rujukan']); ?></td>
                        
                        <td>
                            <?php
                            $file_surat = $row['file_surat'];
                            $file_path = "uploads/$file_surat"; // Pastikan folder "uploads" ada dan berisi file

                            if (!empty($file_surat) && file_exists($file_path)) {
                                $file_extension = strtolower(pathinfo($file_surat, PATHINFO_EXTENSION));

                                // Jika file adalah gambar
                                if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                                    echo "<a href='$file_path' target='_blank'>
                                            <img src='$file_path' alt='File Surat' style='width: 100px; height: auto;'>
                                          </a>";
                                }
                                // Jika file adalah PDF
                                elseif ($file_extension === 'pdf') {
                                    echo "<a href='$file_path' target='_blank'>Lihat PDF</a>";
                                }
                            } else {
                                echo "Tidak ada bukti";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data tersedia</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $('#laporanTable').DataTable();
        });
    </script>
</body>
</html>
        <!-- Logout Modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Ingin Keluar?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Pilih "Logout" jika anda ingin mengakhiri sesi.</div>
                    <div class="modal-footer">
                        <button class="btn btn-close card-shadow-2 btn-sm" type="button" data-dismiss="modal">Batal</button>
                        <a class="btn btn-primary btn-sm card-shadow-2" href="logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Version Info Modal -->
        <!-- Modal -->
        <div class="modal fade" id="VersionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Admin Versi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 style="text-align : center;">V-6.0</h5>
                        <p style="text-align : center;">Copyright © SMP MUHAMMADIYAH 32 Jakarta</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close card-shadow-2 btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/admin.js"></script>
        <!-- Custom scripts for this page-->
        <script src="js/admin-datatables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

    </div>
    <!-- /.content-wrapper-->
    
</body>

</html>
