@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    <!-- HERO -->
    <section class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-3xl p-8 shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold">
            Kelola Data Sekolah Lebih Mudah 🚀
        </h1>
        <p class="text-sm mt-2 text-white/80">
            Sistem manajemen sekolah berbasis web modern
        </p>
    </section>

    <!-- STATS -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-slate-500">Total Siswa</p>
            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalStudents ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-slate-500">Total Guru</p>
            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalTeachers ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-slate-500">Total Kelas</p>
            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalClasses ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-slate-500">Total Jadwal</p>
            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalSchedules ?? 0, 0, ',', '.') }}
            </h2>
        </div>

    </section>

</div>

@endsection