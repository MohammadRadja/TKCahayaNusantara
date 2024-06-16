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

//Logika Data Siswa
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


// Logika Verifikasi Pembayaran 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_siswa = $_GET['id'];
    $action = $_GET['action'] ?? '';

    if ($action == 'terima') {
        // Query untuk mengubah status pendaftaran menjadi "Diterima"
        $sql_update = "UPDATE pembayaran SET status_pendaftaran = 'Sudah Diverifikasi' WHERE id_siswa = '$id_siswa'";
    } elseif ($action == 'tolak') {
        // Query untuk mengubah status pendaftaran menjadi "Ditolak"
        $sql_update = "UPDATE pembayaran SET status_pendaftaran = 'Ditolak' WHERE id_siswa = '$id_siswa'";
    } else {
        $_SESSION['verifikasi_error'] = "Aksi tidak valid.";
        header('location:./pendaftar.php');
        exit;
    }

    // Eksekusi query
    $result_update = mysqli_query($koneksi, $sql_update);

    // Periksa apakah update berhasil
    if ($result_update) {
        $_SESSION['verifikasi_success'] = "Pendaftar berhasil di" . ($action == 'terima' ? 'terima' : 'tolak');
        header('location:./admin/pendaftar.php');
        exit;
    } else {
        $_SESSION['verifikasi_error'] = "Gagal memproses verifikasi: " . mysqli_error($koneksi);
        header('location:./admin/pendaftar.php');
        exit;
    }
} else {
    $_SESSION['verifikasi_error'] = "ID pendaftar tidak valid.";
    header('location:./pendaftar.php');
    exit;
}


// Tutup koneksi
mysqli_close($koneksi);
?>
