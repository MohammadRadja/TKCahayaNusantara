<?php include('../db/auto_load.php'); ?>
<?php include('../template/siswa/dashboard_header.php'); ?>
<?php include('./controller/siswa_dashboard_control.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-grey-800">Formulir siswa TK Cahaya Nusantara</h1>
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
                                    <input type="text" name="nama" value="<?= $data_pendaftar['nama']; ?>" class="form-control" id="nama">
                                </div>
                                <div class="mb-3">
                                    <label for="tglLahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tglLahir" value="<?= $data_pendaftar['tanggal_lahir']; ?>" class="form-control" id="tglLahir">
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" name="alamat" value="<?= $data_pendaftar['alamat']; ?>" class="form-control" id="alamat">
                                </div>
                                <div class="mb-3">
                                    <label for="agama" class="form-label">Agama</label>
                                    <input type="text" name="agama" value="<?= $data_pendaftar['agama']; ?>" class="form-control" id="agama">
                                </div>
                                <div class="mb-3">
                                    <?php
                                    $laki = '';
                                    $pr = '';
                                    if ($data_pendaftar['jenis_kelamin'] == 'L') {
                                        $laki = 'checked';
                                    } else {
                                        $pr = 'checked';
                                    }
                                    ?>
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <br>
                                    <input type="radio" name="gender" value="L" <?= $laki ?>> Laki-laki
                                    <br>
                                    <input type="radio" name="gender" value="P" <?= $pr ?>> Perempuan
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" value="<?= $data_pendaftar['email_orangtua']; ?>" class="form-control" id="email">
                                </div>
                                <div class="mb-3">
                                    <label for="noTlp" class="form-label">NO Telepon</label>
                                    <input type="tel" name="noTlp" value="<?= $data_pendaftar['no_telpon']; ?>" class="form-control" id="noTlp">
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php
                                $foto = isset($data_pendaftar['foto']) ? '../upload/' . $data_pendaftar['foto'] : '../img/undraw_profile.svg';
                                ?>
                                <img src="<?= $foto ?>" class="img-fluid" alt="profile">
                                <input type="file" name="gambar" class="form-control mt-2">
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
<!-- End of Main Content -->

<?php include('../template/siswa/dashboard_footer.php'); ?>
