@extends('layouts.app')

@section('content')

<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Rekap Absensi</h1>
            <p class="text-gray-500 text-sm">Ringkasan kehadiran siswa</p>
        </div>

        <a href="{{ route('attendance.index') }}"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
            Kembali
        </a>
    </div>

    <!-- FILTER -->
    <form method="GET"
        class="bg-white p-4 rounded-xl shadow-sm mb-6 grid md:grid-cols-6 gap-4">

        <!-- Semester -->
        <select name="semester" class="border rounded-lg px-3 py-2">
            <option value="">Semester</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
        </select>

        <!-- Jurusan -->
        <select name="major" class="border rounded-lg px-3 py-2">
            <option value="">Jurusan</option>
            @foreach($classes->unique('major') as $c)
                <option value="{{ $c->major }}">
                    {{ $c->major }}
                </option>
            @endforeach
        </select>

        <!-- Kelas -->
        <select name="class_id" class="border rounded-lg px-3 py-2">
            <option value="">Kelas</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}">
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <!-- Tombol -->
        <button class="bg-blue-600 text-white rounded-lg px-3 py-2 col-span-2">
            Filter
        </button>

    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow p-6 overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-center">Hadir</th>
                    <th class="p-3 text-center">Izin</th>
                    <th class="p-3 text-center">Alpa</th>
                    <th class="p-3 text-center">Total</th>
                    <th class="p-3 text-center">%</th>
                </tr>
            </thead>

            <tbody>

                @foreach($rekap as $r)

                    @php
                        $total = $r->hadir + $r->izin + $r->alpa;
                        $persen = $total > 0 ? round(($r->hadir / $total) * 100, 1) : 0;
                    @endphp

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3 font-medium">
                            {{ $r->student->name ?? '-' }}
                        </td>

                        <td class="p-3 text-center text-green-600">
                            {{ $r->hadir }}
                        </td>

                        <td class="p-3 text-center text-yellow-500">
                            {{ $r->izin }}
                        </td>

                        <td class="p-3 text-center text-red-500">
                            {{ $r->alpa }}
                        </td>

                        <td class="p-3 text-center">
                            {{ $total }}
                        </td>

                        <td class="p-3 text-center font-semibold text-blue-600">
                            {{ $persen }}%
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection