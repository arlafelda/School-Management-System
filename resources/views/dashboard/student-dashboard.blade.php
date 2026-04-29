<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-[Inter] text-gray-800">

<!-- NAVBAR -->
<header class="fixed top-0 left-0 right-0 h-16 bg-white border-b flex items-center justify-between px-6 z-50">
    <h1 class="text-xl font-bold text-blue-700 font-[Manrope]">LENTERA</h1>

    <div class="flex items-center gap-4">
        <button class="p-2 hover:bg-gray-100 rounded-full">🔔</button>
        <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
    </div>
</header>

<!-- SIDEBAR -->
<aside class="fixed top-16 left-0 w-64 h-full bg-white border-r p-4 hidden md:block">

    <p class="text-xs text-gray-500 mb-3">MENU</p>

    <nav class="space-y-2 text-sm">

        <a class="flex items-center gap-2 p-3 bg-blue-100 text-blue-700 rounded-lg font-semibold">
            📊 Beranda
        </a>

        <a class="flex items-center gap-2 p-3 hover:bg-gray-100 rounded-lg">
            📚 Jadwal
        </a>

        <a class="flex items-center gap-2 p-3 hover:bg-gray-100 rounded-lg">
            📝 Nilai
        </a>

        <a class="flex items-center gap-2 p-3 hover:bg-gray-100 rounded-lg">
            👤 Profil
        </a>

    </nav>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
            🚪 Logout
        </button>
    </form>

</aside>

<!-- MAIN -->
<main class="md:ml-64 pt-20 p-6">

    <!-- GREETING -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold font-[Manrope]">
            Halo, {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-500 text-sm">Selamat datang kembali</p>
    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">

        <!-- KEHADIRAN -->
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Persentase Kehadiran</p>
            <h2 class="text-3xl font-bold">{{ $attendancePercent }}%</h2>

            <div class="w-full bg-gray-200 h-2 rounded mt-3">
                <div id="progressBar"
                     data-percent="{{ (int) $attendancePercent }}"
                     class="bg-blue-600 h-2 rounded transition-all duration-500">
                </div>
            </div>
        </div>

        <!-- TOTAL ABSEN -->
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Total Kehadiran</p>
            <h2 class="text-3xl font-bold">
                {{ $totalAttendance }}
            </h2>
        </div>

        <!-- JUMLAH JADWAL -->
        <div class="bg-blue-100 p-5 rounded-lg">
            <p class="text-blue-600 text-sm">Jadwal Hari Ini</p>
            <h2 class="text-2xl font-bold text-blue-700">
                {{ $todaySchedules->count() }} Pelajaran
            </h2>
        </div>

    </div>

    <!-- JADWAL -->
    <div class="bg-white rounded-lg shadow mt-6">

        <div class="p-4 border-b font-semibold">
            Jadwal Hari Ini
        </div>

        <div class="divide-y">

            @forelse($todaySchedules as $schedule)
                <div class="p-4 flex justify-between">
                    <div>
                        <p class="font-semibold">
                            {{ $schedule->subject ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $schedule->teacher->name ?? '-' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Tidak ada jadwal hari ini
                </div>
            @endforelse

        </div>

    </div>

</main>

<script>
    const bar = document.getElementById('progressBar');
    const percent = bar.dataset.percent;

    bar.style.width = percent + '%';
</script>

</body>
</html>