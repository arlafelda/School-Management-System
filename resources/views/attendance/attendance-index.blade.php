@extends('layouts.app')

@section('content')

<div class="p-8">

    <!-- TITLE -->
    <div class="mb-6 flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">Manajemen Absensi</h2>
            <p class="text-gray-500 text-sm">Kelola kehadiran siswa</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('attendance.recap') }}"
                class="px-4 py-2 bg-green-500 text-white rounded-lg">
                Rekap
            </a>

            <a href="{{ route('attendance.create') }}"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Input Absensi
            </a>
        </div>

    </div>

    <!-- STATS -->
    <div class="grid md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Kehadiran</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $persen }}%</h3>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Total Data</p>
            <h3 class="text-2xl font-bold">{{ $attendances->count() }}</h3>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Status</p>
            <h3 class="text-2xl font-bold text-purple-600">Aktif</h3>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-left">Mapel</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($attendances as $i => $a)

                <tr class="hover:bg-gray-50">

                    <td class="p-3">{{ $i + 1 }}</td>

                    <td class="p-3 font-medium">
                        {{ $a->student->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $a->student->class->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $a->schedule->teacher->subject ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $a->date ?? '-' }}
                    </td>

                    <td class="p-3">

                        @php
                            $statusClass = match($a->status) {
                                'hadir' => 'bg-green-100 text-green-600',
                                'izin' => 'bg-yellow-100 text-yellow-600',
                                'sakit' => 'bg-blue-100 text-blue-600',
                                default => 'bg-red-100 text-red-600',
                            };
                        @endphp

                        <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">
                            {{ ucfirst($a->status) }}
                        </span>

                    </td>

                    <td class="p-3 text-right">
                        <a href="{{ route('attendance.edit', $a->id) }}"
                            class="text-blue-500 hover:underline">
                            Edit
                        </a>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-400">
                        Data tidak ditemukan
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection