<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('koneksi.php');

// Mendapatkan host saat ini dan membuat URL dasar
$url_based = "http://" . $_SERVER['HTTP_HOST'] . "/tk";
$uri_segment = explode("/", trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/"));

// Debugging segmen URI (opsional)
// var_dump($uri_segment);

// Memeriksa status login
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    // Pastikan segmen URI tersedia sebelum digunakan
    $user_level = $_SESSION['level'];
    $uri_level = isset($uri_segment[1]) ? $uri_segment[1] : null; // Indeks 1 disesuaikan dengan URI setelah trim
    
    // Validasi akses pengguna berdasarkan level
    if ($uri_level === $user_level) {
        // Pengguna memiliki akses
    } else {
        echo "Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.";
        echo "<br><br><button type='button' onclick='history.back()'>Kembali</button>";
        die;
    }
} else {
    // Redirect ke halaman login jika belum login
    $_SESSION['login_error'] = "Silahkan login terlebih dahulu.";
    header('Location: ' . $url_based . '/login.php');
    exit;
}
?>
