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

// Logika Status Pembayaran
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
        $_SESSION['belum_diterima'] = "Silahkan mengisi data diri anak dan lakukan pembayaran";
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
    $foto = $_POST['foto_profil'] ?? '';

    // Proses unggah foto profil jika ada
    if (!empty($_FILES['profil']['name'])) {
        $file_name = $_FILES['profil']['name'];
        $file_tmp = $_FILES['profil']['tmp_name'];
        $upload_dir = "../assets/profil/";

        // Pastikan direktori upload ada dan dapat ditulisi
        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            die("Direktori upload tidak ada atau tidak dapat ditulisi.");
        }

        // Pindahkan file yang diunggah ke direktori upload
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // Update data termasuk foto profil
            $sql_update = "UPDATE siswa SET 
                            nama = ?, 
                            tanggal_lahir = ?, 
                            jenis_kelamin = ?, 
                            agama = ?, 
                            alamat = ?, 
                            email_orangtua = ?, 
                            no_telpon = ?, 
                            foto_profil = ?
                            WHERE id_siswa = ?";
            $stmt = $koneksi->prepare($sql_update);
            $stmt->bind_param("ssssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email_orang_tua, $no_telp_orang_tua, $file_name, $id_user);
        } else {
            die("Gagal mengunggah file.");
        }
    } else {
        // Update data tanpa mengubah foto profil
        $sql_update = "UPDATE siswa SET 
                        nama = ?, 
                        tanggal_lahir = ?, 
                        alamat = ?, 
                        agama = ?, 
                        jenis_kelamin = ?, 
                        email_orangtua = ?, 
                        no_telpon = ?
                        WHERE id_siswa = ?";
        $stmt = $koneksi->prepare($sql_update);
        $stmt->bind_param("sssssssi", $nama, $tanggal_lahir, $alamat, $agama, $jenis_kelamin, $email_orang_tua, $no_telp_orang_tua, $id_user);
    }

    // Lakukan query untuk melakukan update
    if ($stmt->execute()) {
        $_SESSION['update_profile_success'] = "Profil berhasil diperbarui.";

        // Lakukan query untuk mendapatkan data terbaru
        $sql_pendaftar = "SELECT * FROM siswa WHERE id_siswa = ?";
        $stmt = $koneksi->prepare($sql_pendaftar);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result_pendaftaran = $stmt->get_result();
        if ($result_pendaftaran->num_rows > 0) {
            // Ambil data terbaru
            $data_pendaftar = $result_pendaftaran->fetch_array(MYSQLI_ASSOC);
            // Simpan data terbaru ke dalam sesi agar dapat ditampilkan setelah redirect
            $_SESSION['data_terbaru'] = $data_pendaftar;

        }
    } else {
        $_SESSION['update_profile_error'] = "Error: " . $stmt->error;
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
