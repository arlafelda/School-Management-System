@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <div class="px-6 pt-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>
        <span class="mx-2">/</span>
        <span class="text-gray-700 font-medium">
            Ekstrakurikuler
        </span>
    </div>

    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-xl font-bold text-blue-700">
                Ekstrakurikuler
            </h1>
            <p class="text-sm text-gray-500">
                Kelola data ekskul
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('extracurricular.archived') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Archived
            </a>

            <a href="{{ route('extracurricular.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah
            </a>

        </div>

    </header>

    <main class="p-6">

        <div id="alertBox" class="mb-4"></div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nama Ekskul</th>
                        <th class="p-3 text-center">Pembina</th>
                        <th class="p-3 text-right">Jumlah Siswa</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $d)

                        <tr
                            id="row-{{ $d->id }}"
                            class="border-t hover:bg-gray-50 cursor-pointer"
                            data-url="{{ $d->slug ? route('extracurricular.show', $d->slug) : '#' }}"
                        >

                            <td class="p-3 font-medium">
                                @if($d->slug)
                                    {{ $d->name }}
                                @else
                                    <span class="text-red-500">
                                        {{ $d->name }} (slug kosong)
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                {{ $d->teacher->name ?? '-' }}
                            </td>

                            <td class="p-3 text-right">
                                {{ $d->students->count() }}
                            </td>

                            <td class="p-3 text-center space-x-2">

                                @if($d->slug)

                                    <a href="{{ route('extracurricular.edit', $d->slug) }}"
                                       onclick="event.stopPropagation()"
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </a>

                                    <form action="{{ route('extracurricular.destroy', $d->slug) }}"
                                          method="POST"
                                          class="inline formDelete"
                                          data-id="{{ $d->id }}"
                                          onclick="event.stopPropagation()">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Archive
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">
                                Tidak ada data ekstrakurikuler
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

    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    $(document).on('click', 'tr[data-url]', function (e) {

        if (
            e.target.closest('a') ||
            e.target.closest('button') ||
            e.target.closest('form')
        ) return;

        let url = $(this).data('url');

        if (url && url !== '#') {
            window.location.href = url;
        }

    });

    $(document).on('submit', '.formDelete', function (e) {
        e.preventDefault();

        if (!confirm('Yakin ingin memindahkan data ke arsip?')) return;

        let url = this.action;
        let id = $(this).data('id');
        let row = $('#row-' + id);

        if (typeof window.deleteData === 'function') {

            window.deleteData(url, function () {

                row.remove();

                $('#alertBox').html(`
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        Data berhasil dipindahkan ke arsip
                    </div>
                `);

            });

        } else {
            console.error('deleteData belum tersedia');
        }

    });

});
</script>
@endpush