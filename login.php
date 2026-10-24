<?php
$conn = new mysqli("localhost", "root", "", "tugassadmmm");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT nama, password FROM pemohon WHERE gmail = ?");
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nama, $stored_password);
        $stmt->fetch();

        if ($password === $stored_password) {
            echo "<script>
                alert('Login berhasil! Selamat datang, $nama');
                window.location.href = 'beranda.php';
            </script>";
        } else {
            echo "<script>alert('Password salah.');</script>";
        }
    } else {
        echo "<script>alert('Akun tidak ditemukan.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAS ORMAWA</title>
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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }

        .login-container {
            background: #ffffff;
            padding: 36px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: #1e293b;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .logo h1 {
            color: #1e293b;
            font-weight: 700;
            font-size: 1.4em;
            letter-spacing: -0.02em;
        }

        .logo p {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            color: #1e293b;
            margin-bottom: 6px;
            display: block;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-group input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        button {
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

        button:hover {
            background: #334155;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748b;
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
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
    <div class="login-wrapper">
        <div class="login-container">
            <form method="POST" class="login-form">
                <div class="logo">
                    <div class="logo-icon">📋</div>
                    <h1>SIMAS ORMAWA</h1>
                    <p>Sistem Manajemen Surat Organisasi Mahasiswa</p>
                </div>

                <div class="form-group">
                    <label for="gmail">Email</label>
                    <input type="email" id="gmail" name="gmail" required placeholder="nama@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi">
                </div>

                <button type="submit">Masuk</button>

                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </div>
            </form>
        </div>
        <div class="footer-text">
            &copy; 2026 SIMAS ORMAWA. All rights reserved.
        </div>
    </div>
</body>
</html>
