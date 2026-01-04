<?php
session_start();

// Hapus semua data session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Redirect ke halaman index
header("Location: index.php");
exit;