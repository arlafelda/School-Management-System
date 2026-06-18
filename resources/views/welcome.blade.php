<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Academy Ledger - Transformasi Digital Pendidikan</title>

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
                        primary: "#2a445f",
                        "primary-container": "#425c78",
                        "on-primary": "#ffffff",
                        "on-surface": "#111c2d",
                        "on-surface-variant": "#43474d",
                        "surface-container-low": "#f0f3ff",
                        "surface-container": "#e7eeff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#dee8ff",
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }
    </style>
</head>

<body class="bg-[#f9f9ff] font-sans text-[#111c2d]">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 px-8 py-5">
        <div class="max-w-[1280px] mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl text-primary">school</span>
                <span class="font-bold text-2xl tracking-tight text-primary">Academy Ledger</span>
            </div>

            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#" class="text-primary font-semibold border-b-2 border-primary pb-1">Fitur</a>
                <a href="#" class="hover:text-primary transition-colors">Solusi</a>
                <a href="#" class="hover:text-primary transition-colors">Tentang</a>
                <a href="#" class="hover:text-primary transition-colors">Kontak</a>
            </div>

            <div class="flex items-center gap-4">

                @guest

                <a href="{{ route('login') }}"
                    class="px-6 py-2.5 border border-primary text-primary rounded-xl hover:bg-gray-50 transition-all">

                    Login

                </a>

                <a href="{{ route('register') }}"
                    class="px-6 py-2.5 bg-primary text-white rounded-xl hover:bg-primary/90 transition-all">

                    Register

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
                    class="px-6 py-2.5 bg-primary text-white rounded-xl hover:bg-primary/90 transition-all">

                    Dashboard

                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all">

                        Logout

                    </button>

                </form>

                @endguest

            </div>
        </div>
    </nav>

    <main class="pt-20">
        <!-- Hero -->
        <section class="relative overflow-hidden py-24 px-8">
            <div class="absolute top-0 -right-40 w-[500px] h-[500px] bg-blue-100/60 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-40 w-[400px] h-[400px] bg-indigo-100/60 rounded-full blur-3xl"></div>

            <div class="max-w-[1280px] mx-auto grid md:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full border text-sm font-medium text-primary">
                        <span class="material-symbols-outlined">verified</span>
                        Platform Manajemen Sekolah #1 di Indonesia
                    </div>

                    <h1 class="text-5xl md:text-6xl font-bold leading-tight tracking-tighter">
                        Transformasi Digital <span class="text-primary-container">Ekosistem Pendidikan</span> Anda
                    </h1>

                    <p class="text-xl text-gray-600 max-w-lg">
                        Sistem manajemen sekolah terpadu untuk efisiensi, transparansi, dan kolaborasi antara admin, guru, siswa, dan orang tua.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <button class="px-8 py-4 bg-primary text-white rounded-2xl font-semibold hover:shadow-xl transition-all">Pelajari Selengkapnya</button>
                        <button class="px-8 py-4 border border-gray-300 rounded-2xl font-medium flex items-center gap-2 hover:bg-gray-50 transition-all">
                            <span class="material-symbols-outlined">play_circle</span>
                            Lihat Demo
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-primary-container/10 rounded-3xl rotate-3 scale-105"></div>
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6jsJenLxjB-zxF6k-633Z8FmB2FfCdnlCoF0ccTAgHSQ9xY0iFcJ-58XQYzRmlG5UE9--6D3dojBlkF6qEksA7XSlZA6TqnAc0IB_k8DWEMfL6J7OJMCddj5KiTpF5B2pcAvFzBe2Qwt_Wo4sAHn9S0-MjSjbGsb0sDJqVnouumYxYlluzqTPNmPkyiMcvYs3_3m7nf7eaI_2OGq4-g4O2MQAKJLQklCcyPACxzaCPvYoomNVrMUnTqjy1VLz9j-EN8utVVzNcMw"
                        class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover" alt="Education Dashboard">
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-primary py-16 text-white">
            <div class="max-w-[1280px] mx-auto px-8 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div>
                    <div class="text-5xl font-bold">300+</div>
                    <div class="uppercase tracking-widest text-sm mt-2 opacity-75">Institusi Aktif</div>
                </div>
                <div>
                    <div class="text-5xl font-bold">50.000+</div>
                    <div class="uppercase tracking-widest text-sm mt-2 opacity-75">Siswa Terdaftar</div>
                </div>
                <div>
                    <div class="text-5xl font-bold">100%</div>
                    <div class="uppercase tracking-widest text-sm mt-2 opacity-75">Terintegrasi Cloud</div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-24 px-8 bg-[#f0f3ff]" id="features">
            <div class="max-w-[1280px] mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold">Solusi Terpadu Untuk Seluruh Stakeholder</h2>
                    <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                        Modul yang saling terhubung untuk transparansi dan kemudahan operasional harian.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white p-8 rounded-3xl border hover:border-primary hover:-translate-y-2 transition-all group">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-4xl mb-6 group-hover:bg-primary group-hover:text-white transition-all">⚙️</div>
                        <h4 class="text-2xl font-semibold mb-3">Manajemen Pusat</h4>
                        <p class="text-gray-600">Kontrol penuh data master, keuangan, dan sistem dalam satu dashboard.</p>
                        <a href="#" class="mt-6 inline-flex items-center gap-2 text-primary font-medium hover:gap-3 transition-all">
                            Selengkapnya <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </a>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border hover:border-primary hover:-translate-y-2 transition-all group">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-4xl mb-6 group-hover:bg-primary group-hover:text-white transition-all">🏫</div>
                        <h4 class="text-2xl font-semibold mb-3">Efisiensi Pengajar</h4>
                        <p class="text-gray-600">Jadwal, absensi otomatis, dan input nilai yang mudah bagi guru.</p>
                        <a href="#" class="mt-6 inline-flex items-center gap-2 text-primary font-medium hover:gap-3 transition-all">
                            Selengkapnya <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </a>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border hover:border-primary hover:-translate-y-2 transition-all group">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-4xl mb-6 group-hover:bg-primary group-hover:text-white transition-all">📖</div>
                        <h4 class="text-2xl font-semibold mb-3">Portal Akademik</h4>
                        <p class="text-gray-600">Pantau jadwal, nilai, dan kegiatan secara real-time.</p>
                        <a href="#" class="mt-6 inline-flex items-center gap-2 text-primary font-medium hover:gap-3 transition-all">
                            Selengkapnya <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </a>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border hover:border-primary hover:-translate-y-2 transition-all group">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-4xl mb-6 group-hover:bg-primary group-hover:text-white transition-all">👨‍👩‍👧</div>
                        <h4 class="text-2xl font-semibold mb-3">Monitoring Orang Tua</h4>
                        <p class="text-gray-600">Akses perkembangan anak dalam satu genggaman.</p>
                        <a href="#" class="mt-6 inline-flex items-center gap-2 text-primary font-medium hover:gap-3 transition-all">
                            Selengkapnya <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Big CTA -->
        <section class="py-24 px-8">
            <div class="max-w-[1280px] mx-auto bg-primary rounded-[40px] p-16 text-center text-white relative overflow-hidden">
                <div class="relative z-10 max-w-2xl mx-auto space-y-8">
                    <h2 class="text-5xl font-bold">Siap Modernisasi Sekolah Anda?</h2>
                    <p class="text-xl opacity-90">Bergabunglah dengan ratusan sekolah yang telah beralih ke manajemen digital yang lebih cerdas.</p>
                    <button class="px-12 py-5 bg-white text-primary rounded-2xl font-bold text-xl hover:scale-105 transition-transform">Daftar Sekarang</button>
                    <p class="text-sm opacity-70">Konsultasi Gratis • Tanpa Biaya Pendaftaran • Demo Tersedia</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-20 px-8">
        <div class="max-w-[1280px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <span class="font-bold text-2xl">Academy Ledger</span>
                    <p class="mt-4 text-white/70">Solusi terdepan untuk digitalisasi manajemen sekolah di Indonesia.</p>
                </div>
                <div>
                    <h5 class="font-semibold uppercase mb-4">Produk</h5>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-white">Sistem Manajemen Sekolah</a></li>
                        <li><a href="#" class="hover:text-white">Portal Orang Tua</a></li>
                        <li><a href="#" class="hover:text-white">Dashboard Analitik</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold uppercase mb-4">Perusahaan</h5>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white">Karir</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold uppercase mb-4">Dukungan</h5>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-white">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white">Kontak Kami</a></li>
                        <li><a href="#" class="hover:text-white">Keamanan Data</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-16 pt-8 text-sm text-white/60 flex flex-col md:flex-row justify-between items-center gap-4">
                <p>© 2024 Academy Ledger. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>