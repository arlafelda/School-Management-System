<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Masuk — GameLab Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; 
            font-family: 'Manrope', sans-serif;
            background: #ffffff; 
            color: #1f2937; 
            min-height: 100vh; 
            overflow: hidden;
        }

        .grid-bg {
            position: fixed; inset: 0; z-index: 0;
            background-image: 
                linear-gradient(rgba(79,70,229,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,70,229,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .orb { 
            position: fixed; 
            border-radius: 50%; 
            filter: blur(90px); 
            pointer-events: none; 
            z-index: 0; 
            opacity: 0.07;
        }
        .orb-1 { width: 550px; height: 550px; background: #6366F1; top: -180px; left: -120px; }
        .orb-2 { width: 480px; height: 480px; background: #818cf8; bottom: -140px; right: -80px; }
        .orb-3 { width: 320px; height: 320px; background: #fbbf24; top: 45%; left: 55%; }

        /* Splash Screen */
        #splash {
            position: fixed; inset: 0; z-index: 100;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: #ffffff; 
            transition: opacity .7s ease, transform .7s ease;
        }
        #splash.hide { opacity: 0; transform: scale(1.03); pointer-events: none; }

        .logo-ring {
            position: relative; width: 110px; height: 110px;
            display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
        }
        .logo-ring svg.spinner {
            position: absolute; inset: 0; width: 100%; height: 100%;
            animation: spin 2.5s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .logo-inner {
            width: 80px; height: 80px; border-radius: 22px;
            background: #ffffff; 
            border: 2px solid #e5e7eb;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }

        .splash-title {
            font-size: 28px; font-weight: 900; letter-spacing: -0.5px;
            color: #1f2937;
        }
        .splash-sub {
            font-size: 13px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
            color: #6b7280; margin-top: 8px;
        }

        .progress-wrap {
            margin-top: 50px; width: 220px; height: 3px;
            background: #e5e7eb; border-radius: 999px; overflow: hidden;
        }
        .progress-bar {
            height: 100%; width: 0; border-radius: 999px;
            background: linear-gradient(90deg, #4F46E5, #F59E0B);
            animation: load 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes load { to { width: 100%; } }

        .progress-label {
            margin-top: 12px; font-size: 12px; font-family: 'JetBrains Mono', monospace;
            color: #9ca3af;
        }

        /* Main Content */
        #main {
            position: relative; z-index: 10; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 24px 16px;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px; border-radius: 9999px;
            background: #f0f9ff; border: 1px solid #dbeafe;
            font-size: 13px; font-weight: 600; color: #4338ca;
        }

        .brand { 
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px; 
        }
        .brand-logo {
            width: 58px; height: 58px; border-radius: 16px;
            background: white; border: 1px solid #e5e7eb;
            padding: 6px;
        }
        .brand-text .name { 
            font-size: 22px; font-weight: 900; color: #1f2937;
        }
        .brand-text .name span { color: #F59E0B; }
        .brand-text .sub { 
            font-size: 12px; color: #6b7280; font-weight: 500; 
        }

        .card {
            width: 100%; max-width: 440px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 10px 35px -10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header { 
            padding: 32px 36px 24px; 
            text-align: center;
        }
        .card-header h1 { 
            font-size: 24px; font-weight: 800; margin: 0 0 8px; color: #1f2937;
        }
        .card-header p { 
            color: #6b7280; font-size: 14px; margin: 0;
        }

        .card-body { padding: 10px 36px 32px; }

        .btn-primary {
            display: block; width: 100%; padding: 15px;
            background: linear-gradient(135deg, #4F46E5, #4338ca);
            color: white; font-weight: 700; font-size: 16px;
            border-radius: 14px; text-align: center;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .card-footer {
            padding: 20px 36px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
            font-size: 14px; color: #6b7280;
        }
        .card-footer a {
            color: #4F46E5; font-weight: 700;
        }

        .stats {
            display: flex; align-items: center; justify-content: center;
            gap: 32px; margin-top: 40px;
        }
        .stat-item { text-align: center; }
        .stat-num {
            font-family: 'JetBrains Mono', monospace;
            font-size: 19px; font-weight: 700; color: #1f2937;
        }
        .stat-lbl {
            font-size: 12px; color: #6b7280; margin-top: 4px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .card-header, .card-body, .card-footer { padding-left: 24px; padding-right: 24px; }
            .stats { gap: 20px; }
        }
    </style>
</head>
<body>

<div class="grid-bg"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- SPLASH -->
<div id="splash">
    <div class="logo-ring">
        <!-- Spinner -->
        <svg class="spinner" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="65" cy="65" r="58" stroke="url(#ring-grad)" stroke-width="3" stroke-dasharray="70 300" stroke-linecap="round"/>
            <defs>
                <linearGradient id="ring-grad" x1="0" y1="0" x2="130" y2="130" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#4F46E5"/>
                    <stop offset="100%" stop-color="#F59E0B"/>
                </linearGradient>
            </defs>
        </svg>
        
        <!-- Logo dari file -->
        <div class="logo-inner">
            <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" />
        </div>
    </div>
    
    <div class="splash-title">
        <span style="color:#1f2937">GAMELAB</span>
        <span style="color:#F59E0B"> INDONESIA</span>
    </div>
    <div class="splash-sub">Sistem Manajemen Sekolah</div>
    <div class="progress-wrap"><div class="progress-bar"></div></div>
    <div class="progress-label">Memuat sistem…</div>
</div>
<!-- MAIN -->
<div id="main">
    <div class="badge"><span class="badge-dot"></span> Dipakai 300+ sekolah di Indonesia</div>

    <div class="brand">
        <div class="brand-logo">
            <img src="data:image/png;base64,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" alt="GameLab Indonesia" />
        </div>
        <div class="brand-text">
            <div class="name">GAMELAB <span>INDONESIA</span></div>
            <div class="sub">School Management System</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1>Selamat Datang!</h1>
            <p>Akses dashboard sekolah Anda dengan mudah dan aman.</p>
        </div>
        <div class="card-body">
            <a href="{{ route('login') }}" class="btn-primary">Login →</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-num">300<span>+</span></div>
            <div class="stat-lbl">Sekolah aktif</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat-item">
            <div class="stat-num">50rb<span>+</span></div>
            <div class="stat-lbl">Siswa tercatat</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat-item">
            <div class="stat-num">24<span>/7</span></div>
            <div class="stat-lbl">Akses cloud</div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        document.getElementById('splash').classList.add('hide');
        setTimeout(function() {
            document.getElementById('splash').style.display = 'none';
            document.body.style.overflow = '';
        }, 700);
    }, 2500);
    setTimeout(function() {
        document.getElementById('main').classList.add('show');
    }, 2600);
    var labels = ['Memuat sistem…', 'Menyiapkan data sekolah…', 'Hampir siap…'];
    var i = 0;
    var el = document.querySelector('.progress-label');
    setInterval(function() {
        i = (i + 1) % labels.length;
        el.style.opacity = 0;
        setTimeout(function() {
            el.textContent = labels[i];
            el.style.transition = 'opacity .3s';
            el.style.opacity = 1;
        }, 200);
    }, 700);
</script>
</body>
</html>