<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['level'] != 'admin') {
    header('location:../login.php');
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'tkcahayanusantara';

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

// Periksa apakah koneksi berhasil
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mendapatkan data pendaftar
$sql_pendaftar = "SELECT * FROM siswa";
$result_pendaftaran = mysqli_query($koneksi, $sql_pendaftar);

// Periksa apakah query berhasil dijalankan
if ($result_pendaftaran === false) {
    die("Error pada query pendaftar: " . mysqli_error($koneksi));
}

// Ambil data siswa dan pastikan ada data yang ditemukan
$data_pendaftar = [];
while ($row = mysqli_fetch_array($result_pendaftaran, MYSQLI_ASSOC)) {
    $data_pendaftar[] = $row;
}

// Pastikan ada data yang ditemukan
if (count($data_pendaftar) === 0) {
    die("Data siswa tidak ditemukan.");
}


// Inisialisasi Array Pembayaran
$data_pembayaran = [];

// Query untuk mendapatkan data semua pembayaran
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
foreach ($data_pembayaran as &$pembayaran) {
    $id_siswa = $pembayaran['id_siswa'];

    // Query untuk mendapatkan status pendaftaran siswa
    $sql_status_pendaftaran = "SELECT status_pendaftaran FROM view_pembayaran WHERE id_siswa = '$id_siswa'";
    $result_status_pendaftaran = mysqli_query($koneksi, $sql_status_pendaftaran);

    if ($result_status_pendaftaran === false) {
        die("Error pada query pembayaran: " . mysqli_error($koneksi));
    }

    // Dapatkan status pendaftaran
    if (mysqli_num_rows($result_status_pendaftaran) > 0) {
        $data_status_pendaftaran = mysqli_fetch_array($result_status_pendaftaran);
        $status_pendaftaran = $data_status_pendaftaran['status_pendaftaran'];
    } else {
        $status_pendaftaran = "Belum Bayar";
    }

    // Tambahkan data siswa ke dalam array $data_siswa
    $pembayaran['status_pendaftaran'] = $status_pendaftaran;
}


// Logika Verif Pembayaran
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['action'])) {
    $id_siswa = $_GET['id'];
    $action = $_GET['action'];

    switch ($action) {
        case 'terima':
            $sql_update = "UPDATE view_pembayaran SET status_pendaftaran = 'diterima' WHERE id_siswa = ?";
            break;
        case 'tolak':
            $sql_update = "UPDATE view_pembayaran SET status_pendaftaran = 'belum diterima' WHERE id_siswa = ?";
            break;
        default:
            $_SESSION['verifikasi_error'] = "Aksi tidak valid.";
            header('location: ./pembayaran.php');
            exit;
    }

    // Execute update query with prepared statement
    $stmt = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt, 'i', $id_siswa);
    $result_update = mysqli_stmt_execute($stmt);

    if ($result_update) {
        $action_text = ($action == 'terima') ? 'accepted' : 'rejected';
        $_SESSION['verifikasi_success'] = "Pendaftar successfully " . $action_text;
    } else {
        $_SESSION['verifikasi_error'] = "Failed to process verification: " . mysqli_error($koneksi);
    }

    // Redirect back to payment verification page
    header('location: ../pembayaran.php');
    exit;
}

// Close database connection
mysqli_close($koneksi);
?>