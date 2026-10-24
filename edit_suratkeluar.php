<?php
include 'configg.php';

if (isset($_GET['nomor'])) {
    $nomor = $_GET['nomor']; // Perbaikan: gunakan nama variabel yg benar
    $nomor = $conn->real_escape_string($nomor);

    // Ambil data surat berdasarkan nomor
    $result = $conn->query("SELECT * FROM surat_keluar WHERE nomor = '$nomor'");
    $data = $result->fetch_assoc();

    if (!$data) {
        echo "Data surat tidak ditemukan.";
        exit;
    }
} else {
    echo "Nomor tidak ditemukan.";
    exit;
}

// Proses Update Data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $ditujukan = $_POST['ditujukan'];
    $instansi = $_POST['instansi'];
    $tanggal_surat_keluar = $_POST['tanggal_surat_keluar'];
    $nama_penerima = $_POST['nama_penerima'];

    // Update data ke database (pastikan semua field sesuai dengan database)
    $updateQuery = "UPDATE surat_masuk SET 
                    nomor_surat = '$nomor_surat',
                    perihal = '$perihal',
                    ditujukan = '$ditujukan',
                    instansi = '$instansi',
                    tanggal_surat_keluar = '$tanggal_surat_keluar',
                    nama_penerima = '$nama_penerima',
                    WHERE nomor = '$nomor'"; // perbaikan: hapus koma terakhir sebelum WHERE

    if ($conn->query($updateQuery)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href = 'data_suratkeluar.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Surat Keluar</title>
    <style>
        .form-container {
            background-color: #8590e5 !important;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        .input-group {
            margin-bottom: 15px;
            color: #FFFFFF;
        }
        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #3498db;
            border: none;
            color: white;
            padding: 10px 18px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn + .btn {
            margin-left: 10px;
        }
        h2 {
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="wrapper">
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    </div>

<div class="form-container">
    <h2>Edit Surat Keluar</h2>
    
    <form method="POST">
    <input type="hidden" name="nomor" value="<?= htmlspecialchars($data['nomor']); ?>">    
        <div class="input-group">
            <label>Nomor:</label>
            <input type="text" name="nomor" value="<?= $data['nomor']; ?>" readonly />
        </div>
        <div class="input-group">
            <label>Nomor Surat:</label>
            <input type="text" name="nomor_surat" value="<?= $data['nomor_surat']; ?>" required />
        </div>
        <div class="input-group">
            <label>Perihal:</label>
            <input type="text" name="perihal" value="<?= $data['perihal']; ?>" required />
        </div>
        <div class="input-group">
            <label>Ditujukan:</label>
            <input type="text" name="ditujukan" value="<?= $data['ditujukan']; ?>" required />
        </div>
        <div class="input-group">
            <label>Instansi:</label>
            <input type="text" name="instansi" value="<?= $data['instansi']; ?>" required />
        </div>
        <div class="input-group">
            <label>Tanggal Surat Keluar:</label>
            <input type="date" name="tanggal_surat_keluar" value="<?= date('Y-m-d', strtotime($data['tanggal_surat_keluar'])); ?>" required />
        </div>
        <div class="input-group">
            <label>Nama Penerima:</label>
            <input type="text" name="nama_penerima" value="<?= $data['nama_penerima']; ?>" required />
        </div>
        <div class="input-group">
            <label>Upload File Surat (PDF/JPG/PNG):</label>
            <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" value="<?= $data['file_surat']; ?>" required />
        </div>
        <div>
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="data_suratmasuk.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

</body>
</html>
