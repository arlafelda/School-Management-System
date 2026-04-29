<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Guru</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter'],
                        heading: ['Manrope']
                    }
                }
            }
        }
    </script>

</head>

<body class="bg-slate-50 font-sans text-slate-800">

    <!-- MOBILE TOPBAR -->
    <div class="md:hidden flex items-center justify-between p-4 bg-white shadow">
        <button onclick="toggleSidebar()">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="font-bold">Sekolah</h1>
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-white border-r transform -translate-x-full md:translate-x-0 transition z-50">

        <div class="p-6">
            <h1 class="text-xl font-bold text-blue-700 font-heading">Sekolah</h1>
        </div>

        <nav class="px-4 space-y-2 text-sm">
            <a class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-700 rounded-lg font-semibold">
                <span class="material-symbols-outlined">dashboard</span>
                Beranda
            </a>

            <a class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined">calendar_month</span>
                Jadwal Mengajar
            </a>

            <a class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined">edit_note</span>
                Input Nilai
            </a>

            <a class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined">fact_check</span>
                Absensi
            </a>

            <a class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg text-left">
                    <span class="material-symbols-outlined">logout</span>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- OVERLAY -->
    <div id="overlay"
        class="fixed inset-0 bg-black/30 hidden z-40"
        onclick="toggleSidebar()"></div>

    <!-- MAIN -->
    <main class="md:ml-64 p-6">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold font-heading">
                Dashboard Guru 👋
            </h1>
            <p class="text-slate-500 text-sm md:text-base">
                Anda memiliki 3 kelas hari ini
            </p>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- CARD -->
            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-slate-500">Total Mengajar</p>
                <h2 class="text-2xl font-bold">24 Jam</h2>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-slate-500">Total Siswa</p>
                <h2 class="text-2xl font-bold">182</h2>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-slate-500">Rata-rata Absensi</p>
                <h2 class="text-2xl font-bold text-green-600">96%</h2>
            </div>

        </div>

        <!-- JADWAL -->
        <div class="bg-white p-6 rounded-xl shadow mt-6">
            <h3 class="font-bold mb-4">Jadwal Hari Ini</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>07:30</span>
                    <span>Matematika</span>
                </div>

                <div class="flex justify-between text-blue-600 font-semibold">
                    <span>10:15</span>
                    <span>Matematika Peminatan</span>
                </div>

                <div class="flex justify-between">
                    <span>13:00</span>
                    <span>Matematika</span>
                </div>
            </div>
        </div>

        <!-- TASK -->
        <div class="bg-white p-6 rounded-xl shadow mt-6">
            <h3 class="font-bold mb-4">Input Nilai Pending</h3>

            <div class="space-y-3 text-sm">
                <p>⚠️ Ulangan Harian - Hari ini</p>
                <p>📘 Kuis Transformasi - Besok</p>
                <p>📝 Tugas Kelompok - 3 hari lagi</p>
            </div>
        </div>

    </main>

    <!-- SCRIPT -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

</body>

</html>