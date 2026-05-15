@extends('layouts.app')

@section('title', 'Archived Grades')

@section('content')

<div class="p-6 space-y-6">

    <!-- BREADCRUMB -->
    <div class="text-sm text-gray-500">
        <a href="{{ route('dashboard') }}"
           class="hover:text-blue-600">
            Dashboard
        </a>

        <span class="mx-2">/</span>

        <a href="{{ route('grades.index') }}"
           class="hover:text-blue-600">
            Nilai
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Arsip Nilai
        </span>
    </div>

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Arsip Nilai
            </h1>
            <p class="text-sm text-gray-500">
                Data nilai yang telah diarsipkan
            </p>
        </div>

        <a href="{{ route('grades.index') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Kembali
        </a>

    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white rounded-lg border overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-center">Kelas</th>
                    <th class="p-3 text-center">Mapel</th>
                    <th class="p-3 text-right">Tugas</th>
                    <th class="p-3 text-right">UTS</th>
                    <th class="p-3 text-right">UAS</th>
                    <th class="p-3 text-right">Nilai</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($grades as $grade)

                @php
                    $tugas = $grade->assignment_score ?? 0;
                    $uts   = $grade->mid_exam_score ?? 0;
                    $uas   = $grade->final_exam_score ?? 0;
                    $final = ($tugas + $uts + $uas) / 3;
                @endphp

                <tr class="border-t hover:bg-gray-50">

                    <td class="p-3">
                        {{ $grade->student->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $grade->student->class->name ?? '-' }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $grade->subject }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($tugas) }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($uts) }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($uas) }}
                    </td>

                    <td class="p-3 text-right text-blue-600 font-bold">
                        {{ number_format($final, 1) }}
                    </td>

                    <td class="p-3 text-center">

                        <form action="{{ route('grades.restore', $grade->id) }}"
                              method="POST"
                              onsubmit="return confirm('Restore data ini?')">

                            @csrf
                            @method('PUT')

                            <button type="submit"
                                    class="text-green-600 hover:underline text-sm">
                                Restore
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">
                        Tidak ada data archive
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection