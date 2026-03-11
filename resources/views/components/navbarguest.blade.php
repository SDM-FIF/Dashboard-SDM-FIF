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
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                            </svg>
                            Dashboard
                        </div>
                        <svg class="w-4 h-4 transform transition-transform duration-200" id="dashboardArrow"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="dashboardDropdown" class="hidden bg-red-600 border-l-4 border-red-400">
                        <ul class="py-2">
                            <li>
                                <a href="{{ route('guest') }}"
                                    class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('guest-dosen') }}"
                                    class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                    Dashboard Dosen
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('guest-tpa') }}"
                                    class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-dosen*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                    Dashboard TPA
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('guest-kompetisi') }}"
                                    class="block px-8 py-2 text-sm hover:bg-red-500 transition-colors duration-200 {{ request()->routeIs('data-tpa*') ? 'bg-red-500 text-white' : 'text-red-100' }}">
                                    Dashboard Kompetisi
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>



        <div class="p-6 border-t border-red-500/30 mt-auto">
            <a href="{{ route('login') }}"
                class="block w-full text-center px-4 py-2 bg-white text-red-600 rounded-md text-sm font-bold">
                Login
            </a>
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