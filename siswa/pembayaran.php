<?php include('../db/auto_load.php'); ?>
<?php include('./controller/siswa_dashboard_control.php'); ?>
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
                        <h6 class="m-0 font-weight-bold text-primary">Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['payment_success'])): ?>
                            <div class="alert alert-success">
                                <?= $_SESSION['payment_success']; unset($_SESSION['payment_success']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['payment_error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['payment_error']; unset($_SESSION['payment_error']); ?>
                            </div>
                        <?php endif; ?>

                        <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Siswa</label>
                                        <input type="text" name="nama" value="<?= $data_pendaftar['nama']; ?>" class="form-control" id="nama" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_pendaftaran" class="form-label">Status Pendaftaran</label>
                                        <input type="text" name="status_pendaftaran" value="<?= $data_pendaftar['status_pendaftaran']; ?>" class="form-control" id="status_pendaftaran" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah_bayar" class="form-label">Jumlah Pembayaran</label>
                                        <input type="number" name="jumlah_bayar" class="form-control" id="jumlah_bayar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bukti_pembayaran" class="form-label">Unggah Bukti Pembayaran</label>
                                        <input type="file" name="bukti_pembayaran" class="form-control" id="bukti_pembayaran" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn_bayar" value="bayar" class="btn btn-primary">Bayar</button>
                            <a href="../siswa/profil.php" class="btn btn-danger">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('../template/siswa/dashboard_footer.php'); ?>