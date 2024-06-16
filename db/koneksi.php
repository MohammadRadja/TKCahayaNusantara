<?php
$host = "localhost";
$username =  "root";
$password = "";
$database = "tkcahayanusantara";

$koneksi = new mysqli($host,$username,$password,$database);
if($koneksi -> connect_error){
    echo "Koneksi gagal".mysqli_connect_error();
    die;
}

?>