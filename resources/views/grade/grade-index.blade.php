@extends('layouts.app')

@section('title', 'Manajemen Nilai')

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        <!-- BREADCRUMB -->
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span class="text-gray-700 font-medium">Manajemen Nilai</span>
        </div>

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ $user->role === 'student' ? 'Nilai Saya' : 'Manajemen Nilai' }}
                </h1>
                <p class="text-sm text-gray-400 mt-0.5">
                    {{ $user->role === 'student' ? 'Rekap nilai pribadi' : 'Kelola data nilai siswa' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                <a href="{{ route('grades.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah Nilai
                </a>
                @endif
            </div>

        </div>

        <!-- ALERT -->
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        <!-- STATISTICS CARDS -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-500">Total Data</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2 text-right">
                    {{ number_format($data->count()) }}
                </h3>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-2 text-right">
                    {{
                        $data->count()
                        ? number_format(
                            $data->avg(function($item){
                                return (
                                    ($item->assignment_score ?? 0) +
                                    ($item->mid_exam_score ?? 0) +
                                    ($item->final_exam_score ?? 0)
                                ) / 3;
                            }), 1)
                        : 0
                    }}
                </h3>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-500">Nilai Kosong</p>
                <h3 class="text-3xl font-bold text-red-500 mt-2 text-right">
                    {{
                        $data->filter(function($item){
                            return ($item->assignment_score ?? 0) == 0
                                && ($item->mid_exam_score ?? 0) == 0
                                && ($item->final_exam_score ?? 0) == 0;
                        })->count()
                    }}
                </h3>
            </div>
        </div>

        <!-- FILTER -->
        @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
            <form method="GET" class="grid md:grid-cols-5 gap-3">
                <select name="academic_year" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Tahun Ajaran</option>
                    @foreach($classes->unique('academic_year') as $c)
                    <option value="{{ $c->academic_year }}" {{ request('academic_year') == $c->academic_year ? 'selected' : '' }}>
                        {{ $c->academic_year }}
                    </option>
                    @endforeach
                </select>

                <select name="semester" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semester</option>
                    <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>

                <select name="major" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Jurusan</option>
                    @foreach($classes->unique('major') as $c)
                        @if($c->major)
                        <option value="{{ $c->major }}" {{ request('major') == $c->major ? 'selected' : '' }}>
                            {{ $c->major }}
                        </option>
                        @endif
                    @endforeach
                </select>

                <select name="class_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Kelas</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-indigo-600 text-white rounded-xl px-5 py-2.5 font-medium hover:bg-indigo-700 transition">
                    Terapkan Filter
                </button>
            </form>
        </div>
        @endif

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">UTS</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">UAS</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata</th>
                            @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($data as $g)

                        @php
                            $tugas = $g->assignment_score ?? 0;
                            $uts   = $g->mid_exam_score ?? 0;
                            $uas   = $g->final_exam_score ?? 0;
                            $final = ($tugas + $uts + $uas) / 3;
                        @endphp

                        <tr id="row-{{ $g->id }}"
                            class="hover:bg-indigo-50/30 transition group grade-row"
                            data-url="{{ route('grades.show', $g->id) }}">

                            <td class="px-6 py-4 cursor-pointer grade-show">
                                <div class="font-medium text-gray-800">
                                    {{ $g->student->name ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer grade-show">
                                {{ $g->student->class->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer grade-show">
                                {{ $g->schedule->subject->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700 cursor-pointer grade-show">
                                {{ number_format($tugas) }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700 cursor-pointer grade-show">
                                {{ number_format($uts) }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700 cursor-pointer grade-show">
                                {{ number_format($uas) }}
                            </td>

                            <td class="px-6 py-4 text-right font-bold text-emerald-600 cursor-pointer grade-show">
                                {{ number_format($final, 1) }}
                            </td>

                            @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">

                                    <a href="{{ route('grades.edit', $g->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('grades.destroy', $g->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus data nilai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                            @endif

                        </tr>

                        @empty
                        <tr>
                            <td colspan="{{ in_array($user->role, ['super_admin', 'admin', 'teacher']) ? 8 : 7 }}" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada data nilai</p>
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

    // Klik Row → Detail
    document.addEventListener('click', function (e) {
        let cell = e.target.closest('.grade-show');
        if (cell) {
            const row = cell.closest('tr');
            const url = row ? row.dataset.url : null;
            if (url) window.location.href = url;
        }
    });

});
</script>
@endpush