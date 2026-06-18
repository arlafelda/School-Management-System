@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-4xl">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('teacher.index') }}" class="hover:text-indigo-600 transition">Kelola Guru</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Edit Guru</span>
        </div>

        {{-- CARD --}}
        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">

            <div class="bg-white p-8">

                {{-- TITLE --}}
                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-gray-800">Edit Informasi Guru</h1>
                    <p class="text-sm text-gray-400 mt-1">Perbarui data guru di bawah ini.</p>
                </div>

                {{-- ALERT --}}
                <div id="alertBox" class="hidden mb-5 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

                {{-- FORM --}}
                <form id="formEditGuru"
                      action="{{ route('teacher.update', $teacher->slug) }}"
                      method="POST"
                      class="space-y-5">

                    @csrf
                    @method('PUT')

                    {{-- NAMA LENGKAP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $teacher->name) }}"
                            placeholder="Masukkan nama lengkap guru"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            required>
                    </div>

                    {{-- NIP + NO HP --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="nip"
                                value="{{ old('nip', $teacher->nip) }}"
                                placeholder="Masukkan NIP guru"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                No. HP
                            </label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $teacher->phone) }}"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                    </div>

                    {{-- EMAIL + PASSWORD --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email
                            </label>
                            <input
                                type="email"
                                value="{{ $teacher->user->email ?? '-' }}"
                                disabled
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                            <small class="text-xs text-gray-400 mt-1 block">Email tidak dapat diubah.</small>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password Baru
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                    </div>

                    {{-- JABATAN --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="position"
                            required
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="guru"       {{ $teacher->position == 'guru'       ? 'selected' : '' }}>Guru</option>
                            <option value="wali_kelas" {{ $teacher->position == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                        </select>
                    </div>

                    {{-- MATA PELAJARAN --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Mata Pelajaran
                        </label>

                        <div id="subject-wrapper" class="space-y-2">

                            @forelse($teacher->subjects as $subject)
                                <div class="flex gap-2 subject-row">
                                    <select name="subject_ids[]"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}" {{ $subject->id == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button"
                                        class="remove-subject px-3 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-xl bg-red-50 hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </div>
                            @empty
                                <div class="flex gap-2 subject-row">
                                    <select name="subject_ids[]"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button"
                                        class="remove-subject hidden px-3 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-xl bg-red-50 hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </div>
                            @endforelse

                        </div>

                        <button type="button" id="add-subject"
                            class="mt-2 inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Tambah Mata Pelajaran
                        </button>
                    </div>

                    {{-- DIVIDER --}}
                    <hr class="border-gray-100">

                    {{-- BUTTONS --}}
                    <div class="flex justify-end items-center gap-3 pt-1">

                        <a href="{{ route('teacher.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const wrapper = document.getElementById('subject-wrapper');
    const addBtn = document.getElementById('add-subject');

    // ➕ tambah mapel
    addBtn.addEventListener('click', function () {

        const row = document.createElement('div');
        row.classList.add('flex', 'gap-2', 'subject-row');

        row.innerHTML = `
            <select name="subject_ids[]" class="w-full border p-2 rounded">
                <option value="">Pilih Mata Pelajaran</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <button type="button" class="remove-subject px-3 bg-red-500 text-white rounded">
                X
            </button>
        `;

        wrapper.appendChild(row);
    });

    // ❌ hapus mapel
    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-subject')) {
            e.target.closest('.subject-row').remove();
        }
    });

});
</script>
@endpush

