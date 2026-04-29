@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100">

    <!-- CONTENT -->
    <main class="p-4 md:p-8">

        <div class="max-w-2xl mx-auto">

            <!-- TITLE -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Tambah Admin</h1>
                <p class="text-gray-500 text-sm">Isi data admin baru</p>
            </div>

            <!-- CARD FORM -->
            <div class="bg-white p-6 md:p-8 rounded-xl shadow border">

                <form id="addForm" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Nama Lengkap *</label>
                            <input type="text" name="name"
                                class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Email *</label>
                            <input type="email" name="email"
                                class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Password *</label>
                            <input type="password" name="password"
                                class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Status</label>
                            <select name="archived"
                                class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="0">Aktif</option>
                                <option value="1">Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-3 pt-4">

                        <a href="{{ route('admin.index') }}"
                            class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // cek function ada atau tidak
    if (typeof window.createData === 'function') {

        window.createData('#addForm', "{{ route('admin.store') }}");

    } else {
        console.error('createData belum tersedia (ajax.js belum ke-load)');
    }

});
</script>
@endpush