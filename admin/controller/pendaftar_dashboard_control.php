<?php
include('../db/koneksi.php');

// Periksa apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah admin sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['level'] != 'admin') {
    header('location:../login.php');
    exit;
}

// Query untuk mendapatkan data semua siswa
$sql_siswa = "SELECT * FROM siswa";
$result_siswa = mysqli_query($koneksi, $sql_siswa);

// Periksa apakah query berhasil dijalankan
if ($result_siswa === false) {
    die("Error pada query siswa: " . mysqli_error($koneksi));
}

$data_siswa = [];
while ($row = mysqli_fetch_array($result_siswa)) {
    $id_siswa = $row['id_siswa'];
    
    // Query untuk mendapatkan status pendaftaran siswa
    $sql_pembayaran = "SELECT status_pendaftaran FROM pembayaran WHERE id_pembayaran = '$id_siswa'";
    $result_pembayaran = mysqli_query($koneksi, $sql_pembayaran);

    if ($result_pembayaran === false) {
        die("Error pada query pembayaran: " . mysqli_error($koneksi));
    }

    // Dapatkan status pendaftaran
    if (mysqli_num_rows($result_pembayaran) > 0) {
        $data_pembayaran = mysqli_fetch_array($result_pembayaran);
        $status_pendaftaran = $data_pembayaran['status_pendaftaran'];
    } else {
        $status_pendaftaran = "Belum Bayar";
    }

    $data_siswa[] = [
        'id_siswa' => $id_siswa,
        'nama' => $row['nama'],
        'tanggal_lahir' => $row['tanggal_lahir'],
        'jenis_kelamin' => $row['jenis_kelamin'],
        'agama' => $row['agama'],
        'alamat' => $row['alamat'],
        'email_orangtua' => $row['email_orangtua'],
        'no_telpon' => $row['no_telpon'],
        'status_pendaftaran' => $status_pendaftaran
    ];
}

// Tutup koneksi
mysqli_close($koneksi);
?>
