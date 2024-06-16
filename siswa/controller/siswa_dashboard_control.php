<?php
// Periksa apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah admin sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['level'] != 'siswa') {
    header('location:../login.php');
    exit;
}

$id_user = $_SESSION['id_users'] ?? 'default_id'; // Gunakan nilai default jika tidak ada id_users

// Query untuk mendapatkan data siswa
$sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = '$id_user'";
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


//Logika Status Pembayaran
if (mysqli_num_rows($result_pendaftaran) > 0) {
    // Ambil data pendaftar dari hasil query sebelumnya
    $id_siswa = $data_pendaftar['id_siswa'];

    // Query untuk mendapatkan data pembayaran
    $sql_pembayaran = "SELECT * FROM view_pembayaran WHERE id_siswa = '$id_siswa'";
    $result_bayar = mysqli_query($koneksi, $sql_pembayaran);

    // Periksa apakah query berhasil dijalankan
    if ($result_bayar === false) {
        die("Error pada query siswa: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($result_bayar) > 0) {
        $data_bayar = mysqli_fetch_array($result_bayar);
        $status = $data_bayar['status_pendaftaran'];
        // Lakukan sesuatu dengan $status
        $_SESSION['diterima'] = "Selamat Anak Anda Diterima";
    } else {
        $_SESSION['belum diterima'] = "Silahkan mengisi data diri anak dan lakukan pembayaran";
    }
} else {
    echo "Tidak ada data pendaftar ditemukan.";
}

// Logika Pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_bayar'])) {
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $tanggal_bayar = date("Y-m-d H:i:s");

    // Proses unggah bukti pembayaran jika ada
    if (!empty($_FILES['bukti_pembayaran']['name'])) {
        $file_name = $_FILES['bukti_pembayaran']['name'];
        $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];

        // Pindahkan file yang diunggah ke direktori upload
        move_uploaded_file($file_tmp, "../assets/pembayaran/" . $file_name);

        // Masukkan data pembayaran ke dalam tabel pembayaran
        $sql_pembayaran = "INSERT INTO transaksi (id_siswa, tanggal_bayar, jumlah_bayar, bukti_pembayaran)
                           VALUES ('$id_user', '$tanggal_bayar', '$jumlah_bayar', '$file_name')";

        $result_pembayaran = mysqli_query($koneksi, $sql_pembayaran);

        if ($result_pembayaran) {
            $_SESSION['payment_success'] = "Pembayaran berhasil dilakukan dan sedang menunggu verifikasi.";
        } else {
            $_SESSION['payment_error'] = "Error: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['payment_error'] = "Bukti pembayaran tidak diunggah.";
    }

    header('location: ../siswa/dashboard.php');
    exit;
}

//Logika Edit Data Profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap semua data POST di sini
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tglLahir'];
    $jenis_kelamin = $_POST['gender'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $email_orang_tua = $_POST['email'];
    $no_telp_orang_tua = $_POST['noTlp'];

    // Proses unggah foto profil jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];

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
                        WHERE id_siswa = '$id_user'";
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
                        WHERE id_siswa = '$id_user'";
    }

    // Lakukan query untuk melakukan update
    $result_update = mysqli_query($koneksi, $sql_update);

    // Periksa apakah query update berhasil dijalankan
    if ($result_update) {
    $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";

    // Lakukan query untuk mendapatkan data terbaru
    $result_pendaftaran = mysqli_query($koneksi, $sql_pendaftar);
    if ($result_pendaftaran) {
        // Ambil data terbaru
        $data_pendaftar = mysqli_fetch_array($result_pendaftaran);
        // Simpan data terbaru ke dalam sesi agar dapat ditampilkan setelah redirect
        $_SESSION['data_terbaru'] = $data_pendaftar;
    }
} else {
    $_SESSION['update_profile_error'] = "Error: " . mysqli_error($koneksi);
}
}

// Tutup koneksi
mysqli_close($koneksi);
?>
