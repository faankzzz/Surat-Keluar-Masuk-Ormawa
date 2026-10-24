<?php
include 'configg.php';

$nomor = $_GET['nomor'] ?? $_GET['no_agenda'] ?? null;
if ($nomor) {
    $nomor = $conn->real_escape_string($nomor);
    $conn->query("DELETE FROM surat_keluar WHERE nomor = '$nomor' OR no_agenda = '$nomor'");
    header("Location: data_suratkeluar.php");
    exit;
} else {
    echo "Nomor surat tidak ditemukan.";
}
?>
