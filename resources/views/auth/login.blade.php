<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameLab Indonesia</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- HAPUS CDN TAILWIND --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        body {
            font-family: 'Manrope', sans-serif;
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(120deg, #ffffff 0%, #f4fbfe 35%, #ffffff 70%, #f7fcff 100%);
            background-size: 200% 200%;
            animation: gradientShift 14s ease infinite;
        }

        /* Decorative floating blobs behind the content */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }
        .bg-blob-1 {
            width: 320px;
            height: 320px;
            top: -80px;
            left: -100px;
            background: radial-gradient(circle, #29ABE2, transparent 70%);
            animation: floatBlob1 12s ease-in-out infinite;
        }
        .bg-blob-2 {
            width: 280px;
            height: 280px;
            bottom: -60px;
            right: -90px;
            background: radial-gradient(circle, #7fd4f5, transparent 70%);
            animation: floatBlob2 16s ease-in-out infinite;
        }
        .bg-blob-3 {
            width: 180px;
            height: 180px;
            top: 45%;
            right: 8%;
            background: radial-gradient(circle, #cdeefc, transparent 70%);
            animation: floatBlob1 20s ease-in-out infinite reverse;
            opacity: 0.25;
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* ===== Wave animation (canvas, diagonal ripples) ===== */
        #wave-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ===== Keyframes ===== */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.92);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(41, 171, 226, 0.0); }
            50% { box-shadow: 0 0 0 6px rgba(41, 171, 226, 0.08); }
        }

        @keyframes floatBlob1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -40px) scale(1.08); }
            66%      { transform: translate(-20px, 20px) scale(0.95); }
        }

        @keyframes floatBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(-35px, 25px) scale(0.92); }
            66%      { transform: translate(25px, -25px) scale(1.06); }
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes checkPop {
            from { transform: scale(0) rotate(-10deg); opacity: 0; }
            to   { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        @keyframes underlineGrow {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }

        @keyframes titleGradient {
            0%   { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        /* ===== Page entrance ===== */
        .anim-back {
            opacity: 0;
            animation: fadeSlideUp 0.5s ease-out forwards;
            animation-delay: 0.05s;
        }

        .anim-header {
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out forwards;
            animation-delay: 0.15s;
        }

        .anim-logo {
            opacity: 0;
            animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            animation-delay: 0.1s;
        }

        .anim-card {
            opacity: 0;
            animation: fadeSlideUp 0.6s ease-out forwards;
            animation-delay: 0.25s;
        }

        .anim-footer {
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
            animation-delay: 0.45s;
        }

        /* ===== Interactive elements ===== */
        .back-link svg {
            transition: transform 0.2s ease;
        }
        .back-link:hover svg {
            transform: translateX(-3px);
        }

        .logo-img {
            transition: transform 0.3s ease;
        }
        .logo-img:hover {
            transform: scale(1.06) rotate(-2deg);
        }

        .input-field {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        }
        .input-field:focus {
            transform: translateY(-1px);
        }

        .field-error {
            animation: fadeSlideUp 0.25s ease-out forwards;
        }

        .input-field.has-error {
            animation: shake 0.4s ease-in-out;
            border-color: #f87171 !important;
        }

        .login-btn {
            position: relative;
            overflow: hidden;
            transition: background-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }
        .login-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(41, 171, 226, 0.3);
        }
        .login-btn:active:not(:disabled) {
            transform: translateY(0);
        }
        .login-btn:disabled {
            opacity: 0.85;
            cursor: not-allowed;
            animation: pulseGlow 1.4s ease-in-out infinite;
        }

        /* Ripple effect on click */
        .login-btn .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.45);
            transform: scale(0);
            animation: rippleAnim 0.6s ease-out;
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to {
                transform: scale(3.2);
                opacity: 0;
            }
        }

        /* Button content swap (text <-> spinner) */
        .btn-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity 0.15s ease;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            border-radius: 50%;
            display: inline-block;
            animation: spin 0.7s linear infinite;
        }

        /* Alert messages */
        .alert-box-enter {
            animation: fadeSlideUp 0.3s ease-out forwards;
        }

        .register-link {
            transition: color 0.2s ease;
        }

        a.text-\[\#29ABE2\], .register-link a {
            position: relative;
        }

        /* Checkbox + forgot password link subtle hover lift */
        label.flex {
            transition: color 0.2s ease;
        }

        /* Animated gradient on brand title */
        .brand-title {
            background: linear-gradient(90deg, #29ABE2, #6dd0f0, #29ABE2);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleGradient 4s linear infinite;
        }

        /* Card hover glow */
        .anim-card {
            transition: box-shadow 0.35s ease, border-color 0.35s ease, transform 0.35s ease;
        }
        .anim-card:hover {
            box-shadow: 0 12px 32px rgba(41, 171, 226, 0.12);
            border-color: #cdeefc;
            transform: translateY(-2px);
        }

        /* Underline grow effect for text links */
        .link-underline {
            position: relative;
            text-decoration: none !important;
        }
        .link-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 1.5px;
            background: currentColor;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }
        .link-underline:hover::after {
            transform: scaleX(1);
        }

        /* Custom animated checkbox */
        .custom-checkbox {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border: 1.5px solid #C9D8E0;
            border-radius: 5px;
            transition: border-color 0.2s ease, background-color 0.2s ease, transform 0.15s ease;
            cursor: pointer;
            flex-shrink: 0;
        }
        .custom-checkbox:hover {
            border-color: #29ABE2;
            transform: scale(1.08);
        }
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        .custom-checkbox svg {
            width: 12px;
            height: 12px;
            opacity: 0;
            transform: scale(0);
            color: #fff;
        }
        .custom-checkbox input:checked ~ .check-bg {
            background-color: #29ABE2;
            border-color: #29ABE2;
        }
        .custom-checkbox input:checked ~ svg {
            opacity: 1;
            animation: checkPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .check-bg {
            position: absolute;
            inset: 0;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        /* Remember-me label hover */
        .remember-label {
            cursor: pointer;
            transition: color 0.2s ease;
        }
        .remember-label:hover {
            color: #10243A;
        }

        /* Subtle continuous glow pulse on the primary button (idle, very soft) */
        .login-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            opacity: 0;
            transition: opacity 0.3s ease;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.18), transparent);
        }
        .login-btn:hover::before {
            opacity: 1;
        }

        /* Respect users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .anim-back, .anim-header, .anim-logo, .anim-card, .anim-footer,
            .input-field.has-error, .login-btn:disabled,
            body, .bg-blob-1, .bg-blob-2, .bg-blob-3, .brand-title {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
            #wave-canvas { display: none; }
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center p-6">

    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    <div class="bg-blob bg-blob-3"></div>

    <canvas id="wave-canvas"></canvas>

    <div class="w-full max-w-md page-content">

        <a href="{{ url('/') }}"
           class="back-link anim-back inline-flex items-center gap-1.5 text-sm font-medium text-[#62788A] hover:text-[#10243A] transition-colors mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>

        <div class="anim-header text-center mb-10">
            <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="logo-img anim-logo h-14 w-14 mx-auto object-contain mb-4">

            <h1 class="text-2xl font-extrabold tracking-tight">
                <span class="brand-title">GAMELAB</span> <span class="text-[#10243A]">INDONESIA</span>
            </h1>

            <h2 class="text-lg font-bold mt-2 text-[#10243A]">
                Selamat Datang Kembali
            </h2>

            <p class="text-sm text-[#62788A] mt-2">
                Silakan masuk ke akun Anda
            </p>
        </div>

        <div class="anim-card bg-white p-8 rounded-2xl border border-[#E7EEF3] shadow-sm">

            <div id="alertBox"></div>

            @if(session('status'))
                <div class="alert-box-enter mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form id="loginForm"
                  method="POST"
                  action="{{ route('login') }}"
                  class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="emailField"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="Masukkan email"
                        class="input-field w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('email')
                        <p class="field-error text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Masukkan password"
                        class="input-field w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('password')
                        <p class="field-error text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="remember-label flex items-center gap-2 text-[#10243A]">
                        <span class="custom-checkbox">
                            <input
                                type="checkbox"
                                name="remember"
                            >
                            <span class="check-bg"></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position:relative;z-index:1;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        Ingat saya
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="link-underline text-[#29ABE2] font-semibold">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button id="loginBtn"
                        type="submit"
                        class="login-btn w-full bg-[#29ABE2] text-white py-3 rounded-lg font-semibold hover:bg-[#1C7FAE] transition-colors">
                    <span class="btn-label" id="btnLabel">Masuk</span>
                </button>
            </form>

        </div>

        @if(Route::has('register'))
            <p class="anim-footer register-link text-center text-sm text-[#62788A] mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="link-underline text-[#29ABE2] font-semibold">
                    Daftar
                </a>
            </p>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {

            setTimeout(() => {
                $('#emailField').focus();
            }, 100);

            // Ripple effect on button click
            $('#loginBtn').on('click', function (e) {
                let btn = $(this);
                let ripple = $('<span class="ripple"></span>');
                let offset = btn.offset();
                let size = Math.max(btn.outerWidth(), btn.outerHeight());

                ripple.css({
                    width: size,
                    height: size,
                    left: e.pageX - offset.left - size / 2,
                    top: e.pageY - offset.top - size / 2
                });

                btn.append(ripple);
                setTimeout(() => ripple.remove(), 600);
            });

            $('#loginForm').submit(function(e) {
                e.preventDefault();

                let btn = $('#loginBtn');
                let label = $('#btnLabel');

                btn.prop('disabled', true);
                label.css('opacity', 0);

                setTimeout(() => {
                    label.html('<span class="spinner"></span> Memuat...').css('opacity', 1);
                }, 150);

                $('#alertBox').html('');

                $.ajax({
                    url: "{{ route('login') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        email: $('input[name=email]').val(),
                        password: $('input[name=password]').val(),
                        remember: $('input[name=remember]').is(':checked') ? 1 : 0
                    },

                    success: function(res) {
                        label.css('opacity', 0);
                        setTimeout(() => {
                            label.text('Berhasil!').css('opacity', 1);
                        }, 150);

                        $('#alertBox').html(`
                            <div class="alert-box-enter mb-4 text-sm text-green-600">
                                Login berhasil, mengalihkan...
                            </div>
                        `);

                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 1000);
                    },

                    error: function(xhr) {
                        let message = "Terjadi kesalahan server!";

                        if (xhr.responseJSON) {
                            message = xhr.responseJSON.message;
                        }

                        $('#alertBox').html(`
                            <div class="alert-box-enter mb-4 bg-red-100 text-red-700 p-3 rounded">
                                ${message}
                            </div>
                        `);

                        // Shake the form fields to draw attention
                        $('.input-field').addClass('has-error');
                        setTimeout(() => $('.input-field').removeClass('has-error'), 450);

                        label.css('opacity', 0);
                        setTimeout(() => {
                            label.text('Masuk').css('opacity', 1);
                        }, 150);
                        btn.prop('disabled', false);
                    }
                });
            });

        });

        // ─── WAVE ANIMATION (canvas, diagonal ripples) ──────────────────
        (function() {
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            var canvas = document.getElementById('wave-canvas');
            var ctx    = canvas.getContext('2d');

            function resize() {
                canvas.width  = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resize();
            window.addEventListener('resize', resize);

            // 3 lapisan ripple, tersebar merata sejak awal
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

                // Proyeksi 4 pojok ke sumbu ⊥, agar scan mencakup seluruh layar
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
                        var along   = perpMin + step; // mulai dari perpMin
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
                    grad.addColorStop(0,    'rgba(41,171,226,0)');
                    grad.addColorStop(0.2,  'rgba(41,171,226,'  + (rip.alpha * 0.6) + ')');
                    grad.addColorStop(0.65, 'rgba(73,193,240,' + rip.alpha         + ')');
                    grad.addColorStop(0.85, 'rgba(168,222,251,'+ (rip.alpha * 0.7) + ')');
                    grad.addColorStop(1,    'rgba(41,171,226,0)');
                    ctx.fillStyle = grad;
                    ctx.fill();

                    // Garis puncak ombak
                    ctx.beginPath();
                    for (var sf = 0; sf <= STEPS; sf++) {
                        var pf = pt(sf, 0, 1, 0);
                        if (sf === 0) ctx.moveTo(pf.x, pf.y);
                        else          ctx.lineTo(pf.x, pf.y);
                    }
                    ctx.strokeStyle = 'rgba(73,193,240,0.22)';
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