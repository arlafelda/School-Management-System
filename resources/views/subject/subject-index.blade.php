@extends('layouts.app')

@section('title', 'Data Subject')

@section('content')

<div class="space-y-6">

    {{-- BREADCRUMB --}}
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center space-x-2">

            <li>
                <a href="{{ route('dashboard') }}"
                   class="hover:text-blue-600 transition">
                    Dashboard
                </a>
            </li>

            <li>/</li>

            <li class="text-gray-700 font-medium">
                Subject
            </li>

        </ol>
    </nav>


    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold">
                Data Subject
            </h2>

            <p class="text-gray-500 text-sm">
                Kelola data mata pelajaran
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('subjects.archived') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Archived
            </a>

            <a href="{{ route('subjects.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Add Subject
            </a>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow border overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Code</th>
                    <th class="p-4 text-left">KKM</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($subjects as $key => $subject)
                <tr id="row-{{ $subject->slug }}"
                    class="border-t hover:bg-gray-50">

                    <td class="p-4">
                        {{ $key + 1 }}
                    </td>

                    <td class="p-4">
                        {{ $subject->name }}
                    </td>

                    <td class="p-4">
                        {{ $subject->code }}
                    </td>

                    <td class="p-4">
                        {{ $subject->kkm }}
                    </td>

                    <td class="p-4 text-center space-x-3">

                        {{-- EDIT --}}
                        <a href="{{ route('subjects.edit', $subject->slug) }}"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        {{-- ARCHIVE --}}
                        <button
                            type="button"
                            class="text-red-600 btn-delete"
                            data-slug="{{ $subject->slug }}"
                            data-url="{{ route('subjects.destroy', $subject->slug) }}">
                            Archive
                        </button>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5"
                        class="p-6 text-center text-gray-500">
                        No data found
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
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function(e){

        let btn = e.target.closest('.btn-delete');
        if (!btn) return;

        let slug = btn.dataset.slug;
        let url  = btn.dataset.url;

        deleteData(
            url,
            'Yakin ingin mengarsipkan subject ini?',
            {
                onSuccess: function () {
                    document
                        .getElementById('row-' + slug)
                        ?.remove();
                }
            }
        );

    });

});
</script>
@endpush