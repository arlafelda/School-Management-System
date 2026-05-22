@php
    $user = auth()->user();

    function active($routes)
    {
        return request()->routeIs($routes)
            ? 'bg-blue-100 text-blue-700 font-semibold'
            : 'hover:bg-gray-100';
    }
@endphp

<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white border-r z-40
    transform -translate-x-full md:translate-x-0 transition-transform duration-300">

    <!-- HEADER -->
    <div class="p-6 border-b">
        <h1 class="font-bold text-blue-800">Institution Hub</h1>
        <p class="text-xs text-gray-500 capitalize">{{ $user->role }}</p>
    </div>

    <nav class="p-4 space-y-2 text-sm">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard.' . $user->role) }}"
            class="flex items-center p-3 rounded-lg transition {{ active('dashboard.*') }}">
            Dashboard
        </a>

        <!-- USER MANAGEMENT -->
        @if(in_array($user->role, ['super_admin', 'admin']))
        <div>
            <button type="button"
                onclick="toggleMenu('userMenu','arrowUser')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">

                User Management

                <span id="arrowUser"
                    class="transition-transform
                    {{ request()->routeIs('admin.*','teacher.*','students.*') ? 'rotate-180' : '' }}">
                    ▼
                </span>
            </button>

            <div id="userMenu"
                class="ml-4 mt-1 space-y-1
                {{ request()->routeIs('admin.*','teacher.*','students.*') ? '' : 'hidden' }}">

                @if($user->role === 'super_admin')
                <a href="{{ route('admin.index') }}"
                    class="block p-2 rounded {{ active('admin.*') }}">
                    Admin
                </a>
                @endif

                <a href="{{ route('teacher.index') }}"
                    class="block p-2 rounded {{ active('teacher.*') }}">
                    Teacher
                </a>

                <a href="{{ route('students.index') }}"
                    class="block p-2 rounded {{ active('students.*') }}">
                    Students
                </a>
            </div>
        </div>
        @endif

        <!-- MASTER DATA -->
        @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
        <div>
            <button type="button"
                onclick="toggleMenu('masterMenu','arrowMaster')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">

                Master Data

                <span id="arrowMaster"
                    class="transition-transform
                    {{ request()->routeIs('class.*','extracurricular.*') ? 'rotate-180' : '' }}">
                    ▼
                </span>
            </button>

            <div id="masterMenu"
                class="ml-4 mt-1 space-y-1
                {{ request()->routeIs('class.*','extracurricular.*') ? '' : 'hidden' }}">

                <a href="{{ route('class.index') }}"
                    class="block p-2 rounded {{ active('class.*') }}">
                    Kelas
                </a>

                <a href="{{ route('extracurricular.index') }}"
                    class="block p-2 rounded {{ active('extracurricular.*') }}">
                    Ekstrakurikuler
                </a>
            </div>
        </div>
        @endif

        <!-- AKADEMIK -->
        @if(in_array($user->role, ['super_admin', 'admin', 'teacher']))
        <div>
            <button type="button"
                onclick="toggleMenu('akademikMenu','arrowAkademik')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">

                Akademik

                <span id="arrowAkademik"
                    class="transition-transform
                    {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*') ? 'rotate-180' : '' }}">
                    ▼
                </span>
            </button>

            <div id="akademikMenu"
                class="ml-4 mt-1 space-y-1
                {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*') ? '' : 'hidden' }}">

                <a href="{{ route('schedule.index') }}"
                    class="block p-2 rounded {{ active('schedule.*') }}">
                    Jadwal
                </a>

                <a href="{{ route('grades.index') }}"
                    class="block p-2 rounded {{ active('grades.*') }}">
                    Nilai
                </a>

                <a href="{{ route('attendance.index') }}"
                    class="block p-2 rounded {{ active('attendance.*') }}">
                    Absensi
                </a>

                <!-- SUBJECT MENU -->
                <a href="{{ route('subjects.index') }}"
                    class="block p-2 rounded {{ active('subjects.*') }}">
                    Mata Pelajaran
                </a>

            </div>
        </div>
        @endif

        <!-- STUDENT -->
        @if($user->role === 'student')
        <div>
            <button type="button"
                onclick="toggleMenu('studentMenu','arrowStudent')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">

                Student Menu

                <span id="arrowStudent"
                    class="transition-transform
                    {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? 'rotate-180' : '' }}">
                    ▼
                </span>
            </button>

            <div id="studentMenu"
                class="ml-4 mt-1 space-y-1
                {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? '' : 'hidden' }}">

                <a href="{{ route('grades.index') }}"
                    class="block p-2 rounded {{ active('grades.*') }}">
                    Nilai Saya
                </a>

                <a href="{{ route('attendance.index') }}"
                    class="block p-2 rounded {{ active('attendance.*') }}">
                    Absensi Saya
                </a>

                <a href="{{ route('schedule.index') }}"
                    class="block p-2 rounded {{ active('schedule.*') }}">
                    Jadwal Kelas
                </a>

                <a href="{{ route('student.extracurricular') }}"
                    class="block p-2 rounded {{ active('student.extracurricular') }}">
                    Ekstrakurikuler
                </a>
            </div>
        </div>
        @endif

        <!-- SISTEM -->
        <div>
            <button type="button"
                onclick="toggleMenu('systemMenu','arrowSystem')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">

                Sistem

                <span id="arrowSystem"
                    class="transition-transform
                    {{ request()->routeIs('profile.*') ? 'rotate-180' : '' }}">
                    ▼
                </span>
            </button>

            <div id="systemMenu"
                class="ml-4 mt-1 space-y-1
                {{ request()->routeIs('profile.*') ? '' : 'hidden' }}">

                <a href="{{ route('profile.edit') }}"
                    class="block p-2 rounded hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left p-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </nav>
</aside>

<!-- SCRIPT -->
<script>
window.toggleMenu = function(menuId, arrowId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (!menu || !arrow) return;

    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
};
</script>