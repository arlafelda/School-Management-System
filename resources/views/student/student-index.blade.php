@extends('layouts.app')

@section('title', 'Students Management')

@section('content')

@php
$user = auth()->user();
$isHomeroomTeacher = false;
if ($user->role === 'teacher' && $user->teacher) {
$isHomeroomTeacher = \App\Models\ClassModel::where('teacher_id', $user->teacher->id)->exists();
}
@endphp

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-7xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <span class="text-gray-700 font-medium">Kelola Siswa</span>
        </div>

        {{-- ALERT --}}
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        @if(session('success'))
        <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl text-sm bg-green-50 text-green-700 border border-green-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <path d="M22 4L12 14.01l-3-3" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Daftar Siswa</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola data siswa yang terdaftar di sistem.</p>
            </div>

            @if(in_array($user->role, ['super_admin', 'admin']))
            <div class="flex items-center gap-2">

                <a href="{{ route('students.archived') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 8v13H3V8" />
                        <path d="M1 3h22v5H1z" />
                        <path d="M10 12h4" />
                    </svg>
                    Arsip
                </a>

                <a href="{{ route('students.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Siswa
                </a>

            </div>
            @endif

        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[900px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Kelas</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No HP</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($students as $student)

                        <tr id="row-{{ $student->id }}" class="hover:bg-indigo-50/30 transition group">

                            {{-- NAMA --}}
                            <td class="px-6 py-4">
                                <a href="{{ $student->slug ? route('students.show', $student->slug) : '#' }}"
                                    class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-emerald-600 font-semibold text-sm">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 group-hover:text-indigo-600 transition">
                                            {{ $student->name }}
                                        </p>
                                    </div>
                                </a>
                            </td>

                            {{-- NISN --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-400 font-mono text-xs">{{ $student->nisn ?? '-' }}</span>
                            </td>

                            {{-- NIS --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-400 font-mono text-xs">{{ $student->nis ?? '-' }}</span>
                            </td>

                            {{-- KELAS --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->class)
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ $student->class->name }}
                                </span>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- JURUSAN --}}
                            <td class="px-6 py-4 text-gray-600 text-xs">
                                {{ $student->major ?? '-' }}
                            </td>

                            {{-- NO HP --}}
                            <td class="px-6 py-4 text-gray-600 text-xs">
                                {{ $student->phone ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">
                                @php
                                $statusStyle = match($student->status) {
                                'aktif' => 'bg-green-50 text-green-700 border-green-100',
                                'lulus' => 'bg-blue-50 text-blue-700 border-blue-100',
                                default => 'bg-red-50 text-red-700 border-red-100',
                                };
                                $dotStyle = match($student->status) {
                                'aktif' => 'bg-green-500',
                                'lulus' => 'bg-blue-500',
                                default => 'bg-red-500',
                                };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 {{ $statusStyle }} border px-2.5 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotStyle }} inline-block"></span>
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- RAPOT --}}
                                    <a href="{{ route('students.raport', $student->slug) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-600 border border-emerald-200 rounded-lg bg-emerald-50 hover:bg-emerald-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <line x1="16" y1="13" x2="8" y2="13" />
                                            <line x1="16" y1="17" x2="8" y2="17" />
                                        </svg>
                                        Rapot
                                    </a>

                                    @if(in_array($user->role, ['super_admin', 'admin']))

                                    {{-- EDIT --}}
                                    <a href="{{ $student->slug ? route('students.edit', $student->slug) : '#' }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    <form class="formDelete"
                                        action="{{ $student->slug ? route('students.delete', $student->slug) : '#' }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14H6L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                                <path d="M9 6V4h6v2" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>

                                    @endif

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada siswa terdaftar</p>
                                    @if(in_array($user->role, ['super_admin', 'admin']))
                                    <a href="{{ route('students.create') }}" class="text-xs text-indigo-600 hover:underline">+ Tambah siswa pertama</a>
                                    @endif
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
    document.addEventListener('DOMContentLoaded', function() {

        document.addEventListener('submit', function(e) {

            if (!e.target.classList.contains('formDelete')) return;

            e.preventDefault();

            var form = e.target;
            var url = form.action;
            var row = form.closest('tr');

            if (typeof deleteData === 'undefined') {
                console.error('deleteData belum tersedia');
                return;
            }

            deleteData(
                url,
                'Yakin ingin menghapus data siswa ini?', {
                    onSuccess: function() {

                        if (row) row.remove();

                        var alertBox = document.getElementById('alertBox');
                        alertBox.className = 'mb-4 flex items-center gap-2 px-4 py-3 rounded-xl text-sm bg-green-50 text-green-700 border border-green-200';
                        alertBox.innerHTML =
                            '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>' +
                            '<span>Data siswa berhasil dihapus.</span>';

                        setTimeout(function() {
                            alertBox.className = 'hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2';
                            alertBox.innerHTML = '';
                        }, 3000);
                    }
                }
            );

        });

    });
</script>
@endpush