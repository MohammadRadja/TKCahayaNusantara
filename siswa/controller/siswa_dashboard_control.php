<?php
$id_user = $_SESSION['id_users'];
// Query untuk mendapatkan data siswa
$sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = '$id_user'";
$result_pendaftaran = mysqli_query($koneksi, $sql_pendaftar);

// Periksa apakah query berhasil dijalankan
if ($result_pendaftaran === false) {
    die("Error pada query pendaftar: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($result_pendaftaran) > 0) {
    $data_pendaftar = mysqli_fetch_array($result_pendaftaran);
    $id_pendaftaran = $data_pendaftar['id_siswa'];

    // Query untuk mendapatkan data pembayaran
    $sql_pembayaran = "SELECT * FROM pembayaran WHERE id_pembayaran = '$id_pendaftaran'";
    $result_bayar = mysqli_query($koneksi, $sql_pembayaran);

    // Periksa apakah query berhasil dijalankan
    if ($result_bayar === false) {
        die("Error pada query pembayaran: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($result_bayar) > 0) {
        $data_bayar = mysqli_fetch_array($result_bayar);
        $status = $data_bayar['status_pendaftaran'];
        // Lakukan sesuatu dengan $status
        $_SESSION['sudah_bayar'] = "Selamat Anak Anda Di Terima";
    } else {
      $_SESSION['belum_bayar'] = "Silahkan Mengisi data diri anak dan lakukan pembayaran";
    }
} else {
    echo "Tidak ada data pendaftar ditemukan.";
}

// Tutup koneksi
mysqli_close($koneksi);
?>
