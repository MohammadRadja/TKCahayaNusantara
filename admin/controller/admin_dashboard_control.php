<?php
// Memuat file auto_load.php untuk koneksi ke database
// include('../db/auto_load.php');

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['level'] != 'admin') {
//     header('location:../login.php');
//     exit;
// }

// Query untuk mendapatkan data semua siswa
$sql_pembayaran = "SELECT * FROM view_pembayaran";
$result_pembayaran = mysqli_query($koneksi, $sql_pembayaran);

// Periksa apakah query berhasil dijalankan
if ($result_pembayaran) {
    while ($row = mysqli_fetch_assoc($result_pembayaran)) {
        // Populate $data_siswa array
        $data_pembayaran[] = $row;
    }
} else {
    // Handle query error
    die("Error: " . mysqli_error($koneksi));
}

// Loop untuk mengambil data siswa dan status pendaftaran
while ($row = mysqli_fetch_array($result_pembayaran)) {
    $id_siswa = $row['id_siswa'];

    // Query untuk mendapatkan status pendaftaran siswa
    $sql_pembayaran = "SELECT status_pendaftaran FROM view_pembayaran WHERE id_siswa = '$id_siswa'";
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

    // Tambahkan data siswa ke dalam array $data_siswa
    $data_pembayaran[] = [
        'id_siswa' => $id_siswa,
        'nama' => $row['nama'],
        'tanggal_lahir' => $row['tanggal_lahir'],
        'jenis_kelamin' => $row['jenis_kelamin'],
        'agama' => $row['agama'],
        'alamat' => $row['alamat'],
        'email_orangtua' => $row['email_orangtua'],
        'no_telpon' => $row['no_telpon'],
        'tanggal_bayar' => $row['tanggal_bayar'],
        'jumlah_bayar' => $row['jumlah_bayar'],
        'status_pendaftaran' => $status_pendaftaran
    ];
}

//Logika Verif Pembayaran
if (isset($_GET['id_siswa']) && is_numeric($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];
    $action = $_GET['action'] ?? '';

    if ($action == 'terima') {
        // Query to update status to "Sudah Diverifikasi"
        $sql_update = "UPDATE view_pembayaran SET status_pendaftaran = 'diterima' WHERE id_siswa = '$id_siswa'";
    } elseif ($action == 'tolak') {
        // Query to update status to "Ditolak"
        $sql_update = "UPDATE view_pembayaran SET status_pendaftaran = 'Ditolak' WHERE id_siswa = '$id_siswa'";
    } else {
        $_SESSION['verifikasi_error'] = "Invalid action.";
        header('location:./pendaftar.php');
        exit;
    }

    // Execute update query
    $result_update = mysqli_query($koneksi, $sql_update);

    // Check if update was successful
    if ($result_update) {
        $_SESSION['verifikasi_success'] = "Pendaftar successfully " . ($action == 'diterima' ? 'accepted' : 'rejected');
        header('location:./pendaftar.php');
        exit;
    } else {
        $_SESSION['verifikasi_error'] = "Failed to process verification: " . mysqli_error($koneksi);
        header('location:./pendaftar.php');
        exit;
    }
} else {
    // $_SESSION['verifikasi_error'] = "Invalid student ID.";
    // header('location:./pendaftar.php');
    // exit;
}

// Close database connection
mysqli_close($koneksi);
?>