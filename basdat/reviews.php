<?php
session_start();
require __DIR__ . '/config.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$place_id = $_GET['place_id'] ?? '';

// Ambil data tempat wisata
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

if (!isset($places[$place_id])) {
    header("Location: index.php");
    exit;
}

$place_name = $places[$place_id];

// Proses submit review
$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        $error = "Rating harus antara 1-5";
    } elseif (strlen($comment) < 10) {
        $error = "Komentar minimal 10 karakter";
    } else {
        // Cek apakah kolom comment atau body yang ada di database
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM reviews LIKE 'comment'");
        $column_name = (mysqli_num_rows($check_column) > 0) ? 'comment' : 'body';
        
        // Insert review ke database
        $stmt = mysqli_prepare($conn, 
            "INSERT INTO reviews (author_user_id, target_id, rating, body, created_at) VALUES (?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "isis", $user_id, $place_id, $rating, $comment);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $error = "Gagal menyimpan review: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil semua reviews untuk tempat ini
$stmt = mysqli_prepare($conn, 
    "SELECT r.*, u.username as user_name, 
     r.body as review_text
     FROM reviews r 
     JOIN users u ON r.author_user_id = u.id 
     WHERE r.target_id = ? 
     ORDER BY r.created_at DESC"
);
mysqli_stmt_bind_param($stmt, "s", $place_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Hitung rata-rata rating
$avg_rating = 0;
$total_reviews = count($reviews);
if ($total_reviews > 0) {
    $sum = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($sum / $total_reviews, 1);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review - <?= htmlspecialchars($place_name) ?></title>
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
            position: relative;
            overflow-x: hidden;
        }

        /* DECORATIVE BACKGROUND */
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

        /* FLOATING SHAPES */
        .shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
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

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
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
            position: relative;
            z-index: 10;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-back {
            background: linear-gradient(135deg, #FF8C00, #D2691E);
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 138, 61, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 138, 61, 0.4);
        }

        /* CONTAINER */
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* HEADER SECTION */
        .header-section {
            background: linear-gradient(135deg, rgba(210, 105, 30, 0.95), rgba(139, 69, 19, 0.95));
            color: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(139, 69, 19, 0.3);
            backdrop-filter: blur(10px);
        }

        .header-section h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .rating-summary {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }

        .avg-rating {
            font-size: 48px;
            font-weight: bold;
        }

        .rating-details {
            flex: 1;
        }

        .stars {
            color: #ffd700;
            font-size: 24px;
            letter-spacing: 2px;
        }

        /* REVIEW FORM */
        .review-form {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .review-form h2 {
            margin-bottom: 20px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        /* RATING INPUT */
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
        }

        .rating-input input[type="radio"] {
            display: none;
        }

        .rating-input label {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-input input[type="radio"]:checked ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            color: #ffd700;
        }

        /* TEXTAREA */
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            resize: vertical;
            min-height: 100px;
            font-family: 'Segoe UI', sans-serif;
        }

        textarea:focus {
            outline: none;
            border-color: #D2691E;
        }

        /* BUTTON */
        .btn-submit {
            background: linear-gradient(135deg, #D2691E, #8B4513);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(210, 105, 30, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(210, 105, 30, 0.4);
        }

        /* ALERTS */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
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

        /* REVIEWS LIST */
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .review-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s;
            backdrop-filter: blur(10px);
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .review-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .author-info h4 {
            margin-bottom: 3px;
            color: #333;
        }

        .review-date {
            font-size: 13px;
            color: #888;
        }

        .review-rating {
            color: #ffd700;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .review-comment {
            color: #555;
            line-height: 1.7;
            margin-top: 10px;
        }

        /* NO REVIEWS */
        .no-reviews {
            text-align: center;
            padding: 60px 20px;
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }

        .no-reviews svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .no-reviews h3 {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 24px;
            }

            .avg-rating {
                font-size: 36px;
            }

            .review-form, .review-card {
                padding: 20px;
            }

            .rating-input label {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<!-- FLOATING SHAPES -->
<div class="shape shape-1"></div>
<div class="shape shape-2"></div>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">TravLing!</div>
    <a href="detail.php?id=<?= $place_id ?>" class="btn-back">← Kembali</a>
</div>

<!-- CONTAINER -->
<div class="container">
    <!-- HEADER -->
    <div class="header-section">
        <h1>Review: <?= htmlspecialchars($place_name) ?></h1>
        <div class="rating-summary">
            <div class="avg-rating"><?= $avg_rating ?></div>
            <div class="rating-details">
                <div class="stars">
                    <?php 
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= round($avg_rating) ? '★' : '☆';
                    }
                    ?>
                </div>
                <div><?= $total_reviews ?> review<?= $total_reviews != 1 ? 's' : '' ?></div>
            </div>
        </div>
    </div>

    <!-- ALERTS -->
    <?php if ($success): ?>
    <div class="alert alert-success">
        ✓ Review Anda berhasil disimpan!
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-error">
        ⚠ <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- FORM REVIEW -->
    <div class="review-form">
        <h2>Tulis Review Anda</h2>
        <form method="post">
            <div class="form-group">
                <label>Rating</label>
                <div class="rating-input">
                    <input type="radio" name="rating" value="5" id="star5" required>
                    <label for="star5">★</label>
                    
                    <input type="radio" name="rating" value="4" id="star4">
                    <label for="star4">★</label>
                    
                    <input type="radio" name="rating" value="3" id="star3">
                    <label for="star3">★</label>
                    
                    <input type="radio" name="rating" value="2" id="star2">
                    <label for="star2">★</label>
                    
                    <input type="radio" name="rating" value="1" id="star1">
                    <label for="star1">★</label>
                </div>
            </div>

            <div class="form-group">
                <label>Komentar</label>
                <textarea name="comment" placeholder="Ceritakan pengalaman Anda berkunjung ke tempat ini..." required></textarea>
            </div>

            <button type="submit" name="submit_review" class="btn-submit">Kirim Review</button>
        </form>
    </div>

    <!-- DAFTAR REVIEWS -->
    <h2 style="margin-bottom: 20px; color: #fff; text-shadow: 0 3px 10px rgba(0,0,0,0.3);">Semua Review</h2>
    
    <?php if (empty($reviews)): ?>
    <!-- JIKA BELUM ADA REVIEW -->
    <div class="no-reviews">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <h3>Belum ada review</h3>
        <p>Jadilah yang pertama memberikan review untuk tempat ini!</p>
    </div>
    
    <?php else: ?>
    <!-- TAMPILKAN SEMUA REVIEW -->
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
        <div class="review-card">
            <div class="review-header">
                <div class="review-author">
                    <div class="author-avatar">
                        <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                    </div>
                    <div class="author-info">
                        <h4><?= htmlspecialchars($review['user_name']) ?></h4>
                        <div class="review-date">
                            <?= date('d M Y, H:i', strtotime($review['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <div class="review-rating">
                    <?php 
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $review['rating'] ? '★' : '☆';
                    }
                    ?>
                </div>
            </div>
            <div class="review-comment">
                <?= nl2br(htmlspecialchars($review['review_text'])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</body>
</html>