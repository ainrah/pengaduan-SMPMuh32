<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Kontak | SMP Muhammadiyah 32 Jakarta Barat</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Main Styles CSS -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <?php
    require_once("private/database.php");
    session_start();
    include('header.php');
    include('navbar.php');
    ?>
            <!-- content -->
            <div class="main-content">
                <h3>Kontak</h3>
                <hr/>
                <div class="row">
                    <div class="col-md-6">
                        <div id="map" class="card-shadow-2" style="width:100%;height:300px"></div>
                            <script>
                            function myMap() {
                                  var mapCanvas = document.getElementById("map");
                                  var myCenter = new google.maps.LatLng(-6.149878, 106.812317);
                                  var mapOptions = {center: myCenter, zoom: 18};
                                  var map = new google.maps.Map(mapCanvas,mapOptions);
                                  var marker = new google.maps.Marker({
                                    position: myCenter,
                                    animation: google.maps.Animation.BOUNCE
                                  });
                                  marker.setMap(map);
                            }
                            </script>
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXyK9sf3rI0EKVupuALaOAzq1NKlUES98&callback=myMap"></script>
                    </div>
                   
                    <div class="col-md-6"></div>
                </div>
                <hr>
                <h4>Alamat Sekolah</h4>
                <p>Jalan Keagungan RT.2/RW.1, Kec. Taman Sari </p>
                <p>Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11130</p>
                <hr>
                <h4>Contact Info:</h4>
                <p>087742608327</p>
                <p>smpmuhammadiyah32-jkt.sch.id</p>
                <p>&nbsp;</p>

                <!-- link to top -->
                <a id="top" href="#" onclick="topFunction()">
                    <i class="fa fa-arrow-circle-up"></i>
                </a>
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


            <!-- end main-content -->
            </div>

            <hr>

         <!-- Footer -->
                <footer class="footer text-center">
                    <div class="row">
                        <div class="col-md-4 mb-5 mb-lg-0">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <i class="fa fa-top fa-map-marker"></i>
                                </li>
                                <li class="list-inline-item">
                                    <h4 class="text-uppercase mb-4">Kantor</h4>
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
