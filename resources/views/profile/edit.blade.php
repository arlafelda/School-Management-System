@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">

        <!-- BREADCRUMB -->
        <div class="mb-6 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span class="text-gray-700 font-medium">Profile</span>
        </div>

        <!-- HERO HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 tracking-tight">Profile Saya</h1>
                <p class="text-gray-500 mt-2 text-lg">Kelola informasi akun dan keamanan Anda</p>
            </div>

            <a href="{{ route('dashboard.super_admin') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-2xl hover:border-gray-300 hover:shadow transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="space-y-8">

            <!-- PROFILE INFORMATION -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl">👤</div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Informasi Pribadi</h3>
                        <p class="text-gray-500 text-sm">Perbarui data profil dan foto Anda</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="max-w-2xl mx-auto">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl">🔒</div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Keamanan Akun</h3>
                        <p class="text-gray-500 text-sm">Ubah password secara berkala</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="max-w-2xl mx-auto">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- DELETE ACCOUNT -->
            <div class="bg-white rounded-3xl shadow-xl shadow-red-100/50 border border-red-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
                <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-3xl">🗑️</div>
                    <div>
                        <h3 class="text-xl font-semibold text-red-700">Hapus Akun</h3>
                        <p class="text-red-600/80 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="max-w-2xl mx-auto">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection