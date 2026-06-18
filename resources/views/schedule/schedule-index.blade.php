@extends('layouts.app')

@section('title', 'Kelola Jadwal')

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
            <span class="text-gray-700 font-medium">Kelola Jadwal</span>
        </div>

        <!-- ALERT -->
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Kelola Jadwal</h1>
                <p class="text-sm text-gray-400 mt-0.5">Manajemen jadwal pelajaran sekolah</p>
            </div>

            @if(in_array($user->role, ['super_admin', 'admin']))
            <div class="flex items-center gap-2">

                <a href="{{ route('schedule.archived') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                    </svg>
                    Data Arsip
                </a>

                <a href="{{ route('schedule.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah Jadwal
                </a>

            </div>
            @endif

        </div>

        <!-- FILTER -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 mb-6">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <select name="class_id"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                    onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                    @endforeach
                </select>

                <input type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari mata pelajaran, guru, atau kelas..."
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm w-80 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">

                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                    <span>🔍</span> Cari
                </button>

                <a href="{{ route('schedule.index') }}"
                    class="inline-flex items-center px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Reset
                </a>
            </form>
        </div>

        <!-- TABLE CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Hari</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Jam</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru</th>
                            @if(in_array($user->role, ['super_admin', 'admin']))
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($schedules as $schedule)

                        @if($user->role !== 'student' || optional($schedule->classModel)->id == optional($user->student)->class_id)

                        <tr id="row-{{ $schedule->id }}"
                            class="hover:bg-indigo-50/30 transition group schedule-row"
                            data-url="{{ route('schedule.show', $schedule->id) }}">

                            <td class="px-6 py-4 font-medium text-gray-800 cursor-pointer schedule-show">
                                {{ $schedule->day }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer schedule-show">
                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer schedule-show">
                                {{ $schedule->classModel->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer schedule-show">
                                {{ $schedule->classModel->major ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center font-medium text-gray-700 cursor-pointer schedule-show">
                                {{ $schedule->subject->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600 cursor-pointer schedule-show">
                                {{ $schedule->teacher->name ?? '-' }}
                            </td>

                            @if(in_array($user->role, ['super_admin', 'admin']))
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">

                                    <a href="{{ route('schedule.edit', $schedule->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition btn-archive"
                                        data-id="{{ $schedule->id }}"
                                        data-url="{{ route('schedule.delete', $schedule->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                                        </svg>
                                        Arsipkan
                                    </button>

                                </div>
                            </td>
                            @endif

                        </tr>

                        @endif

                        @empty
                        <tr>
                            <td colspan="{{ in_array($user->role, ['super_admin', 'admin']) ? 7 : 6 }}" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada data jadwal</p>
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
        let row = e.target.closest('.schedule-show');
        if (row) {
            const url = row.closest('tr').dataset.url;
            if (url) window.location.href = url;
        }
    });

    // Tombol Arsipkan
    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.btn-archive');
        if (!btn) return;

        const url = btn.dataset.url;
        const id  = btn.dataset.id;

        if (typeof deleteData === 'function') {
            deleteData(url, 'Yakin ingin memindahkan jadwal ke arsip?', {
                onSuccess: function () {
                    document.getElementById('row-' + id)?.remove();
                }
            });
        }
    });

});
</script>
@endpush