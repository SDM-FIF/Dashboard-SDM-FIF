{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-redmain text-white min-h-screen w-64 hidden md:flex flex-col font-nunito ">
    {{-- Header/Logo Section --}}
    <div class="p-4 border-b border-red-500">
        <h2 class="text-lg font-semibold">Dashboard SDM FIF</h2>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex-1 py-4">
        <ul class="space-y-2.5">
            {{-- Dashboard with Dropdown --}}
            <li class="relative">
                <button onclick="toggleDropdown('dashboardDropdown')" 
                        class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('dashboard*') || request()->routeIs('data-dosen*') || request()->routeIs('data-tpa*') ? 'bg-[#FBB03B] border-r-4 border-white' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Dashboard
                    </div>
                    <svg class="w-4 h-4 transform transition-transform duration-200" id="dashboardArrow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                
                {{-- Dropdown Menu --}}
                <div id="dashboardDropdown" class="hidden bg-red-600 border-l-4 border-red-400">
                    <ul class="py-2">
                        <li>
                            <a href="{{ route('dashboard') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Dashboard SDM
                            </a>
                        </li>                        
                        <li>
                            <a href="{{ route('dashboard-dosen') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Dashboard Dosen
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('data-dosen') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Data Dosen
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('data-dosen') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Dashboard TPA
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('data-dosen') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Data TPA
                            </a>
                        </li>                                                                        
                        <li>
                            <a href="{{ route('data-tpa') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-tpa*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Dashboard Kompetisi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kompetisi') }}" 
                               class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-tpa*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                Data Kompetisi
                            </a>
                        </li>                        
                    </ul>
                </div>
            </li>


            {{-- Manajemen Dosen --}}
            <li>
                <a href="{{ route('manajemen-dosen') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('manajemen-dosen*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Manajemen Dosen
                </a>
            </li>

            {{-- Manajemen TPA --}}
            <li>
                <a href="{{ route('manajemen-tpa') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('manajemen-tpa*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                    Manajemen TPA
                </a>
            </li>


            {{-- Rekrutasi Dosen --}}
            <li>
                <a href="{{ route('rekrutasi-dosen') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('rekrutasi-dosen*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                    </svg>
                    Rekrutasi Dosen
                </a>
            </li>

            {{-- Manajemen Mahasiswa --}}
            <li>
                <a href="{{ route('manajemen-mahasiswa') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('manajemen-mahasiswa*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Manajemen Mahasiswa
                </a>
            </li>

            {{-- Master Data --}}
            <li>
                <a href="{{ route('master-data') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('master-data*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 3a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Master Data
                </a>
            </li>

            {{-- Pengaturan --}}
            <li>
                <a href="{{ route('pengaturan') }}" 
                   class="flex items-center px-4 py-3 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('pengaturan*') ? 'bg-red-500 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Pengaturan
                </a>
            </li>
        </ul>
    </div>

    {{-- User Profile Section (Bottom) --}}
    <div class="p-4 border-t border-red-500">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-red-200">{{ Auth::user()->role ?? 'Administrator' }}</p>
            </div>
        </div>
        
        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left px-2 py-2 text-sm text-red-200 hover:text-white hover:bg-red-500 rounded transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 001-1h10.586l-2.293-2.293a1 1 0 10-1.414 1.414L14.586 5H5a3 3 0 00-3 3v8a3 3 0 003 3h10a3 3 0 003-3V8a1 1 0 10-2 0v8a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h1.586l2.707-2.707A1 1 0 0010.414 3H5z" clip-rule="evenodd"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</nav>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const arrow = document.getElementById('dashboardArrow');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Auto-open dropdown if current route is one of the dashboard sub-pages
document.addEventListener('DOMContentLoaded', function() {
    const currentRoute = window.location.pathname;
    const dashboardRoutes = ['{{ route("dashboard") }}', '{{ route("data-dosen") }}', '{{ route("data-tpa") }}'];
    
    if (dashboardRoutes.some(route => currentRoute.includes(route.split('/').pop()))) {
        const dropdown = document.getElementById('dashboardDropdown');
        const arrow = document.getElementById('dashboardArrow');
        dropdown.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    }
});
</script>