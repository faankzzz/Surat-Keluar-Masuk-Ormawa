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
    LIMIT 3
");
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Platform Manajemen Surat Ormawa</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
     :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            --success: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
            --warning: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
            --danger: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
    }

    body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--primary);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

    .app-container {
            display: none;
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

        .header {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            border: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            color: white;
            font-size: 1.8em;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            background: var(--glass);
            padding: 10px 15px;
            border-radius: 15px;
            border: 1px solid var(--glass-border);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary { background: var(--secondary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-danger { background: var(--danger); color: white; }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--glass-border);
            text-align: center;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--secondary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: bold;
        }

        .card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--glass-border);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        .card-title {
            color: white;
            font-size: 1.4em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: white;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--glass-border);
            border-radius: 10px;
            background: var(--glass);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            background: var(--glass);
            backdrop-filter: blur(20px);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        .table th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .badge-success { background: var(--success); color: white; }
        .badge-primary { background: var(--secondary); color: white; }
        .badge-warning { background: var(--warning); color: white; }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: var(--glass);
            backdrop-filter: blur(20px);
            margin: 2% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            color: white;
            font-size: 1.4em;
            font-weight: 600;
        }

        .modal-body {
            padding: 30px;
        }

        .close {
            color: rgba(255, 255, 255, 0.6);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: white;
        }

        .search-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .timeline {
            max-height: 400px;
            overflow-y: auto;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border-left: 4px solid #4facfe;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .timeline-date {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9em;
            margin-right: 15px;
            min-width: 100px;
        }

        .timeline-content {
            color: white;
            flex: 1;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .action-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--glass-border);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .action-icon {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .action-title {
            color: white;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9em;
        }
      .activity-timeline {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.card-aktivitas {
    flex: 1;
    min-width: 150px;
    display: flex;
    margin-bottom: 2px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border-left: 4px solid #4facfe;
    transition: all 0.3s ease;;
}

.card-aktivitas:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

.card-aktivitas h4 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

.aktivitas-item {
    color: rgba(255, 255, 255, 0.8);
    font-size: 17px;
    margin-right: 15px;
    min-width: 100px;;
}
 .aktivitas-content {
    color: white;
    flex: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .header {
                padding: 15px 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
            }
        }

        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(67, 233, 123, 0.1);
            border-color: rgba(67, 233, 123, 0.3);
            color: #43e97b;
        }

        .alert-danger {
            background: rgba(250, 112, 154, 0.1);
            border-color: rgba(250, 112, 154, 0.3);
            color: #fa709a;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: block;
            padding: 15px;
            background: var(--glass);
            border: 2px dashed var(--glass-border);
            border-radius: 10px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: #4facfe;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination button {
            padding: 8px 15px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button:hover,
        .pagination button.active {
            background: var(--secondary);
        }
  </style>
</head>
<body>

 <!-- Main Application -->
    <div id="mainApp" class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
        <?php include 'sidebar.php'; ?>
      </div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1 class="header-title" id="pageTitle">📊 Dashboard</h1>
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
    <h3 class="card-title">🚀 Aksi Cepat</h3>
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
                            <div class="action-icon">🥚</div>
                            <div class="action-title">Data Surat Masuk</div>
                            <div class="action-desc">Surat-surat yang telah diterima</div>
                        </div>
                        </a>
                        <a href="data_suratkeluar.php" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon">🐣</div>
                            <div class="action-title">Data Surat Keluar</div>
                            <div class="action-desc">Surat-surat yang telah dikirim</div>
                        </div>
                        </a>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <!-- Chart -->
                    <div class="card">
                        <div class="card-header">
<h3 class="card-title">📈 Statistik Surat</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartCanvas" width="700" height="300"></canvas>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">⏰ Aktivitas Terakhir</h3>
                        </div>
                       <div class="activity-timeline">
    <?php
    // Simpan semua data ke array dulu
    $aktivitas = [];
    while($row = mysqli_fetch_assoc($query_aktivitas)) {
        $aktivitas[] = $row;
    }

    // Hitung jumlah per card
    $total = count($aktivitas);
    $per_card = ceil($total / 3);

    // Bagi ke 3 card
    for ($i = 0; $i < 3; $i++): ?>
        <div class="card-aktivitas">
            <?php
            for ($j = $i * $per_card; $j < ($i + 1) * $per_card && $j < $total; $j++):
                $row = $aktivitas[$j];
            ?>
                <div class="aktivitas-item">
                    <small><?= date('d M Y', strtotime($row['tanggal'])); ?></small>
                    <div class ="aktivitas-content">
                    <strong>[<?= $row['jenis']; ?>]</strong>
                    Nomor: <?= $row['nomor']; ?> — 
                    <?= $row['detail']; ?> <br>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    <?php endfor; ?>
</div>


                    </div>
                </div>
            </div>
                    </main>
    </div> <!-- Penutup #mainApp -->

    <script>
      function showPage(pageId) {
        document.querySelectorAll('.page-section').forEach(p => p.classList.remove('active'));
        const target = document.getElementById(pageId);
        if (target) {
          target.classList.add('active');
          document.getElementById('pageTitle').innerText = {
            'dashboard': '📊 Dashboard',
            'surat-masuk': '📥 Surat Masuk',
            'surat-keluar': '📤 Surat Keluar',
            'arsip': '🗃️ Arsip',
            'laporan': '📊 Laporan',
            'pengaturan': '⚙️ Pengaturan'
          }[pageId] || '';
        }

        document.querySelectorAll('.sidebar-menu a').forEach(link => link.classList.remove('active'));
        event.target.classList.add('active');
      }

      function logout() {
        alert("Anda telah logout.");
      }

      function generateReport() {
        alert("Laporan berhasil dibuat (simulasi).");
      }

      function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'block';
      }

      function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'none';
      }

      window.onload = () => {
    // Ambil elemen canvas yang benar
    const canvas = document.getElementById('chartCanvas');
    const ctx = canvas.getContext('2d');
    
    // Buat objek Image
    const img = new Image();
    
// Set path gambar yang benar (relatif dari root website)
    img.src = 'uploads/sintak.JPEG';
    
    // Gambar ke canvas saat gambar selesai dimuat
    img.onload = function() {
        // Pertahankan ukuran canvas asli (500x300)
        // Hitung rasio untuk mempertahankan proporsi gambar
        const ratio = Math.min(
            canvas.width / img.width,
            canvas.height / img.height
        );
        const newWidth = img.width * ratio;
        const newHeight = img.height * ratio;
        
        // Gambar gambar di tengah canvas
        const x = (canvas.width - newWidth) / 2;
        const y = (canvas.height - newHeight) / 2;
        
        ctx.drawImage(img, x, y, newWidth, newHeight);
    };
    
    // Handle error jika gambar tidak ditemukan
    img.onerror = function() {
        ctx.fillStyle = "rgba(255, 255, 255, 0.1)";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "white";
        ctx.font = "16px Arial";
        ctx.textAlign = "center";
        ctx.fillText("Gambar tidak ditemukan", canvas.width/2, canvas.height/2);
        console.error("Gagal memuat gambar: " + img.src);
    };
    
    // Tampilkan aplikasi
    document.getElementById("mainApp").style.display = "block";
};
</script>
</body>
</html>
