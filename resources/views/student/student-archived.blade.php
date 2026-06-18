@extends('layouts.app')

@section('title', 'Arsip Siswa')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>Dashboard</li>
            <li>/</li>
            <li>Student</li>
            <li>/</li>
            <li class="font-semibold text-gray-700">Arsip</li>
        </ol>
    </nav>

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Arsip Siswa</h1>

        <a href="{{ route('students.index') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            ← Kembali
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[800px]">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-center">NIS</th>
                    <th class="p-4 text-center">Kelas</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($students as $student)
                <tr id="row-{{ $student->slug }}" class="border-b hover:bg-gray-50">

                    <td class="p-4">
                        <p class="font-semibold">{{ $student->name }}</p>
                        <p class="text-xs text-gray-500">{{ $student->user->email ?? '-' }}</p>
                    </td>

                    <td class="p-4 text-center">
                        {{ $student->nis }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $student->class->name ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Archived
                        </span>
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- RESTORE -->
                            <button
                                class="btn-restore bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                data-slug="{{ $student->slug }}"
                                data-url="{{ route('students.restore', $student->slug) }}">
                                Restore
                            </button>

                            <!-- HAPUS PERMANEN -->
                            <button
                                class="btn-force-delete bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                data-slug="{{ $student->slug }}"
                                data-url="{{ route('students.forceDelete', $student->slug) }}">
                                Hapus Permanen
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
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

    document.addEventListener('click', function (e) {

        // =====================
        // RESTORE
        // =====================
        document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-restore');
        if (!btn) return;

        const id  = btn.dataset.id;
        const url = btn.dataset.url;

        if (typeof restoreData === 'undefined') {
            console.error('restoreData belum tersedia');
            return;
        }

        restoreData(
            url,
            'Restore data ini?',
            {
                onSuccess: function () {
                    document.getElementById('row-' + id)?.remove();
                }
            }
        );
    });
        // =====================
        // HAPUS PERMANEN
        // =====================
        let btnDelete = e.target.closest('.btn-force-delete');
        if (btnDelete) {

            let url = btnDelete.dataset.url;
            let slug = btnDelete.dataset.slug;
            let row = document.getElementById('row-' + slug);

            if (!confirm('Hapus permanen data siswa ini? Tindakan ini tidak bisa dibatalkan!')) return;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (row) row.remove();

                    document.getElementById('alertBox').innerHTML = `
                        <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                            Data siswa berhasil dihapus permanen
                        </div>
                    `;

                    setTimeout(() => {
                        document.getElementById('alertBox').innerHTML = '';
                    }, 3000);
                }
            })
            .catch(err => console.error(err));

        }

    });

});
</script>
@endpush