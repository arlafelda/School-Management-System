<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Sekolah</title>

    @vite('resources/css/app.css')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        }
    </script>
</head>

<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950 text-white flex flex-col
        transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        <!-- HEADER -->
        <div class="px-6 py-6 border-b border-slate-800">
            <h1 class="text-xl font-bold">Admin Sekolah</h1>
            <p class="text-xs text-slate-400 mt-1">Sistem Manajemen Sekolah</p>
        </div>

        <!-- MENU -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

            <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 font-medium">
                🏠 Dashboard
            </a>

            <a href="{{ route('students.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                👨‍🎓 Data Siswa
            </a>

            <a href="{{ route('teacher.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                👩‍🏫 Data Guru
            </a>

            <a href="{{ route('class.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                🏫 Data Kelas
            </a>

            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                📚 Mata Pelajaran
            </a>

            <a href="{{ route('schedule.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                🗓️ Jadwal
            </a>

            <!-- 🔥 DIGANTI -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                📊 Statistik
            </a>

            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 transition">
                ⚙️ Pengaturan
            </a>

        </nav>

        <!-- FOOTER -->
        <div class="p-6 border-t border-slate-800">
            <div class="bg-slate-800 rounded-xl p-4">
                <p class="font-semibold text-sm">Admin Aktif</p>
                <p class="text-xs text-slate-400 mt-1">Kelola data sekolah</p>
            </div>
        </div>

    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col lg:ml-72 w-full">

        <!-- TOPBAR -->
        <header class="bg-white shadow-sm sticky top-0 z-40">

            <div class="flex items-center justify-between px-4 md:px-6 py-4">

                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden text-2xl">☰</button>

                    <div>
                        <h2 class="text-xl font-bold">Dashboard</h2>
                        <p class="text-xs text-slate-500">Selamat datang 👋</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">

                    <div class="hidden sm:flex items-center bg-slate-100 px-3 py-2 rounded-xl">
                        🔍
                        <input type="text"
                            class="bg-transparent outline-none ml-2 text-sm w-40"
                            placeholder="Cari data...">
                    </div>

                    <button class="bg-slate-100 hover:bg-slate-200 transition p-2 rounded-xl">
                        🔔
                    </button>

                    <div class="flex items-center gap-3 bg-slate-100 px-3 py-2 rounded-xl">

                        <div class="w-9 h-9 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 transition text-white px-3 py-1 rounded-lg text-xs">
                                Logout
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <main class="p-4 md:p-6 lg:p-8 space-y-8">

            <!-- HERO -->
            <section class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-3xl p-8 shadow-lg">
                <h1 class="text-2xl md:text-3xl font-bold">
                    Kelola Data Sekolah Lebih Mudah 🚀
                </h1>
                <p class="text-sm mt-2 text-white/80">
                    Sistem manajemen sekolah berbasis web modern
                </p>
            </section>

            <!-- STATS REAL DATA -->
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <p class="text-sm text-slate-500">Total Siswa</p>
                    <h2 class="text-2xl font-bold mt-2 text-right">
                        {{ number_format($totalStudents, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <p class="text-sm text-slate-500">Total Guru</p>
                    <h2 class="text-2xl font-bold mt-2 text-right">
                        {{ number_format($totalTeachers, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <p class="text-sm text-slate-500">Total Kelas</p>
                    <h2 class="text-2xl font-bold mt-2 text-right">
                        {{ number_format($totalClasses, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <p class="text-sm text-slate-500">Total Jadwal</p>
                    <h2 class="text-2xl font-bold mt-2 text-right">
                        {{ number_format($totalSchedules, 0, ',', '.') }}
                    </h2>
                </div>

            </section>

        </main>

    </div>

</div>

</body>
</html>