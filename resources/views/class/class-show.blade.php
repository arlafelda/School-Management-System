@extends('layouts.app')

@section('content')

<div class="p-6 space-y-6">

    <!-- 🔥 BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex space-x-2">
            <li>
                <a href="{{ route('class.index') }}" class="hover:text-blue-600">
                    Kelas
                </a>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">
                {{ $class->name }}
            </li>
        </ol>
    </nav>

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

            <!-- 🔥 SLUG BACK -->
            <a href="{{ route('class.index') }}"
               class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition">
                ← Kembali
            </a>

        </div>

        <!-- INFO -->
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
                <p class="font-bold text-lg">
                    {{ $class->level }}
                </p>
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
                        <th class="p-4 text-right">NISN</th>
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

                        <td class="p-4 text-right text-gray-600">
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

@endsection