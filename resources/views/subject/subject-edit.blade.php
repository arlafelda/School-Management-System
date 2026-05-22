@extends('layouts.app')

@section('title', 'Edit Subject')

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
            Edit Subject
        </span>

    </nav>


    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b bg-gray-50">

            <h1 class="text-2xl font-bold text-gray-800">
                Edit Subject
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Perbarui data mata pelajaran
            </p>

        </div>


        <div class="p-6">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">

                    <div class="text-sm text-red-700 font-medium mb-2">
                        Terjadi kesalahan:
                    </div>

                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            {{-- FORM --}}
            <form id="formEditSubject"
                  autocomplete="off"
                  class="space-y-5">

                @csrf

                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Subject
                    </label>

                    <input
                        type="text"
                        id="firstInput"
                        name="name"
                        value="{{ old('name', $subject->name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>


                {{-- CODE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', $subject->code) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>


                {{-- DESCRIPTION --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $subject->description) }}</textarea>
                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('subjects.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                        Kembali
                    </a>

                    <button type="submit"
                            class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                        Update
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

    document.getElementById('firstInput')?.focus();

    if (typeof updateData !== 'undefined') {

        updateData(
            '#formEditSubject',
            "{{ route('subjects.update', $subject->slug) }}",
            {
                onSuccess: function () {
                    window.location.href =
                        "{{ route('subjects.index') }}";
                }
            }
        );

    } else {
        console.error('updateData tidak ditemukan');
    }

});
</script>
@endpush