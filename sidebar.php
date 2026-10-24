<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
     :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #3b82f6;
            --page-bg: #f1f5f9;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --success: #22c55e;
            --success-light: #dcfce7;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
            --radius: 8px;
            --radius-lg: 12px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--page-bg);
            min-height: 100vh;
            color: var(--text-primary);
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            padding: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-logo {
            font-size: 1.5em;
            margin-bottom: 4px;
        }

        .sidebar-title {
            color: #f8fafc;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .sidebar-subtitle {
            color: var(--text-muted);
            font-size: 0.75em;
            margin-top: 4px;
            font-weight: 400;
        }

        .sidebar-menu {
            list-style: none;
            padding: 12px 0;
        }

        .sidebar-menu li {
            margin-bottom: 2px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.15s ease;
            font-size: 14px;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: #f1f5f9;
            border-left-color: rgba(59,130,246,0.4);
        }

        .sidebar-menu a.active {
            background: rgba(59,130,246,0.15);
            color: #93c5fd;
            border-left-color: var(--sidebar-active);
        }

        .sidebar-menu a span {
            margin-right: 12px;
            font-size: 1.1em;
            width: 24px;
            text-align: center;
        }

        .sidebar-section-label {
            padding: 20px 20px 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
        }

        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            box-sizing: border-box;
            padding: 32px 24px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .header {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            margin-bottom: 24px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            color: var(--text-primary);
            font-size: 1.4em;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            background: var(--page-bg);
            padding: 8px 14px;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
        }

        .btn {
            padding: 9px 18px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-family: inherit;
        }

        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #16a34a; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #dc2626; }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s ease;
            position: relative;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            font-size: 1.8em;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--card-border);
        }

        .card-title {
            color: var(--text-primary);
            font-size: 1.05em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            background: #fff;
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            background: var(--card-bg);
            border: 1px solid var(--card-border);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            color: var(--text-primary);
        }

        .table th,
        .table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--card-border);
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-secondary);
        }

        .table tr:hover {
            background: #f8fafc;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: var(--success-light); color: #15803d; }
        .badge-primary { background: #dbeafe; color: #1d4ed8; }
        .badge-warning { background: var(--warning-light); color: #b45309; }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15,23,42,0.5);
        }

        .modal-content {
            background: var(--card-bg);
            margin: 8% auto;
            padding: 0;
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 500px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            color: var(--text-primary);
            font-size: 1.1em;
            font-weight: 600;
        }

        .modal-body {
            padding: 24px;
        }

        .close {
            color: var(--text-muted);
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.15s ease;
            line-height: 1;
        }

        .close:hover {
            color: var(--text-primary);
        }

        .search-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: var(--radius);
            border: 1px solid var(--card-border);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 16px;
        }

        .timeline {
            max-height: 400px;
            overflow-y: auto;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: var(--radius);
            border-left: 3px solid var(--accent);
            transition: background 0.15s ease;
        }

        .timeline-item:hover {
            background: #f1f5f9;
        }

        .timeline-date {
            color: var(--text-secondary);
            font-size: 13px;
            margin-right: 16px;
            min-width: 90px;
        }

        .timeline-content {
            color: var(--text-primary);
            flex: 1;
            font-size: 14px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .action-card {
            background: #f8fafc;
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--card-border);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-card:hover {
            background: #fff;
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
        }

        .action-icon {
            font-size: 2em;
            margin-bottom: 8px;
        }

        .action-title {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .action-desc {
            color: var(--text-secondary);
            font-size: 12px;
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
                width: 100%;
                padding: 16px;
            }
            
            .header {
                padding: 12px 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            margin-bottom: 16px;
            border: 1px solid transparent;
            font-size: 14px;
        }

        .alert-success {
            background: var(--success-light);
            border-color: #bbf7d0;
            color: #15803d;
        }

        .alert-danger {
            background: var(--danger-light);
            border-color: #fecaca;
            color: #b91c1c;
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
            padding: 14px;
            background: #f8fafc;
            border: 2px dashed var(--card-border);
            border-radius: var(--radius);
            text-align: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .file-upload-label:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #eff6ff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }

        .pagination button {
            padding: 8px 14px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            color: var(--text-primary);
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            transition: all 0.15s ease;
        }

        .pagination button:hover,
        .pagination button.active {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }
  </style>

    <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">📋</div>
                <div class="sidebar-title">Surat Ormawa</div>
                <div class="sidebar-subtitle">Sistem Manajemen Surat</div>
            </div>

            <div class="sidebar-section-label">Menu Utama</div>
            <ul class="sidebar-menu">
                <li><a href="beranda.php" class="<?= $currentPage == 'beranda.php' ? 'active' : '' ?>">
                    <span>🏠</span> Dashboard
                </a></li>
                <li><a href="form_suratmasuk.php" class="<?= $currentPage == 'form_suratmasuk.php' ? 'active' : '' ?>">
                    <span>📥</span> Surat Masuk
                </a></li>
                <li><a href="form_suratkeluar.php" class="<?= $currentPage == 'form_suratkeluar.php' ? 'active' : '' ?>">
                    <span>📤</span> Surat Keluar
                </a></li>
            </ul>

            <div class="sidebar-section-label">Data & Arsip</div>
            <ul class="sidebar-menu">
                <li><a href="data_suratmasuk.php" class="<?= $currentPage == 'data_suratmasuk.php' ? 'active' : '' ?>">
                    <span>📁</span> Data Surat Masuk
                </a></li>
                <li><a href="data_suratkeluar.php" class="<?= $currentPage == 'data_suratkeluar.php' ? 'active' : '' ?>">
                    <span>📁</span> Data Surat Keluar
                </a></li>
            </ul>

            <div class="sidebar-section-label">Akun</div>
            <ul class="sidebar-menu">
                <li><a href="login.php">
                    <span>🚪</span> Logout
                </a></li>
            </ul>
        </nav>