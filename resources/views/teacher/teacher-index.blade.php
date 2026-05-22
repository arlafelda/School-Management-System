@extends('layouts.app')

@section('title', 'Teachers Management')

@section('content')

<div class="space-y-6">

    {{-- BREADCRUMB --}}
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <span class="text-gray-700 font-medium">
                    Dashboard
                </span>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">
                Guru
            </li>
        </ol>
    </nav>

    {{-- ALERT --}}
    <div id="alertBox"></div>

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold">
                Daftar Guru
            </h1>
            <p class="text-gray-500 text-sm">
                Kelola data guru
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('teacher.archived') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Arsip
            </a>

            <a href="{{ route('teacher.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Guru
            </a>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[900px]">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Guru</th>
                    <th class="p-4 text-center">NIP</th>
                    <th class="p-4 text-center">Mapel</th>
                    <th class="p-4 text-center">Jabatan</th>
                    <th class="p-4 text-center">No HP</th>
                    <th class="p-4 text-center">Email</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($teachers as $teacher)

                <tr id="row-{{ $teacher->id }}"
                    class="hover:bg-gray-50 cursor-pointer"
                    data-url="{{ route('teacher.show', $teacher->slug) }}">

                    {{-- NAME --}}
                    <td class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>

                        <div class="font-semibold">
                            {{ $teacher->name }}
                        </div>
                    </td>

                    {{-- NIP --}}
                    <td class="p-4 text-center">
                        {{ $teacher->nip }}
                    </td>

                    {{-- SUBJECT --}}
                    <td class="p-4 text-center">
                        @if($teacher->subjects->count())
                            @foreach($teacher->subjects as $subject)
                                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1 mb-1">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    {{-- POSITION --}}
                    <td class="p-4 text-center">
                        @if($teacher->position == 'wali_kelas')
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                Wali Kelas
                            </span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs">
                                Guru
                            </span>
                        @endif
                    </td>

                    {{-- PHONE --}}
                    <td class="p-4 text-center">
                        {{ $teacher->phone ?? '-' }}
                    </td>

                    {{-- EMAIL --}}
                    <td class="p-4 text-center">
                        {{ $teacher->user->email ?? '-' }}
                    </td>

                    {{-- ACTION --}}
                    <td class="p-4 text-center space-x-2">

                        <a href="{{ route('teacher.edit', $teacher->slug) }}"
                           class="text-blue-600"
                           onclick="event.stopPropagation()">
                            Edit
                        </a>

                        {{-- FIXED ROUTE: teacher.destroy (BUKAN teacher.delete) --}}
                        <button
                            type="button"
                            class="btn-delete text-red-600"
                            data-id="{{ $teacher->id }}"
                            data-url="{{ route('teacher.destroy', $teacher->slug) }}">
                            Arsipkan
                        </button>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="7"
                        class="text-center p-6 text-gray-500">
                        Data guru tidak tersedia
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // DETAIL CLICK ROW
    // =========================
    document.querySelectorAll('tr[data-url]').forEach(function(row){

        row.addEventListener('click', function(e){

            if (
                e.target.closest('.btn-delete') ||
                e.target.closest('a')
            ) return;

            window.location.href = row.dataset.url;
        });

    });

    // =========================
    // ARCHIVE (DELETE AJAX)
    // =========================
    document.querySelectorAll('.btn-delete').forEach(function(btn){

        btn.addEventListener('click', function(e){

            e.preventDefault();
            e.stopPropagation();

            let id  = this.dataset.id;
            let url = this.dataset.url;

            // fallback kalau helper tidak ada
            if (typeof deleteData !== 'undefined') {

                deleteData(
                    url,
                    'Yakin ingin memindahkan data ke arsip?',
                    {
                        onSuccess: function () {

                            let row = document.getElementById('row-' + id);

                            if (row) row.remove();

                            let alertBox = document.getElementById('alertBox');

                            alertBox.innerHTML = `
                                <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                                    Guru berhasil dipindahkan ke arsip
                                </div>
                            `;

                            setTimeout(() => {
                                alertBox.innerHTML = '';
                            }, 3000);
                        }
                    }
                );

            } else {
                alert('deleteData() tidak ditemukan. Pastikan script helper sudah dimuat.');
            }

        });

    });

});
</script>
@endpush