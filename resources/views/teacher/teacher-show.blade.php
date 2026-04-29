@extends('layouts.app')

@section('title', 'Profile Guru')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-blue-700">Profile Guru</h2>

        <a href="{{ route('teacher.index') }}"
           class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>
    </div>

    <!-- CARD -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow border p-6">

        <!-- PROFILE -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

            <!-- AVATAR -->
            <div class="w-28 h-28 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>

            <!-- INFO -->
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold">{{ $teacher->name }}</h2>

                <p class="text-gray-500 text-sm">
                    {{ $teacher->email ?? '-' }}
                </p>

                <span class="inline-block mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">
                    {{ ucfirst($teacher->position) }}
                </span>
            </div>

        </div>

        <!-- DETAIL -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

            <div>
                <p class="text-gray-500">NIP</p>
                <p class="font-semibold">{{ $teacher->nip }}</p>
            </div>

            <div>
                <p class="text-gray-500">Mata Pelajaran</p>
                <p class="font-semibold">{{ $teacher->subject }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP</p>
                <p class="font-semibold">{{ $teacher->phone }}</p>
            </div>

            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-semibold">{{ $teacher->email ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">ID Guru</p>
                <p class="font-semibold">
                    #{{ str_pad($teacher->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Jabatan</p>
                <p class="font-semibold">
                    {{ ucfirst($teacher->position) }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Tanggal Dibuat</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($teacher->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Terakhir Update</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($teacher->updated_at)->translatedFormat('d F Y') }}
                </p>
            </div>

        </div>

        <!-- ACTION -->
        <div class="mt-8 flex flex-wrap gap-3">

            <a href="{{ route('teacher.edit', $teacher->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                ✏️ Edit Profile
            </a>

            <form action="{{ route('teacher.delete', $teacher->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus guru ini?')">

                @csrf
                @method('DELETE')

                <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">
                    🗑️ Hapus
                </button>

            </form>

        </div>

    </div>

</div>

@endsection