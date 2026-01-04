<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/config.php';

$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === "" || $password === "") {
        $error = "Semua kolom wajib diisi.";
    }
    elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $error = "Password minimal 8 karakter, mengandung huruf besar dan angka.";
    }
    else {
        // cek user
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            $error = "Username tidak ditemukan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET password = ? WHERE username = ?"
            );
            mysqli_stmt_bind_param($update, "ss", $hash, $username);
            mysqli_stmt_execute($update);

            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<style>
body{font-family:Segoe UI;background:#f7fcff}
.box{max-width:420px;margin:80px auto;background:#fff;padding:30px;border-radius:18px;box-shadow:0 15px 35px rgba(0,0,0,.1)}
input,button{width:100%;padding:13px;margin-top:12px;border-radius:10px;border:1px solid #ccc}
button{background:#D2691E;color:#fff;font-weight:bold;border:none}
.error{background:#ffe0e0;color:#b30000;padding:10px;border-radius:8px;margin-bottom:10px}
.success{text-align:center}
.link{text-align:center;display:block;margin-top:15px;color:#555;text-decoration:none}
</style>
</head>
<body>

<div class="box">

<?php if ($success): ?>
    <div class="success">
        <h3>Password Berhasil Diubah</h3>
        <p>Silakan login dengan password baru</p>
        <a href="login.php">
            <button style="margin-top:15px">Login</button>
        </a>
    </div>
<?php else: ?>

<h2>Reset Password</h2>

<?php if ($error): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="post">
    <input name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password Baru" required>
    <button>Reset Password</button>
</form>

<a href="login.php" class="link">Kembali ke Login</a>

<?php endif; ?>

</div>

</body>
</html>