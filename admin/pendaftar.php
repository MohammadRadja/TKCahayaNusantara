<?php
// Memuat file auto_load.php untuk koneksi ke database
include('../db/auto_load.php');

// Memuat file controller untuk logika verifikasi
include('../admin/controller/admin_dashboard_control.php');

// Memuat header admin setelah mendapatkan data siswa
include('../template/admin/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="container">
        <h1>Data Pendaftar</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Siswa</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>Alamat</th>
                    <th>Email Orang Tua</th>
                    <th>No Telp Orang Tua</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data_pendaftar as $pendaftar) { ?>
                    <tr>
                        <td><?= htmlspecialchars($pendaftar['id_siswa']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['nama']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['tanggal_lahir']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['jenis_kelamin']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['agama']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['alamat']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['email_orangtua']); ?></td>
                        <td><?= htmlspecialchars($pendaftar['no_telpon']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- End of Page Content -->

<?php include('../template/admin/footer.php'); ?>