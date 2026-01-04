<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require __DIR__ . '/config.php';

/* =====================
   INISIALISASI VARIABEL
===================== */
$error   = "";
$success = false;

/* =====================
   JIKA SUDAH LOGIN
===================== */
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

/* =====================
   PROSES REGISTER
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === "" || $email === "" || $phone === "" || $password === "") {
        $error = "Semua kolom wajib diisi.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $error = "Password minimal 8 karakter, mengandung huruf besar dan angka.";
    }
    else {
        // Cek email atau phone sudah terdaftar
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? OR phone = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email atau nomor telepon sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert ke database dengan kolom yang sesuai
            $stmt2 = mysqli_prepare(
                $conn,
                "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt2, "ssss", $name, $email, $phone, $hash);
            
            if (mysqli_stmt_execute($stmt2)) {
                $success = true;
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
            }
            mysqli_stmt_close($stmt2);
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
<title>Register - TravLing!</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #D2691E 0%, #8B4513 50%, #CD853F 100%);
    background-attachment: fixed;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

        /* DECORATIVE BACKGROUND WITH BATIK PATTERN */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(255, 140, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(139, 69, 19, 0.1) 0%, transparent 50%),
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255, 255, 255, 0.03) 35px, rgba(255, 255, 255, 0.03) 70px);
            pointer-events: none;
            z-index: 0;
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
    right: 10%;
    animation: float 20s ease-in-out infinite;
}

.shape-2 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #CD853F, #8B4513);
    bottom: 15%;
    left: 15%;
    animation: float 15s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-40px) rotate(180deg); }
}

.box {
    max-width: 420px;
    width: 100%;
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 1;
}

h2 {
    margin-top: 0;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #D2691E, #8B4513);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
    font-size: 28px;
}

.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 14px;
}

input, button {
    width: 100%;
    padding: 14px;
    margin-top: 12px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    font-size: 15px;
    box-sizing: border-box;
    transition: all 0.3s;
}

input:focus {
    outline: none;
    border-color: #D2691E;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

button {
    background: linear-gradient(135deg, #D2691E, #8B4513);
    color: #fff;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.error {
    background: linear-gradient(135deg, #ffe0e0, #ffcccb);
    color: #b30000;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-size: 14px;
    border-left: 4px solid #C85A17;
}

.success {
    text-align: center;
}

.success h3 {
    background: linear-gradient(135deg, #8B7355, #A0826D);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 15px;
    font-size: 24px;
}

.success p {
    color: #555;
    margin-bottom: 20px;
}

.link {
    text-align: center;
    display: block;
    margin-top: 20px;
    color: #D2691E;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.link:hover {
    text-decoration: underline;
}

.password-wrapper {
    position: relative;
    margin-top: 12px;
}

.password-wrapper input {
    margin-top: 0;
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #777;
    user-select: none;
}

.toggle-password:hover {
    color: #D2691E;
}
</style>
</head>
<body>

<!-- FLOATING SHAPES -->
<div class="shape shape-1"></div>
<div class="shape shape-2"></div>

<div class="box">

<?php if ($success): ?>
    <div class="success">
        <h3>✓ Registrasi Berhasil</h3>
        <p>Akun Anda berhasil dibuat. Silakan login untuk melanjutkan.</p>
        <a href="login.php">
            <button style="margin-top:20px">Login Sekarang</button>
        </a>
    </div>
<?php else: ?>

<h2>Join TravLing!</h2>
<p class="subtitle">Create account to explore Yogyakarta</p>

<?php if ($error): ?>
<div class="error">⚠ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="Nama Lengkap" required autocomplete="name">
    <input type="email" name="email" placeholder="Email" required autocomplete="email">
    <input type="tel" name="phone" placeholder="No Handphone (contoh: 081234567890)" required autocomplete="tel">

    <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password (min. 8 karakter)" required autocomplete="new-password">
        <span class="toggle-password" onclick="togglePassword()">👁</span>
    </div>

    <button type="submit">Daftar</button>
</form>

<a href="login.php" class="link">Sudah punya akun? Login di sini</a>

<?php endif; ?>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (pass.type === "password") {
        pass.type = "text";
        icon.textContent = "👁‍🗨";
    } else {
        pass.type = "password";
        icon.textContent = "👁";
    }
}
</script>

</body>
</html>