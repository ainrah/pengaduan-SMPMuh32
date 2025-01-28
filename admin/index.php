<?php
    require_once("database.php"); // koneksi DB
    require_once("auth.php"); // Session
    logged_admin ();
    global $total_laporan_masuk, $total_laporan_menunggu, $total_laporan_ditanggapi;
    if ($id_admin > 0) {
        
        foreach($db->query("SELECT COUNT(*) FROM laporan WHERE status = \"Ditanggapi\" ") as $row) {
            $total_laporan_ditanggapi = $row['COUNT(*)'];
        }

        foreach($db->query("SELECT COUNT(*) FROM laporan WHERE status = \"Menunggu\" ") as $row) {
            $total_laporan_menunggu = $row['COUNT(*)'];
        }
    } else {
        foreach($db->query("SELECT COUNT(*) FROM laporan") as $row) {
            $total_laporan_masuk = $row['COUNT(*)'];
        }

        foreach($db->query("SELECT COUNT(*) FROM laporan WHERE status = \"Divalidasi\"") as $row) {
            $total_laporan_ditanggapi = $row['COUNT(*)'];
        }

        foreach($db->query("SELECT COUNT(*) FROM laporan WHERE status = \"Menunggu\"") as $row) {
            $total_laporan_menunggu = $row['COUNT(*)'];
        }
    } 
    if ($id_admin > 0) {
        // Menghitung total data dari tabel srt_laporan
        foreach ($db->query("SELECT COUNT(*) FROM srt_penanganan") as $row) {
            $total_srt_laporan = $row['COUNT(*)'];
        }
    
        // Menghitung total data dari tabel jdwl_penanganan
        foreach ($db->query("SELECT COUNT(*) FROM jdwl_penanganan") as $row) {
            $total_jdwl_penanganan = $row['COUNT(*)'];
        }
    } else {
        // Menghitung total data dari tabel srt_laporan
        foreach ($db->query("SELECT COUNT(*) FROM srt_penanganan") as $row) {
            $total_srt_laporan = $row['COUNT(*)'];
        }
    
        // Menghitung total data dari tabel jdwl_penanganan
        foreach ($db->query("SELECT COUNT(*) FROM jdwl_penanganan") as $row) {
            $total_jdwl_penanganan = $row['COUNT(*)'];
        }
    }
    // Ambil nama gambar dari parameter URL
if (isset($_GET['image'])) {
    $image = $_GET['image'];
    $imagePath = "C:/xampp/htdocs/PengaduanSMPM32/images/" . $image;

    // Cek jika gambar ada di folder images
    if (file_exists($imagePath)) {
        // Tampilkan gambar
        header('Content-Type: C:/xampp/htdocs/PengaduanSMPM32/images/'); // Sesuaikan dengan tipe gambar (png, jpg, gif, dll.)
        readfile($imagePath);
        exit;
    } else {
        echo "Gambar tidak ditemukan.";
    }
}


 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon-16x16.ico">
    <title>Dashboard - Pengaduan</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/admin.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (popper.js and bootstrap.js required) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
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
                        <span class="d-lg-none">Laporan
                            <span class="badge badge-pill badge-primary">1 Baru</span>
                        </span>
                        <span class="indicator text-primary d-none d-lg-block">
                            <i class="fa fa-fw fa-circle"></i>
                        </span>
                    </a>
                    <?php
                        $statement = $db->query("SELECT * FROM laporan ORDER BY id_laporan DESC LIMIT 1");
                        foreach ($statement as $key ) {
                            $mysqldate = $key['tanggal_pengaduan'];
                            $phpdate = strtotime($mysqldate);
                            $tanggal_pengaduan = date( 'd/m/Y', $phpdate);
                     ?>
                    <div class="dropdown-menu" aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">Laporan Baru :</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <strong><?php echo $key['nama_plp']; ?></strong>
                            <span class="small float-right text-muted"><?php echo $key['tanggal_pengaduan']; ?></span>
                            <div class="dropdown-message small"><?php echo $key['kronologi_kejadian']; ?></div>
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


    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $divisi; ?></li>
            </ol>

            <!-- Icon Cards-->
            <div class="row">

            <div class="col-xl-2 col-sm-1 mb-1">
                    <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-comments-o"></i>
                            </div>
                            <div class="mr-5"><?php echo $total_laporan_masuk; ?> Laporan Masuk</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="tables">
                            <span class="float-left">Total Laporan Masuk</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-xl-2 col-sm-1 mb-1">
                    <div class="card text-white bg-danger o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-minus-circle"></i>
                            </div>
                            <div class="mr-5"><?php echo $total_laporan_menunggu; ?> Belum Divalidasi</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="tables">
                            <span class="float-left">Belum Divalidasi</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-xl-2 col-sm-1 mb-1">
                    <div class="card text-white bg-success o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-check-square"></i>
                            </div>
                            <div class="mr-5"><?php echo $total_laporan_ditanggapi; ?> Belum Di Tanggapi</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="tablestanggapi">
                            <span class="float-left">Sudah Validasi</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                  <div class="col-xl-2 col-sm-1 mb-1">
                    <div class="card text-white bg-warning o-hidden h-100">
                        <div class="card-body">
                        
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-check-square"></i>
                            </div>
                            <div class="mr-5"><?php echo $total_srt_laporan; ?> Total Surat</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="form_penanganan">
                            <span class="float-left">Lihat Form Surat</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                </div>
               
            <!-- <div class="col-xl-2 col-sm-6 mb-3">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fa fa-fw fa-print"></i>
                        </div>
                        <div class="mr-5">Print</div>
                    </div>
                   <a class="card-footer text-white clearfix small z-1 print-button" onclick="printTable()">
                        <span class="float-left">Print Table</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </button>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fa fa-fw fa-print"></i>
                        </div>
                        <div class="mr-5">Print</div>
                    </div>
                   <a class="card-footer text-white clearfix small z-1 print-button" onclick="printTable()">
                        <span class="float-left">Print Table</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </button>
                    </a>
                </div> -->
            <!-- </div> -->
                <!-- <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-warning o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-support"></i>
                            </div>
                            <div class="mr-5">13 New Tickets!</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                            <span class="float-left">Laporan Masuk</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div> -->

            <!-- </div> -->
            <!-- ./Icon Cards-->
            
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Semua Laporan
                </div>
                <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
            <?php
// Query database
$sql = "SELECT * FROM laporan ORDER BY id_laporan DESC";
$statement = $db->query($sql);

// Ambil data
$result = $statement->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua data dalam bentuk array asosiatif

// Jika ada data
if (!empty($result)) {
    foreach ($result as $key) {
        $tanggal_pengaduan = !empty($key['tanggal_pengaduan']) ? date('d/m/Y', strtotime($key['tanggal_pengaduan'])) : '-';
        $tanggal_kejadian = !empty($key['tanggal_kejadian']) ? date('d/m/Y', strtotime($key['tanggal_kejadian'])) : '-';
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
    }
}
?>

       
 


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
        </div>
        <!-- /.container-fluid-->

        <!-- /.content-wrapper-->
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright © SMP MUHAMMADIYAH 32 Jakarta</small>
                </div>
            </div>
        </footer>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fa fa-angle-up"></i>
        </a>

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
                        <h5 style="text-align : center;">V-1.0</h5>
                        <p style="text-align : center;">Copyright © SMP MUHAMMADIYAH 32</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close card-shadow-2 btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Page level plugin JavaScript-->
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/admin.js"></script>
        <!-- Custom scripts for this page-->
        <script src="js/admin-datatables.js"></script>

    </div>
    <script>
                function printTable() {
            // Ambil elemen tabel berdasarkan ID yang benar
            var tableContent = document.getElementById('dataTable').outerHTML;

            // Buka jendela baru untuk mencetak
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Print Tabel</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('table, th, td { border: 1px solid black; }');
            printWindow.document.write('th, td { padding: 8px; text-align: left; }');
            printWindow.document.write('th { background-color: #f2f2f2; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(tableContent);
            printWindow.document.write('</body></html>');

            // Cetak isi jendela
            printWindow.document.close();
            printWindow.print();
        }

    </script>
</body>

</html>
