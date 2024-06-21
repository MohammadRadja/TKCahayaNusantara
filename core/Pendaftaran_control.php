<?php
include('../db/koneksi.php');
session_start();

if(isset($_POST['btn_registrasi'])){
    // Ambil dan sanitasi input
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tglLahir = date("Y-m-d", strtotime($_POST['tglLahir']));
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $gender = mysqli_real_escape_string($koneksi, $_POST['gender']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $noTlp = mysqli_real_escape_string($koneksi, $_POST['noTlp']);
    $password = md5($_POST['password']);
    $randomId = rand(100, 999);

    // Buat koneksi ke database
    try {
        // Mulai transaksi
        mysqli_begin_transaction($koneksi);

        // Query untuk memasukkan data ke tabel user
        $sql_user = "INSERT INTO user (id, username, password, level) VALUES (?, ?, ?, 'siswa')";
        $stmt_user = mysqli_prepare($koneksi, $sql_user);
        mysqli_stmt_bind_param($stmt_user, 'iss', $randomId, $nama, $password);
        mysqli_stmt_execute($stmt_user);

        // Query untuk memasukkan data ke tabel siswa
        $sql_pendaftar = "INSERT INTO siswa (id_siswa, nama, tanggal_lahir, jenis_kelamin, agama, alamat, email_orangtua, no_telpon, status_pendaftaran) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'belum diterima')";
        $stmt_pendaftar = mysqli_prepare($koneksi, $sql_pendaftar);
        mysqli_stmt_bind_param($stmt_pendaftar, 'isssssss', $randomId, $nama, $tglLahir, $gender, $agama, $alamat, $email, $noTlp);
        mysqli_stmt_execute($stmt_pendaftar);

        // Komit transaksi
        mysqli_commit($koneksi);

        $_SESSION['pesan_regisB'] = "Registrasi anda berhasil, login menggunakan nama dan password";
        header('Location: ../login.php');
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($koneksi);

        // Menampilkan pesan kesalahan
        $_SESSION['error'] = "Terjadi kesalahan: " . $exception->getMessage();
        header('Location: ../pendaftaran.php');
    } finally {
        // Menutup statement dan koneksi
        mysqli_stmt_close($stmt_user);
        mysqli_stmt_close($stmt_pendaftar);
        mysqli_close($koneksi);
    }
} else {
    $_SESSION['pendafataran_error'] = "Galat";
    header('Location: ../pendaftaran.php');
}
?>
