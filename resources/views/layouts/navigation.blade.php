@php
$user = auth()->user();

function active($routes)
{
    return request()->routeIs($routes)
        ? 'bg-[#29ABE2]/10 text-[#1C7FAE] font-semibold'
        : 'text-[#10243A] hover:bg-[#F7FAFC]';
}
@endphp

<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white border-r border-[#E7EEF3] z-40
    transform -translate-x-full md:translate-x-0 transition-transform duration-300">

    <!-- HEADER -->
    <div class="p-6 border-b border-[#E7EEF3] flex items-center gap-2.5">

        <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-9 w-9 object-contain">

        <div>
            <h1 class="font-extrabold text-sm tracking-tight leading-tight">
                <span class="text-[#29ABE2]">GAMELAB</span> <span class="text-[#10243A]">INDONESIA</span>
            </h1>

            <p class="text-xs text-[#62788A] capitalize mt-0.5">
                {{ $user->role }}
            </p>
        </div>

    </div>

    <nav class="p-4 space-y-2 text-sm">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard.' . $user->role) }}"
            class="flex items-center p-3 rounded-lg transition-colors {{ active('dashboard.*') }}">

            Dashboard

        </a>

        <!-- USER MANAGEMENT -->
        @if(in_array($user->role, ['super_admin', 'admin']))

        <div>

            <button type="button"
                onclick="toggleMenu('userMenu','arrowUser')"
                class="w-full flex justify-between items-center p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                User Management

                <span id="arrowUser"
                    class="transition-transform text-[#62788A] text-xs
                    {{ request()->routeIs('admin.*','teacher.*','students.*') ? 'rotate-180' : '' }}">

                    ▼

                </span>

            </button>

            <div id="userMenu"
                class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3
                {{ request()->routeIs('admin.*','teacher.*','students.*') ? '' : 'hidden' }}">

                @if($user->role === 'super_admin')

                <a href="{{ route('admin.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('admin.*') }}">

                    Admin

                </a>

                @endif

                <a href="{{ route('teacher.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('teacher.*') }}">

                    Teacher

                </a>

                <a href="{{ route('students.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('students.*') }}">

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
                class="w-full flex justify-between items-center p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                Master Data

                <span id="arrowMaster"
                    class="transition-transform text-[#62788A] text-xs
                    {{ request()->routeIs('class.*','extracurricular.*') ? 'rotate-180' : '' }}">

                    ▼

                </span>

            </button>

            <div id="masterMenu"
                class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3
                {{ request()->routeIs('class.*','extracurricular.*') ? '' : 'hidden' }}">

                <a href="{{ route('class.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('class.*') }}">

                    Kelas

                </a>

                <a href="{{ route('extracurricular.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('extracurricular.*') }}">

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
                class="w-full flex justify-between items-center p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                Akademik

                <span id="arrowAkademik"
                    class="transition-transform text-[#62788A] text-xs
                    {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*','teacher.homeroom.students') ? 'rotate-180' : '' }}">

                    ▼

                </span>

            </button>

            <div id="akademikMenu"
                class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3
                {{ request()->routeIs('schedule.*','grades.*','attendance.*','subjects.*','teacher.homeroom.students') ? '' : 'hidden' }}">

                <a href="{{ route('schedule.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('schedule.*') }}">

                    Jadwal

                </a>

                <a href="{{ route('grades.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('grades.*') }}">

                    Nilai

                </a>

                <a href="{{ route('attendance.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('attendance.*') }}">

                    Absensi

                </a>

                <a href="{{ route('subjects.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('subjects.*') }}">

                    Mata Pelajaran

                </a>

                {{-- KHUSUS WALI KELAS --}}
                @if(
                    $user->role === 'teacher' &&
                    $user->teacher &&
                    \App\Models\ClassModel::where('teacher_id', $user->teacher->id)->exists()
                )

                <a href="{{ route('teacher.homeroom.students') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('teacher.homeroom.students') }}">

                    Siswa Kelas Saya

                </a>

                @endif

            </div>

        </div>

        @endif

        <!-- STUDENT -->
        @if($user->role === 'student')

        <div>

            <button type="button"
                onclick="toggleMenu('studentMenu','arrowStudent')"
                class="w-full flex justify-between items-center p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                Student Menu

                <span id="arrowStudent"
                    class="transition-transform text-[#62788A] text-xs
                    {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? 'rotate-180' : '' }}">

                    ▼

                </span>

            </button>

            <div id="studentMenu"
                class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3
                {{ request()->routeIs('student.*','grades.*','attendance.*','schedule.*','extracurricular.*') ? '' : 'hidden' }}">

                <a href="{{ route('grades.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('grades.*') }}">

                    Nilai Saya

                </a>

                <a href="{{ route('attendance.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('attendance.*') }}">

                    Absensi Saya

                </a>

                <a href="{{ route('schedule.index') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('schedule.*') }}">

                    Jadwal Kelas

                </a>

                <a href="{{ route('student.extracurricular') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('student.extracurricular') }}">

                    Ekstrakurikuler

                </a>

                <a href="{{ route('student.raport') }}"
                    class="block p-2 rounded-lg transition-colors {{ active('student.raport') }}">

                    Rapot Saya

                </a>

            </div>

        </div>

        @endif

        <!-- SISTEM -->
        <div>

            <button type="button"
                onclick="toggleMenu('systemMenu','arrowSystem')"
                class="w-full flex justify-between items-center p-3 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                Sistem

                <span id="arrowSystem"
                    class="transition-transform text-[#62788A] text-xs
                    {{ request()->routeIs('profile.*') ? 'rotate-180' : '' }}">

                    ▼

                </span>

            </button>

            <div id="systemMenu"
                class="ml-4 mt-1 space-y-1 border-l border-[#E7EEF3] pl-3
                {{ request()->routeIs('profile.*') ? '' : 'hidden' }}">

                {{--
                    super_admin -> halaman edit profile
                    admin, teacher, student -> halaman show profile (read-only)
                --}}
                <a href="{{ $user->role === 'super_admin' ? route('profile.edit') : route('profile.show') }}"
                    class="block p-2 rounded-lg text-[#10243A] hover:bg-[#F7FAFC] transition-colors">

                    Profile

                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-1">

                    @csrf

                    <button type="submit"
                        class="w-full text-left p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors font-medium">

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