<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Kelas</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Inter', sans-serif; }
</style>

</head>

<body class="bg-gray-50">

<div class="max-w-6xl mx-auto mt-10 space-y-6">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-8 rounded-2xl shadow-lg">
        
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-3xl font-bold">
                    {{ $class->name }}
                </h1>
                <p class="text-sm opacity-90 mt-1">
                    Detail informasi kelas & siswa
                </p>
            </div>

            <a href="{{ route('class.index') }}"
               class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition">
                ← Kembali
            </a>

        </div>

        <!-- INFO CARDS -->
        <div class="grid md:grid-cols-6 gap-4 mt-6">

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Tahun Ajaran</p>
                <p class="font-bold text-lg">{{ $year ?? '-' }}</p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Semester</p>
                <p class="font-bold text-lg">{{ $semester ?? '-' }}</p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Tingkat</p>
                <p class="font-bold text-lg">{{ $class->level }}</p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Jurusan</p>
                <p class="font-bold text-lg">{{ $class->major ?? '-' }}</p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Wali Kelas</p>
                <p class="font-bold text-lg">
                    {{ $class->teacher->name ?? '-' }}
                </p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Total Siswa</p>
                <p class="font-bold text-lg">
                    {{ $class->students->count() }}
                </p>
            </div>

        </div>

    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow">

        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="font-semibold text-lg">Daftar Siswa</h2>

            <span class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                {{ $class->students->count() }} Siswa
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-center">NISN</th>
                        <th class="p-4 text-center">Email</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($class->students as $student)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                    {{ strtoupper(substr($student->name,0,1)) }}
                                </div>

                                <span class="font-medium">
                                    {{ $student->name }}
                                </span>

                            </div>
                        </td>

                        <td class="p-4 text-center text-gray-600">
                            {{ $student->nisn ?? '-' }}
                        </td>

                        <td class="p-4 text-center text-gray-600">
                            {{ optional($student->user)->email ?? '-' }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" class="text-center p-6 text-gray-400">
                            Belum ada siswa di kelas ini
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>