<?php
include 'configg.php';

$success = null;
$error = null;
$uploadedFileName = null;
$uploadedFilePath = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor = $conn->real_escape_string($_POST['nomor'] ?? '');
    $nama_pengirim = $conn->real_escape_string($_POST['nama_pengirim'] ?? '');
    $instansi = $conn->real_escape_string($_POST['instansi'] ?? '');
    $nomor_surat = $conn->real_escape_string($_POST['nomor_surat'] ?? '');
    $tanggal_surat = $conn->real_escape_string($_POST['tanggal_surat'] ?? '');
    $perihal = $conn->real_escape_string($_POST['perihal'] ?? '');

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $rawFileName = basename($_FILES["file_surat"]["name"] ?? '');
    if (!empty($rawFileName)) {
        $fileName = time() . "_" . $rawFileName;
        $targetFile = $uploadDir . $fileName;

        // Cek duplikasi nomor
        $cek = mysqli_query($conn, "SELECT * FROM surat_masuk WHERE nomor = '$nomor'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Nomor '$nomor' sudah ada. Silakan gunakan nomor yang berbeda.";
        } else {
            if (move_uploaded_file($_FILES["file_surat"]["tmp_name"], $targetFile)) {
                $sql = "INSERT INTO surat_masuk (
                    nomor, nama_pengirim, instansi, nomor_surat, tanggal_surat,
                    perihal, file_surat
                ) VALUES (
                    '$nomor', '$nama_pengirim', '$instansi', '$nomor_surat', '$tanggal_surat',
                    '$perihal', '$fileName'
                )";

                if (mysqli_query($conn, $sql)) {
                    $success = "Data surat masuk berhasil disimpan ke sistem!";
                    $uploadedFileName = $fileName;
                    $uploadedFilePath = $targetFile;
                } else {
                    $error = "Gagal menyimpan ke database: " . mysqli_error($conn);
                }
            } else {
                $error = "Gagal mengupload berkas surat.";
            }
        }
    } else {
        $error = "Silakan pilih berkas surat untuk diupload.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Surat Masuk - SIMAS ORMAWA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .content-wrapper {
            max-width: 660px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            text-align: center;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #eff6ff;
            color: var(--accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .page-header h2 {
            font-size: 1.35em;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin: 0 0 6px 0;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 13px;
            margin: 0;
        }

        .form-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--card-border);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            color: var(--text-primary);
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 13px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            color: var(--text-primary);
            background: #fff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-group input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .form-group input[type="file"] {
            padding: 9px 12px;
            background: #f8fafc;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--accent-hover);
        }

        .message {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: var(--radius);
            font-size: 14px;
            border: 1px solid #bbf7d0;
            background: #dcfce7;
            color: #15803d;
            text-align: center;
        }

        .message a {
            color: #15803d;
            font-weight: 600;
            text-decoration: underline;
        }

        .message.error {
            background: #fee2e2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .form-actions-bar {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
        }

        .btn-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: var(--radius);
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            background: #f8fafc;
            border: 1px solid var(--card-border);
            transition: all 0.15s ease;
        }

        .btn-link:hover {
            color: var(--accent);
            border-color: var(--accent);
            background: #fff;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <div class="header-icon">📥</div>
            <h2>Form Input Surat Masuk</h2>
            <p>Tambahkan arsip dan catatan surat masuk ke dalam sistem</p>
        </div>

        <?php if ($success): ?>
            <div class="message">
                <?= htmlspecialchars($success); ?>
                <br>
                <small>Berkas: <a href="<?= htmlspecialchars($uploadedFilePath); ?>" target="_blank"><?= htmlspecialchars($uploadedFileName); ?></a></small>
                <div class="form-actions-bar">
                    <a href="data_suratmasuk.php" class="btn-link">📁 Lihat Daftar Surat</a>
                    <a href="form_suratmasuk.php" class="btn-link">➕ Input Lagi</a>
                </div>
            </div>
        <?php elseif ($error): ?>
            <div class="message error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nomor">Nomor</label>
                    <input type="text" id="nomor" name="nomor" placeholder="Contoh: 01" required />
                </div>

                <div class="form-group">
                    <label for="nomor_surat">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" placeholder="Contoh: 11/HMJ-AB/SEMINAR/U/V/2025" required />
                </div>

                <div class="form-group">
                    <label for="perihal">Perihal</label>
                    <input type="text" id="perihal" name="perihal" placeholder="Contoh: Undangan Kegiatan Mahasiswa" required />
                </div>

                <div class="form-group">
                    <label for="nama_pengirim">Nama Pengirim</label>
                    <input type="text" id="nama_pengirim" name="nama_pengirim" placeholder="Contoh: HMJ Administrasi Bisnis" required />
                </div>

                <div class="form-group">
                    <label for="instansi">Instansi / Lembaga</label>
                    <input type="text" id="instansi" name="instansi" placeholder="Contoh: Politeknik Negeri Sriwijaya" required />
                </div>

                <div class="form-group">
                    <label for="tanggal_surat">Tanggal Surat Masuk</label>
                    <input type="date" id="tanggal_surat" name="tanggal_surat" required />
                </div>

                <div class="form-group">
                    <label for="file_surat">Upload Berkas Surat (PDF/JPG/PNG)</label>
                    <input type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <button type="submit" class="btn-submit">
                    <span>💾</span> Simpan Data Surat Masuk
                </button>
            </form>
        </div>

        <div class="form-actions-bar">
            <a href="data_suratmasuk.php" class="btn-link">📁 Lihat Data Surat Masuk</a>
            <a href="beranda.php" class="btn-link">🏠 Kembali ke Dashboard</a>
        </div>
    </div>
</main>

</body>
</html>
