<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Form Surat Keluar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: linear-gradient(-45deg, #1e88e5, #42a5f5, #64b5f6, #bbdefb);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
        }

        h2 {
            margin-bottom: 25px;
            font-size: 24px;
            display: flex;
            justify-content: center;
            color: #0d47a1;
            background-color: #8590e5;
            padding: 10px 20px;
            border-radius: 8px;
        }
        
        .form-container {
                display: flex;
                justify-content: center;
            }

            form {
                background-color: #8590e5;
                padding: 25px;
                border-radius: 12px;
                max-width: 700px;
                width: 100%;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

        label {
            display: block;
            color:#ffffff;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        input, button {
            width: 100%;
            background-color:  rgba(255, 255, 255, 0.2);
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            border-color: #1a73e8;
            outline: none;
            box-shadow: 0 0 5px rgba(26, 115, 232, 0.3);
        }

        button {
            background-color: #1e88e5;
            color: white;
            font-weight: bold;
            border: none;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #1565c0;
        }

        .message {
            background-color: #d4edda;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 6px;
            font-size: 14px;
        }

        .message.error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <div class="sidebar">
        <div class="logo">
            <img src="uploads/logo.jpeg" alt="Logo" />
        </div>
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
        <h2>FORM INPUT SURAT KELUAR</h2>

        <?php
        include 'configg.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor = $_POST['nomor'];
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $ditujukan = $_POST['ditujukan'];
    $instansi = $_POST['instansi'];
    $tanggal_surat_keluar = $_POST['tanggal_surat_keluar'];
    $nama_penerima = $_POST['nama_penerima'];
   

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES["file_surat"]["name"]);
    $targetFile = $uploadDir . $fileName;

    // Upload file terlebih dahulu
    if (move_uploaded_file($_FILES["file_surat"]["tmp_name"], $targetFile)) {
        // File berhasil diupload, lanjut simpan ke database
        $sql = "INSERT INTO surat_keluar (
            nomor, nomor_surat, perihal, ditujukan, instansi, tanggal_surat_keluar,
            nama_penerima, file_surat
        ) VALUES (
            '$nomor','$nomor_surat', '$perihal', '$ditujukan', '$instansi', '$tanggal_surat_keluar',
            '$nama_penerima', '$fileName'
        )";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='message'>Data berhasil disimpan ke database dan file berhasil diupload!<br>
                  File surat tersimpan sebagai: <a href='$targetFile' target='_blank'>$fileName</a></div>";
        } else {
            echo "<div class='message error'>Gagal menyimpan ke database: " . mysqli_error($conn) . "</div>";
        }

    } else {
        echo "<div class='message error'>Gagal mengupload file.</div>";
    }
}
?>
        <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <label>Nomor:</label>
            <input type="text" name="nomor" required />

            <label>Nomor Surat:</label>
            <input type="text" name="nomor_surat" required />

            <label>Perihal:</label>
            <input type="text" name="perihal" required />

            <label>Ditujukan:</label>
            <input type="text" name="ditujukan" required />

            <label>Instansi:</label>
            <input type="text" name="instansi" required />

            <label>Tanggal Surat Keluar:</label>
            <input type="date" name="tanggal_surat_keluar" required />

            <label>Nama Penerima:</label>
            <input type="text" name="nama penerima" required />

            <label>Upload File Surat (PDF/JPG/PNG):</label>
            <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" required />

            <button type="submit">Simpan Data</button>
        </form>
        </div>
    </div>

</div>

</body>
</html>
