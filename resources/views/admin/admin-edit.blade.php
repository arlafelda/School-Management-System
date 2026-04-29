@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="min-h-screen bg-gray-100">

    <!-- CONTENT -->
    <main class="p-4 md:p-8">

        <div class="max-w-2xl mx-auto">

            <!-- TITLE -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Edit Admin</h1>
                <p class="text-gray-500 text-sm">Perbarui data admin</p>
            </div>

            <!-- CARD -->
            <div class="bg-white rounded-xl shadow border p-6 md:p-8">

                <h1 class="text-xl font-bold mb-6">Form Edit Admin</h1>

                <!-- ERROR VALIDATION -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-600 p-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM AJAX -->
                <form id="editForm" class="space-y-4">
                    @csrf

                    <!-- NAMA -->
                    <div>
                        <label class="block text-sm font-medium">Nama</label>
                        <input type="text" name="name"
                            value="{{ $admin->name }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email"
                            value="{{ $admin->email }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-medium">
                            Password (kosongkan jika tidak diubah)
                        </label>
                        <input type="password" name="password"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- KONFIRMASI -->
                    <div>
                        <label class="block text-sm font-medium">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-3 pt-4">

                        <a href="{{ route('admin.index') }}"
                            class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update
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
    // pakai function dari ajax.js
    updateData('#editForm', "{{ route('admin.update', $admin->id) }}");
</script>

@endpush