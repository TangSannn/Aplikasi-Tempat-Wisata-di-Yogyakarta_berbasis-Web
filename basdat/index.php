<?php
session_start();
$popular = [

    [
        "id" => "prambanan",
        "nama" => "Candi Prambanan",
        "gambar" => "assets/images/prambanan.jpg"
    ],
    [
        "id" => "Keraton",
        "nama" => "Keraton Ngayogyakarta Hadiningrat",
        "gambar" => "assets/images/Keraton.jpg"
    ],
    [
        "id" => "Heha",
        "nama" => "HeHa Sky View",
        "gambar" => "assets/images/Heha.jpg"
    ],
    [
        "id" => "Breksi",
        "nama" => "Tebing Breksi",
        "gambar" => "assets/images/Breksi.jpg"
    ],
    [
        "id" => "ratu-boko",
        "nama" => "Keraton Ratu Boko",
        "gambar" => "assets/images/bokor.jpg"
    ],
    [
        "id" => "parangtritis",
        "nama" => "Pantai Parangtritis",
        "gambar" => "assets/images/parangtritis.jpg"
    ],
    [
        "id" => "obelix-hills",
        "nama" => "Obelix Hills",
        "gambar" => "assets/images/obelix-hills.jpg"
    ],
    [
        "id" => "taman-sari",
        "nama" => "Taman Sari Yogyakarta",
        "gambar" => "assets/images/taman-sari.jpg"
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Traveling Yogyakarta</title>

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
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #D2691E, #DEB887);
            top: 10%;
            right: 5%;
            animation: float 20s ease-in-out infinite;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #CD853F, #8B4513);
            bottom: 10%;
            left: 10%;
            animation: float 15s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #F4A460, #DEB887);
            top: 50%;
            left: 5%;
            animation: float 18s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-40px) rotate(180deg);
            }
        }

        /* NAVBAR */
        .navbar {
            max-width: 1200px;
            margin: auto;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-menu a {
            margin: 0 15px;
            text-decoration: none;
            color: #444;
            font-size: 14px;
        }

        .nav-menu span {
            color: #D2691E;
            font-weight: 600;
        }

        .btn-signup {
            background: linear-gradient(135deg, #FF8C00, #D2691E);
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 138, 61, 0.3);
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 138, 61, 0.4);
        }

        /* HERO */
        .hero {
            max-width: 1200px;
            margin: auto;
            padding: 60px 20px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            align-items: center;
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        .hero-text h1 {
            font-size: 44px;
            font-weight: 700;
            line-height: 1.3;
            color: #fff;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .hero-text span {
            background: linear-gradient(135deg, #FFED4E 0%, #FFD700 50%, #FFA500 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            font-weight: 900 !important;
            -webkit-text-stroke: 0px transparent !important;
            text-shadow: none !important;
            filter: none !important;
        }

        .hero-text p {
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
            max-width: 450px;
            line-height: 1.7;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* HERO RIGHT */
        .hero-visual {
            position: relative;
            height: 420px;
        }

        .tugu-image {
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            overflow: hidden;
            right: 0;
            top: 40px;
            z-index: 2;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .tugu-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .circle-outline {
            width: 420px;
            height: 420px;
            border: 3px dashed rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            position: absolute;
            right: -40px;
            top: 0;
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .card-float {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 180px;
            z-index: 3;
            backdrop-filter: blur(10px);
        }

        .card-float h4 {
            font-size: 14px;
            color: #D2691E;
        }

        .card-float p {
            font-size: 12px;
            color: #666;
        }

        .card-1 { 
            top: 260px; 
            right: 220px;
            animation: floatCard 3s ease-in-out infinite;
        }
        
        .card-2 { 
            top: 140px; 
            right: -80px;
            animation: floatCard 3s ease-in-out infinite 1.5s;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* POPULAR DESTINATIONS */
        .popular {
            max-width: 1200px;
            margin: 40px auto 80px;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .popular h2 {
            font-size: 26px;
            margin-bottom: 6px;
            color: #fff;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .popular p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 30px;
        }

        .popular-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            max-width: 100%;
        }

        .popular-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            text-decoration: none;
            color: #000;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .popular-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .popular-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .popular-card-body {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .popular-dot {
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            border-radius: 50%;
        }

        .popular-card-body h4 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 32px;
            }

            .popular-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    <div class="nav-menu">
        <?php if (isset($_SESSION['login'])): ?>
            <span>Hi, <?= htmlspecialchars($_SESSION['name']) ?></span>
            <a href="logout.php" class="btn-signup">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn-signup">Login/Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-text">
        <h1>
            Start your journey <br>
            by one click, explore <br>
            <span>Yogyakarta</span> !
        </h1>
        <p>
            Temukan destinasi wisata terbaik di Yogyakarta dengan pengalaman yang mudah, cepat, dan menyenangkan.
        </p>
    </div>

    <div class="hero-visual">
        <div class="circle-outline"></div>

        <div class="tugu-image">
            <img src="assets/images/image.png" alt="Tugu Jogja">
        </div>

        <div class="card-float card-1">
            <h4>Tugu Jogja</h4>
            <p>Ikon kota Yogyakarta</p>
        </div>

        <div class="card-float card-2">
            <h4>Wisata Populer</h4>
            <p>Rekomendasi terbaik</p>
        </div>
    </div>
</div>

<!-- POPULAR DESTINATIONS -->
<div class="popular">
    <h2>Popular Destinations</h2>
    <p>Vacations to make your experience enjoyable in Yogyakarta!</p>

    <div class="popular-grid">
        <?php foreach ($popular as $p): ?>
            <a href="detail.php?id=<?= $p['id'] ?>" class="popular-card">
                <img src="<?= $p['gambar'] ?>" alt="<?= $p['nama'] ?>">
                <div class="popular-card-body">
                    <div class="popular-dot"></div>
                    <h4><?= $p['nama'] ?></h4>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>