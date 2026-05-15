@extends('layouts.app')

@section('title', 'Archived Classes')

@section('content')

@php
$user = auth()->user();
@endphp

<div class="min-h-screen bg-gray-100 text-gray-800">

    <div class="px-6 pt-4 text-sm text-gray-500">
        <a href="{{ route('class.index') }}" class="hover:text-blue-600">
            Kelas
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Archived
        </span>
    </div>

    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-red-600">
                Kelas Diarsipkan
            </h1>

            <p class="text-sm text-gray-500">
                Daftar kelas yang telah diarsipkan
            </p>
        </div>

        <a href="{{ route('class.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            ← Kembali
        </a>

    </header>

    <main class="p-6">

        <div id="alertBox"></div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Nama Kelas</th>
                        <th class="p-4 text-center">Tingkat</th>
                        <th class="p-4 text-center">Jurusan</th>
                        <th class="p-4 text-left">Wali Kelas</th>
                        <th class="p-4 text-center">Jumlah Siswa</th>

                        @if(in_array($user->role, ['super_admin', 'admin']))
                        <th class="p-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($classes as $class)

                    <tr id="row-{{ $class->id }}" class="hover:bg-gray-50">

                        <td class="p-4 font-semibold text-gray-700">
                            {{ $class->name }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->level }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->major ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $class->teacher->name ?? 'Belum ada wali kelas' }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $class->students->count() }}
                        </td>

                        @if(in_array($user->role, ['super_admin', 'admin']))
                        <td class="p-4 text-center">

                            <form action="{{ route('class.restore', $class->slug) }}"
                                  method="POST"
                                  class="inline formRestore">

                                @csrf
                                @method('PUT')

                                <button type="submit"
                                    class="text-green-600 hover:underline text-sm">
                                    Restore
                                </button>

                            </form>

                        </td>
                        @endif

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center p-6 text-gray-500">
                            Tidak ada data archived
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    $(document).on('submit', '.formRestore', function (e) {

        e.preventDefault();

        if (!confirm('Yakin ingin restore data ini?')) return;

        let url = this.action;
        let row = $(this).closest('tr');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                row.remove();

                $('#alertBox').html(`
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        ${res.message}
                    </div>
                `);
            }
        });

    });

});
</script>
@endpush