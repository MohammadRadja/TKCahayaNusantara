<?php include('template/siswa/header.php') ?>
<div class="jumbotron">
    <div class="container">
        <h1 data-aos="fade-down" data-aos-duration="2000" class="display-4">Selamat Datang di Sekolah TK CAHAYA NUSANTARA</h1>
        <p data-aos="fade-down" data-aos-duration="2000" class="lead">Silahkan isi data siswa</p>
    </div>
</div>

<div class="pendaftaran mt-5" style="height: 1000px;">
    <div class="container">
        <?php
        session_start();
        if (isset($_SESSION['pendafataran_error'])) { ?>
            <div class="alert alert-danger">
                <?= $_SESSION['pendafataran_error'] ?>
            </div>
        <?php }
        session_destroy();
        ?>
        <form action="core/pendaftaran_control.php" method="POST">
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
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button name="btn_registrasi" value="simpan" class="btn btn-primary">Submit</button>
            <a class="btn btn-danger" href="index.php">Kembali</a>
        </form>

    </div>
</div>
<?php include('template/siswa/footer.php') ?>