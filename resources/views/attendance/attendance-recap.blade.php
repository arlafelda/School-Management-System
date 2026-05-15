@extends('layouts.app')

@section('content')

<div class="p-8">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>
        <span class="mx-2">/</span>

        <a href="{{ route('attendance.index') }}"
            class="hover:text-blue-600">
            Attendance
        </a>
        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Rekap
        </span>
    </div>


    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">
                Rekap Absensi
            </h1>

            <p class="text-gray-500 text-sm">
                Ringkasan kehadiran siswa
            </p>
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
        <select name="semester"
            class="border rounded-lg px-3 py-2">
            <option value="">
                Semester
            </option>

            <option value="Ganjil"
                {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>
                Ganjil
            </option>

            <option value="Genap"
                {{ request('semester') == 'Genap' ? 'selected' : '' }}>
                Genap
            </option>
        </select>


        <!-- Jurusan -->
        <select name="major"
            class="border rounded-lg px-3 py-2">

            <option value="">
                Jurusan
            </option>

            @foreach($classes->unique('major') as $c)
            <option value="{{ $c->major }}"
                {{ request('major') == $c->major ? 'selected' : '' }}>
                {{ $c->major }}
            </option>
            @endforeach

        </select>


        <!-- Kelas -->
        <select name="class_id"
            class="border rounded-lg px-3 py-2">

            <option value="">
                Kelas
            </option>

            @foreach($classes as $c)
            <option value="{{ $c->id }}"
                {{ request('class_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach

        </select>


        <!-- BUTTON -->
        <button
            class="bg-blue-600 text-white rounded-lg px-3 py-2 col-span-2">
            Filter
        </button>

    </form>


    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow p-6 overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-right">Hadir</th>
                    <th class="p-3 text-right">Izin</th>
                    <th class="p-3 text-right">Alpa</th>
                    <th class="p-3 text-right">Total</th>
                    <th class="p-3 text-right">%</th>
                </tr>
            </thead>

            <tbody>

                @forelse($rekap as $r)

                @php
                $hadir = $r->hadir ?? 0;
                $izin = $r->izin ?? 0;
                $alpa = $r->alpa ?? 0;

                $total = $hadir + $izin + $alpa;
                $persen = $total > 0
                ? ($hadir / $total) * 100
                : 0;
                @endphp

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 font-medium">
                        {{ $r->student->name ?? '-' }}
                    </td>

                    <td class="p-3 text-right text-green-600">
                        {{ number_format($hadir) }}
                    </td>

                    <td class="p-3 text-right text-yellow-500">
                        {{ number_format($izin) }}
                    </td>

                    <td class="p-3 text-right text-red-500">
                        {{ number_format($alpa) }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($total) }}
                    </td>

                    <td class="p-3 text-right font-semibold text-blue-600">
                        {{ number_format($persen, 1) }}%
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6"
                        class="p-6 text-center text-gray-400">
                        Data rekap tidak ditemukan
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection