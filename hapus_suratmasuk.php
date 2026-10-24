<?php
include 'configg.php';

if (isset($_GET['no_agenda'])) {
    $no_agenda = $_GET['no_agenda'];
    $conn->query("DELETE FROM surat_masuk WHERE no_agenda = '$no_agenda'");
    header("Location: data_suratmasuk.php");
    exit;
} else {
    echo "No agenda tidak ditemukan.";
}
?>
