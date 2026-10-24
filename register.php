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
    <title>Form Pendaftaran</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 350px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2980b9;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border 0.3s;
        }

        .form-container input:focus {
            border-color: #2980b9;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background: #2573a6;
        }

        .message {
            margin: 20px auto;
            width: fit-content;
            padding: 10px 15px;
            border-radius: 6px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        a {
            color: #155724;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Daftar Akun</h2>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama lengkap" required>
            <input type="email" name="gmail" placeholder="Alamat Gmail" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
