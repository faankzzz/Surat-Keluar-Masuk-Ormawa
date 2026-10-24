<?php
include 'configg.php';

if (isset($_GET['no_agenda'])) {
    $no_agenda = $_GET['no_agenda'];
    $conn->query("DELETE FROM surat_keluar WHERE no_agenda = '$no_agenda'");
    header("Location: data_suratkeluar.php");
    exit;
} else {
    echo "No agenda tidak ditemukan.";
}
?>
