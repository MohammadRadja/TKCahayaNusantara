<?php include('../db/auto_load.php'); ?>
<?php include('./controller/siswa_dashboard_control.php') ?>
<?php include('../template/siswa/dashboard_header.php'); ?>

<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pembayaran</h1>
    </div>
    <div class="container">
        <div class="jumbotron p-1 mb-4 bg-light rounded-3">
            <div class="container py-5">
                <img src="../assets/img/brosur.jpg" class="w-5 img-fluid" alt="TestingBrosur">
            </div>
        </div>
    </div>
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                </div>
                <div class="card-body">
                    <form class="user" method="POST" action="<?= $url_based ?>/siswa/editProfile.php" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" value="<?= $data_pendaftar['nama']; ?>" class="form-control" id="nama" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" value="<?= $data_pendaftar['status_pendaftaran']; ?>" class="form-control" id="status_pendaftaran" disabled>
                                </div>
                            </div>
                        </div>
                        <a href="../siswa/dashboard_siswa.php" class="btn btn-danger">Kembali</a>
                        <button type="submit" name="btn_simpan" value="simpan_profil" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- <div class="container">
        <div class="alert alert-success">
            <h1 class="display-4">Selamat Anak Anda Di Terima</h1>
            <p class="lead">Silahkan menghubungi kontak</p>
        </div>
    </div> -->

    <?php include('../template/siswa/dashboard_footer.php'); ?>