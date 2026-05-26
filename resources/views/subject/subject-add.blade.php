@extends('layouts.app')

@section('title', 'Tambah Subject')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    {{-- BREADCRUMB --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6">

        <a href="{{ route('dashboard') }}"
           class="hover:text-blue-600 transition">
            Dashboard
        </a>

        <span class="mx-2">/</span>

        <a href="{{ route('subjects.index') }}"
           class="hover:text-blue-600 transition">
            Subject
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Tambah Subject
        </span>

    </nav>

    <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b bg-gray-50">
            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Subject
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Isi data mata pelajaran dengan lengkap
            </p>
        </div>

        {{-- BODY --}}
        <div class="p-6">

            <form id="formSubject"
                  autocomplete="off"
                  class="space-y-5">

                @csrf

                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Subject
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="firstInput"
                        name="name"
                        required
                        placeholder="Masukkan nama subject"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- CODE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="code"
                        required
                        placeholder="Contoh: MTK001"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- DESCRIPTION --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Masukkan deskripsi subject (opsional)"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('subjects.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AUTO FOCUS INPUT PERTAMA
    document.getElementById('firstInput')?.focus();

    if (typeof createData !== 'undefined') {

        createData(
            '#formSubject',
            "{{ route('subjects.store') }}",
            {
                onSuccess: function () {
                    window.location.href =
                        "{{ route('subjects.index') }}";
                }
            }
        );

    } else {
        console.error('createData tidak ditemukan');
    }

});
</script>
@endpush