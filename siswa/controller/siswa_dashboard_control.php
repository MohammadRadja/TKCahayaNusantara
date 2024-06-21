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
    $error_messages[] = "Data Siswa tidak ditemukan";
}

// Query untuk mendapatkan data orang tua
$sql_orangtua = "SELECT * FROM orangtua WHERE id_siswa = '$id_user'";
$result_orangtua = mysqli_query($koneksi, $sql_orangtua);
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


// Query untuk mendapatkan data kk
$sql_kk = "SELECT foto_kk FROM dokumen WHERE id_siswa = '$id_user'";
$result_kk = mysqli_query($koneksi, $sql_kk);
// Periksa apakah query berhasil dijalankan
if ($result_kk === false) {
    die("Error pada query kartu keluarga: " . mysqli_error($koneksi));
}
// Ambil data siswa
$data_kk = mysqli_fetch_array($result_kk);
// Pastikan ada data yang ditemukan
if (!$data_kk) {
    $error_messages[] = "Silahkan upload foto kartu keluarga";
}


// Query untuk mendapatkan data akte
$sql_akte = "SELECT foto_akte FROM dokumen WHERE id_siswa = '$id_user'";
$result_akte = mysqli_query($koneksi, $sql_akte);
// Periksa apakah query berhasil dijalankan
if ($result_akte === false) {
    die("Error pada query akte kelahiran: " . mysqli_error($koneksi));
}
// Ambil data siswa
$data_akte = mysqli_fetch_array($result_akte);
// Pastikan ada data yang ditemukan
if (!$data_akte) {
    $error_messages[] = "Silahkan upload foto akte kelahiran";
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
    $randomId = rand(100, 999);

    // Data Tambahan
    $foto_profil = $_POST['foto_profil'] ?? '';
    $foto_kk = $_FILES['foto_kk'] ?? '';
    $foto_akte = $_FILES['foto_akte'] ?? '';

    // Batas ukuran file dalam bytes (misalnya 2MB)
    $max_file_size = 2 * 1024 * 1024;

    // Tipe file yang diizinkan untuk foto profil
    $allowed_file_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Inisialisasi pesan kesalahan
    $error_messages = [];

    // Proses unggah foto profil jika ada
    if (!empty($_FILES['profil']['name'])) {
        $file_name = $_FILES['profil']['name'];
        $file_tmp = $_FILES['profil']['tmp_name'];
        $file_size = $_FILES['profil']['size'];
        $file_type = $_FILES['profil']['type'];
        $upload_dir = "../assets/profil/";

        // Validasi ukuran file
        if ($file_size > $max_file_size) {
            $error_messages[] = "Ukuran file profil terlalu besar. Maksimum ukuran file adalah 2MB.";
        }

        // Validasi tipe file
        if (!in_array($file_type, $allowed_file_types)) {
            $error_messages[] = "Tipe file profil tidak valid. Hanya diperbolehkan mengunggah file gambar (jpeg, png, gif).";
        }

        // Pastikan direktori upload ada dan dapat ditulisi
        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            die("Direktori upload profil tidak ada atau tidak dapat ditulisi.");
        }

        // Jika tidak ada kesalahan validasi, pindahkan file
        if (empty($error_messages)) {
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                // Berhasil upload, siapkan query update dengan foto profil
                $sql_update_profil = "UPDATE siswa SET 
                    nama = ?, 
                    tanggal_lahir = ?, 
                    jenis_kelamin = ?, 
                    agama = ?, 
                    alamat = ?, 
                    email_orangtua = ?, 
                    no_telpon = ?, 
                    foto_profil = ?
                    WHERE id_siswa = ?";
                $stmt_profil = $koneksi->prepare($sql_update_profil);
                $stmt_profil->bind_param("ssssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email_orang_tua, $no_telp_orang_tua, $file_name, $id_user);
            } else {
                $error_messages[] = "Gagal mengunggah file profil.";
            }
        }
    } else {
        // Update data tanpa mengubah foto profil
        $sql_update_profil = "UPDATE siswa SET 
            nama = ?, 
            tanggal_lahir = ?, 
            jenis_kelamin = ?, 
            agama = ?, 
            alamat = ?, 
            email_orangtua = ?, 
            no_telpon = ?
            WHERE id_siswa = ?";
        $stmt_profil = $koneksi->prepare($sql_update_profil);
        $stmt_profil->bind_param("ssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email_orang_tua, $no_telp_orang_tua, $id_user);
    }

    // Handle kesalahan validasi atau pengunggahan
    if (!empty($error_messages)) {
        $_SESSION['update_profile_error'] = implode("<br>", $error_messages);
        header('location: ../siswa/profil.php');
        exit;
    }

    // Lakukan query untuk mengupdate profil siswa
    if ($stmt_profil->execute()) {
        // Update berhasil, lanjutkan dengan data tambahan (orang tua, foto KK, foto Akte)
        // Lakukan query untuk update data orang tua jika ada perubahan
        $sql_update_orang_tua = "INSERT INTO orangtua (id_siswa, nama_ayah, pekerjaan_ayah, telp_ayah, nama_ibu, pekerjaan_ibu, telp_ibu)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_orangtua = mysqli_prepare($koneksi, $sql_update_orang_tua);
        mysqli_stmt_bind_param($stmt_orangtua, 'issssss', $id_user, $nama_ayah, $pekerjaan_ayah, $telp_ayah, $nama_ibu, $pekerjaan_ibu, $telp_ibu);
        mysqli_stmt_execute($stmt_orangtua);

        // Lakukan query untuk update foto KK jika ada perubahan
        if (!empty($foto_kk['name'])) {
            $file_name_kk = $foto_kk['name'];
            $file_tmp_kk = $foto_kk['tmp_name'];
            $file_size_kk = $foto_kk['size'];
            $file_type_kk = $foto_kk['type'];
            $upload_dir_kk = "../assets/dokumen/KartuKeluarga";

            // Validasi ukuran file
            if ($file_size_kk > $max_file_size) {
                $_SESSION['update_profile_error'] = "Ukuran file Kartu Keluarga terlalu besar. Maksimum ukuran file adalah 2MB.";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;   
            }

            // Validasi tipe file
            if (!in_array($file_type_kk, $allowed_file_types)) {
                $_SESSION['update_profile_error'] = "Tipe file Kartu Keluarga tidak valid. Hanya diperbolehkan mengunggah file gambar (jpeg, png, gif).";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  
            }

            // Pastikan direktori upload ada dan dapat ditulisi
            if (!is_dir($upload_dir_kk) || !is_writable($upload_dir_kk)) {
                die("Direktori upload Kartu Keluarga tidak ada atau tidak dapat ditulisi.");
            }

            // Pindahkan file yang diunggah ke direktori upload
            if (move_uploaded_file($file_tmp_kk, $upload_dir_kk . $file_name_kk)) {
                // Update nama file foto KK di database
                $sql_update_dokumen = "INSERT INTO dokumen (id_siswa, foto_kk) VALUES (?, ?)";
                $stmt_dokumen = mysqli_prepare($koneksi, $sql_update_dokumen);
                mysqli_stmt_bind_param($stmt_dokumen, "is", $randomId, $file_name_kk);
                mysqli_stmt_execute($stmt_dokumen);
            } else {
                $_SESSION['update_profile_error'] = "Gagal mengunggah file Kartu Keluarga.";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  
            }
        }

        // Lakukan query untuk update foto Akte Kelahiran jika ada perubahan
        if (!empty($foto_akte['name'])) {
            $file_name_akte = $foto_akte['name'];
            $file_tmp_akte = $foto_akte['tmp_name'];
            $file_size_akte = $foto_akte['size'];
            $file_type_akte = $foto_akte['type'];
            $upload_dir_akte = "../assets/dokumen/AkteKelahiran";

            // Validasi ukuran file
            if ($file_size_akte > $max_file_size) {
                $_SESSION['update_profile_error'] = "Ukuran file Akte Kelahiran terlalu besar. Maksimum ukuran file adalah 2MB.";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  
            }

            // Validasi tipe file
            if (!in_array($file_type_akte, $allowed_file_types)) {
                $_SESSION['update_profile_error'] = "Tipe file Akte Kelahiran tidak valid. Hanya diperbolehkan mengunggah file gambar (jpeg, png, gif).";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  
            }

            // Pastikan direktori upload ada dan dapat ditulisi
            if (!is_dir($upload_dir_akte) || !is_writable($upload_dir_akte)) {
                die("Direktori upload Akte Kelahiran tidak ada atau tidak dapat ditulisi.");
            }

            // Pindahkan file yang diunggah ke direktori upload
            if (move_uploaded_file($file_tmp_akte, $upload_dir_akte . $file_name_akte)) {
                // Update nama file foto Akte Kelahiran di database
                $sql_update_dokumen = "INSERT INTO dokumen (id_siswa, foto_akte) VALUES (?, ?)";
                $stmt_dokumen = mysqli_prepare($koneksi, $sql_update_dokumen);
                mysqli_stmt_bind_param($stmt_dokumen, "is", $randomId, $file_name_akte);
                mysqli_stmt_execute($stmt_dokumen);
            } else {
                $_SESSION['update_profile_error'] = "Gagal mengunggah file Akte Kelahiran.";
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  
            }
        }

        // Redirect back to profile page after updates
        echo '<script>window.location.href = "../siswa/profil.php";</script>';
        exit;


        // Jika semua query berhasil dieksekusi, maka redirect dengan pesan sukses
        $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";

        // Lakukan query untuk mengupdate profil siswa
    if ($stmt_profil->execute()) {
        // Update berhasil, lanjutkan dengan data tambahan (orang tua, foto KK, foto Akte)
        
        // Ambil Data Orang Tua
        $sql_orangtua = "SELECT * FROM orangtua WHERE id_siswa = ?";
        $stmt_orangtua = $koneksi->prepare($sql_orangtua);
        $stmt_orangtua->bind_param("i", $id_user);
        $stmt_orangtua->execute();
        $result_orangtua = $stmt_orangtua->get_result();

        // Ambil Data Foto KK
        $sql_kk = "SELECT foto_kk FROM dokumen WHERE id_siswa = ?";
        $stmt_kk = $koneksi->prepare($sql_kk);
        $stmt_kk->bind_param("i", $id_user);
        $stmt_kk->execute();
        $result_kk = $stmt_kk->get_result();

        // Ambil Data Foto Akte Kelahiran
        $sql_akte = "SELECT foto_akte FROM dokumen WHERE id_siswa = ?";
        $stmt_akte = $koneksi->prepare($sql_akte);
        $stmt_akte->bind_param("i", $id_user);
        $stmt_akte->execute();
        $result_akte = $stmt_akte->get_result();

        // Ambil Data Pendaftar Terbaru
        $sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = ?";
        $stmt_pendaftar = $koneksi->prepare($sql_pendaftar);
        $stmt_pendaftar->bind_param("i", $id_user);
        $stmt_pendaftar->execute();
        $result_pendaftaran = $stmt_pendaftar->get_result();
        if ($result_pendaftaran->num_rows > 0) {
            $data_pendaftar = $result_pendaftaran->fetch_array(MYSQLI_ASSOC);
            $_SESSION['data_terbaru'] = $data_pendaftar;
        }

        // Simpan data orang tua ke dalam sesi
        if ($result_orangtua->num_rows > 0) {
            $data_orangtua = $result_orangtua->fetch_array(MYSQLI_ASSOC);
            $_SESSION['data_orangtua'] = $data_orangtua;
        }

        // Simpan nama file KK ke dalam sesi
        if ($result_kk->num_rows > 0) {
            $data_kk = $result_kk->fetch_array(MYSQLI_ASSOC);
            $_SESSION['data_kk'] = $data_kk['foto_kk'];
        }

        // Simpan nama file Akte Kelahiran ke dalam sesi
        if ($result_akte->num_rows > 0) {
            $data_akte = $result_akte->fetch_array(MYSQLI_ASSOC);
            $_SESSION['data_akte'] = $data_akte['foto_akte'];
        }

        // Set pesan sukses
        $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";
    } else {
        // Gagal update profil
        $_SESSION['update_profile_error'] = "Error saat mengupdate profil: " . $stmt_profil->error;
    }

    // Redirect ke halaman profil
    // header('location: ../siswa/profil.php');
                // exit;
                echo '<script>window.location.href = "../siswa/profil.php";</script>';
                exit;  

    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
