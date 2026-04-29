<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white border-r z-40 transform -translate-x-full md:translate-x-0 transition duration-300">

    <div class="p-6 border-b">
        <h1 class="font-bold text-blue-800">Institution Hub</h1>
        <p class="text-xs text-gray-500">Super Admin</p>
    </div>

    <nav class="p-4 space-y-2 text-sm">

        <!-- DASHBOARD -->
        <a href="{{ route('dashboard.superadmin') }}"
            class="block p-3 rounded-lg bg-blue-100 text-blue-700 font-semibold">
            Dashboard
        </a>

        <!-- USER MANAGEMENT -->
        <div>
            <button onclick="toggleMenu('userMenu','arrowUser')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">
                User Management
                <span id="arrowUser">▼</span>
            </button>

            <div id="userMenu" class="hidden ml-4 mt-1 space-y-1">
                <a href="{{ route('admin.index') }}" class="block p-2 rounded hover:bg-gray-100">Admin</a>
                <a href="{{ route('teacher.index') }}" class="block p-2 rounded hover:bg-gray-100">Teachers</a>
                <a href="{{ route('students.index') }}" class="block p-2 rounded hover:bg-gray-100">Students</a>
            </div>
        </div>

        <!-- MASTER DATA -->
        <div>
            <button onclick="toggleMenu('masterMenu','arrowMaster')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">
                Master Data
                <span id="arrowMaster">▼</span>
            </button>

            <div id="masterMenu" class="hidden ml-4 mt-1 space-y-1">
                <a href="{{ route('class.index') }}" class="block p-2 rounded hover:bg-gray-100">Kelas</a>
                <a href="{{ route('extracurricular.index') }}" class="block p-2 rounded hover:bg-gray-100">Ekstrakurikuler</a>
            </div>
        </div>

        <!-- AKADEMIK -->
        <div>
            <button onclick="toggleMenu('akademikMenu','arrowAkademik')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">
                Akademik
                <span id="arrowAkademik">▼</span>
            </button>

            <div id="akademikMenu" class="hidden ml-4 mt-1 space-y-1">
                <a href="{{ route('schedule.index') }}" class="block p-2 rounded hover:bg-gray-100">Jadwal</a>
                <a href="{{ route('grades.index') }}" class="block p-2 rounded hover:bg-gray-100">Nilai</a>
                <a href="{{ route('attendance.index') }}" class="block p-2 rounded hover:bg-gray-100">Absensi</a>
            </div>
        </div>

        <!-- SISTEM -->
        <div>
            <button onclick="toggleMenu('systemMenu','arrowSystem')"
                class="w-full flex justify-between items-center p-3 rounded-lg hover:bg-gray-100">
                Sistem
                <span id="arrowSystem">▼</span>
            </button>

            <div id="systemMenu" class="hidden ml-4 mt-1 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block p-2 rounded hover:bg-gray-100">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block w-full text-left p-2 rounded bg-red-600 text-white hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </nav>
</aside>

<!-- HEADER -->
<header class="bg-white border-b px-4 md:px-8 py-4 flex justify-between items-center md:ml-64">

    <input type="text" placeholder="Search..."
        class="w-40 md:w-72 px-4 py-2 border rounded-lg text-sm">

    <div class="relative">
        <button onclick="toggleProfile()" class="flex items-center gap-3">
            <img src="https://i.pravatar.cc/40" class="w-8 h-8 rounded-full">
            <div class="hidden md:block text-sm">
                <p class="font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-gray-500 text-xs">{{ auth()->user()->email }}</p>
            </div>
        </button>

        <div id="profileMenu"
            class="hidden absolute right-0 mt-3 w-44 bg-white rounded-xl shadow border py-2">
            <a class="block px-4 py-2 hover:bg-gray-100 text-sm">Profile</a>
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>

</header>