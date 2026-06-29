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

        /* ─── WAVE CANVAS ───────────────────────────────────── */
        #wave-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ─── GRID OVERLAY ──────────────────────────────────── */
        .grid-bg {
            position: fixed; inset: 0; z-index: 1;
            background-image:
                linear-gradient(rgba(79,70,229,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,70,229,0.04) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* ─── SPLASH ────────────────────────────────────────── */
        #splash {
            position: fixed; inset: 0; z-index: 100;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: #ffffff;
            transition: opacity .7s ease, transform .7s ease;
        }
        #splash.hide { opacity: 0; transform: scale(1.03); pointer-events: none; }

        .logo-ring {
            position: relative; width: 110px; height: 110px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 28px;
        }
        .logo-ring svg.spinner {
            position: absolute; inset: 0; width: 100%; height: 100%;
            animation: spin 2.5s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .logo-inner {
            width: 80px; height: 80px; border-radius: 22px;
            background: #ffffff; border: 2px solid #e5e7eb;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }

        .splash-title  { font-size: 28px; font-weight: 900; letter-spacing: -0.5px; color: #1f2937; }
        .splash-sub    { font-size: 13px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #6b7280; margin-top: 8px; }

        .progress-wrap { margin-top: 50px; width: 220px; height: 3px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .progress-bar  { height: 100%; width: 0; border-radius: 999px; background: linear-gradient(90deg, #4F46E5, #F59E0B); animation: load 2s cubic-bezier(0.4,0,0.2,1) forwards; }
        @keyframes load { to { width: 100%; } }
        .progress-label { margin-top: 12px; font-size: 12px; font-family: 'JetBrains Mono', monospace; color: #9ca3af; }

        /* ─── MAIN ──────────────────────────────────────────── */
        #main {
            position: relative; z-index: 10; min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 24px 16px;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px; border-radius: 9999px;
            background: #f0f9ff; border: 1px solid #dbeafe;
            font-size: 13px; font-weight: 600; color: #4338ca;
        }

        .brand { display: flex; align-items: center; gap: 14px; margin-bottom: 32px; }
        .brand-logo { width: 58px; height: 58px; border-radius: 16px; background: white; border: 1px solid #e5e7eb; padding: 6px; }
        .brand-text .name { font-size: 22px; font-weight: 900; color: #1f2937; }
        .brand-text .name span { color: #F59E0B; }
        .brand-text .sub { font-size: 12px; color: #6b7280; font-weight: 500; }

        .card {
            width: 100%; max-width: 440px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 10px 35px -10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header  { padding: 32px 36px 24px; text-align: center; }
        .card-header h1 { font-size: 24px; font-weight: 800; margin: 0 0 8px; color: #1f2937; }
        .card-header p  { color: #6b7280; font-size: 14px; margin: 0; }
        .card-body    { padding: 10px 36px 32px; }

        .btn-primary {
            display: block; width: 100%; padding: 15px;
            background: linear-gradient(135deg, #4F46E5, #4338ca);
            color: white; font-weight: 700; font-size: 16px;
            border-radius: 14px; text-align: center;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(79,70,229,0.3); }

        .card-footer { padding: 20px 36px; text-align: center; border-top: 1px solid #f3f4f6; font-size: 14px; color: #6b7280; }
        .card-footer a { color: #4F46E5; font-weight: 700; }

        .stats { display: flex; align-items: center; justify-content: center; gap: 32px; margin-top: 40px; }
        .stat-item { text-align: center; }
        .stat-num  { font-family: 'JetBrains Mono', monospace; font-size: 19px; font-weight: 700; color: #1f2937; }
        .stat-lbl  { font-size: 12px; color: #6b7280; margin-top: 4px; }

        @media (max-width: 480px) {
            .card-header, .card-body, .card-footer { padding-left: 24px; padding-right: 24px; }
            .stats { gap: 20px; }
        }

        /* ─── REDUCED-MOTION ────────────────────────────────── */
        @media (prefers-reduced-motion: reduce) {
            #wave-canvas { display: none; }
        }
    </style>
</head>
<body>

<!-- WAVE CANVAS -->
<canvas id="wave-canvas"></canvas>

<div class="grid-bg"></div>

<!-- SPLASH -->
<div id="splash">
    <div class="logo-ring">
        <svg class="spinner" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="65" cy="65" r="58" stroke="url(#ring-grad)" stroke-width="3" stroke-dasharray="70 300" stroke-linecap="round"/>
            <defs>
                <linearGradient id="ring-grad" x1="0" y1="0" x2="130" y2="130" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#4F46E5"/>
                    <stop offset="100%" stop-color="#F59E0B"/>
                </linearGradient>
            </defs>
        </svg>
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
            <img src="{{ asset('images/logo-gamelab.png') }}" />
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
        <div class="stat-item">
            <div class="stat-num">50rb<span>+</span></div>
            <div class="stat-lbl">Siswa tercatat</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">24<span>/7</span></div>
            <div class="stat-lbl">Akses cloud</div>
        </div>
    </div>
</div>

<script>
// ─── SPLASH LOGIC ────────────────────────────────────────────────
setTimeout(function() {
    document.getElementById('splash').classList.add('hide');
    setTimeout(function() {
        document.getElementById('splash').style.display = 'none';
        document.body.style.overflow = '';
    }, 700);
}, 2500);

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

// ─── WAVE ANIMATION ─────────────────────────────────────────────
(function() {
    var canvas = document.getElementById('wave-canvas');
    var ctx    = canvas.getContext('2d');

    function resize() {
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    // 3 ripple layers, tersebar merata sejak awal
    var ripples = [
        { t: 0.00, speed: 0.00032, amp: 36, freq: 0.018, wt: 0.0012, alpha: 0.10, sw: 210 },
        { t: 0.38, speed: 0.00024, amp: 50, freq: 0.013, wt: 0.0009, alpha: 0.07, sw: 260 },
        { t: 0.70, speed: 0.00042, amp: 24, freq: 0.024, wt: 0.0015, alpha: 0.08, sw: 160 }
    ];

    var last = null;

    function draw(ts) {
        if (!last) last = ts;
        var dt = ts - last;
        last = ts;

        var W = canvas.width;
        var H = canvas.height;
        ctx.clearRect(0, 0, W, H);

        // Vektor satuan arah diagonal TL→BR
        var diagLen = Math.sqrt(W * W + H * H);
        var dxU = W / diagLen;
        var dyU = H / diagLen;

        // Vektor tegak-lurus (⊥) terhadap diagonal
        var pxU = -dyU;
        var pyU =  dxU;

        // Proyeksi 4 pojok ke sumbu ⊥:
        //   TL (0,0)  → 0
        //   TR (W,0)  → W*pxU = -W*dyU  ← NEGATIF (pojok kanan-atas)
        //   BL (0,H)  → H*pyU =  H*dxU  ← POSITIF (pojok kiri-bawah)
        //   BR (W,H)  → 0  (simetri)
        // Kita scan dari perpMin hingga perpMax agar seluruh layar tercakup.
        var perpMin  = -W * dyU;          // pojok kanan-atas
        var perpMax  =  H * dxU;          // pojok kiri-bawah
        var perpSpan = perpMax - perpMin; // rentang scan penuh

        ripples.forEach(function(rip) {
            // Maju & wrap dalam [0, 1)
            rip.t = (rip.t + rip.speed * dt) % 1;

            // Peta t ke rentang [-sw, diagLen+sw] agar ombak masuk/keluar
            // dari luar layar secara mulus di kedua sisi
            var totalRange  = diagLen + rip.sw * 2;
            var centerDist  = rip.t * totalRange - rip.sw;

            // Jumlah langkah scan sepanjang sumbu ⊥ (mencakup perpSpan penuh)
            var STEPS = Math.ceil(perpSpan) + 4;

            // Helper: hitung titik kanvas dari posisi sepanjang ⊥ dan diagonal
            function pt(step, diagOffset, ampScale, phaseShift) {
                var along   = perpMin + step; // ← mulai dari perpMin, bukan 0
                var wOff    = rip.amp * ampScale * Math.sin(along * rip.freq + ts * rip.wt + phaseShift);
                var d       = centerDist + diagOffset + wOff;
                return { x: along * pxU + d * dxU,
                         y: along * pyU + d * dyU };
            }

            // Tepi depan gelombang
            ctx.beginPath();
            for (var s = 0; s <= STEPS; s++) {
                var p = pt(s, 0, 1, 0);
                if (s === 0) ctx.moveTo(p.x, p.y);
                else         ctx.lineTo(p.x, p.y);
            }
            // Tepi belakang (reversed) untuk menutup strip
            for (var s2 = STEPS; s2 >= 0; s2--) {
                var p2 = pt(s2, -rip.sw, 0.55, 1.5);
                ctx.lineTo(p2.x, p2.y);
            }
            ctx.closePath();

            // Gradien sepanjang arah diagonal untuk soft fade di tepi ombak
            var g1x = (centerDist - rip.sw) * dxU;
            var g1y = (centerDist - rip.sw) * dyU;
            var g2x = (centerDist + 40)     * dxU;
            var g2y = (centerDist + 40)     * dyU;
            var grad = ctx.createLinearGradient(g1x, g1y, g2x, g2y);
            grad.addColorStop(0,    'rgba(79,70,229,0)');
            grad.addColorStop(0.2,  'rgba(79,70,229,'  + (rip.alpha * 0.6) + ')');
            grad.addColorStop(0.65, 'rgba(99,102,241,' + rip.alpha         + ')');
            grad.addColorStop(0.85, 'rgba(147,197,253,'+ (rip.alpha * 0.7) + ')');
            grad.addColorStop(1,    'rgba(79,70,229,0)');
            ctx.fillStyle = grad;
            ctx.fill();

            // Garis puncak ombak
            ctx.beginPath();
            for (var sf = 0; sf <= STEPS; sf++) {
                var pf = pt(sf, 0, 1, 0);
                if (sf === 0) ctx.moveTo(pf.x, pf.y);
                else          ctx.lineTo(pf.x, pf.y);
            }
            ctx.strokeStyle = 'rgba(99,102,241,0.22)';
            ctx.lineWidth   = 1.5;
            ctx.stroke();
        });

        requestAnimationFrame(draw);
    }

    requestAnimationFrame(draw);
})();
</script>
</body>
</html>