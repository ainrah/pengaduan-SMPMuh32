<?php
    // database
    require_once("database.php");
    require_once("auth.php"); // Session
    logged_admin ();
    // global var
    global $nomor, $foundreply;
    // hapus Balasan laporan berdasarkan id Balasan laporan


    if (isset($_POST['Hapus'])) {
        $id_hapus = $_POST['id_laporan'];
    
        // Menyiapkan statement DELETE
        $statement = $db->prepare("DELETE FROM `laporan` WHERE `id_laporan` = :id_laporan");
        $statement->bindParam(':id_laporan', $id_hapus, PDO::PARAM_INT);
        $statement->execute();
    }
    if (isset($_POST['Balas'])) {
        // Insert tabel tanggapan
        
        $id_laporan = intval($_POST['id_laporan']);
        $id_laporan = $_POST['id_laporan'];
        $isi_tanggapan = $_POST['isi_tanggapan'];
        $admin = "Admin";
        $cerita_real_krb = $_POST['cerita_real_krb'];
        $kasus_penanganan = $_POST['kasus_penanganan'];
        $nama_pendamping = $_POST['nama_pendamping'];
    
        $sql = "INSERT INTO `tanggapan` 
                (`id_tanggapan`, `id_laporan`, `admin`, `isi_tanggapan`, `tanggal_tanggapan`, `cerita_real_krb`, `kasus_penanganan`, `nama_pendamping`) 
                VALUES 
                (NULL, :id_laporan, :admin, :isi_tanggapan, CURRENT_TIMESTAMP, :cerita_real_krb, :kasus_penanganan, :nama_pendamping)";
    
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_laporan', $id_laporan, PDO::PARAM_INT);
        $stmt->bindValue(':admin', $admin, PDO::PARAM_STR);
        $stmt->bindValue(':isi_tanggapan', htmlspecialchars($isi_tanggapan), PDO::PARAM_STR);
        $stmt->bindValue(':cerita_real_krb', htmlspecialchars($cerita_real_krb), PDO::PARAM_STR);
        $stmt->bindValue(':kasus_penanganan', htmlspecialchars($kasus_penanganan), PDO::PARAM_STR);
        $stmt->bindValue(':nama_pendamping', htmlspecialchars($nama_pendamping), PDO::PARAM_STR);
        $stmt->execute();
    
        // Jika ada tanggapan, update status laporan menjadi 'Ditanggapi'
        $sql = "UPDATE `laporan` SET `status` = 'Ditanggapi' WHERE `id_laporan` = :id_laporan";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_laporan', intval($id_laporan), PDO::PARAM_INT);
        $stmt->execute();
        // Redirect ke page tables
        // header("Location: tables");
    }

    if (isset($_POST['submit_penanganan'])) {
        // Ambil data dari form
        $id_laporan = intval($_POST['id_laporan']);
        $jenis_penanganan = $_POST['jenis_penanganan'];
        $tanggal_penanganan = $_POST['tanggal_penanganan'];
        $alamat_penanganan = $_POST['alamat_penanganan'];
        $nama_pendamping = $_POST['nama_pendamping'];
        $nomor_hp_pendamping = $_POST['nomor_hp_pendamping'];
        
        // Format tanggal
        $tanggal = date('Y-m-d'); // Menggunakan tanggal saat ini
        
        try {
            // Ambil nomor urut terakhir berdasarkan tanggal yang sama
            $sql = "SELECT MAX(CAST(SUBSTRING(id_penanganan, 15) AS UNSIGNED)) AS last_order
                    FROM penanganan
                    WHERE tanggal_penanganan = :tanggal";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':tanggal', $tanggal, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $last_order = $row['last_order'] ? $row['last_order'] + 1 : 1; // Jika tidak ada, mulai dari 1
    
            // Format id_penanganan
            $id_penanganan = 'IDP-' . $tanggal . '-' . str_pad($last_order, 6, '0', STR_PAD_LEFT);
    
            // Insert data ke tabel penanganan
            $sql = "INSERT INTO `penanganan` 
                    (`id_penanganan`, `id_laporan`, `jenis_penanganan`, `tanggal_penanganan`, 
                     `alamat_penanganan`, `nama_pendamping`, `nomor_hp_pendamping`) 
                    VALUES 
                    (:id_penanganan, :id_laporan, :jenis_penanganan, :tanggal_penanganan, 
                     :alamat_penanganan, :nama_pendamping, :nomor_hp_pendamping)";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_penanganan', $id_penanganan, PDO::PARAM_STR);
            $stmt->bindValue(':id_laporan', $id_laporan, PDO::PARAM_INT);
            $stmt->bindValue(':jenis_penanganan', htmlspecialchars($jenis_penanganan), PDO::PARAM_STR);
            $stmt->bindValue(':tanggal_penanganan', $tanggal_penanganan, PDO::PARAM_STR);
            $stmt->bindValue(':alamat_penanganan', htmlspecialchars($alamat_penanganan), PDO::PARAM_STR);
            $stmt->bindValue(':nama_pendamping', htmlspecialchars($nama_pendamping), PDO::PARAM_STR);
            $stmt->bindValue(':nomor_hp_pendamping', htmlspecialchars($nomor_hp_pendamping), PDO::PARAM_STR);
            $stmt->execute();
    
            // Perbarui status laporan menjadi 'Ditangani'
            $sql = "UPDATE `laporan` SET `status` = 'Ditangani' WHERE `id_laporan` = :id_laporan";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_laporan', $id_laporan, PDO::PARAM_INT);
            $stmt->execute();
    
            // Redirect ke halaman lain atau tampilkan pesan sukses
            header("Location: penanganan.php?success=1");
            exit;
    
        } catch (PDOException $e) {
            // Tangani error jika terjadi masalah
            echo "Error: " . $e->getMessage();
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
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Table - SMP MUHAMMADIYAH 32 Jakarta</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/admin.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer" id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="index">SMP MUHAMMADIYAH 32 Jakarta</a>
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

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Export">
                    <a class="nav-link" href="export">
                        <i class="fa fa-fw fa-print"></i>
                        <span class="nav-link-text">Ekspor</span>
                    </a>
                </li>

                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Version">
                    <a class="nav-link" href="#VersionModal" data-toggle="modal" data-target="#VersionModal">
                        <i class="fa fa-fw fa-code"></i>
                        <span class="nav-link-text">v-6.0</span>
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


    <!-- Body -->
    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Kelola</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $divisi; ?></li>
            </ol>

            <!-- DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Laporan Masuk
                </div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

<?php
// Ambil semua record dari tabel laporan

    
        $statement = $db->query("SELECT * FROM `laporan` ORDER BY id_laporan DESC");
    
        foreach ($statement as $key ) {
        $mysqldate = $key['tanggal_pengaduan'];
        $phpdate = strtotime($mysqldate);
        $tanggal = date( 'd/m/Y', $phpdate);
        $status  = $key['status'];
        if($status == "Ditanggapi") {
            $style_status = "<p style=\"background-color:#009688;color:#fff;padding-left:2px;padding-right:2px;padding-bottom:2px;margin-top:16px;font-size:15px;font-style:italic;\">Ditanggapi</p>";
        } else {
            $style_status = "<p style=\"background-color:#FF9800;color:#fff;padding-left:2px;padding-right:2px;padding-bottom:2px;margin-top:16px;font-size:15px;font-style:italic;\">Menunggu</p>";
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
                                <td><?php echo $key['bukti_kekerasan']; ?></td>
                                <td><?php echo $key['status']; ?></td>
                                <td class="td-no-border">
                    
                 <button type="button" class="btn btn-primary-custom btn-sm btn-custom card-shadow-2" data-toggle="modal" data-target="#ModalPenanganan<?php echo $key['id_laporan']; ?>">
                         Penanganan
                </button>
                
                </td>
        <td class="td-no-border">
            <button type="button" class="btn btn-primary-custom btn-sm btn-custom card-shadow-2" data-toggle="modal" data-target="#ModalBalas<?php echo $key['id_laporan']; ?>">
             Validasi
            </button>
        </td>
        <td class="td-no-border">
            <button type="button" class="btn btn-danger btn-sm btn-custom card-shadow-2" data-toggle="modal" data-target="#ModalHapus<?php echo $key['id_laporan']; ?>">
                 Hapus
            </button>
        </td>
<?php
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

        <!-- Isi masing2 modal, detail, balas dan hapus -->
      <!-- Modal Tanggapan -->
            <div<div class="modal fade" id="ModalBalas<?php echo $key['id_laporan']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Balas Laporan</h5>
                    </div>
                    <div class="modal-body">    
                        <form  method="post">
                            <div class="form-group">
                                <p><b>Nama Pelapor:</b></p>
                                <?php echo $key['nama_plp']; ?>
                                <hr>
                            </div>
                            <div class="form-group">
                                <p><b>Isi Laporan :</b></p>
                                <p>"<?php echo $key['kronologi_kejadian']; ?>"</p>
                                <hr>
                            </div>
                            <div class="form-group">
                                <p><b>Tanggapan :</b></p>
                                <textarea class="form-control" name="isi_tanggapan" placeholder="Isi Tanggapan" required></textarea>
                            </div>
                            <div class="form-group">
                                 <p><b>Tanggapan :</b></p>
                                <textarea class="form-control" name="id_laporan" placeholder=<?php echo $key['id_laporan']; ?> readonly></textarea>
                            </div>
                            <div class="form-group">
                                <p><b>Tanggapan :</b></p>
                                <textarea class="form-control" name="cerita_real_krb" placeholder="Isi Tanggapan" required></textarea>
                            </div>
                            <div class="form-group">
                                <p><b>Tanggapan :</b></p>
                                <textarea class="form-control" name="kasus_penanganan" placeholder="Isi Tanggapan" required></textarea>
                            </div>
                           
                            <div class="form-group">
                                <p><b>Tanggapan :</b></p>
                                <textarea class="form-control" name="nama_pendamping" placeholder="Isi Tanggapan" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id_laporan" value="<?php echo $key['id_laporan']; ?>">
                                <input type="submit" class="btn btn-primary-custom card-shadow-2 btn-sm" name="Balas" value="Balas">
                                <button type="button" class="btn btn-close btn-sm card-shadow-2" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Detail laporan akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Penanganan -->
<div class="modal fade" id="ModalPenanganan<?php echo $key['id_laporan']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penanganan</h5>
            </div>
            <div class="modal-body">
                <form method="post">
                    <!-- ID Laporan -->
                    <div class="form-group">
                        <label for="id_penanganan"><b>ID Penanganan:</b></label>
                        <input type="text" class="form-control" name="id_penanganan" value="<?php echo isset($id_penanganan) ? $id_penanganan : ''; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="id_laporan"><b>ID Laporan:</b></label>
                        <input type="text" class="form-control" name="id_laporan" value="<?php echo $key['id_laporan']; ?>" readonly>
                    </div>

                    <!-- Jenis Penanganan -->
                    <div class="form-group">
                        <label for="jenis_penanganan"><b>Jenis Penanganan:</b></label>
                        <textarea class="form-control" name="jenis_penanganan" placeholder="Jenis Penanganan" required></textarea>
                    </div>
                  
                    <!-- Tanggal Penanganan -->
                    <div class="form-group">
                        <label for="tanggal_penanganan"><b>Tanggal Penanganan:</b></label>
                        <input type="date" class="form-control" name="tanggal_penanganan" required>
                    </div>

                    <!-- Alamat Penanganan -->
                    <div class="form-group">
                        <label for="alamat_penanganan"><b>Alamat Penanganan:</b></label>
                        <textarea class="form-control" name="alamat_penanganan" placeholder="Alamat Penanganan" required></textarea>
                    </div>

                    <!-- Nama Pendamping -->
                    <div class="form-group">
                        <label for="nama_pendamping"><b>Nama Pendamping:</b></label>
                        <input type="text" class="form-control" name="nama_pendamping" placeholder="Nama Pendamping" required>
                    </div>

                    <!-- Nomor HP Pendamping -->
                    <div class="form-group">
                        <label for="nomor_hp_pendamping"><b>Nomor HP Pendamping:</b></label>
                        <input type="text" class="form-control" name="nomor_hp_pendamping" placeholder="Nomor HP Pendamping" required>
                    </div>

                    <!-- Penanganan -->
                   

                    <!-- Submit -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-custom card-shadow-2 btn-sm" name="submit_penanganan">Simpan</button>
                        <button type="button" class="btn btn-close btn-sm card-shadow-2" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="ModalHapus<?php echo $key['id_laporan']; ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title text-center">Hapus Laporan</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">Hapus Pengaduan</p>
                        <p class="text-center">Dari <b><?php echo $key['nama_plp']; ?></b> ?</p>
                    </div>
                    <div class="modal-footer">
                        <form method="post">
                            <input type="hidden" name="id_laporan" value="<?php echo $key['id_laporan']; ?>">
                            <input type="submit" class="btn btn-danger btn-sm card-shadow-2" name="Hapus" value="Hapus">
                            <button type="button" class="btn btn-close btn-sm card-shadow-2" data-dismiss="modal">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ./Modal Hapus-->
        <?php
            
        ?>

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
                        <h5 style="text-align : center;">V-6.0</h5>
                        <p style="text-align : center;">Copyright © SMP MUHAMMADIYAH 32 Jakarta</p>
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
    <!-- /.content-wrapper-->
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const elems = document.querySelectorAll('.modal');
    M.Modal.init(elems);
});
</script>
