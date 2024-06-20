<?php include('../tk/template/siswa/header.php') ?>
<div class="pendaftaran mt-5" style="height: 1000px;">
    <div class="container">
    <?php
    session_start();
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['pendaftaran_error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['pendaftaran_error'] . '</div>';
            unset($_SESSION['pendaftaran_error']); // Hapus session error setelah ditampilkan
        }

        // Tampilkan pesan sukses jika ada
        if (isset($_SESSION['pendaftaran_succes'])) {
            echo '<div class="alert alert-success">' . $_SESSION['pendaftaran_succes'] . '</div>';
            unset($_SESSION['pendaftaran_succes']); // Hapus session sukses setelah ditampilkan
        }
        session_destroy();
        ?>
        <form action="core/pendaftaran_control.php" method="POST" enctype="multipart/form-data">
            <h1>Pendaftaran Murid Baru</h1>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" id="nama" required>
            </div>
            <div class="mb-3">
                <label for="tglLahir" class="form-label">Tanggal Lahir</label>
                <input type="date" name="tglLahir" class="form-control" id="tglLahir" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" id="alamat" required>
            </div>
            <div class="mb-3">
                <label for="agama" class="form-label">Agama</label>
                <input type="text" name="agama" class="form-control" id="agama" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-check-label">Jenis Kelamin</label>
                <br>
                <input type="radio" name="gender" class="form-check-input" id="gender_laki" value="Laki-laki" required> Laki-laki
                <input type="radio" name="gender" class="form-check-input" id="gender_perempuan" value="Perempuan" required> Perempuan
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Orang Tua</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="noTlp" class="form-label">NO Telepon Orang Tua</label>
                <input type="tel" name="noTlp" class="form-control" id="noTlp" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="username" name="username" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
               <?php
                $foto = isset($_SESSION['foto_profil']) ? $_SESSION['foto_profil'] : 'default.png';
                $foto_path = "../assets/profil/" . $foto;
                ?>
                <img src="<?= $foto_path ?>" class="img-fluid" alt="Upload Foto">                
                <input type="file" name="profil" class="form-control mt-2" id="profil" required>
            </div>
            <button name="btn_registrasi" value="simpan" class="btn btn-primary">Submit</button>
            <a class="btn btn-danger" href="index.php">Kembali</a>
        </form>

    </div>
</div>
<?php include('../tk/template/siswa/footer.php') ?>