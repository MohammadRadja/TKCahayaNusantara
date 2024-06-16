<?php include('../db/auto_load.php'); ?>
<?php include('./controller/siswa_dashboard_control.php') ?>
<?php include('../template/siswa/dashboard_header.php'); ?>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <div class="container">
        <div class="jumbotron p-1 mb-4 bg-light rounded-3">
            <div class="container py-5">
                <h1 class="display-4">Selamat Datang di Sekolah TK</h1>
                <p class="lead">Kami senang Anda bergabung dengan kami. Mari bersama-sama menciptakan pengalaman belajar yang menyenangkan untuk anak-anak.</p>
            </div>
        </div>
    </div>
    <?php
    if (isset($_SESSION['belum diterima'])) { ?>
        <div class="container">
            <div class="alert alert-danger">
                <h1 class="display-4"><?= $_SESSION['belum diterima'] ?></h1>
                <p class="lead"></p>
            </div>
        </div>
    <?php }  
    if (isset($_SESSION['diterima'])) { ?>
    <div class="container">
        <div class="alert alert-success">
            <h1 class="display-4">Selamat Anak Anda Di Terima</h1>
            <p class="lead">Silahkan menghubungi kontak</p>
        </div>
    </div>
    <?php }

    ?>
    <!-- <div class="container">
        <div class="alert alert-success">
            <h1 class="display-4">Selamat Anak Anda Di Terima</h1>
            <p class="lead">Silahkan menghubungi kontak</p>
        </div>
    </div> -->

    <?php include('../template/siswa/dashboard_footer.php'); ?>