<?php
// Include necessary files and start session
include('../db/auto_load.php');
include('../template/siswa/dashboard_header.php');
include('../siswa/controller/siswa_dashboard_control.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Formulir siswa TK Cahaya Nusantara</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                </div>
                <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" value="<?= htmlspecialchars($data_pendaftar['nama'] ?? '') ?>" class="form-control" id="nama">
                            </div>
                            <div class="mb-3">
                                <label for="tglLahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tglLahir" value="<?= htmlspecialchars($data_pendaftar['tanggal_lahir'] ?? '') ?>" class="form-control" id="tglLahir">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" name="alamat" value="<?= htmlspecialchars($data_pendaftar['alamat'] ?? '') ?>" class="form-control" id="alamat">
                            </div>
                            <div class="mb-3">
                                <label for="agama" class="form-label">Agama</label>
                                <input type="text" name="agama" value="<?= htmlspecialchars($data_pendaftar['agama'] ?? '') ?>" class="form-control" id="agama">
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <br>
                                <input type="radio" name="gender" value="L" <?= ($data_pendaftar['jenis_kelamin'] == 'L') ? 'checked' : '' ?>> Laki-laki
                                <br>
                                <input type="radio" name="gender" value="P" <?= ($data_pendaftar['jenis_kelamin'] == 'P') ? 'checked' : '' ?>> Perempuan
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($data_pendaftar['email_orangtua'] ?? '') ?>" class="form-control" id="email">
                            </div>
                            <div class="mb-3">
                                <label for="noTlp" class="form-label">NO Telepon</label>
                                <input type="tel" name="noTlp" value="<?= htmlspecialchars($data_pendaftar['no_telpon'] ?? '') ?>" class="form-control" id="noTlp">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Pendaftaran</label>
                                <input type="text" name="status" value="<?= htmlspecialchars($data_pendaftar['status_pendaftaran'] ?? '') ?>" class="form-control" id="status" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <?php
                            $foto = isset($data_pendaftar['foto_profil']) && !empty($data_pendaftar['foto_profil']) ? '../assets/profil/' . $data_pendaftar['foto_profil'] : '../assets/profil/default.png';
                            ?>
                            <img src="<?= $foto ?>" class="img-fluid" alt="profile">
                            <input type="file" name="profil" class="form-control mt-2">
                        </div>
                    </div>
                    <button type="submit" name="btn_update_profile" value="update_profil" class="btn btn-primary">Edit</button>
                    <a href="../siswa/dashboard.php" class="btn btn-danger">Kembali</a>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<?php include('../template/siswa/dashboard_footer.php'); ?>
