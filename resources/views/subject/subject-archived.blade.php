@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Archived Subjects
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                List subject yang sudah diarsipkan
            </p>
        </div>

        <a href="{{ route('subjects.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

            Kembali
        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-700">
                Archived Subject Table
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Subject Name</th>
                        <th class="px-6 py-4">Subject Code</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($subjects as $subject)

                    <tr id="subject-row-{{ $subject->slug }}"
                        class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $subject->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $subject->code }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-center gap-2">

                                <button
                                    type="button"
                                    data-url="{{ route('subjects.restore', $subject->slug) }}"
                                    data-slug="{{ $subject->slug }}"
                                    class="btn-restore px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg">
                                    Restore
                                </button>

                                <button
                                    type="button"
                                    data-url="{{ route('subjects.delete', $subject->slug) }}"
                                    data-slug="{{ $subject->slug }}"
                                    class="btn-delete px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg">
                                    Delete
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-10 text-center text-gray-500">
                            Tidak ada archived subject
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {

        /*
        =====================
        RESTORE
        =====================
        */
        if (e.target.classList.contains('btn-restore')) {

            let url  = e.target.dataset.url;
            let slug = e.target.dataset.slug;

            restoreData(
                url,
                'Yakin ingin restore subject ini?',
                {
                    onSuccess: function () {
                        document
                            .getElementById('subject-row-' + slug)
                            ?.remove();
                    }
                }
            );
        }

        /*
        =====================
        DELETE
        =====================
        */
        if (e.target.classList.contains('btn-delete')) {

            let url  = e.target.dataset.url;
            let slug = e.target.dataset.slug;

            deleteData(
                url,
                'Hapus permanen subject ini?',
                {
                    onSuccess: function () {
                        document
                            .getElementById('subject-row-' + slug)
                            ?.remove();
                    }
                }
            );
        }

    });

});
</script>
@endpush