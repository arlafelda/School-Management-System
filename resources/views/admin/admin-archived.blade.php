@extends('layouts.app')

@section('title', 'Arsip Admin')

@section('content')

<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('dashboard.super_admin') }}" class="hover:text-blue-600">
                    Dashboard
                </a>
            </li>
            <li>/</li>
            <li>
                <a href="{{ route('admin.index') }}" class="hover:text-blue-600">
                    Admin
                </a>
            </li>
            <li>/</li>
            <li class="text-gray-700 font-medium">
                Arsip
            </li>
        </ol>
    </nav>

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Arsip Admin</h1>

        <a href="{{ route('admin.index') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            ← Kembali
        </a>
    </div>

    <!-- ALERT -->
    <div id="alertBox"></div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm min-w-[700px]">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-center">Role</th>
                    <th class="p-4 text-center">ID</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($admins as $user)

                <tr id="row-{{ $user->id }}" class="border-b hover:bg-gray-50">

                    <!-- USER -->
                    <td class="p-4">
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $user->email }}</p>
                    </td>

                    <!-- ROLE -->
                    <td class="p-4 text-center">
                        {{ ucfirst($user->role) }}
                    </td>

                    <!-- ID -->
                    <td class="p-4 text-center">
                        #{{ $user->id }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-4 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                            Archived
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="p-4 text-center">

                        <div class="flex justify-center gap-2">

                            <!-- RESTORE -->
                            <button
                                class="btn-restore bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700"
                                data-id="{{ $user->id }}"
                                data-slug="{{ $user->slug }}">
                                Restore
                            </button>

                            <!-- DELETE -->
                            <button
                                class="btn-delete bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                data-id="{{ $user->id }}"
                                data-slug="{{ $user->slug }}">
                                Delete
                            </button>

                        </div>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-500">
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

    // =====================
    // RESTORE ADMIN
    // =====================
    document.querySelectorAll('.btn-restore').forEach(btn => {

        btn.addEventListener('click', function () {

            const id = this.dataset.id;
            const slug = this.dataset.slug;

            restoreData(
                `/admin/${slug}/restore`,
                "Restore data ini?",
                {
                    onSuccess: function () {
                        const row = document.getElementById('row-' + id);
                        if (row) row.remove();
                    }
                }
            );

        });

    });

    // =====================
    // DELETE ADMIN
    // =====================
    document.querySelectorAll('.btn-delete').forEach(btn => {

        btn.addEventListener('click', function () {

            const id = this.dataset.id;
            const slug = this.dataset.slug;

            deleteData(
                `/admin/${slug}`,
                "Hapus permanen data ini? Data tidak bisa dikembalikan!",
                {
                    onSuccess: function () {
                        const row = document.getElementById('row-' + id);
                        if (row) row.remove();
                    }
                }
            );

        });

    });

});
</script>

@endpush