<?php
include 'configg.php';

$instansi = isset($_GET['instansi']) ? $conn->real_escape_string($_GET['instansi']) : '';
$perihal  = isset($_GET['perihal']) ? $conn->real_escape_string($_GET['perihal']) : '';

if ($instansi && $perihal) {
    $result = $conn->query("SELECT * FROM surat_masuk WHERE nama_instansi LIKE '%$instansi%' AND perihal LIKE '%$perihal%' ORDER BY CAST(nomor AS UNSIGNED) ASC");
} elseif ($instansi) {
    $result = $conn->query("SELECT * FROM surat_masuk WHERE nama_instansi LIKE '%$instansi%' ORDER BY CAST(nomor AS UNSIGNED) ASC");
} else {
    $result = $conn->query("SELECT * FROM surat_masuk ORDER BY CAST(nomor AS UNSIGNED) ASC");
}


$suratMasuk = [];
while ($row = $result->fetch_assoc()) {
    $suratMasuk[] = $row;
}

$total = count($suratMasuk);
$current = isset($_GET['index']) ? (int)$_GET['index'] : 0;
if ($current < 0) $current = 0;
if ($current >= $total) $current = $total - 1;

if ($total === 0) {
    echo "<div class='main-content'>";
    echo "<div class='message' style='background-color:#ffe0e0;'>Tidak ada data ditemukan untuk instansi '<strong>" . htmlspecialchars($instansi) . "</strong>' dan perihal '<strong>" . htmlspecialchars($perihal) . "</strong>'</div>";
    echo "<a href='data_suratmasuk.php' class='btn'>🔄 Kembali</a>";
    echo "</div>";
    exit;
}

$data = $suratMasuk[$current];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Surat Masuk</title>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #1e88e5, #42a5f5, #64b5f6, #bbdefb);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: #333;
        }
        @keyframes gradientShift {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            background-color: #1a237e;
            padding: 20px;
            width: 240px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            margin-top: 0;
            font-size: 1.2rem;
            text-align: center;
            color: #ffc107;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 36px;
        }

        .sidebar ul li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            color: #fff;
            text-decoration: none;
            font-family: "Tahoma";
            font-size:16px;
            display: block;
            padding: 15px 20px;
            border-radius: 2px;
            transition: background 0.3s;
        }

        .sidebar ul li a.active,
        .sidebar ul li a:hover {
            background-color:rgba(255, 255, 255, 0.8);
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .logo img {
            width: 120px;
            border-radius: 50%;
            border: 3px solid #fff;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 280px; /* Tambahkan ini */
            background-color: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            backdrop-filter: blur(8px);
            overflow-y: auto;
            align-items: center;
                }
        
        .section-header {
            display: flex;
            justify-content: space-between; /* Judul di kiri, tombol di kanan */
            align-items: center;
            background-color: #8590e5;
            color: #0d47a1;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .action-buttons {
            display: flex;
            gap: 10px; /* jarak antar tombol */
        }

        .btn {
            background-color: #1e88e5; /* default warna biru */
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #1565c0; 
        }
        .btn-nav {
            background-color: #1565c0;
        }
        .btn-nav:hover {
            background-color: #0d47a1;
        }
        .btn-gray {
            background-color: #6c757d;
        }
        .btn-gray:hover {
            background-color: #5a6268;
        }
        
        .table-container {
            background-color: #8590e5 !important;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            background-color:  rgba(255, 255, 255, 0.2);
            border-collapse: collapse;
        }
        table td {
            border: 1px solid #ddd;
            color : #0d47a1;
            padding: 12px;
        }
        table td:first-child {
            background-color: #e3f2fd;
            font-weight: bold;
            width: 35%;
        }
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .message {
            background-color: #d4edda;
            padding: 12px 15px;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 6px;
            font-size: 14px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .modal input {
            width: 100%;
            padding: 10px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .close {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
    <div class="section-header">
        <h2>Data Surat Masuk</h2>
        <div class="action-buttons">
            <button class="btn" onclick="openInstansiModal()">🔍 Cari Surat Masuk</button>
            <a href="data_suratmasuk.php" class="btn btn-secondary">🔄 Reset</a>
        </div>
    </div>

        <br><br>

        <!-- Tabel Data -->
        <div class="table-container">
            <table>
                <tr><td>Nomor</td><td><?= htmlspecialchars($data['nomor']); ?></td></tr>
                <tr><td>Nomor Surat</td><td><?= htmlspecialchars($data['nomor_surat']); ?></td></tr>
                <tr><td>Perihal</td><td><?= htmlspecialchars($data['perihal']); ?></td></tr>
                <tr><td>Nama Pengirim</td><td><?= htmlspecialchars($data['nama_pengirim']); ?></td></tr>
                <tr><td>Instansi</td><td><?= htmlspecialchars($data['instansi']); ?></td></tr>
                <tr><td>Tanggal Surat Masuk</td><td><?= date('d/m/Y', strtotime($data['tanggal_surat'])); ?></td></tr>
                    <td>File Surat</td>
                    <td>
                        <?php
                        $fileSurat = $data['file_surat'];
                        $ext = strtolower(pathinfo($fileSurat, PATHINFO_EXTENSION));
                        $link = "uploads/" . $fileSurat;
                        if ($ext === 'pdf') {
                            echo "<a href='$link' target='_blank'>📄 Lihat atau Unduh PDF</a>";
                        } else {
                            echo "<a href='$link' target='_blank' style='color:#0d47a1;'>🖼️ Lihat Gambar</a>";
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <div class="navigation">
                <a href="?index=<?= max(0, $current - 1) . ($instansi ? '&instansi=' . urlencode($instansi) : '') . ($perihal ? '&perihal=' . urlencode($perihal) : '') ?>" class="btn btn-nav">◀ Sebelumnya</a>
                <div>
                    <a href="form_suratmasuk.php" class="btn btn-gray">➕ Tambah Data</a>
                    <a href="edit_suratmasuk.php?nomor=<?= urlencode($data['nomor']); ?>" class="btn btn-gray">✏️ Edit</a>
                    <a href="hapus_suratmasuk.php?nomor=<?= urlencode($data['nomor']); ?>" onclick="return confirm('Yakin ingin menghapus surat ini?');" class="btn btn-gray">🗑️ Hapus</a>
                </div>
                <a href="?index=<?= min($total - 1, $current + 1) . ($instansi ? '&instansi=' . urlencode($instansi) : '') . ($perihal ? '&perihal=' . urlencode($perihal) : '') ?>" class="btn btn-nav">▶ Berikutnya</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pencarian -->
<div id="instansiModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeInstansiModal()">&times;</span>
    <h3>Cari Nama Instansi/Perusahaan</h3>
    <input type="text" id="instansiInput" placeholder="Masukkan nama instansi...">
    <button class="btn" onclick="submitInstansi()">Lanjut</button>
  </div>
</div>

<div id="perihalModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closePerihalModal()">&times;</span>
    <h3>Cari Perihal Surat</h3>
    <input type="text" id="perihalInput" placeholder="Masukkan perihal...">
    <button class="btn" onclick="submitPerihal()">Tampilkan Data</button>
  </div>
</div>

<!-- Script Modal -->
<script>
let instansi = '';

function openInstansiModal() {
    document.getElementById('instansiModal').style.display = 'flex';
}
function closeInstansiModal() {
    document.getElementById('instansiModal').style.display = 'none';
}
function submitInstansi() {
    instansi = document.getElementById('instansiInput').value.trim();
    if (!instansi) {
        alert('Isi nama instansi terlebih dahulu!');
        return;
    }
    closeInstansiModal();
    document.getElementById('perihalModal').style.display = 'flex';
}
function closePerihalModal() {
    document.getElementById('perihalModal').style.display = 'none';
}
function submitPerihal() {
    const perihal = document.getElementById('perihalInput').value.trim();
    if (!perihal) {
        alert('Isi perihal surat terlebih dahulu!');
        return;
    }
    window.location.href = `data_suratmasuk.php?instansi=${encodeURIComponent(instansi)}&perihal=${encodeURIComponent(perihal)}`;
}
</script>

</body>
</html>
