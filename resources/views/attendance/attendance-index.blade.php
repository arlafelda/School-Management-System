@extends('layouts.app')

@section('title', 'Manajemen Absensi')

@section('content')

@php
    $user = auth()->user();
    $studentClass = $user->role === 'student'
        ? optional(optional($user->student)->class)
        : null;
@endphp

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        <!-- BREADCRUMB -->
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span class="text-gray-700 font-medium">Manajemen Absensi</span>
        </div>

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Manajemen Absensi</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola kehadiran siswa</p>
            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('attendance.recap') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2 2 2 0 01-2-2 2 2 0 01-2-2 2 2 0 012-2zm10 10v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                    Rekap
                </a>

                @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                <a href="{{ route('attendance.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Absensi
                </a>
                @endif

            </div>

        </div>

        <!-- FILTER -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
            <form method="GET" action="{{ route('attendance.index') }}" class="grid md:grid-cols-5 gap-4">

                <!-- CLASS -->
                @if($user->role === 'student')
                    <div>
                        <input type="text"
                               value="{{ $studentClass->name ?? '-' }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 bg-gray-100 text-gray-500"
                               disabled>
                        <input type="hidden" name="class_id" value="{{ $studentClass->id ?? '' }}">
                    </div>
                @else
                    <select name="class_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <!-- SUBJECT -->
                <select name="subject_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>

                <!-- STATUS -->
                <select name="status" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>

                <!-- DATE -->
                <div>
                    <input type="date"
                           id="tanggalInput"
                           name="date"
                           value="{{ request('date') }}"
                           class="border border-gray-300 rounded-xl px-4 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none">
                    <small id="tanggalPreview" class="text-gray-500 text-xs block mt-1"></small>
                </div>

                <button type="submit"
                    class="bg-indigo-600 text-white rounded-xl px-5 py-2.5 font-medium hover:bg-indigo-700 transition">
                    Terapkan Filter
                </button>

            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[950px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($attendances as $i => $a)

                            @php
                                $isOwnerTeacher = false;
                                if ($user->role === 'teacher') {
                                    $isOwnerTeacher = optional(optional($a->schedule)->teacher)->user_id == $user->id;
                                }

                                $statusClass = match($a->status) {
                                    'hadir' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                    'izin'  => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    'sakit' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    default => 'bg-red-100 text-red-700 border border-red-200'
                                };
                            @endphp

                            <tr id="row-{{ $a->id }}" class="hover:bg-indigo-50/30 transition group">

                                <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ optional($a->student)->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ optional(optional($a->student)->class)->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ optional(optional($a->schedule)->subject)->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($a->date)->locale('id')->translatedFormat('d F Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">

                                        @if(in_array($user->role, ['super_admin', 'admin']) || ($user->role === 'teacher' && $isOwnerTeacher))
                                            <a href="{{ route('attendance.edit', $a->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        @endif

                                        @if(in_array($user->role, ['super_admin', 'admin']))
                                            <button
                                                data-id="{{ $a->id }}"
                                                data-url="{{ route('attendance.destroy', $a->id) }}"
                                                class="btn-delete inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.595 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.595-1.858L5 7"/>
                                                    <path d="M10 11v6m4-6v6m1-10V9a1 1 0 00-1-1h-4a1 1 0 00-1 1v1M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        @endif

                                        @if(!in_array($user->role, ['super_admin', 'admin']) && !($user->role === 'teacher' && $isOwnerTeacher))
                                            <span class="text-xs text-gray-400">View Only</span>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500">Data absensi tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Date Preview
    const tanggalInput = document.getElementById('tanggalInput');
    const preview = document.getElementById('tanggalPreview');

    function formatTanggalIndonesia(value) {
        if (!value) return '';
        return new Date(value).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    if (tanggalInput && preview) {
        preview.innerText = formatTanggalIndonesia(tanggalInput.value);
        tanggalInput.addEventListener('change', function () {
            preview.innerText = formatTanggalIndonesia(this.value);
        });
    }

    // Delete Button
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            if (!confirm('Yakin ingin menghapus data absensi ini?')) return;

            const id = this.dataset.id;
            const url = this.dataset.url;

            if (typeof deleteData === 'function') {
                deleteData(url, {
                    onSuccess: function () {
                        document.getElementById('row-' + id)?.remove();
                    }
                });
            }
        });
    });

});
</script>
@endpush