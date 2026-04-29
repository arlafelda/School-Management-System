@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-8 rounded-2xl shadow-lg">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ $student->name }}
                </h1>
                <p class="text-sm opacity-90 mt-1">
                    Detail informasi siswa
                </p>
            </div>

            <a href="{{ route('students.index') }}"
               class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition">
                ← Kembali
            </a>
        </div>

        <!-- INFO CARDS -->
        <div class="grid md:grid-cols-5 gap-4 mt-6">

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">NISN</p>
                <p class="font-bold text-lg">{{ $student->nisn ?? '-' }}</p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Email</p>
                <p class="font-bold text-sm break-all">
                    {{ optional($student->user)->email ?? '-' }}
                </p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Kelas</p>
                <p class="font-bold text-lg">
                    {{ $student->class->name ?? '-' }}
                </p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Jurusan</p>
                <p class="font-bold text-lg">
                    {{ $student->major ?? '-' }}
                </p>
            </div>

            <div class="bg-white/20 p-4 rounded-xl">
                <p class="text-xs opacity-80">Wali Kelas</p>
                <p class="font-bold text-sm">
                    {{ optional($student->class->teacher)->name ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <!-- DETAIL BIODATA -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h2 class="text-lg font-semibold mb-4">Biodata Siswa</h2>

        <div class="grid md:grid-cols-2 gap-6 text-sm">

            <div>
                <p class="text-gray-500">Nama Lengkap</p>
                <p class="font-medium">{{ $student->name }}</p>
            </div>

            <div>
                <p class="text-gray-500">NIS</p>
                <p class="font-medium">{{ $student->nis ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Jenis Kelamin</p>
                <p class="font-medium">{{ $student->gender ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP</p>
                <p class="font-medium">{{ $student->phone ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Tempat, Tanggal Lahir</p>
                <p class="font-medium">
                    {{ $student->birth_place ?? '-' }},
                    {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>
                <p class="font-medium">{{ $student->status ?? '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500">Alamat</p>
                <p class="font-medium">{{ $student->address ?? '-' }}</p>
            </div>

        </div>

    </div>

    <!-- DATA ORANG TUA -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h2 class="text-lg font-semibold mb-4">Data Orang Tua</h2>

        <div class="grid md:grid-cols-2 gap-6 text-sm">

            <div>
                <p class="text-gray-500">Nama Ayah</p>
                <p class="font-medium">{{ $student->father_name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Nama Ibu</p>
                <p class="font-medium">{{ $student->mother_name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP Orang Tua</p>
                <p class="font-medium">{{ $student->parent_phone ?? '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500">Alamat Orang Tua</p>
                <p class="font-medium">{{ $student->parent_address ?? '-' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection