
<nav class="navbar navbar-fixed navbar-inverse form-shadow">
    <div class="container-fluid">
        <!-- Brand and toggle for mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index">
                <img alt="Brand" src="images/favicon-40x47.png">
            </a>
        </div>

        <!-- Navigation Links -->
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
