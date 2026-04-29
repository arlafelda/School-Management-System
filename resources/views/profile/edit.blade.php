@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <div class="bg-white border-b px-6 py-4 flex justify-between items-center">

        <h2 class="font-semibold text-xl text-gray-800">
            Profile
        </h2>

        <a href="{{ route('dashboard.superadmin') }}"
            class="text-sm bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
            ← Kembali
        </a>

    </div>

    <!-- CONTENT -->
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- UPDATE PROFILE -->
        <div class="bg-white border shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- UPDATE PASSWORD -->
        <div class="bg-white border shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- DELETE USER -->
        <div class="bg-white border shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

</div>

@endsection