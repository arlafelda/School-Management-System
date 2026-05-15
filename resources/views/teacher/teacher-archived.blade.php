@extends('layouts.app')

@section('title', 'Arsip Guru')

@section('content')

<div class="space-y-6">

    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('teacher.index') }}"
                   class="text-blue-600 hover:underline">
                    Guru
                </a>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">
                Arsip
            </li>
        </ol>
    </nav>

    <div id="alertBox"></div>

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold">Arsip Guru</h1>
            <p class="text-gray-500 text-sm">
                Data guru yang telah diarsipkan
            </p>
        </div>

        <a href="{{ route('teacher.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            Kembali
        </a>

    </div>

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
                    class="hover:bg-gray-50">

                    <td class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-500 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>

                        <div class="font-semibold text-gray-700">
                            {{ $teacher->name }}
                        </div>
                    </td>

                    <td class="p-4 text-center">
                        {{ $teacher->nip }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $teacher->subject }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $teacher->position }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $teacher->phone }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $teacher->user->email ?? '-' }}
                    </td>

                    <td class="p-4 text-center">

                        <button
                            class="text-green-600 text-sm btn-restore"
                            data-id="{{ $teacher->id }}"
                            data-url="{{ route('teacher.restore', $teacher->slug) }}">
                            Restore
                        </button>

                    </td>

                </tr>

                @empty
                    <tr>
                        <td colspan="7"
                            class="text-center p-6 text-gray-500">
                            Tidak ada data arsip
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
document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener('click', function(e){

        let btn = e.target.closest('.btn-restore');
        if(!btn) return;

        let url = btn.dataset.url;
        let id = btn.dataset.id;
        let row = document.getElementById('row-' + id);

        if(!confirm('Restore data ini?')) return;

        fetch(url,{
            method:'PUT',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept':'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {

            if(data.success){

                if(row) row.remove();

                document.getElementById('alertBox').innerHTML = `
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        Guru berhasil direstore
                    </div>
                `;
            }

        });

    });

});
</script>
@endpush