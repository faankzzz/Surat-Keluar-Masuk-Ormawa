<?php
include 'configg.php';

$no_agenda = $_POST['no_agenda'];
$nama_instansi = $_POST['nama_instansi'];
$alamat = $_POST['alamat'];
$nomor_surat = $_POST['nomor_surat'];
$tanggal_surat = $_POST['tanggal_surat'];
$ditujukan = $_POST['ditujukan'];
$perihal = $_POST['perihal'];
$penanda_tangan = $_POST['penanda_tangan'];
$jabatan = $_POST['jabatan'];
$tanggal_pemusnahan = $_POST['tanggal_pemusnahan'];

$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function simpanFile($inputName, $uploadDir) {
    $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return null;
}

$file_surat = simpanFile('file_surat', $uploadDir);

if (!$file_surat) {
    die("Upload file surat gagal, coba lagi.");
}

$stmt = $conn->prepare("INSERT INTO surat_masuk 
    (no_agenda, nama_instansi, alamat, nomor_surat, tanggal_surat, ditujukan, perihal, penanda_tangan, jabatan, tanggal_pemusnahan, file_surat) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssss", 
    $no_agenda, $nama_instansi, $alamat, $nomor_surat, $tanggal_surat, 
    $ditujukan, $perihal, $penanda_tangan, $jabatan, $tanggal_pemusnahan, $file_surat
);

if (!$stmt->execute()) {
    die("Gagal menyimpan data ke database: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>

<?php include 'sidebar.php'; ?>
<div class="main-content p-4">
    <h3 class="mb-4">Data Surat Masuk</h3>

    <div class="alert alert-success shadow">
        <h4 class="text-success">✅ Data Surat Masuk Berhasil Disimpan!</h4>
        <table class="table table-bordered mt-3" style="background-color: #d4edda;">
            <tr><td><strong>No urut Agenda</strong></td><td><?= htmlspecialchars($no_agenda); ?></td></tr>
            <tr><td><strong>Nama Kementerian/Perusahaan</strong></td><td><?= htmlspecialchars($nama_instansi); ?></td></tr>
            <tr><td><strong>Alamat</strong></td><td><?= htmlspecialchars($alamat); ?></td></tr>
            <tr><td><strong>Nomor Surat</strong></td><td><?= htmlspecialchars($nomor_surat); ?></td></tr>
            <tr><td><strong>Tanggal Surat</strong></td><td><?= date('m/d/Y', strtotime($tanggal_surat)); ?></td></tr>
            <tr><td><strong>Surat Ditujukan</strong></td><td><?= htmlspecialchars($ditujukan); ?></td></tr>
            <tr><td><strong>Perihal</strong></td><td><?= htmlspecialchars($perihal); ?></td></tr>
            <tr><td><strong>Penanda tangan</strong></td><td><?= htmlspecialchars($penanda_tangan); ?></td></tr>
            <tr><td><strong>Jabatan</strong></td><td><?= htmlspecialchars($jabatan); ?></td></tr>
            <tr><td><strong>Tanggal Pemusnahan Surat</strong></td><td><?= date('m/d/Y', strtotime($tanggal_pemusnahan)); ?></td></tr>
            <tr>
                <td><strong>File Surat</strong></td>
                <td>
                    <?php
                    $ext = pathinfo($file_surat, PATHINFO_EXTENSION);
                    $link = "../uploads/" . $file_surat;

                    if ($ext === 'pdf') {
                        echo "<a href='$link' target='_blank'>📄 Lihat atau Unduh PDF</a>";
                    } else {
                        echo "<a href='$link' target='_blank'>🖼️ Lihat Gambar</a>";
                    }
                    ?>
                </td>
            </tr>
        </table>

        <div class="mt-4">
            <a href="data_suratmasuk.php" class="btn btn-primary">📁 Lihat Daftar Surat</a>
            <a href="form_suratmasuk.php" class="btn btn-success">➕ Tambah Surat Lain</a>
        </div>
    </div>
</div>
