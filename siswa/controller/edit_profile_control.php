<?php
// Sertakan file auto_load.php untuk memuat fungsi atau konfigurasi lainnya
include('../../db/auto_load.php');

// Periksa apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah admin sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['level'] != 'siswa') {
    header('location:../login.php');
    exit;
}

// Ambil id siswa dari session aktif
$id_siswa = $_SESSION['id_users'];

// Query untuk mendapatkan data siswa
$sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'";
$result_pendaftaran = mysqli_query($koneksi, $sql_pendaftar);

// Periksa apakah query berhasil dijalankan
if ($result_pendaftaran === false) {
    die("Error pada query pendaftar: " . mysqli_error($koneksi));
}

// Ambil data siswa
$data_pendaftar = mysqli_fetch_array($result_pendaftaran);

// Pastikan ada data yang ditemukan
if (!$data_pendaftar) {
    die("Data siswa tidak ditemukan.");
}

// Tangkap data dari formulir yang di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap semua data POST di sini
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tglLahir'];
    $jenis_kelamin = $_POST['gender'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $email_orang_tua = $_POST['email'];
    $no_telp_orang_tua = $_POST['noTlp'];

    // Debug: Tambahkan echo untuk memeriksa nilai
    echo "Nama: $nama, Tanggal Lahir: $tanggal_lahir, Alamat: $alamat, Agama: $agama, Jenis Kelamin: $jenis_kelamin, Email: $email_orang_tua, No. Telepon: $no_telp_orang_tua";

    // Proses unggah foto profil jika ada
    if ($_FILES['gambar']['name'] != '') {
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_type = $_FILES['gambar']['type'];

        // Pindahkan file yang diunggah ke direktori upload
        move_uploaded_file($file_tmp, "../upload/" . $file_name);

        // Update data termasuk foto profil
        $sql_update = "UPDATE siswa SET 
                        nama = '$nama', 
                        tanggal_lahir = '$tanggal_lahir', 
                        jenis_kelamin = '$jenis_kelamin', 
                        agama = '$agama', 
                        alamat = '$alamat', 
                        email_orangtua = '$email_orang_tua', 
                        no_telpon = '$no_telp_orang_tua', 
                        foto = '$file_name'
                        WHERE id_siswa = '$id_siswa'";
    } else {
        // Update data tanpa mengubah foto profil
        $sql_update = "UPDATE siswa SET 
                        nama = '$nama', 
                        tanggal_lahir = '$tanggal_lahir', 
                        alamat = '$alamat', 
                        agama = '$agama', 
                        jenis_kelamin = '$jenis_kelamin', 
                        email_orangtua = '$email_orang_tua', 
                        no_telpon = '$no_telp_orang_tua'
                        WHERE id_siswa = '$id_siswa'";
    }

    // Lakukan query untuk melakukan update
    $result_update = mysqli_query($koneksi, $sql_update);

    // Periksa apakah query update berhasil dijalankan
    if ($result_update) {
        $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION['update_profile_error'] = "Error: " . mysqli_error($koneksi);
    }

    // Redirect kembali ke halaman dashboard setelah update
    header('location: /siswa/dashboard.php');
    exit;
}
?>
