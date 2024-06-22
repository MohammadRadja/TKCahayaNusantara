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
$sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = ?";
$stmt_pendaftaran = $koneksi->prepare($sql_pendaftar);
$stmt_pendaftaran->bind_param("i", $id_user);
$stmt_pendaftaran->execute();
$result_pendaftaran = $stmt_pendaftaran->get_result();
// Periksa apakah query berhasil dijalankan
if ($result_pendaftaran === false) {
    die("Error pada query pendaftar: " . mysqli_error($koneksi));
}
// Ambil data siswa
$data_pendaftar = mysqli_fetch_array($result_pendaftaran);
// Pastikan ada data yang ditemukan
if (!$data_pendaftar) {
    $error_messages[] = "Data Siswa tidak ditemukan";
}

// Query untuk mendapatkan data orang tua
$sql_orangtua = "SELECT * FROM orangtua WHERE id_siswa = ?";
$stmt_orangtua = $koneksi->prepare($sql_orangtua);
$stmt_orangtua->bind_param("i", $id_user);
$stmt_orangtua->execute();
$result_orangtua = $stmt_orangtua->get_result();
// Periksa apakah query berhasil dijalankan
if ($result_orangtua === false) {
    die("Error pada query orangtua: " . mysqli_error($koneksi));
}
// Ambil data siswa
$data_orangtua = mysqli_fetch_array($result_orangtua);
// Pastikan ada data yang ditemukan
if (!$data_orangtua) {
    $error_messages[] = "Silahkan update data orang tua";
}


// Query untuk mendapatkan data dokumen
$sql_dokumen = "SELECT *FROM dokumen WHERE id_siswa = ?";
$stmt_dokumen = $koneksi->prepare($sql_dokumen);
$stmt_dokumen->bind_param("i", $id_user);
$stmt_dokumen->execute();
$result_dokumen = $stmt_dokumen->get_result();
$data_dokumen = mysqli_fetch_array($result_dokumen);
// Pastikan ada data yang ditemukan
if (!$data_dokumen) {
    $error_messages[] = "Silahkan upload foto kartu keluarga & akte kelahiran";
}


// Logika Status Pembayaran
if (mysqli_num_rows($result_pendaftaran) > 0) {
    // Ambil data pendaftar dari hasil query sebelumnya
    $id_siswa = $data_pendaftar['id_siswa'];

    // Query untuk mendapatkan data pembayaran
    $sql_pembayaran = "SELECT * FROM view_pembayaran WHERE id_siswa = ?";
    $stmt_pembayaran = $koneksi->prepare($sql_pembayaran);
    $stmt_pembayaran->bind_param("i", $id_siswa);
    $stmt_pembayaran->execute();
    $result_bayar = $stmt_pembayaran->get_result();


    // Periksa apakah query berhasil dijalankan
    if (!$result_bayar) {
        die("Error pada query pembayaran: " . $stmt_pembayaran->error);
    }

    if ($result_bayar->num_rows > 0) {
        $data_bayar = $result_bayar->fetch_array(MYSQLI_ASSOC);
        $status = $data_bayar['status_pendaftaran'];
        
        // Lakukan sesuatu dengan $status
        $_SESSION['diterima'] = "Selamat, anak Anda telah diterima.";
    } else {
        $_SESSION['belum_diterima'] = "Silakan lengkapi data diri anak dan lakukan pembayaran.";
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


// Logika Edit Data Profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update_profile'])) {
    // Tangkap semua data POST di sini
    $nama = $_POST['nama'] ?? '';
    $tanggal_lahir = $_POST['tglLahir'] ?? '';
    $jenis_kelamin = $_POST['gender'] ?? '';
    $agama = $_POST['agama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email_orang_tua = $_POST['email'] ?? '';
    $no_telp_orang_tua = $_POST['noTlp'] ?? '';
    $status = $_POST['status_pendaftaran'] ?? '';

    // Data Orang Tua
    $nama_ayah = $_POST['nama_ayah'] ?? '';
    $pekerjaan_ayah = $_POST['pekerjaan_ayah'] ?? '';
    $telp_ayah = $_POST['telp_ayah'] ?? '';
    $nama_ibu = $_POST['nama_ibu'] ?? '';
    $pekerjaan_ibu = $_POST['pekerjaan_ibu'] ?? '';
    $telp_ibu = $_POST['telp_ibu'] ?? '';

    // Batas ukuran file dalam bytes (misalnya 2MB)
    $max_file_size = 2 * 1024 * 1024;
    // Tipe file yang diizinkan untuk foto
    $allowed_file_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Direktori upload
    $upload_dirs = [
        'profil' => "../assets/profil", // Contoh direktori untuk foto profil
        'kk' => "../assets/dokumen/kartukeluarga", // Direktori untuk foto KK
        'akte' => "../assets/dokumen/aktekelahiran" // Direktori untuk foto akte kelahiran
    ];

    // Inisialisasi pesan kesalahan
    $error_messages = [];
    $uploaded_files = [];

    // Fungsi untuk mengupload file
    function upload_file($file, $dir, $allowed_types, $max_size, $error_messages) {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];

        // Validasi ukuran file
        if ($file_size > $max_size) {
            $error_messages[] = "Ukuran file $file_name terlalu besar. Maksimum ukuran file adalah 2MB.";
            return false;
        }

        // Validasi tipe file
        if (!in_array($file_type, $allowed_types)) {
            $error_messages[] = "Tipe file $file_name tidak valid. Hanya diperbolehkan mengunggah file gambar (jpeg, png, gif).";
            return false;
        }

        // Pastikan direktori upload ada dan dapat ditulisi
        if (!is_dir($dir) || !is_writable($dir)) {
            $error_messages[] = "Direktori upload $dir tidak ada atau tidak dapat ditulisi.";
            return false;
        }

        // Pindahkan file yang diunggah ke direktori upload
        if (move_uploaded_file($file_tmp, $dir . $file_name)) {
            return $file_name;
        } else {
            $error_messages[] = "Gagal mengunggah file $file_name.";
            return false;
        }
    }

    // Proses upload foto profil
    if (!empty($_FILES['profil']['name'])) {
        $uploaded_files['profil'] = upload_file($_FILES['profil'], $upload_dirs['profil'], $allowed_file_types, $max_file_size, $error_messages);
    }

    // Proses upload foto KK
    if (!empty($_FILES['foto_kk']['name'])) {
        $uploaded_files['kk'] = upload_file($_FILES['foto_kk'], $upload_dirs['kk'], $allowed_file_types, $max_file_size, $error_messages);
    }

    // Proses upload foto Akte Kelahiran
    if (!empty($_FILES['foto_akte']['name'])) {
        $uploaded_files['akte'] = upload_file($_FILES['foto_akte'], $upload_dirs['akte'], $allowed_file_types, $max_file_size, $error_messages);
    }

    // Handle kesalahan validasi atau pengunggahan
    if (!empty($error_messages)) {
        $_SESSION['update_profile_error'] = implode("<br>", $error_messages);
        header('location: ../siswa/profil.php');
        exit;
    }

    // Update data profil siswa di database
    $sql_update_profil = "UPDATE siswa SET 
        nama = ?, 
        tanggal_lahir = ?, 
        jenis_kelamin = ?, 
        agama = ?, 
        alamat = ?, 
        email_orangtua = ?, 
        no_telpon = ?" . 
        (!empty($uploaded_files['profil']) ? ", foto_profil = ?" : "") . 
        " WHERE id_siswa = ?";
    $stmt_profil = $koneksi->prepare($sql_update_profil);
    if (!empty($uploaded_files['profil'])) {
        $stmt_profil->bind_param("ssssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email_orang_tua, $no_telp_orang_tua, $uploaded_files['profil'], $id_user);
    } else {
        $stmt_profil->bind_param("sssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email_orang_tua, $no_telp_orang_tua, $id_user);
    }

    if ($stmt_profil->execute()) {
        // Update data orang tua
        $sql_update_orang_tua = "INSERT INTO orangtua (id_siswa, nama_ayah, pekerjaan_ayah, telp_ayah, nama_ibu, pekerjaan_ibu, telp_ibu)
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_orangtua = mysqli_prepare($koneksi, $sql_update_orang_tua);
        mysqli_stmt_bind_param($stmt_orangtua, 'issssss', $id_user, $nama_ayah, $pekerjaan_ayah, $telp_ayah, $nama_ibu, $pekerjaan_ibu, $telp_ibu);
        mysqli_stmt_execute($stmt_orangtua);

        // Update data dokumen (KK dan Akte)
        if ($stmt_profil->execute()) {
            // Update data dokumen (KK dan Akte)
            if (!empty($uploaded_files['kk']) || !empty($uploaded_files['akte'])) {
                $sql_dokumen = "INSERT INTO dokumen (id_siswa, foto_kk, foto_akte) VALUES (?, ?, ?)";
                $stmt_dokumen = $koneksi->prepare($sql_dokumen);
                $stmt_dokumen->bind_param("iss", $id_user, $file_name_kk, $file_name_akte);
                $file_name_kk = $uploaded_files['kk'] ?? ''; // Pastikan file KK dan Akte telah diunggah sebelum bind
                $file_name_akte = $uploaded_files['akte'] ?? ''; // Menyesuaikan dengan nama variabel yang Anda gunakan
                $stmt_dokumen->execute();
                $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";
            }
        } else {
            $_SESSION['update_profile_error'] = "Error saat mengupdate profil: " . $stmt_profil->error;
        }
    }        

    // Redirect ke halaman profil
    header('location: ../siswa/profil.php');
    exit;
}

// Tutup koneksi
mysqli_close($koneksi);
?>
