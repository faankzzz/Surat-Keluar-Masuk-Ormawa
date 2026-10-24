<?php
$conn = new mysqli("localhost", "root", "", "tugassadmmm");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO pemohon (nama, gmail, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $gmail, $password);

    if ($stmt->execute()) {
        echo "<div class='message success'>Akun berhasil dibuat. <a href='login.php'>Login di sini</a></div>";
    } else {
        echo "<div class='message error'>Gagal mendaftar: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIMAS ORMAWA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }

        .form-container {
            background: #ffffff;
            padding: 36px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 8px;
            color: #1e293b;
            font-size: 1.4em;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
            color: #1e293b;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-container input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .form-container input::placeholder {
            color: #94a3b8;
        }

        .form-container button {
            width: 100%;
            padding: 11px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s ease;
            margin-top: 4px;
        }

        .form-container button:hover {
            background: #334155;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748b;
        }

        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .message {
            margin: 0 auto 16px;
            padding: 12px 16px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
        }

        .message.success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .message.success a {
            color: #15803d;
            font-weight: 600;
        }

        .message.error {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="form-container">
            <h2>Buat Akun Baru</h2>
            <p class="form-subtitle">Daftar untuk mengakses SIMAS ORMAWA</p>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="gmail" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" placeholder="Buat kata sandi" required>
                </div>
                <button type="submit">Daftar</button>
            </form>
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </div>
        </div>
        <div class="footer-text">
            &copy; 2026 SIMAS ORMAWA. All rights reserved.
        </div>
    </div>
</body>
</html>
