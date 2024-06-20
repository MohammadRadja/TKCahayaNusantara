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
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $randomId = rand(100, 999);

    // Batas ukuran file dalam bytes (misalnya 2MB)
    $max_file_size = 2 * 1024 * 1024;

    // Tipe file yang diizinkan
    $allowed_file_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Validasi apakah file foto diupload
    if (!empty($_FILES['foto_profil']['name'])) {
        $file_name = $_FILES['foto_profil']['name'];
        $file_tmp = $_FILES['foto_profil']['tmp_name'];
        $file_size = $_FILES['foto_profil']['size'];
        $file_type = $_FILES['foto_profil']['type'];

        // Validasi ukuran file
        if ($file_size > $max_file_size) {
            $_SESSION['pendaftaran_error'] = "Ukuran file foto terlalu besar. Maksimal 2MB.";
            header('Location: ../pendaftaran.php');
            exit;
        }

        // Validasi tipe file
        if (!in_array($file_type, $allowed_file_types)) {
            $_SESSION['pendaftaran_error'] = "Tipe file foto tidak diizinkan. Gunakan JPEG, PNG, atau GIF.";
            header('Location: ../pendaftaran.php');
            exit;
        }

        // Direktori untuk menyimpan foto profil
        $upload_dir = "../assets/profil/";

        // Pastikan direktori upload ada dan dapat ditulisi
        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            $_SESSION['pendaftaran_error'] = "Direktori upload tidak tersedia atau tidak dapat ditulisi.";
            header('Location: ../pendaftaran.php');
            exit;
        }

        // Generate nama unik untuk file foto
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_new_name = $randomId . '_' . time() . '.' . $file_extension;

        // Pindahkan file yang diunggah ke direktori upload
        if (!move_uploaded_file($file_tmp, $upload_dir . $file_new_name)) {
            $_SESSION['pendaftaran_error'] = "Gagal mengunggah foto profil.";
            header('Location: ../pendaftaran.php');
            exit;
        }
    } else {
        $_SESSION['pendaftaran_error'] = "Mohon unggah foto profil.";
        header('Location: ../pendaftaran.php');
        exit;
    }

    // Buat koneksi ke database
    try {
        // Mulai transaksi
        mysqli_begin_transaction($koneksi);

        // Query untuk memasukkan data ke tabel user
        $sql_user = "INSERT INTO user (id, username, password, level) VALUES (?, ?, ?, 'siswa')";
        $stmt_user = mysqli_prepare($koneksi, $sql_user);
        mysqli_stmt_bind_param($stmt_user, 'iss', $randomId, $username, $password);
        mysqli_stmt_execute($stmt_user);

        // Query untuk memasukkan data ke tabel siswa
        $sql_pendaftar = "INSERT INTO siswa (id_siswa, nama, tanggal_lahir, jenis_kelamin, agama, alamat, email_orangtua, no_telpon, foto_profil, status_pendaftaran) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'belum diterima')";
        $stmt_pendaftar = mysqli_prepare($koneksi, $sql_pendaftar);
        mysqli_stmt_bind_param($stmt_pendaftar, 'issssssss', $randomId, $nama, $tglLahir, $gender, $agama, $alamat, $email, $noTlp, $file_new_name);
        mysqli_stmt_execute($stmt_pendaftar);

        // Komit transaksi
        mysqli_commit($koneksi);

        $_SESSION['pendaftaran_success'] = "Registrasi berhasil. Silakan login menggunakan username dan password.";
        header('Location: ../pendaftaran.php');
        exit;
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($koneksi);

        // Menampilkan pesan kesalahan
        $_SESSION['error'] = "Terjadi kesalahan saat melakukan registrasi: " . $exception->getMessage();
        header('Location: ../pendaftaran.php');
        exit;
    } finally {
        // Menutup statement dan koneksi
        mysqli_stmt_close($stmt_user);
        mysqli_stmt_close($stmt_pendaftar);
        mysqli_close($koneksi);
    }
} else {
    $_SESSION['pendaftaran_error'] = "Permintaan tidak valid.";
    header('Location: ../pendaftaran.php');
    exit;
}
?>
