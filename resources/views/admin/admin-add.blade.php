@extends('layouts.app')

@section('title', 'Tambah Admin')

@section('content')

<div class="min-h-screen bg-gray-100">

    <main class="p-4 md:p-8">

        <!-- BREADCRUMB -->
        <div class="mb-4 text-sm text-gray-500">
            <a href="{{ route('dashboard.super_admin') }}"
               class="hover:text-blue-600">
                Dashboard
            </a>

            <span class="mx-2">/</span>

            <a href="{{ route('admin.index') }}"
               class="hover:text-blue-600">
                Kelola Admin
            </a>

            <span class="mx-2">/</span>

            <span class="text-gray-700 font-medium">
                Tambah Admin
            </span>
        </div>

        <div class="max-w-2xl mx-auto">

            <!-- TITLE -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Tambah Admin</h1>
                <p class="text-gray-500 text-sm">Isi data admin baru</p>
            </div>

            <!-- ALERT -->
            <div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

            <!-- CARD FORM -->
            <div class="bg-white p-6 md:p-8 rounded-xl shadow border">

                <form id="addForm" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- INPUT PERTAMA (AUTO-FOCUS TARGET) -->
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Nama Lengkap *</label>
                            <input
                                type="text"
                                name="name"
                                id="firstInput"
                                class="mt-1 w-full px-4 py-2 border rounded-lg"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Email *</label>
                            <input type="email"
                                   name="email"
                                   class="mt-1 w-full px-4 py-2 border rounded-lg"
                                   required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Password *</label>
                            <input type="password"
                                   name="password"
                                   class="mt-1 w-full px-4 py-2 border rounded-lg"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Status</label>
                            <select name="archived"
                                    class="mt-1 w-full px-4 py-2 border rounded-lg">
                                <option value="0">Aktif</option>
                                <option value="1">Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 pt-4">

                        <a href="{{ route('admin.index') }}"
                           class="px-5 py-2 border rounded-lg">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 text-white rounded-lg">
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

    // AUTO-FOCUS SAAT HALAMAN DIBUKA
    document.getElementById('firstInput')?.focus();

    if (typeof window.createData === 'function') {

        window.createData('#addForm', "{{ route('admin.store') }}", {
            onSuccess: function (data) {

                let alertBox = document.getElementById('alertBox');

                alertBox.classList.remove('hidden');
                alertBox.className = "mb-4 p-3 rounded text-sm bg-green-100 text-green-700";
                alertBox.innerText = data.message;

                document.getElementById('addForm').reset();

                // AUTO-FOCUS LAGI SETELAH RESET
                document.getElementById('firstInput')?.focus();
            },

            onError: function (err) {

                let alertBox = document.getElementById('alertBox');

                alertBox.classList.remove('hidden');
                alertBox.className = "mb-4 p-3 rounded text-sm bg-red-100 text-red-700";
                alertBox.innerText = err.error || 'Terjadi kesalahan';
            }
        });

    } else {
        console.error('ajax.js belum ke-load!');
    }

});
</script>
@endpush