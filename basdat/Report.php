<?php
session_start();
require __DIR__ . '/config.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Validasi place_id
if (!isset($_GET['place_id']) || empty($_GET['place_id'])) {
    header("Location: index.php");
    exit;
}

$place_id = $_GET['place_id'];

// Daftar tempat yang valid
$places = [
    "prambanan" => "Candi Prambanan",
    "keraton" => "Keraton Yogyakarta",
    "heha" => "HeHa Sky View",
    "breksi" => "Tebing Breksi",
    "ratu-boko" => "Keraton Ratu Boko",
    "parangtritis" => "Pantai Parangtritis",
    "obelix-hills" => "Obelix Hills",
    "taman-sari" => "Taman Sari Yogyakarta"
];

// Cek apakah place_id valid
if (!isset($places[$place_id])) {
    header("Location: index.php");
    exit;
}

$place_name = $places[$place_id];
$success = false;
$error = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['reason'] ?? '');
    
    if (empty($reason)) {
        $error = "Alasan laporan tidak boleh kosong";
    } elseif (strlen($reason) < 20) {
        $error = "Alasan laporan minimal 20 karakter";
    } else {
        $user_id = $_SESSION['user_id'];
        
        $stmt = mysqli_prepare($conn, 
            "INSERT INTO reports (reporter_user_id, target_id, reason, status, created_at) 
             VALUES (?, ?, ?, 'pending', NOW())"
        );
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $place_id, $reason);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $error = "Gagal mengirim laporan. Silakan coba lagi.";
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporkan - <?= htmlspecialchars($place_name) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #D2691E 0%, #8B4513 50%, #CD853F 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: #222;
        }

        /* NAVBAR */
        .navbar {
            max-width: 1200px;
            margin: auto;
            padding: 25px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

                .logo {
            font-size: 22px;
            font-weight: bold;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(210, 105, 30, 0.3);
            letter-spacing: 1px;
        }

        .btn-back {
            background: #FF8C00;
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #e67a2e;
        }

        /* CONTAINER */
        .container {
            max-width: 700px;
            margin: 20px auto;
            padding: 0 20px;
        }

        /* HEADER SECTION */
        .header-section {
            background: linear-gradient(135deg, #C85A17, #A0420D);
            color: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .header-section h1 {
            font-size: 32px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-section .subtitle {
            font-size: 18px;
            opacity: 0.95;
            font-weight: 600;
        }

        /* REPORT FORM */
        .report-form {
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .report-form h2 {
            margin-bottom: 20px;
            color: #1e3c72;
            font-size: 24px;
        }

        /* INFO BOX */
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.7;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
            color: #856404;
            font-size: 15px;
        }

        .info-box ul {
            margin-left: 20px;
            color: #856404;
        }

        .info-box li {
            margin-bottom: 5px;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }

        .required {
            color: #C85A17;
        }

        /* TEXTAREA */
        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            resize: vertical;
            min-height: 150px;
            font-family: 'Segoe UI', sans-serif;
            transition: border-color 0.3s;
        }

        textarea:focus {
            outline: none;
            border-color: #D2691E;
        }

        /* CHAR COUNT */
        .char-count {
            text-align: right;
            color: #888;
            font-size: 13px;
            margin-top: 8px;
        }

        .char-count.valid {
            color: #28a745;
        }

        .char-count.invalid {
            color: #C85A17;
        }

        /* BUTTONS */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-submit {
            background: #C85A17;
            color: white;
        }

        .btn-submit:hover {
            background: #A0420D;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        /* ALERTS */
        .alert {
            padding: 18px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-icon {
            font-size: 24px;
        }

        /* SUCCESS STATE */
        .success-state {
            text-align: center;
            padding: 40px 20px;
        }

        .success-icon {
            font-size: 72px;
            margin-bottom: 20px;
        }

        .success-state h2 {
            color: #28a745;
            margin-bottom: 15px;
        }

        .success-state p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 24px;
            }

            .header-section .subtitle {
                font-size: 16px;
            }

            .report-form {
                padding: 25px 20px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        /* DECORATIVE ELEMENTS */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .navbar,
        .container {
            position: relative;
            z-index: 1;
        }

        /* FLOATING SHAPES */
        .shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #D2691E, #DEB887);
            top: 10%;
            left: 5%;
            animation: float 20s ease-in-out infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #CD853F, #8B4513);
            bottom: 15%;
            right: 10%;
            animation: float 15s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #F4A460, #DEB887);
            top: 60%;
            left: 15%;
            animation: float 18s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }
    </style>
</head>
<body>

<!-- FLOATING SHAPES -->
<div class="shape shape-1"></div>
<div class="shape shape-2"></div>
<div class="shape shape-3"></div>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">TravLing!</div>
    <a href="detail.php?id=<?= $place_id ?>" class="btn-back">← Kembali</a>
</div>

<!-- CONTAINER -->
<div class="container">
    <!-- HEADER -->
    <div class="header-section">
        <h1>🚩 Laporkan Tempat</h1>
        <div class="subtitle"><?= htmlspecialchars($place_name) ?></div>
    </div>

    <?php if ($success): ?>
    <!-- SUCCESS STATE -->
    <div class="report-form">
        <div class="success-state">
            <div class="success-icon">✅</div>
            <h2>Laporan Berhasil Dikirim!</h2>
            <p>
                Terima kasih telah membantu kami meningkatkan kualitas informasi.<br>
                Tim kami akan meninjau laporan Anda dalam waktu 1-2 hari kerja.
            </p>
            <div class="btn-group">
                <a href="detail.php?id=<?= $place_id ?>" class="btn btn-cancel">Kembali ke Detail</a>
                <a href="index.php" class="btn btn-submit">Ke Beranda</a>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    
    <!-- ALERTS -->
    <?php if ($error): ?>
    <div class="alert alert-error">
        <span class="alert-icon">⚠️</span>
        <div><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <!-- INFO BOX -->
    <div class="info-box">
        <strong>ℹ️ Informasi Penting:</strong>
        <ul>
            <li>Jelaskan alasan Anda melaporkan tempat ini secara detail</li>
            <li>Minimal 20 karakter diperlukan</li>
            <li>Laporan akan ditinjau oleh tim kami</li>
            <li>Gunakan bahasa yang sopan dan jelas</li>
        </ul>
    </div>

    <!-- FORM REPORT -->
    <div class="report-form">
        <h2>Formulir Laporan</h2>
        <form method="POST" id="reportForm">
            <div class="form-group">
                <label for="reason">
                    Alasan Laporan <span class="required">*</span>
                </label>
                <textarea 
                    name="reason" 
                    id="reason" 
                    placeholder="Contoh: Tempat ini sudah tidak beroperasi sejak tahun lalu, informasi jam buka tidak akurat, lokasi yang ditampilkan salah, fasilitas tidak sesuai deskripsi, dll."
                    required
                    minlength="20"
                ><?= isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : '' ?></textarea>
                <div class="char-count" id="charCountDisplay">
                    <span id="charCount">0</span> karakter (minimal 20)
                </div>
            </div>

            <div class="btn-group">
                <a href="detail.php?id=<?= $place_id ?>" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit" id="submitBtn">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>

    <?php endif; ?>
</div>

<script>
    const textarea = document.getElementById('reason');
    const charCount = document.getElementById('charCount');
    const charCountDisplay = document.getElementById('charCountDisplay');
    const submitBtn = document.getElementById('submitBtn');

    function updateCharCount() {
        const length = textarea.value.length;
        charCount.textContent = length;
        
        if (length < 20) {
            charCountDisplay.classList.remove('valid');
            charCountDisplay.classList.add('invalid');
            submitBtn.disabled = true;
        } else {
            charCountDisplay.classList.remove('invalid');
            charCountDisplay.classList.add('valid');
            submitBtn.disabled = false;
        }
    }

    textarea.addEventListener('input', updateCharCount);
    
    // Initial check
    updateCharCount();
</script>

</body>
</html>