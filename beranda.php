<?php 
include 'configg.php'; 

// Ambil jumlah total surat keluar
$query_keluar = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_keluar");
$jumlah_keluar = 0;
if ($row = mysqli_fetch_assoc($query_keluar)) {
    $jumlah_keluar = (int)$row['total'];
}

// Ambil jumlah total surat masuk
$query_masuk = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_masuk");
$jumlah_masuk = 0;
if ($row = mysqli_fetch_assoc($query_masuk)) {
    $jumlah_masuk = (int)$row['total'];
}

// Ambil jumlah surat bulan ini
$query_bulan = mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM (
        SELECT tanggal_surat FROM surat_masuk 
        WHERE MONTH(tanggal_surat) = MONTH(CURRENT_DATE()) 
        AND YEAR(tanggal_surat) = YEAR(CURRENT_DATE())
        UNION ALL
        SELECT tanggal_surat_keluar FROM surat_keluar 
        WHERE MONTH(tanggal_surat_keluar) = MONTH(CURRENT_DATE()) 
        AND YEAR(tanggal_surat_keluar) = YEAR(CURRENT_DATE())
    ) AS total_bulanan
");

$jumlah_bulan_ini = 0;
if ($row = mysqli_fetch_assoc($query_bulan)) {
    $jumlah_bulan_ini = $row['total'];
}

$query_aktivitas = mysqli_query($conn, "
    SELECT 'Surat Masuk' AS jenis, nomor, tanggal_surat AS tanggal, nama_pengirim AS detail 
    FROM surat_masuk
    UNION ALL
    SELECT 'Surat Keluar' AS jenis, nomor, tanggal_surat_keluar AS tanggal, ditujukan AS detail 
    FROM surat_keluar
    ORDER BY tanggal DESC
    LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - SIMAS ORMAWA</title>
  <style>
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid #3b82f6;
        transition: background 0.15s ease;
    }

    .activity-item:hover {
        background: #f1f5f9;
    }

    .activity-item .date {
        color: #64748b;
        font-size: 13px;
        min-width: 90px;
        font-weight: 500;
    }

    .activity-item .detail {
        flex: 1;
        font-size: 14px;
        color: #1e293b;
        line-height: 1.5;
    }

    .activity-item .detail .type-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 4px;
        margin-right: 8px;
    }

    .type-masuk {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .type-keluar {
        background: #dcfce7;
        color: #15803d;
    }

    .empty-state {
        text-align: center;
        padding: 36px;
        color: #94a3b8;
        font-size: 14px;
    }
  </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <h1 class="header-title">Dashboard</h1>
            <div class="header-actions">
                <div class="user-profile">
                    <span>👤</span>
                    <span>Admin Ormawa</span>
                </div>
            </div>
        </header>

        <!-- Dashboard Page -->
        <div id="dashboard" class="page-section active">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-number"><?php echo $jumlah_masuk + $jumlah_keluar; ?></div>
                    <div class="stat-label">Total Surat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📥</div>
                    <div class="stat-number"><?php echo $jumlah_masuk; ?></div>
                    <div class="stat-label">Surat Masuk</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📤</div>
                    <div class="stat-number"><?php echo $jumlah_keluar; ?></div>
                    <div class="stat-label">Surat Keluar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number"><?php echo $jumlah_bulan_ini; ?></div>
                    <div class="stat-label">Bulan Ini</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="quick-actions">
                    <a href="form_suratmasuk.php" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon">📥</div>
                            <div class="action-title">Tambah Surat Masuk</div>
                            <div class="action-desc">Input surat masuk baru</div>
                        </div>
                    </a>
                    <a href="form_suratkeluar.php" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon">📤</div>
                            <div class="action-title">Tambah Surat Keluar</div>
                            <div class="action-desc">Buat surat keluar baru</div>
                        </div>
                    </a>
                    <a href="data_suratmasuk.php" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon">📁</div>
                            <div class="action-title">Data Surat Masuk</div>
                            <div class="action-desc">Lihat arsip surat masuk</div>
                        </div>
                    </a>
                    <a href="data_suratkeluar.php" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon">📁</div>
                            <div class="action-title">Data Surat Keluar</div>
                            <div class="action-desc">Lihat arsip surat keluar</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Aktivitas Terakhir -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Terakhir</h3>
                </div>
                <div class="activity-list">
                    <?php
                    $aktivitas = [];
                    if ($query_aktivitas) {
                        while($row = mysqli_fetch_assoc($query_aktivitas)) {
                            $aktivitas[] = $row;
                        }
                    }

                    if (count($aktivitas) === 0): ?>
                        <div class="empty-state">Belum ada aktivitas surat</div>
                    <?php else:
                        foreach ($aktivitas as $row): ?>
                            <div class="activity-item">
                                <div class="date"><?= date('d M Y', strtotime($row['tanggal'])); ?></div>
                                <div class="detail">
                                    <span class="type-badge <?= $row['jenis'] === 'Surat Masuk' ? 'type-masuk' : 'type-keluar' ?>">
                                        <?= $row['jenis']; ?>
                                    </span>
                                    No. <?= $row['nomor']; ?> — <?= htmlspecialchars($row['detail']); ?>
                                </div>
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
