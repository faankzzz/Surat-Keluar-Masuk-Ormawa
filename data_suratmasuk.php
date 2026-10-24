<?php
include 'configg.php';

$q = isset($_GET['q']) ? trim($conn->real_escape_string($_GET['q'])) : '';
$instansi = isset($_GET['instansi']) ? trim($conn->real_escape_string($_GET['instansi'])) : '';
$perihal  = isset($_GET['perihal']) ? trim($conn->real_escape_string($_GET['perihal'])) : '';

$where = [];
if ($q !== '') {
    $where[] = "(nomor LIKE '%$q%' OR nomor_surat LIKE '%$q%' OR perihal LIKE '%$q%' OR instansi LIKE '%$q%' OR nama_pengirim LIKE '%$q%')";
}
if ($instansi !== '') {
    $where[] = "instansi LIKE '%$instansi%'";
}
if ($perihal !== '') {
    $where[] = "perihal LIKE '%$perihal%'";
}

$whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
$sql = "SELECT * FROM surat_masuk $whereClause ORDER BY CAST(nomor AS UNSIGNED) ASC";
$result = $conn->query($sql);

$suratMasuk = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $suratMasuk[] = $row;
    }
}

$total = count($suratMasuk);
$current = isset($_GET['index']) ? (int)$_GET['index'] : 0;
if ($current < 0) $current = 0;
if ($total > 0 && $current >= $total) $current = $total - 1;

$data = $total > 0 ? $suratMasuk[$current] : null;

// Cek apakah ada surat keluar yang juga cocok dengan kata kunci
$keluarCount = 0;
if ($q !== '') {
    $checkKeluar = $conn->query("SELECT COUNT(*) as total FROM surat_keluar WHERE nomor LIKE '%$q%' OR nomor_surat LIKE '%$q%' OR perihal LIKE '%$q%' OR ditujukan LIKE '%$q%' OR instansi LIKE '%$q%' OR nama_penerima LIKE '%$q%'");
    if ($checkKeluar && $rowK = $checkKeluar->fetch_assoc()) {
        $keluarCount = (int)$rowK['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Surat Masuk - SIMAS ORMAWA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .content-wrapper {
            max-width: 740px;
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

        /* Search Box in Header */
        .search-container {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .search-input-group {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 2px 4px 2px 14px;
            width: 100%;
            max-width: 480px;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .search-input-group:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .search-input-group input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-primary);
            background: transparent;
            padding: 8px 4px;
        }

        .btn-search-submit {
            background: var(--accent);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease;
            white-space: nowrap;
        }

        .btn-search-submit:hover {
            background: var(--accent-hover);
        }

        .header-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .btn-header {
            padding: 8px 16px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .btn-header-secondary {
            background: #f8fafc;
            color: var(--text-secondary);
            border-color: var(--card-border);
        }

        .btn-header-secondary:hover {
            background: #fff;
            color: var(--accent);
            border-color: var(--accent);
        }

        /* Search Badge Alert */
        .search-info-bar {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .search-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: var(--accent);
        }

        .other-search-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: #92400e;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .other-search-link:hover {
            background: #fde68a;
        }

        /* Centered Data Card */
        .data-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--card-border);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table tr {
            border-bottom: 1px solid var(--card-border);
        }

        .data-table tr:last-child {
            border-bottom: none;
        }

        .data-table td {
            padding: 14px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        /* Centered Data Column & Centered Label */
        .data-table .data-label {
            background: #f8fafc;
            font-weight: 600;
            width: 38%;
            color: var(--text-secondary);
            font-size: 13px;
            text-align: center;
            border-right: 1px solid var(--card-border);
        }

        .data-table .data-value {
            color: var(--text-primary);
            text-align: center;
            font-weight: 500;
            word-break: break-word;
        }

        .file-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            background: #eff6ff;
            color: var(--accent);
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #bfdbfe;
            transition: all 0.15s ease;
        }

        .file-link-btn:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        /* Centered Navigation Bar */
        .navigation {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .btn-nav {
            background: var(--accent);
            color: white;
            padding: 9px 16px;
            border-radius: var(--radius);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.15s ease;
        }

        .btn-nav:hover {
            background: var(--accent-hover);
        }

        .btn-nav.disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            pointer-events: none;
        }

        .nav-actions {
            display: flex;
            gap: 6px;
        }

        .btn-action-item {
            background: #f8fafc;
            color: var(--text-primary);
            padding: 9px 14px;
            border-radius: var(--radius);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid var(--card-border);
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-action-item:hover {
            background: #fff;
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-action-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        .record-info {
            text-align: center;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            padding: 6px 0 16px;
        }

        .empty-state {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 48px 24px;
            text-align: center;
            color: var(--text-secondary);
            box-shadow: var(--shadow-sm);
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.45);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .modal-content {
            background: var(--card-bg);
            padding: 28px;
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 440px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--card-border);
            text-align: center;
        }

        .modal-content h3 {
            color: var(--text-primary);
            font-size: 1.15em;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .modal-content p {
            color: var(--text-secondary);
            font-size: 13px;
            margin-bottom: 16px;
        }

        .modal-input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            margin-bottom: 14px;
            text-align: center;
        }

        .modal-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .modal-target-select {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .modal-target-select label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: var(--text-primary);
        }

        .modal-actions {
            display: flex;
            gap: 8px;
        }

        .modal-actions button {
            flex: 1;
            padding: 10px;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-modal-primary {
            background: var(--accent);
            color: white;
        }

        .btn-modal-primary:hover {
            background: var(--accent-hover);
        }

        .btn-modal-cancel {
            background: #f1f5f9;
            color: var(--text-secondary);
        }

        .btn-modal-cancel:hover {
            background: #e2e8f0;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <div class="header-icon">📁</div>
            <h2>Data Surat Masuk</h2>
            <p>Arsip dan detail data surat masuk organisasi</p>

            <!-- Search Form Langsung Sekaligus -->
            <form action="data_suratmasuk.php" method="GET" class="search-container">
                <div class="search-input-group">
                    <span>🔍</span>
                    <input type="text" name="q" value="<?= htmlspecialchars($q); ?>" placeholder="Cari langsung semua: nomor, perihal, instansi, pengirim..." />
                    <button type="submit" class="btn-search-submit">Cari</button>
                </div>
            </form>

            <div class="header-actions">
                <button class="btn-header btn-header-secondary" onclick="openSearchModal()">
                    <span>🔎</span> Pencarian Lengkap
                </button>
                <?php if ($q || $instansi || $perihal): ?>
                    <a href="data_suratmasuk.php" class="btn-header btn-header-secondary">
                        <span>🔄</span> Reset Pencarian
                    </a>
                <?php endif; ?>
                <a href="form_suratmasuk.php" class="btn-header btn-header-secondary">
                    <span>➕</span> Tambah Surat
                </a>
            </div>

            <?php if ($q || $instansi || $perihal): ?>
                <div class="search-info-bar">
                    <div class="search-badge">
                        <span>🔍 Hasil pencarian:</span>
                        <strong>"<?= htmlspecialchars($q ?: ($instansi . ' ' . $perihal)); ?>"</strong>
                        <span>(<?= $total; ?> surat ditemukan)</span>
                    </div>

                    <?php if ($keluarCount > 0): ?>
                        <a href="data_suratkeluar.php?q=<?= urlencode($q); ?>" class="other-search-link">
                            📤 Ditemukan juga <strong><?= $keluarCount; ?> surat keluar</strong> dengan kata kunci ini →
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total === 0): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Tidak Ada Data Surat Masuk</h3>
                <p>
                    <?= ($q || $instansi || $perihal) 
                        ? 'Tidak ditemukan data surat masuk yang cocok dengan kata kunci pencarian.' 
                        : 'Belum ada data surat masuk yang tersimpan dalam sistem.' ?>
                </p>
                <div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
                    <?php if ($q || $instansi || $perihal): ?>
                        <a href="data_suratmasuk.php" class="btn-header btn-header-primary">🔄 Tampilkan Semua Surat</a>
                        <?php if ($keluarCount > 0): ?>
                            <a href="data_suratkeluar.php?q=<?= urlencode($q); ?>" class="btn-header btn-header-secondary">
                                📤 Cari di Surat Keluar (<?= $keluarCount; ?> hasil)
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="form_suratmasuk.php" class="btn-header btn-header-primary">➕ Tambah Surat Sekarang</a>
                </div>
            </div>
        <?php else: ?>
            <div class="data-card">
                <table class="data-table">
                    <tr>
                        <td class="data-label">Nomor</td>
                        <td class="data-value"><?= htmlspecialchars($data['nomor']); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Nomor Surat</td>
                        <td class="data-value"><?= htmlspecialchars($data['nomor_surat']); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Perihal</td>
                        <td class="data-value"><?= htmlspecialchars($data['perihal']); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Nama Pengirim</td>
                        <td class="data-value"><?= htmlspecialchars($data['nama_pengirim']); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Instansi</td>
                        <td class="data-value"><?= htmlspecialchars($data['instansi']); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Tanggal Surat</td>
                        <td class="data-value"><?= date('d/m/Y', strtotime($data['tanggal_surat'])); ?></td>
                    </tr>
                    <tr>
                        <td class="data-label">Berkas Lampiran</td>
                        <td class="data-value">
                            <?php
                            $fileSurat = $data['file_surat'];
                            $ext = strtolower(pathinfo($fileSurat, PATHINFO_EXTENSION));
                            $link = "uploads/" . $fileSurat;
                            if ($ext === 'pdf') {
                                echo "<a href='$link' target='_blank' class='file-link-btn'>📄 Lihat / Unduh PDF</a>";
                            } else {
                                echo "<a href='$link' target='_blank' class='file-link-btn'>🖼️ Lihat Berkas Gambar</a>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Centered Navigation -->
            <div class="navigation">
                <?php 
                $prevIndex = max(0, $current - 1);
                $nextIndex = min($total - 1, $current + 1);
                $querySuffix = $q ? '&q=' . urlencode($q) : (($instansi ? '&instansi=' . urlencode($instansi) : '') . ($perihal ? '&perihal=' . urlencode($perihal) : ''));
                ?>
                <a href="?index=<?= $prevIndex . $querySuffix ?>" class="btn-nav <?= $current <= 0 ? 'disabled' : '' ?>">
                    ← Sebelumnya
                </a>

                <div class="nav-actions">
                    <a href="form_suratmasuk.php" class="btn-action-item">
                        <span>➕</span> Tambah
                    </a>
                    <a href="edit_suratmasuk.php?nomor=<?= urlencode($data['nomor']); ?>" class="btn-action-item">
                        <span>✏️</span> Edit
                    </a>
                    <a href="hapus_suratmasuk.php?nomor=<?= urlencode($data['nomor']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus surat nomor <?= htmlspecialchars($data['nomor']); ?> ini?');" class="btn-action-item btn-action-danger">
                        <span>🗑️</span> Hapus
                    </a>
                </div>

                <a href="?index=<?= $nextIndex . $querySuffix ?>" class="btn-nav <?= $current >= ($total - 1) ? 'disabled' : '' ?>">
                    Berikutnya →
                </a>
            </div>

            <div class="record-info">
                Data ke-<?= $current + 1 ?> dari <?= $total ?> surat
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal Pencarian All-in-One (Bisa Cari Surat Masuk atau Keluar) -->
<div id="searchModal" class="modal">
  <div class="modal-content">
    <h3>Pencarian Surat</h3>
    <p>Cari langsung sekaligus berdasarkan nomor, nomor surat, perihal, instansi, atau nama</p>
    
    <input type="text" id="modalSearchInput" class="modal-input" placeholder="Masukkan kata kunci pencarian..." autofocus />
    
    <div class="modal-target-select">
        <label>
            <input type="radio" name="searchTarget" value="masuk" checked />
            <span>Surat Masuk</span>
        </label>
        <label>
            <input type="radio" name="searchTarget" value="keluar" />
            <span>Surat Keluar</span>
        </label>
    </div>

    <div class="modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeSearchModal()">Batal</button>
        <button type="button" class="btn-modal-primary" onclick="submitSearchModal()">Cari Sekarang</button>
    </div>
  </div>
</div>

<script>
function openSearchModal() {
    document.getElementById('searchModal').style.display = 'flex';
    document.getElementById('modalSearchInput').focus();
}

function closeSearchModal() {
    document.getElementById('searchModal').style.display = 'none';
}

function submitSearchModal() {
    const val = document.getElementById('modalSearchInput').value.trim();
    if (!val) {
        alert('Silakan ketikkan kata kunci pencarian terlebih dahulu!');
        return;
    }
    const target = document.querySelector('input[name="searchTarget"]:checked').value;
    if (target === 'keluar') {
        window.location.href = `data_suratkeluar.php?q=${encodeURIComponent(val)}`;
    } else {
        window.location.href = `data_suratmasuk.php?q=${encodeURIComponent(val)}`;
    }
}

document.getElementById('modalSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        submitSearchModal();
    }
});

window.onclick = function(event) {
    const m = document.getElementById('searchModal');
    if (event.target === m) closeSearchModal();
}
</script>

</body>
</html>
