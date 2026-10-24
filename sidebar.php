<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
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

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background:   #8590e5;
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            padding: 20px 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 0 20px 30px;
            text-align: center;
            border-bottom: 1px solid var(--glass-border);
        }

        .sidebar-logo {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .sidebar-title {
            color: white;
            font-size: 1.3em;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--glass);
            color: white;
            border-right: 3px solid #4facfe;
        }

        .sidebar-menu a span {
            margin-right: 15px;
            font-size: 1.2em;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: margin-left 0.3s ease;
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
            font-size: 1.1em;
            font-weight: 500;
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

<body>
    <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">🏛️</div>
                <div class="sidebar-title">Surat Ormawa</div>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="beranda.php" onclick="showPage('dashboard')">
                    <span>🏠</span> Dashboard
                </a></li>
                <li><a href="form_suratmasuk.php" onclick="showPage('surat-masuk')">
                    <span>📥</span> Surat Masuk
                </a></li>
                <li><a href="form_suratkeluar.php" onclick="showPage('surat-keluar')">
                    <span>📤</span> Surat Keluar
                </a></li>
                <li><a href="data_suratmasuk.php" onclick="showPage('arsip')">
                    <span>🗃️</span> Data Surat Masuk
                </a></li>
                <li><a href="data_suratkeluar.php" onclick="showPage('laporan')">
                    <span>🗃️</span> Data Surat Keluar
                </a></li>
                <li><a href="login.php" onclick="logout()">
                    <span>🚪</span> Logout
                </a></li>
            </ul>
        </nav>
</body>