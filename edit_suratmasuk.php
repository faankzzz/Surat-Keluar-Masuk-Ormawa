<?php
include 'configg.php';

if (isset($_GET['nomor'])) {
    $nomor = $_GET['nomor']; // Perbaikan: gunakan nama variabel yg benar
    $nomor = $conn->real_escape_string($nomor);

    // Ambil data surat berdasarkan nomor
    $result = $conn->query("SELECT * FROM surat_masuk WHERE nomor = '$nomor'");
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
    $nama_kementerian = $_POST['nama_kementerian'];
    $nama_instansi = $_POST['nama_instansi'];
    $alamat = $_POST['alamat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $ditujukan = $_POST['ditujukan'];
    $penanda_tangan = $_POST['penanda_tangan'];
    $jabatan = $_POST['jabatan'];
    $tanggal_pemusnahan = $_POST['tanggal_pemusnahan'];

    // Update data ke database (pastikan semua field sesuai dengan database)
    $updateQuery = "UPDATE surat_masuk SET 
                    nama_kementerian = '$nama_kementerian',
                    nama_instansi = '$nama_instansi',
                    alamat = '$alamat',
                    nomor_surat = '$nomor_surat',
                    tanggal_surat = '$tanggal_surat',
                    ditujukan = '$ditujukan',
                    perihal = '$perihal',
                    penanda_tangan = '$penanda_tangan',
                    jabatan = '$jabatan',
                    tanggal_pemusnahan = '$tanggal_pemusnahan'
                    WHERE nomor = '$nomor'"; // perbaikan: hapus koma terakhir sebelum WHERE

    if ($conn->query($updateQuery)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href = 'data_suratmasuk.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Surat Masuk - SIMAS ORMAWA</title>
    <style>
        .page-header {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .page-header h2 {
            font-size: 1.3em;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 13px;
            margin-top: 4px;
        }

        .form-card {
            background: var(--card-bg);
            padding: 28px;
            border-radius: var(--radius-lg);
            max-width: 640px;
            width: 100%;
            margin: 0 auto;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--card-border);
        }

        .input-group {
            margin-bottom: 16px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
            color: var(--text-primary);
        }

        .input-group input {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            color: var(--text-primary);
            background: #fff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .input-group input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .input-group input[readonly] {
            background: #f8fafc;
            color: var(--text-secondary);
        }

        .input-group input[type="file"] {
            padding: 8px 12px;
            background: #f8fafc;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-submit {
            flex: 1;
            padding: 11px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-submit:hover {
            background: var(--accent-hover);
        }

        .btn-cancel {
            padding: 11px 24px;
            background: #f8fafc;
            color: var(--text-primary);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .btn-cancel:hover {
            background: #fff;
            border-color: var(--text-muted);
        }
    </style>
</head>
<body>
<div class="wrapper" style="display:flex; min-height:100vh;">
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Edit Surat Masuk</h2>
            <p>Perbarui data surat masuk nomor <?= htmlspecialchars($data['nomor']); ?></p>
        </div>

        <div class="form-card">
            <form method="POST">
                <input type="hidden" name="nomor" value="<?= htmlspecialchars($data['nomor']); ?>">
                <div class="input-group">
                    <label>Nomor</label>
                    <input type="text" name="nomor" value="<?= $data['nomor']; ?>" readonly />
                </div>
                <div class="input-group">
                    <label>Nomor Surat</label>
                    <input type="text" name="nomor_surat" value="<?= $data['nomor_surat']; ?>" required />
                </div>
                <div class="input-group">
                    <label>Perihal</label>
                    <input type="text" name="perihal" value="<?= $data['perihal']; ?>" required />
                </div>
                <div class="input-group">
                    <label>Nama Pengirim</label>
                    <input type="text" name="nama_pengirim" value="<?= $data['nama_pengirim']; ?>" required />
                </div>
                <div class="input-group">
                    <label>Instansi</label>
                    <input type="text" name="instansi" value="<?= $data['instansi']; ?>" required />
                </div>
                <div class="input-group">
                    <label>Tanggal Surat Masuk</label>
                    <input type="date" name="tanggal_surat" value="<?= date('Y-m-d', strtotime($data['tanggal_surat'])); ?>" required />
                </div>
                <div class="input-group">
                    <label>Upload File Surat (PDF/JPG/PNG)</label>
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" />
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="data_suratmasuk.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
