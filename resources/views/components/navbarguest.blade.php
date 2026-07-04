<nav class="bg-redmain text-white h-screen w-64 hidden md:flex flex-col font-nunito sticky top-0">

    {{-- Menu --}}
    <div class="flex-1 py-6">

        <ul class="space-y-1">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('guest') }}"
                    class="flex items-center px-6 py-4 transition-all duration-200
                    {{ request()->routeIs('guest') ? 'bg-[#FBB03B] text-white font-semibold' : 'hover:bg-red-500 text-white' }}">
                    <i class="fa-solid fa-table-columns w-5 mr-4"></i>
                    Dashboard
                </a>
            </li>

            {{-- Dashboard Dosen --}}
            <li>
                <a href="{{ route('guest-dosen') }}"
                    class="flex items-center px-6 py-4 transition-all duration-200
                    {{ request()->routeIs('guest-dosen') ? 'bg-[#FBB03B] text-white font-semibold' : 'hover:bg-red-500 text-white' }}">
                    <i class="fa-solid fa-user-tie w-5 mr-4"></i>
                    Dashboard Dosen
                </a>
            </li>

            {{-- Dashboard TPA --}}
            <li>
                <a href="{{ route('guest-tpa') }}"
                    class="flex items-center px-6 py-4 transition-all duration-200
                    {{ request()->routeIs('guest-tpa') ? 'bg-[#FBB03B] text-white font-semibold' : 'hover:bg-red-500 text-white' }}">
                    <i class="fa-solid fa-users w-5 mr-4"></i>
                    Dashboard TPA
                </a>
            </li>

            {{-- Dashboard Mahasiswa --}}
            <li>
                <a href="{{ route('guest-kompetisi') }}"
                    class="flex items-center px-6 py-4 transition-all duration-200
                    {{ request()->routeIs('guest-kompetisi') ? 'bg-[#FBB03B] text-white font-semibold' : 'hover:bg-red-500 text-white' }}">
                    <i class="fa-solid fa-graduation-cap w-5 mr-4"></i>
                    Dashboard Mahasiswa
                </a>
            </li>

        </ul>

    </div>

    {{-- Login --}}
    <div class="p-6 border-t border-red-500/30">
        <a href="{{ route('login') }}"
            class="flex items-center justify-center gap-2 bg-white text-red-600 font-bold rounded-xl py-3 hover:bg-gray-100 transition">
            <i class="fa-solid fa-right-to-bracket"></i>
            Login
        </a>
    </div>

</nav>