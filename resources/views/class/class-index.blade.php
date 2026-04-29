@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-blue-700 font-[Manrope]">
                Master Data Kelas
            </h1>
            <p class="text-sm text-gray-500">
                Kelola data kelas sekolah
            </p>
        </div>

        <a href="{{ route('class.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            + Tambah Kelas
        </a>

    </header>

    <!-- CONTENT -->
    <main class="p-6">

        <!-- ALERT AJAX -->
        <div id="alertBox"></div>

        <!-- TABLE -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Nama Kelas</th>
                        <th class="p-4 text-center">Tingkat</th>
                        <th class="p-4 text-center">Jurusan</th>
                        <th class="p-4 text-left">Wali Kelas</th>
                        <th class="p-4 text-center">Jumlah Siswa</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($classes as $class)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold">
                            <a href="{{ route('class.show', $class->id) }}"
                               class="text-blue-600 hover:underline">
                                {{ $class->name }}
                            </a>
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

                        <td class="p-4 text-center space-x-3">

                            <a href="{{ route('class.edit', $class->id) }}"
                               class="text-blue-600 hover:underline text-sm">
                                Edit
                            </a>

                            <!-- 🔥 DELETE AJAX -->
                            <form action="{{ route('class.delete', $class->id) }}"
                                  method="POST"
                                  class="inline formDelete">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-500 hover:underline text-sm">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            Data kelas belum tersedia
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

    // ❗ Pastikan jQuery sudah load
    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    // 🔥 DELETE AJAX GLOBAL
    $(document).on('submit', '.formDelete', function (e) {
        e.preventDefault();

        if (!confirm("Yakin ingin menghapus data ini?")) return;

        let url = this.action;
        let row = $(this).closest('tr');

        // ❗ Pastikan function ajax ada
        if (typeof window.deleteData === 'function') {

            window.deleteData(url, function () {

                // hapus row tabel
                row.remove();

                // tampilkan alert
                $('#alertBox').html(`
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        Data kelas berhasil dihapus
                    </div>
                `);

            });

        } else {
            console.error('deleteData belum tersedia (ajax.js belum ke-load)');
        }

    });

});
</script>
@endpush