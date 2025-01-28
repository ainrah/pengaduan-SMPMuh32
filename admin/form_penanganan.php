<?php
require_once("database.php"); // koneksi DB
require_once("auth.php"); // Session
logged_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Hapus'])) {
    // Ambil ID dari form
    $id_hapus = filter_input(INPUT_POST, 'id_srt_penanganan', FILTER_SANITIZE_STRING);

    if (!empty($id_hapus)) {
        try {
            // Query DELETE
            $statement = $db->prepare("DELETE FROM `srt_penanganan` WHERE `id_srt_penanganan` = :id_srt_penanganan");
            $statement->bindParam(':id_srt_penanganan', $id_hapus, PDO::PARAM_STR);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                echo "<script>alert('Data berhasil dihapus.'); window.location.href = 'surat_penanganan.php';</script>";
            } else {
                echo "<script>alert('Data tidak ditemukan.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('ID tidak valid.');</script>";
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
                        $statement = $db->query("SELECT * FROM tanggapan ORDER BY id_tanggapan DESC LIMIT 1");
                        foreach ($statement as $key ) {
                            $mysqldate = $key['tanggal_tanggapan'];
                            $phpdate = strtotime($mysqldate);
                            $tanggal_tanggapan = date('d/m/Y', $phpdate);
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
   
            <!-- Breadcrumbs-->
            <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Surat Penanganan</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $divisi; ?></li>
            </ol>

            <!-- Icon Cards-->
            <div class="row">

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-inbox"></i>
                            </div>
                            <div class="mr-5">Tambah Surat Laporan</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="surat_penanganan">
                            <span class="float-left">Tambahkan Surat Laporan</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
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
            <!-- Tombol Tambah Surat -->
            
   
            <!-- Tabel Data Surat Penanganan -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Data Surat Penanganan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                                ?>
                                                
                                            </td>
                                            <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalHapus<?php echo $row['id_srt_penanganan']; ?>">
        Hapus
    </button>

                </td>
                                    </td>        
                                                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Data tidak tersedia.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
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
         <!-- Modal -->
         <div class="modal fade" id="modalHapus<?php echo $row['id_srt_penanganan']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data dengan ID <strong><?php echo $row['id_srt_penanganan']; ?></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form method="POST" action="">
                        <input type="hidden" name="id_srt_penanganan" value="<?php echo $row['id_srt_penanganan']; ?>">
                        <button type="submit" name="Hapus" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</td>
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