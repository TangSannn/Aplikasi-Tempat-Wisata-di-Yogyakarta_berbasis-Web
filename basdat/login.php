<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require __DIR__ . '/config.php';

// Jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_or_phone = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email_or_phone) || empty($password)) {
        $error = "Email/Phone dan password wajib diisi";
    } else {
        // Login bisa pakai email atau phone
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE email = ? OR phone = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email_or_phone, $email_or_phone);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['username'];  // Pakai 'username', bukan 'name'
            $_SESSION['email'] = $user['email'];
    
            header("Location: index.php");
            exit;
        }else {
            $error = "Email/Phone atau password salah";
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
<title>Login - TravLing!</title>
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
    left: 10%;
    animation: float 20s ease-in-out infinite;
}

.shape-2 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #CD853F, #8B4513);
    bottom: 15%;
    right: 15%;
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
    margin-top: 20px;
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

.link {
    display: block;
    text-align: center;
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

.forgot-link {
    display: block;
    text-align: right;
    margin-top: 8px;
    color: #D2691E;
    text-decoration: none;
    font-size: 13px;
}

.forgot-link:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<!-- FLOATING SHAPES -->
<div class="shape shape-1"></div>
<div class="shape shape-2"></div>

<div class="box">
<h2>Welcome Back!</h2>
<p class="subtitle">Login to explore Yogyakarta</p>

<?php if ($error): ?>
<div class="error">⚠ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <input type="text" name="login" placeholder="Email atau No Handphone" required autocomplete="username">

    <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">
        <span class="toggle-password" onclick="togglePassword()">👁</span>
    </div>

    <a href="reset_password.php" class="forgot-link">Lupa password?</a>

    <button type="submit">Login</button>
</form>

<a href="register.php" class="link">Belum punya akun? Daftar di sini</a>
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