<?php

session_start();

include('koneksi.php');

// Menggunakan $_SERVER['HTTP_HOST'] untuk mendapatkan host saat ini
$url_based = "http://" . $_SERVER['HTTP_HOST'] . "/tk";
$uri_segment = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Debugging segmen URI (opsional)
// var_dump($uri_segment);

if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    // Pastikan segmen URI[2] tersedia sebelum digunakan
    if (isset($uri_segment[2]) && $uri_segment[2] == $_SESSION['level']) {
        // Pengguna memiliki akses
    } else {
        echo "Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.";
        echo "<br><br> <button type='button' onclick='history.back()'>Kembali</button>";
        die;
    }
} else {
    $_SESSION['login_error'] = "Silahkan login terlebih dahulu.";
    header('Location: ' . $url_based . '/login.php');
    exit;
}
?>
