<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>GameLab Indonesia — Platform Manajemen Sekolah</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            content: ["./**/*.{html,js}"],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    },
                    colors: {
                        lab: {
                            bg: "#FFFFFF",
                            soft: "#F7FAFC",
                            line: "#E7EEF3",
                            text: "#10243A",
                            dim: "#62788A",
                            blue: "#29ABE2",
                            blueDeep: "#1C7FAE",
                            navy: "#10243A",
                            yellow: "#FFC72C",
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .focus-ring:focus-visible {
            outline: 2px solid #29ABE2;
            outline-offset: 3px;
        }

        ::selection { background: #FFC72C; color: #10243A; }
    </style>
</head>

<body class="bg-white font-sans text-lab-text antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-lab-line px-6 md:px-8 py-4">
        <div class="max-w-[1140px] mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-9 w-9 object-contain" />
                <span class="font-extrabold text-lg tracking-tight">
                    <span class="text-lab-blue">GAMELAB</span> <span class="text-lab-navy">INDONESIA</span>
                </span>
            </div>

            <div class="hidden md:flex items-center gap-10 text-[15px] font-medium text-lab-dim">
                <a href="#" class="text-lab-navy">Beranda</a>
                <a href="#solusi" class="hover:text-lab-navy transition-colors">Solusi</a>
                <a href="#tentang" class="hover:text-lab-navy transition-colors">Tentang</a>
                <a href="#" class="hover:text-lab-navy transition-colors">Kontak</a>
            </div>

            <div class="flex items-center gap-3">

                @guest

                <a href="{{ route('login') }}"
                    class="focus-ring px-5 py-2.5 text-lab-navy font-medium hover:text-lab-blue transition-colors text-[15px]">

                    Login

                </a>

                <a href="{{ route('register') }}"
                    class="focus-ring px-5 py-2.5 bg-lab-navy text-white rounded-lg font-medium hover:bg-lab-blue transition-colors text-[15px]">

                    Daftar

                </a>

                @else

                @php
                $dashboard = match(auth()->user()->role) {
                'super_admin' => route('dashboard.super_admin'),
                'admin' => route('dashboard.admin'),
                'teacher' => route('dashboard.teacher'),
                'student' => route('dashboard.student'),
                default => url('/'),
                };
                @endphp

                <a href="{{ $dashboard }}"
                    class="focus-ring px-5 py-2.5 bg-lab-navy text-white rounded-lg font-medium hover:bg-lab-blue transition-colors text-[15px]">

                    Dashboard

                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="focus-ring px-5 py-2.5 text-red-500 font-medium hover:text-red-600 transition-colors text-[15px]">

                        Logout

                    </button>

                </form>

                @endguest

            </div>
        </div>
    </nav>

    <main class="pt-20">
        <!-- Hero -->
        <section class="px-6 md:px-8 py-24 md:py-32">
            <div class="max-w-[760px] mx-auto text-center space-y-7">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-lab-soft rounded-full text-sm font-medium text-lab-blueDeep">
                    300+ sekolah telah bergabung
                </div>

                <h1 class="text-5xl md:text-6xl font-extrabold leading-[1.1] tracking-tight text-lab-navy">
                    Manajemen sekolah, dibuat sederhana.
                </h1>

                <p class="text-lg md:text-xl text-lab-dim max-w-xl mx-auto leading-relaxed">
                    Satu platform untuk admin, guru, siswa, dan orang tua — terhubung, transparan, dan mudah dipakai setiap hari.
                </p>

                <div class="flex flex-wrap justify-center gap-3 pt-2">
                    <button class="focus-ring px-7 py-3.5 bg-lab-blue text-white rounded-xl font-semibold hover:bg-lab-blueDeep transition-colors">
                        Coba Gratis
                    </button>
                    <button class="focus-ring px-7 py-3.5 border border-lab-line text-lab-navy rounded-xl font-semibold hover:border-lab-blue transition-colors">
                        Lihat Demo
                    </button>
                </div>
            </div>

            <div class="max-w-[980px] mx-auto mt-16 rounded-2xl overflow-hidden border border-lab-line shadow-sm">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6jsJenLxjB-zxF6k-633Z8FmB2FfCdnlCoF0ccTAgHSQ9xY0iFcJ-58XQYzRmlG5UE9--6D3dojBlkF6qEksA7XSlZA6TqnAc0IB_k8DWEMfL6J7OJMCddj5KiTpF5B2pcAvFzBe2Qwt_Wo4sAHn9S0-MjSjbGsb0sDJqVnouumYxYlluzqTPNmPkyiMcvYs3_3m7nf7eaI_2OGq4-g4O2MQAKJLQklCcyPACxzaCPvYoomNVrMUnTqjy1VLz9j-EN8utVVzNcMw"
                    class="w-full h-[420px] object-cover" alt="GameLab Indonesia Dashboard">
            </div>
        </section>

        <!-- Stats -->
        <section class="border-y border-lab-line py-14 bg-lab-soft">
            <div class="max-w-[1140px] mx-auto px-6 md:px-8 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                <div>
                    <div class="text-4xl font-extrabold text-lab-navy">300+</div>
                    <div class="text-sm text-lab-dim mt-1">Institusi Aktif</div>
                </div>
                <div>
                    <div class="text-4xl font-extrabold text-lab-navy">50.000+</div>
                    <div class="text-sm text-lab-dim mt-1">Siswa Terdaftar</div>
                </div>
                <div>
                    <div class="text-4xl font-extrabold text-lab-navy">100%</div>
                    <div class="text-sm text-lab-dim mt-1">Terintegrasi Cloud</div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-24 px-6 md:px-8" id="solusi">
            <div class="max-w-[1140px] mx-auto">
                <div class="text-center mb-16 max-w-xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-lab-navy tracking-tight">Solusi untuk seluruh stakeholder</h2>
                    <p class="mt-3 text-lab-dim">Modul yang saling terhubung untuk transparansi dan kemudahan operasional harian.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-7 rounded-2xl border border-lab-line hover:border-lab-blue transition-colors">
                        <div class="w-11 h-11 rounded-xl bg-lab-blue/10 flex items-center justify-center text-xl mb-5">⚙️</div>
                        <h4 class="text-lg font-bold text-lab-navy mb-2">Manajemen Pusat</h4>
                        <p class="text-sm text-lab-dim leading-relaxed">Kontrol penuh data master, keuangan, dan sistem dalam satu dashboard.</p>
                    </div>

                    <div class="p-7 rounded-2xl border border-lab-line hover:border-lab-blue transition-colors">
                        <div class="w-11 h-11 rounded-xl bg-lab-yellow/15 flex items-center justify-center text-xl mb-5">🏫</div>
                        <h4 class="text-lg font-bold text-lab-navy mb-2">Efisiensi Pengajar</h4>
                        <p class="text-sm text-lab-dim leading-relaxed">Jadwal, absensi otomatis, dan input nilai yang mudah bagi guru.</p>
                    </div>

                    <div class="p-7 rounded-2xl border border-lab-line hover:border-lab-blue transition-colors">
                        <div class="w-11 h-11 rounded-xl bg-lab-blue/10 flex items-center justify-center text-xl mb-5">📖</div>
                        <h4 class="text-lg font-bold text-lab-navy mb-2">Portal Akademik</h4>
                        <p class="text-sm text-lab-dim leading-relaxed">Pantau jadwal, nilai, dan kegiatan secara real-time.</p>
                    </div>

                    <div class="p-7 rounded-2xl border border-lab-line hover:border-lab-blue transition-colors">
                        <div class="w-11 h-11 rounded-xl bg-lab-yellow/15 flex items-center justify-center text-xl mb-5">👨‍👩‍👧</div>
                        <h4 class="text-lg font-bold text-lab-navy mb-2">Monitoring Orang Tua</h4>
                        <p class="text-sm text-lab-dim leading-relaxed">Akses perkembangan anak dalam satu genggaman.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-24 px-6 md:px-8" id="tentang">
            <div class="max-w-[1140px] mx-auto bg-lab-navy rounded-3xl p-14 md:p-20 text-center">
                <div class="max-w-xl mx-auto space-y-6">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Siap modernisasi sekolah Anda?</h2>
                    <p class="text-white/70">Bergabunglah dengan ratusan sekolah yang telah beralih ke manajemen digital yang lebih cerdas.</p>
                    <button class="focus-ring px-8 py-4 bg-lab-yellow text-lab-navy rounded-xl font-bold hover:brightness-105 transition-all">
                        Daftar Sekarang
                    </button>
                    <p class="text-sm text-white/50">Konsultasi gratis · Tanpa biaya pendaftaran · Demo tersedia</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-lab-line py-16 px-6 md:px-8">
        <div class="max-w-[1140px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-7 w-7 object-contain" />
                        <span class="font-extrabold text-base">
                            <span class="text-lab-blue">GAMELAB</span> <span class="text-lab-navy">INDONESIA</span>
                        </span>
                    </div>
                    <p class="text-sm text-lab-dim leading-relaxed">Solusi terdepan untuk digitalisasi manajemen sekolah di Indonesia.</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-lab-navy mb-4">Produk</h5>
                    <ul class="space-y-3 text-sm text-lab-dim">
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Sistem Manajemen Sekolah</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Portal Orang Tua</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Dashboard Analitik</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-lab-navy mb-4">Perusahaan</h5>
                    <ul class="space-y-3 text-sm text-lab-dim">
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-lab-navy mb-4">Dukungan</h5>
                    <ul class="space-y-3 text-sm text-lab-dim">
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Kontak Kami</a></li>
                        <li><a href="#" class="hover:text-lab-navy transition-colors">Keamanan Data</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-lab-line mt-14 pt-8 text-sm text-lab-dim flex flex-col md:flex-row justify-between items-center gap-4">
                <p>© 2024 GameLab Indonesia. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-lab-navy transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-lab-navy transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>