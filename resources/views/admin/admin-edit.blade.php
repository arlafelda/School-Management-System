@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="min-h-screen bg-gray-100">

    <main class="p-4 md:p-8">

        <div class="max-w-2xl mx-auto">

            <!-- BREADCRUMB -->
            <nav class="text-sm text-gray-500 mb-4">

                <a href="{{ route('admin.index') }}"
                   class="hover:text-blue-600">
                    Admin
                </a>

                <span class="mx-2">/</span>

                <a href="{{ route('admin.show', $admin->slug) }}"
                   class="hover:text-blue-600">
                    {{ $admin->name }}
                </a>

                <span class="mx-2">/</span>

                <span class="text-gray-700 font-medium">
                    Edit
                </span>

            </nav>


            <!-- TITLE -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold">
                    Edit Admin
                </h1>

                <p class="text-gray-500 text-sm">
                    Perbarui data admin
                </p>
            </div>


            <!-- ALERT -->
            <div id="alertBox"
                 class="hidden mb-4 p-3 rounded text-sm">
            </div>


            <!-- CARD -->
            <div class="bg-white rounded-xl shadow border p-6 md:p-8">

                <h2 class="text-lg font-semibold mb-6">
                    Form Edit Admin
                </h2>


                <form id="editForm"
                      method="POST"
                      action="{{ route('admin.update', $admin->slug) }}"
                      class="space-y-4">

                    @csrf
                    @method('PUT')


                    <div>
                        <label class="block text-sm font-medium">
                            Nama
                        </label>

                        <input type="text"
                               id="firstInput"
                               name="name"
                               value="{{ $admin->name }}"
                               required
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ $admin->email }}"
                               required
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium">
                            Password (opsional)
                        </label>

                        <input type="password"
                               name="password"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>


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
document.addEventListener(
    'DOMContentLoaded',
    function () {

        document
            .getElementById('firstInput')
            ?.focus();

        if (
            typeof window.updateData
            === 'function'
        ) {

            window.updateData(
                '#editForm',
                "{{ route('admin.update', $admin->slug) }}",
                {
                    onSuccess: function(res) {

                        let alertBox =
                            document.getElementById(
                                'alertBox'
                            );

                        alertBox.classList.remove(
                            'hidden'
                        );

                        alertBox.className =
                            "mb-4 p-3 rounded text-sm bg-green-100 text-green-700";

                        alertBox.innerText =
                            res.message;

                        let slug =
                            res.slug
                            ?? "{{ $admin->slug }}";

                        setTimeout(() => {
                            window.location.href =
                                `/admin/${slug}`;
                        }, 800);
                    },

                    onError: function(err) {

                        let alertBox =
                            document.getElementById(
                                'alertBox'
                            );

                        alertBox.classList.remove(
                            'hidden'
                        );

                        alertBox.className =
                            "mb-4 p-3 rounded text-sm bg-red-100 text-red-700";

                        alertBox.innerText =
                            err.error
                            || 'Terjadi kesalahan';
                    }
                }
            );

        } else {
            console.error(
                'updateData belum tersedia'
            );
        }
    }
);
</script>
@endpush