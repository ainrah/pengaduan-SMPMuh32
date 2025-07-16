<?php
    require_once("private/database.php");
    session_start();

// Debug isi session

?>
<?php
// fungsi untuk merandom avatar profil
function RandomAvatar(){
    $photoAreas = array("avatar1.png", "avatar2.png", "avatar3.png", "avatar4.png", "avatar5.png", "avatar6.png", "avatar7.png", "avatar8.png", "avatar9.png", "avatar10.png", "avatar11.png");
    $randomNumber = array_rand($photoAreas);
    $randomImage = $photoAreas[$randomNumber];
    echo $randomImage;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <meta name="viewport" content="width=device-width">
    <title>Pengaduan SMP Muhammadiyah 32 Jakarta Barat</title>
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
</head>

<body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v2.11';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!--Success Modal Saved-->
    <div class="modal fade" id="successmodalclear" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm " role="document">
            <div class="modal-content bg-2">
                <div class="modal-header ">
                    <h4 class="modal-title text-center text-green">Sukses</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">Pengaduan Berhasil Di Kirim</p>
                    <p class="text-center">Untuk Mengetahui Status Pengaduan</p>
                    <p class="text-center">Silahkan Buka Menu <a href="lihat">Lihat Pengaduan</a> </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn button-green" onclick="location.href='index';" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php
        if(isset($_GET['status'])) {
    ?>
    <script type="text/javascript">
        $("#successmodalclear").modal();
    </script>
    <?php
        }
    ?>
    <!-- body -->
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
                    <!-- <li><a href="lihat">LIHAT PENGADUAN</a></li> -->
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
                            <!-- <li><a href="reset_password">Reset Password</a></li> -->
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

        <!-- end navbar -->

    <!-- start slider -->
    <div id="mainCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#mainCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#mainCarousel" data-slide-to="1"></li>
           
        </ol>

        <!-- Wrapper for slides -->

        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <img src="images/header_02.jpg" alt="...">
                <div class="carousel-caption welcome">
                    <h2 class="animated bounceInRight">Selamat Datang</h2>
                    <h3 class="animated bounceInLeft">Layanan PEDULIMU SMP Muhammadiyah 32 Jakarta Barat</h3>
                </div>
            </div>
            <div class="item">
                <img src="images/header_04.png" alt="...">
                <div class="carousel-caption">
                    <h2 class="animated bounceInDown">Tata Cara Pengaduan</h2>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#mainCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#mainCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!-- end Slider -->

    <!-- content -->
    
        <script>
        // When the user scrolls down 100px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                document.getElementById("top").style.display = "block";
            } else {
                document.getElementById("top").style.display = "none";
            }
        }
        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        </script>
        <!-- link to top -->

    </div>
    <!-- end main-content -->

     <!-- Footer -->
     <footer class="footer text-center">
                    <div class="row">
                        <div class="col-md-4 mb-5 mb-lg-0">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <i class="fa fa-top fa-map-marker"></i>
                                </li>
                                <li class="list-inline-item">
                                    <h4 class="text-uppercase mb-4">Alamat</h4>
                                </li>
                            </ul>
                            <p class="mb-0">
                                Jalan Keagungan RT.2/RW.1, Kec. Taman Sari<br>
                                Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11130
                            </p>
                        </div>

                        <!-- Kontak dipindahkan ke pojok kanan -->
                        <div class="kontak-pojok">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <i class="fa fa-top fa-envelope-o"></i>
                                </li>
                                <li class="list-inline-item">
                                    <h4 class="text-uppercase mb-4">Kontak</h4>
                                </li>
                            </ul>
                            <p class="mb-0">
                                087742608327 <br>
                                smpmuhammadiyah32-jkt.sch.id<br>
                            </p>
                        </div>
                    </div>
                </footer>

                <!-- /footer -->

                <div class="copyright py-4 text-center text-white">
                    <small>v-1.0 | Copyright &copy; Pengaduan SMP Muhammadiyah 32 Jakarta Barat</small>
                </div>
                <!-- shadow -->
        </div>

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.js"></script>

</body>
</html>