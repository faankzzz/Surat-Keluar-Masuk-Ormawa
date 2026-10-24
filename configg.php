<?php
$host = "localhost";
$user = "root";
$pass = ""; // sesuaikan kalau ada password
$db = "tugassadmmm"; // ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
