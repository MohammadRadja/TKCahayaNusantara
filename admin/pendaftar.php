<?php include('../db/auto_load.php'); ?>
<?php include('./controller/pendaftar_dashboard_control.php') ?>
<?php include('../template/admin/header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="container">
        <h1>Data Siswa</h1>
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
                    <th>Status Pendaftaran</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_siswa as $siswa) { ?>
                    <tr>
                        <td><?php echo $siswa['id_siswa']; ?></td>
                        <td><?php echo $siswa['nama']; ?></td>
                        <td><?php echo $siswa['tanggal_lahir']; ?></td>
                        <td><?php echo $siswa['jenis_kelamin']; ?></td>
                        <td><?php echo $siswa['agama']; ?></td>
                        <td><?php echo $siswa['alamat']; ?></td>
                        <td><?php echo $siswa['email_orangtua']; ?></td>
                        <td><?php echo $siswa['no_telpon']; ?></td>
                        <td><?php echo $siswa['status_pendaftaran']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- End of Page Content -->

<?php include('../template/admin/footer.php'); ?>
