{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-[#C41E3A] text-white min-h-screen w-64 hidden md:flex flex-col font-nunito shadow-lg">
    {{-- Header/Logo Section --}}
    <div class="p-6 border-b border-red-500/30">
        <h2 class="text-xl font-bold text-center">Dashboard SDM FIF</h2>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex-1 py-4">
        <ul class="space-y-1">
            {{-- Dashboard Section --}}
            <li class="relative">
                <button onclick="toggleDropdown('dashboardDropdown')"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('dashboard*') || request()->routeIs('data-dosen*') || request()->routeIs('data-tpa*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110"
                        id="dashboardArrow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Dashboard Dropdown Menu --}}
                <div id="dashboardDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
                    <ul class="py-2 space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Dashboard SDM
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard-dosen') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('dashboard-dosen') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Dashboard Dosen
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard-tpa') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('dashboard-tpa') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Dashboard TPA
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard-kompetisi') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('dashboard-kompetisi') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Dashboard Kompetisi
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Manajemen Dosen Section --}}
            <li class="relative">
                <button onclick="toggleDropdown('dosenDropdown')"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('manajemen-dosen*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-4h3v-3.5c0-.83.67-1.5 1.5-1.5S10 9.67 10 10.5V11h2.5l-2.54 7.63A1.5 1.5 0 0 1 8.46 20H5.5c-.83 0-1.5-.67-1.5-1.5zm15.64-4.24-1.42-1.42A6.97 6.97 0 0 0 19 9c0-3.87-3.13-7-7-7S5 5.13 5 9c0 1.26.35 2.44.96 3.46L4.5 14H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h1.5v-1H7v1h1.5v-1h1v1H11v-2.5L14.5 11H17v2.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V12h.14z" />
                        </svg>
                        <span>Manajemen Dosen</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110"
                        id="dosenArrow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Manajemen Dosen Dropdown Menu --}}
                <div id="dosenDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
                    <ul class="py-2 space-y-1">
                        <li>
                            <a href="{{ route('manajemen-dosen.kelola-data') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100{{ request()->routeIs('manajemen-dosen.kelola-data') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Kelola Data
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('import-dosen') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('import-dosen') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Import Data
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('manajemen-dosen.laporan') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('manajemen-dosen.laporan') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                                Laporan Dosen
                            </a>
                        </li>
                        <!-- Add more dropdown items here -->
                    </ul>
                </div>
            </li>

            {{-- Manajemen TPA Section --}}
            <li class="relative">
                <button onclick="toggleDropdown('tpaDropdown')"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('manajemen-tpa.*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1V3H9V1L3 7V9H21ZM12 8C14.76 8 17 10.24 17 13V22H15V13C15 11.34 13.66 10 12 10S9 11.34 9 13V22H7V13C7 10.24 9.24 8 12 8Z" />
                        </svg>
                        <span>Manajemen TPA</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110" id="tpaArrow"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Manajemen TPA Dropdown Menu --}}
                <div id="tpaDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
                    <ul class="py-2 space-y-1">
                        <li>
                            <a href="{{ route('manajemen-tpa.kelola-data') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('manajemen-tpa.kelola-data') ? 'bg-red-600/60' : '' }}">
                                Kelola Data
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('manajemen-tpa.import-data') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('manajemen-tpa.import-data') || request()->routeIs('manajemen-tpa.import-process') ? 'bg-red-600/60' : '' }}">
                                Import Data
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('manajemen-tpa.laporan') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('manajemen-tpa.laporan') ? 'bg-red-600/60' : '' }}">
                                Laporan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

           {{-- Rekrutasi Dosen Section --}}
<li class="relative">
    <button onclick="toggleDropdown('rekrutasiDropdown')"
        class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('rekrutasi-dosen*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V8c0-.55-.45-1-1-1s-1 .45-1 1v2H2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <span>Rekrutasi Dosen</span>
        </div>
        <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110"
            id="rekrutasiArrow" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Rekrutasi Dosen Dropdown Menu --}}
    <div id="rekrutasiDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
        <ul class="py-2 space-y-1">
            <li>
                <a href="{{ route('rekrutasi-dosen') }}"
                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('rekrutasi-dosen') && !request()->routeIs('rekrutasi-dosen.*') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                    Overview
                </a>
            </li>
            <li>
                <a href="{{ route('import-rekruitasi') }}"
                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('import-rekruitasi') || request()->routeIs('rekrutasi-dosen.import*') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                    Import Rekrutasi Dosen
                </a>
            </li>
            <li>
                <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}"
                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('rekrutasi-dosen.jadwal-pengujian') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                    Jadwal Pengujian Dosen
                </a>
            </li>
            <li>
                <a href="{{ route('rekrutasi-dosen.hasil-pengujian') }}"
                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 {{ request()->routeIs('rekrutasi-dosen.hasil-pengujian') ? 'bg-red-600 text-white border-r-4 border-yellow-400' : 'text-red-100' }}">
                    Hasil Pengujian
                </a>
            </li>
        </ul>
    </div>
</li>

                {{-- Manajemen Mahasiswa Section --}}
                <li class="relative">
                    {{-- Button Dropdown: Aktif (Kuning) jika route diawali dengan 'mahasiswa.' --}}
                    <button onclick="toggleDropdown('mahasiswaDropdown')"
                        class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('mahasiswa.*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                        <div class="flex items-center">
                            {{-- Icon Cap Graduation untuk Mahasiswa --}}
                            <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
                            </svg>
                            <span>Manajemen Mahasiswa</span>
                        </div>
                        
                        {{-- Panah Dropdown --}}
                        <svg class="w-4 h-4 transform transition-transform duration-200 {{ request()->routeIs('mahasiswa.*') ? 'rotate-180' : '' }} group-hover:scale-110" 
                            id="mahasiswaArrow"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Menu Dropdown: Hidden secara default kecuali sedang di route mahasiswa --}}
                    <div id="mahasiswaDropdown" class="{{ request()->routeIs('mahasiswa.*') ? '' : 'hidden' }} bg-red-700/50 backdrop-blur-sm">
                        <ul class="py-2 space-y-1">
                            <li>
                                {{-- Link Kelola Data --}}
                                <a href="{{ route('mahasiswa.kelola-data') }}"
                                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('mahasiswa.kelola-data') ? 'bg-red-600/60' : '' }}">
                                    Kelola Data
                                </a>
                            </li>
                            <li>
                                {{-- Link Import Data --}}
                                <a href="{{ route('mahasiswa.import.view') }}"
                                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('mahasiswa.import.*') || request()->routeIs('mahasiswa.import-process') ? 'bg-red-600/60' : '' }}">
                                    Import Data
                                </a>
                            </li>
                            <li>
                                {{-- Link Laporan --}}
                                <a href="{{ route('mahasiswa.laporan') }}"
                                    class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100 {{ request()->routeIs('mahasiswa.laporan') ? 'bg-red-600/60' : '' }}">
                                    Laporan
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            {{-- Master Data Section --}}
            <li class="relative">
                <button onclick="toggleDropdown('masterDataDropdown')"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('master-data*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                        </svg>
                        <span>Master Data</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110"
                        id="masterDataArrow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Master Data Dropdown Menu --}}
                <div id="masterDataDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
                    <ul class="py-2 space-y-1">
                        <li>
                            <a href="{{ route('master-data') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100">
                                Data Fakultas
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100">
                                Data Mahasiswa
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100">
                                Data Mahasiswa Kompetisi
                            </a>
                        </li>
                        <!-- Add more dropdown items here -->
                    </ul>
                </div>
            </li>

            {{-- Pengaturan Section --}}
            <li class="relative">
                <button onclick="toggleDropdown('pengaturanDropdown')"
                    class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium hover:bg-red-600 transition-all duration-200 group {{ request()->routeIs('pengaturan*') ? 'bg-[#FBB03B] text-black shadow-md' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                        </svg>
                        <span>Pengaturan</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200 group-hover:scale-110"
                        id="pengaturanArrow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Pengaturan Dropdown Menu --}}
                <div id="pengaturanDropdown" class="hidden bg-red-700/50 backdrop-blur-sm">
                    <ul class="py-2 space-y-1">
                        <li>
                            <a href="{{ route('pengaturan') }}"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100">
                                Konfigurasi Sistem
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-12 py-3 text-sm font-medium hover:bg-red-600 transition-colors duration-200 text-red-100">
                                User Management
                            </a>
                        </li>
                        <!-- Add more dropdown items here -->
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    {{-- User Profile Section (Bottom) --}}
    <div class="p-6 border-t border-red-500/30 mt-auto">
        <div class="flex items-center space-x-3 mb-4">
            <div
                class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-md">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-red-200 opacity-80">{{ Auth::user()->role ?? 'Administrator' }}</p>
            </div>
        </div>

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-200 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 001-1h10.586l-2.293-2.293a1 1 0 10-1.414 1.414L14.586 5H5a3 3 0 00-3 3v8a3 3 0 003 3h10a3 3 0 003-3V8a1 1 0 10-2 0v8a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h1.586l2.707-2.707A1 1 0 0010.414 3H5z"
                        clip-rule="evenodd" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const arrowId = dropdownId.replace('Dropdown', 'Arrow');
        const arrow = document.getElementById(arrowId);

        // Close all other dropdowns first
        const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
        const allArrows = document.querySelectorAll('[id$="Arrow"]');

        allDropdowns.forEach(d => {
            if (d.id !== dropdownId && !d.classList.contains('hidden')) {
                d.classList.add('hidden');
            }
        });

        allArrows.forEach(a => {
            if (a.id !== arrowId) {
                a.style.transform = 'rotate(0deg)';
            }
        });

        // Toggle current dropdown
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Auto-open dropdown if current route is one of the sub-pages
    document.addEventListener('DOMContentLoaded', function () {
        const currentRoute = window.location.pathname;

        // Define route mappings for each section
        const routeMappings = {
            'dashboardDropdown': ['dashboard', 'data-dosen', 'data-tpa', 'dashboard-dosen', 'dashboard-tpa', 'dashboard-kompetisi', 'kompetisi'],
            'dosenDropdown': ['manajemen-dosen', 'kelola-data'],
            'tpaDropdown': ['manajemen-tpa'],
            'rekrutasiDropdown': ['rekrutasi-dosen'],
            'mahasiswaDropdown': ['manajemen-mahasiswa'],
            'masterDataDropdown': ['master-data'],
            'pengaturanDropdown': ['pengaturan']
        };

        // Check each dropdown and auto-open if current route matches
        Object.keys(routeMappings).forEach(dropdownId => {
            const routes = routeMappings[dropdownId];
            const shouldOpen = routes.some(route => currentRoute.includes(route));

            if (shouldOpen) {
                const dropdown = document.getElementById(dropdownId);
                const arrowId = dropdownId.replace('Dropdown', 'Arrow');
                const arrow = document.getElementById(arrowId);

                if (dropdown && arrow) {
                    dropdown.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                }
            }
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const nav = event.target.closest('nav');
        if (!nav) return;

        const isDropdownButton = event.target.closest('button[onclick*="toggleDropdown"]');
        if (!isDropdownButton) {
            const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
            const allArrows = document.querySelectorAll('[id$="Arrow"]');

            allDropdowns.forEach(d => d.classList.add('hidden'));
            allArrows.forEach(a => a.style.transform = 'rotate(0deg)');
        }
    });
</script>