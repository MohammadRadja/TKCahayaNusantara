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
        <h1>Data Pembayaran</h1>
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
                    <th>Tanggal Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Status Pendaftaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pembayaran as $pembayaran) { ?>
                    <tr>
                        <td><?= htmlspecialchars($pembayaran['id_siswa']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['nama']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['tanggal_lahir']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['jenis_kelamin']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['agama']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['alamat']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['email_orangtua']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['no_telpon']); ?></td>
                        <td><?= htmlspecialchars($pembayaran['tanggal_bayar']); ?></td>
                        <td>Rp<?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($pembayaran['status_pendaftaran']); ?></td>
                        <td>
                            <?php if ($pembayaran['status_pendaftaran'] == 'belum diterima'): ?>
                                <a href="./controller/admin_dashboard_control.php?id=<?= htmlspecialchars($pembayaran['id_siswa']); ?>&action=terima" class="btn btn-success btn-sm">Terima</a>
                                <a href="./controller/admin_dashboard_control.php?id=<?= htmlspecialchars($pembayaran['id_siswa']); ?>&action=tolak" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menolak pendaftar ini?')">Tolak</a>
                            <?php else: ?>
                                <span class="badge badge-success">Terverifikasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- End of Page Content -->

<?php include('../template/admin/footer.php'); ?>