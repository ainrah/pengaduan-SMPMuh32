<?php
    require_once("database.php"); // koneksi DB
    require_once("auth.php"); // Session
    logged_admin ();
    if (isset($_POST['submit_surat'])) {
        $tanggal_srt = $_POST['tanggal_srt'];
        $nomor_surat = $_POST['nomor_surat'];
        $jenis_surat = $_POST['jenis_surat'];
        $nama_pendamping_srt = $_POST['nama_pendamping_srt'];
        $file_surat = $_FILES['file_surat']['name'];
    
        // Lokasi file
        $target_dir = "images/";
        $target_file = $target_dir . basename($file_surat);
    
        // Simpan file ke folder uploads
        if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
            // Validasi file upload
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
            $file_extension = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
    
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Hanya file dengan ekstensi jpg, jpeg, png, atau pdf yang diperbolehkan.'
                ]);
                exit;
            }
    
            // Tentukan nama file baru
            $file_name = uniqid('surat_', true) . '.' . $file_extension;
            $file_path = $target_dir . $file_name;
    
            // Pindahkan file ke folder upload
            if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $file_path)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengunggah file.'
                ]);
                exit;
            }
    
            // Simpan hanya nama file ke database
            $file_surat = $file_name;
    
            // Format ID Surat
            $tanggal = date('Ymd'); // Tanggal saat ini
            $sql = "SELECT MAX(CAST(SUBSTRING(id_srt_penanganan, 14) AS SIGNED)) AS last_order 
                    FROM srt_penanganan 
                    WHERE tanggal_srt = :tanggal";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':tanggal', $tanggal_srt, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $last_order = $row['last_order'] ? $row['last_order'] + 1 : 1;
            $id_srt_penanganan = "IDSP-" . $tanggal . "-" . str_pad($last_order, 6, "0", STR_PAD_LEFT);
            
            // Simpan data ke database
            $sql = "INSERT INTO srt_penanganan 
                    (id_srt_penanganan, tanggal_srt, nomor_surat, jenis_surat, nama_pendamping_srt, file_surat) 
                    VALUES 
                    (:id_srt_penanganan, :tanggal_srt, :nomor_surat, :jenis_surat, :nama_pendamping_srt, :file_surat)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_srt_penanganan', $id_srt_penanganan, PDO::PARAM_STR);
            $stmt->bindValue(':tanggal_srt', $tanggal_srt, PDO::PARAM_STR);
            $stmt->bindValue(':nomor_surat', $nomor_surat, PDO::PARAM_STR);
            $stmt->bindValue(':jenis_surat', htmlspecialchars($jenis_surat), PDO::PARAM_STR);
            $stmt->bindValue(':nama_pendamping_srt', htmlspecialchars($nama_pendamping_srt), PDO::PARAM_STR);
            $stmt->bindValue(':file_surat', $file_surat, PDO::PARAM_STR);
            $stmt->execute();
    
           
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
    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Surat Penanganan</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $divisi; ?></li>
            </ol>

           

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

            </div>
<body>
    <div class="container">
        <h3>Surat Penanganan - Tambah Data</h3>
        <form method="post" enctype="multipart/form-data">
            <label for="tanggal_srt">Tanggal Permohonan:</label>
            <input type="date" class="form-control" name="tanggal_srt" required>
            
            <label for="nomor_surat">Nomor Surat:</label>
            <input type="text" class="form-control" name="nomor_surat" required>
            
            <label for="jenis_surat">Jenis Surat:</label>
            <input type="text" class="form-control" name="jenis_surat" required>
            
            <label for="nama_pendamping_srt">Nama Pendamping:</label>
            <input type="text" class="form-control" name="nama_pendamping_srt" required>
            
            <label for="file_surat">File Surat:</label>
            <input type="file" class="form-control" name="file_surat" required>
            <br></br>
            <button type="submit" class="btn" name="submit_surat">Simpan</button>
        </form>
    </div>
</body>
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

</body>

</html>