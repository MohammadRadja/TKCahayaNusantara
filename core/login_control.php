<?php
include('../db/koneksi.php');
session_start();

if (isset($_POST['btn_login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Menggunakan md5 untuk hashing password

    $sql_user = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $sql_user);

    if (mysqli_num_rows($result) > 0) {
        while ($data_user = mysqli_fetch_array($result)) {
            $_SESSION['status'] = 'login';
            $_SESSION['id_users'] = $data_user['id'];
            $_SESSION['nama'] = $data_user['nama'];
            $_SESSION['level'] = $data_user['level'];

            if ($data_user['level'] == 'admin') {
                $_SESSION['admin_logged_in'] = true; // Mengatur sesi admin
                header('location:../admin/dashboard.php');
            } else if ($data_user['level'] == 'siswa') {
                $_SESSION['siswa_logged_in'] = true; // Mengatur sesi siswa
                header('location:../siswa/dashboard.php');
            }
        }
    } else {
        $_SESSION['login_error'] = "Username atau password salah!";
        header('location:../login.php');
    }
} else {
    header('location:../login.php');
}
?>
