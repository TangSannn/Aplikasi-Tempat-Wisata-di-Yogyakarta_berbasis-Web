<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$data = [
    "prambanan" => [
        "nama" => "Candi Prambanan",
        "deskripsi" => "Candi Prambanan merupakan candi Hindu terbesar di Indonesia yang memiliki nilai sejarah, budaya, dan arsitektur yang sangat tinggi.",
        "maps" => "-7.752020,110.491467",
        "foto" => "assets/images/prambanan.jpg"
    ],
    "Keraton" => [
        "nama" => "Keraton Yogyakarta",
        "deskripsi" => "Keraton Yogyakarta adalah pusat kebudayaan Jawa serta kediaman resmi Sultan Yogyakarta.",
        "maps" => "-7.805284,110.364203",
        "foto" => "assets/images/Keraton.jpg"
    ],
    "Heha" => [
        "nama" => "HeHa Sky View",
        "deskripsi" => "HeHa Sky View menawarkan pengalaman wisata modern dengan panorama kota Yogyakarta dari ketinggian.",
        "maps" => "-7.8491474861217725,110.47761367168262",
        "foto" => "assets/images/Heha.jpg"
    ],
    "Breksi" => [
        "nama" => "Tebing Breksi",
        "deskripsi" => "Tebing Breksi adalah kawasan wisata alam bekas tambang yang kini dikembangkan menjadi tempat seni dan edukasi.",
        "maps" => "-7.781473,110.504553",
        "foto" => "assets/images/Breksi.jpg"
    ],
    "ratu-boko" => [
        "nama" => "Keraton Ratu Boko",
        "deskripsi" => "Keraton Ratu Boko adalah situs kompleks istana dengan pemandangan sunset yang memukau dan nilai sejarah tinggi.",
        "maps" => "-7.770120,110.489380",
        "foto" => "assets/images/bokor.jpg"
    ],
    "parangtritis" => [
        "nama" => "Pantai Parangtritis",
        "deskripsi" => "Pantai Parangtritis adalah pantai legendaris dengan pemandangan sunset indah, ombak besar, dan nuansa mistis yang memikat wisatawan.",
        "maps" => "-8.024920,110.329460",
        "foto" => "assets/images/parangtritis.jpg"
    ],
    "taman-sari" => [
        "nama" => "Taman Sari Yogyakarta",
        "deskripsi" => "Taman Sari adalah bekas taman istana Keraton Yogyakarta dengan arsitektur kolonial yang unik, kolam pemandian bersejarah, dan lorong bawah tanah yang misterius.",
        "maps" => "-7.809734317831394,110.35924818621102",
        "foto" => "assets/images/taman-sari.jpg"
    ],
    "obelix-hills" => [
        "nama" => "Obelix Hills",
        "deskripsi" => "Obelix Hills adalah destinasi wisata hits dengan nuansa Eropa yang Instagram-able, menawarkan spot foto menarik dan pemandangan perbukitan yang memesona",
        "maps" => "-7.806673674834174,110.52148830949649",
        "foto" => "assets/images/obelix-hills.jpg"
    ]
];

$id = $_GET['id'] ?? '';

if (!isset($data[$id])) {
    echo "Data tidak ditemukan";
    exit;
}

$wisata = $data[$id];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $wisata['nama'] ?></title>

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
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #D2691E, #DEB887);
            top: 15%;
            left: 5%;
            animation: float 20s ease-in-out infinite;
        }

        .shape-2 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #CD853F, #8B4513);
            bottom: 20%;
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
            text-shadow: 0 2px 10px rgba(210, 105, 30, 0.3);
            letter-spacing: 1px;
        }

        .btn-back-top {
            background: linear-gradient(135deg, #FF8C00, #D2691E);
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 138, 61, 0.3);
        }

        .btn-back-top:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 138, 61, 0.4);
        }

        /* HERO DETAIL */
        .hero-detail {
            max-width: 1200px;
            margin: 30px auto 40px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-text {
            background: rgba(255, 255, 255, 0.95);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .hero-text h1 {
            font-size: 38px;
            line-height: 1.3;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        /* INFO CARD */
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            padding: 25px;
            border: 3px solid rgba(210, 105, 30, 0.3);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }

        .info-card h3 {
            margin-bottom: 15px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            margin-bottom: 10px;
            padding-left: 18px;
            position: relative;
            color: #444;
        }

        .info-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #D2691E;
            font-weight: bold;
        }

        /* CTA */
        .cta {
            max-width: 1200px;
            margin: 40px auto 40px;
            padding: 30px 20px;
            background: linear-gradient(135deg, rgba(210, 105, 30, 0.95), rgba(139, 69, 19, 0.95));
            color: white;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: 0 15px 40px rgba(139, 69, 19, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .cta h2 {
            font-size: 26px;
        }

        .cta a {
            background: linear-gradient(135deg, #FF8C00, #D2691E);
            color: #fff;
            padding: 12px 26px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 138, 61, 0.3);
        }

        .cta a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 138, 61, 0.4);
        }

        /* ACTION BUTTONS */
        .action-buttons {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            text-decoration: none;
            color: #222;
            transition: all 0.3s;
            border: 2px solid transparent;
            backdrop-filter: blur(10px);
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .action-card.review {
            border-color: rgba(210, 105, 30, 0.3);
        }

        .action-card.review:hover {
            border-color: #D2691E;
            background: rgba(210, 105, 30, 0.05);
        }

        .action-card.report {
            border-color: rgba(220, 53, 69, 0.3);
        }

        .action-card.report:hover {
            border-color: #C85A17;
            background: rgba(220, 53, 69, 0.05);
        }

        .action-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .action-card h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .action-card p {
            color: #666;
            line-height: 1.6;
        }

        /* MAPS */
        .maps-box {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }

        .maps-box h3 {
            margin-bottom: 15px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .maps-frame {
            width: 100%;
            height: 350px;
            border: none;
            border-radius: 14px;
        }

        /* FOTO WISATA */
        .photo-box {
            width: 100%;
            height: 320px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0,0,0,0.25);
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero-detail {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 30px;
            }

            .cta {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                grid-template-columns: 1fr;
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
    <a href="index.php" class="btn-back-top">← Kembali</a>
</div>

<!-- HERO DETAIL -->
<div class="hero-detail">
    <div class="hero-text">
        <h1><?= $wisata['nama'] ?></h1>
        <p><?= $wisata['deskripsi'] ?></p>
    </div>

    <!-- FOTO -->
    <div class="photo-box">
        <img src="<?= $wisata['foto'] ?>" alt="<?= $wisata['nama'] ?>">
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 1;">
    <div class="info-card">
        <!-- MAPS -->
        <div class="maps-box">
            <h3>Lokasi Wisata</h3>
            <iframe
                class="maps-frame"
                src="https://www.google.com/maps?q=<?= $wisata['maps'] ?>&hl=id&z=15&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <h3 style="margin-top: 30px;">Informasi Singkat</h3>
        <ul class="info-list">
            <li>Lokasi: Yogyakarta</li>
            <li>Kategori: Wisata Populer</li>
            <li>Akses: Mudah dijangkau</li>
            <li>Cocok untuk: Keluarga & Wisata Edukasi</li>
        </ul>
    </div>
</div>

<!-- CTA -->
<div class="cta">
    <h2>Siap menjelajah <?= $wisata['nama'] ?>?</h2>
    <a href="index.php">Lihat Destinasi Lain</a>
</div>

<!-- ACTION BUTTONS -->
<div class="action-buttons">
    <a href="reviews.php?place_id=<?= $id ?>" class="action-card review">
        <div class="action-icon">⭐</div>
        <h3>Tulis Review</h3>
        <p>Bagikan pengalaman Anda mengunjungi tempat ini dan bantu wisatawan lain membuat keputusan</p>
    </a>

    <a href="report.php?place_id=<?= $id ?>" class="action-card report">
        <div class="action-icon">🚩</div>
        <h3>Laporkan Masalah</h3>
        <p>Temukan informasi yang tidak sesuai? Laporkan kepada kami untuk perbaikan</p>
    </a>
</div>

</body>
</html>