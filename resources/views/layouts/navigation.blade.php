@php
$user = auth()->user();

function active($routes)
{
    return request()->routeIs($routes)
        ? 'bg-[#29ABE2]/10 text-[#1C7FAE] font-semibold'
        : 'text-[#10243A] hover:bg-[#F7FAFC]';
}
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white border-r border-[#E7EEF3] z-40
    transform -translate-x-full md:translate-x-0 transition-transform duration-300">

    <div class="p-6 border-b border-[#E7EEF3] flex items-center gap-2.5">
        <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-9 w-9 object-contain">
        <div>
            <h1 class="font-extrabold text-sm tracking-tight leading-tight">
                <span class="text-[#29ABE2]">GAMELAB</span> <span class="text-[#10243A]">INDONESIA</span>
            </h1>
            <p class="text-xs text-[#62788A] capitalize mt-0.5">{{ $user->role }}</p>
        </div>
    </div>

    <nav class="p-4 space-y-1 text-sm">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard.' . $user->role) }}"
            class="flex items-center gap-2.5 p-3 rounded-lg transition-colors {{ active('dashboard.*') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Dashboard
        </a>

        <!-- USER MANAGEMENT -->
        @if(in_array($user->role, ['super_admin', 'admin']))
        <div>
            <button type="button" onclick="toggleMenu('userMenu','arrowUser')"
                class="w-full flex justify-between items-center gap-2.5 p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    User Management
                </span>
                <span id="arrowUser" class="transition-transform text-[#62788A] text-xs {{ request()->routeIs('admin.*','teacher.*','students.*') ? 'rotate-180' : '' }}">▼</span>
            </button>
            <div id="userMenu" class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3 {{ request()->routeIs('admin.*','teacher.*','students.*') ? '' : 'hidden' }}">
                @if($user->role === 'super_admin')
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('admin.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Admin
                </a>
                @endif
                <a href="{{ route('teacher.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('teacher.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Teacher
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('students.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    Students
                </a>
            </div>
        </div>
        @endif

        <!-- MASTER DATA -->
        @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
        <div>
            <button type="button" onclick="toggleMenu('masterMenu','arrowMaster')"
                class="w-full flex justify-between items-center gap-2.5 p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                    </svg>
                    Master Data
                </span>
                <span id="arrowMaster" class="transition-transform text-[#62788A] text-xs {{ request()->routeIs('class.*','extracurricular.*') ? 'rotate-180' : '' }}">▼</span>
            </button>
            <div id="masterMenu" class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3 {{ request()->routeIs('class.*','extracurricular.*') ? '' : 'hidden' }}">
                <a href="{{ route('class.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('class.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    Kelas
                </a>
                <a href="{{ route('extracurricular.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('extracurricular.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Ekstrakurikuler
                </a>
            </div>
        </div>
        @endif

        <!-- AKADEMIK -->
        @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
        <div>
            <button type="button" onclick="toggleMenu('akademikMenu','arrowAkademik')"
                class="w-full flex justify-between items-center gap-2.5 p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Akademik
                </span>
                <span id="arrowAkademik" class="transition-transform text-[#62788A] text-xs {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*','teacher.homeroom.students') ? 'rotate-180' : '' }}">▼</span>
            </button>
            <div id="akademikMenu" class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3 {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*','teacher.homeroom.students') ? '' : 'hidden' }}">
                <a href="{{ route('schedule.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('schedule.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Jadwal
                </a>
                <a href="{{ route('grades.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('grades.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    Nilai
                </a>
                <a href="{{ route('attendance.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('attendance.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/>
                    </svg>
                    Absensi
                </a>
                <a href="{{ route('subjects.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('subjects.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                    Mata Pelajaran
                </a>
                @if($user->role === 'teacher' && $user->teacher && \App\Models\ClassModel::where('teacher_id', $user->teacher->id)->exists())
                <a href="{{ route('teacher.homeroom.students') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('teacher.homeroom.students') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Siswa Kelas Saya
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- STUDENT MENU -->
        @if($user->role === 'student')
        <div>
            <button type="button" onclick="toggleMenu('studentMenu','arrowStudent')"
                class="w-full flex justify-between items-center gap-2.5 p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Student Menu
                </span>
                <span id="arrowStudent" class="transition-transform text-[#62788A] text-xs {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? 'rotate-180' : '' }}">▼</span>
            </button>
            <div id="studentMenu" class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3 {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? '' : 'hidden' }}">
                <a href="{{ route('grades.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('grades.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    Nilai Saya
                </a>
                <a href="{{ route('attendance.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('attendance.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/>
                    </svg>
                    Absensi Saya
                </a>
                <a href="{{ route('schedule.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('schedule.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Jadwal Kelas
                </a>
                <a href="{{ route('student.extracurricular') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('student.extracurricular') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Ekstrakurikuler
                </a>
                <a href="{{ route('student.raport') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('student.raport') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Rapot Saya
                </a>
            </div>
        </div>
        @endif

        <!-- SISTEM -->
        <div>
            <button type="button" onclick="toggleMenu('systemMenu','arrowSystem')"
                class="w-full flex justify-between items-center gap-2.5 p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                    Sistem
                </span>
                <span id="arrowSystem" class="transition-transform text-[#62788A] text-xs {{ request()->routeIs('profile.*','announcement.*','activity-log.*') ? 'rotate-180' : '' }}">▼</span>
            </button>
            <div id="systemMenu" class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3 {{ request()->routeIs('profile.*','announcement.*','activity-log.*') ? '' : 'hidden' }}">

                {{-- Pengumuman --}}
                @if(in_array($user->role, ['super_admin', 'admin']))
                <a href="{{ route('announcement.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('announcement.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    Pengumuman
                </a>
                @elseif(in_array($user->role, ['teacher', 'student']))
                <a href="{{ route('announcement.board') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('announcement.board') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    Pengumuman
                </a>
                @endif

                {{-- Log Aktivitas --}}
                @if(in_array($user->role, ['super_admin', 'admin']))
                <a href="{{ route('activity-log.index') }}" class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('activity-log.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Log Aktivitas
                </a>
                @endif

                {{-- Profile --}}
                <a href="{{ $user->role === 'super_admin' ? route('profile.edit') : route('profile.show') }}"
                    class="flex items-center gap-2 p-2 rounded-lg transition-colors {{ active('profile.*') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profile
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </nav>
</aside>

<script>
window.toggleMenu = function(menuId, arrowId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);
    if (!menu || !arrow) return;
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
};
</script>