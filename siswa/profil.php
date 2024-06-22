<?php
// Include necessary files and start session
include('../db/auto_load.php');
include('../template/siswa/dashboard_header.php');
include('../siswa/controller/siswa_dashboard_control.php');
?>


<!-- Begin Page Content -->
<div class="container-fluid">
<?php
if (isset($_SESSION['update_profile_error'])) { ?>
    <div class="container">
        <div class="alert alert-danger">
            <h1 class="display-4"><?= $_SESSION['update_profile_error'] ?></h1>
            <p class="lead"></p>
        </div>
    </div>
    <?php unset($_SESSION['update_profile_error']); // Hapus session setelah digunakan ?>
<?php } ?>

<?php
if (isset($_SESSION['update_profile_success'])) { ?>
    <div class="container">
        <div class="alert alert-success">
            <h1 class="display-4"><?= $_SESSION['update_profile_success'] ?></h1>
            <p class="lead"></p>
        </div>
    </div>
    <?php unset($_SESSION['update_profile_success']); // Hapus session setelah digunakan ?>
<?php } ?>
    <h1 class="h3 mb-4 text-gray-800">Formulir siswa TK Cahaya Nusantara</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                </div>
                <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <h1>Data Siswa</h1>
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
                                <input type="radio" id="gender_l" name="gender" value="L" <?= ($data_pendaftar['jenis_kelamin'] == 'L') ? 'checked' : '' ?>> Laki-laki
                                <br>
                                <input type="radio" id="gender_p" name="gender" value="P" <?= ($data_pendaftar['jenis_kelamin'] == 'P') ? 'checked' : '' ?>> Perempuan
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

                            <h1>Data Ayah</h1>
                            <div class="mb-3">
                                <label for="father_name" class="form-label">Nama Ayah</label>
                                <input type="text" value="<?= htmlspecialchars($data_orangtua['nama_ayah'] ?? '') ?>" class="form-control"  id="nama_ayah" name="nama_ayah">
                            </div>
                            <div class="mb-3">
                                <label for="father_occupation" class="form-label">Pekerjaan Ayah</label>
                                <input type="text" value="<?= htmlspecialchars($data_orangtua['pekerjaan_ayah'] ?? '') ?>" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah">
                            </div>
                            <div class="mb-3">
                                <label for="father_phone" class="form-label">Telepon Ayah</label>
                                <input type="tel" value="<?= htmlspecialchars($data_orangtua['telp_ayah'] ?? '') ?>" class="form-control" id="telp_ayah" name="telp_ayah">
                            </div>

                            <h1>Data Ibu</h1>
                            <div class="mb-3">
                                <label for="mother_name" class="form-label">Nama Ibu</label>
                                <input type="text" value="<?= htmlspecialchars($data_orangtua['nama_ibu'] ?? '') ?>" class="form-control" id="nama_ibu" name="nama_ibu">
                            </div>
                            <div class="mb-3">
                                <label for="mother_occupation" class="form-label">Pekerjaan Ibu</label>
                                <input type="text" value="<?= htmlspecialchars($data_orangtua['pekerjaan_ibu'] ?? '') ?>" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu">
                            </div>
                            <div class="mb-3">
                                <label for="mother_phone" class="form-label">Telepon Ibu</label>
                                <input type="tel" value="<?= htmlspecialchars($data_orangtua['telp_ibu'] ?? '') ?>" class="form-control" id="telp_ibu" name="telp_ibu">
                            </div>

                            <div class="mb-3">
                                <label for="kk" class="form-label">Foto Kartu Keluarga</label>
                                <?php
                                $foto_kk = isset($data_dokumen['foto_kk']) && !empty($data_dokumen['foto_kk']) ? '../assets/dokumen/kartukeluarga/' . $data_dokumen['foto_kk'] : '../assets/dokumen/kartukeluarga/default.png';
                                ?>
                                <img src="<?= $foto_kk ?>" class="img-fluid" alt="Foto Kartu Keluarga">
                                <input type="file" name="kk" class="form-control mt-2" id="kk">
                            </div>
                            <div class="mb-3">
                                <label for="akte" class="form-label">Akte Kelahiran</label>
                                <?php
                                $foto_akte = isset($data_dokumen['foto_akte']) && !empty($data_dokumen['foto_akte']) ? '../assets/dokumen/aktekelahiran/' . $data_dokumen['foto_akte'] : '../assets/dokumen/aktekelahiran/default.png';
                                ?>
                                <img src="<?= $foto_akte ?>" class="img-fluid" alt="Foto Akte Kelahiran">
                                <input type="file" name="akte" class="form-control mt-2" id="akte">
                            </div>
                            <div class="mb-3">
                                <label for="profil" class="form-label">Foto Siswa</label>
                                <?php
                                $foto_profil = isset($data_pendaftar['foto_profil']) && !empty($data_pendaftar['foto_profil']) ? '../assets/profil/' . $data_pendaftar['foto_profil'] : '../assets/profil/default.png';
                                ?>
                                <img src="<?= $foto_profil ?>" class="img-fluid" alt="Foto Profil Siswa">
                                <input type="file" name="profil" class="form-control mt-2">
                            </div>
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
