<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body class="flex">
    <x-navbarguest />
    <main class="flex-1 p-6">


        <!-- Title -->
        <div class="flex justify-between">
            <h1 class="text-4xl  font-bold mb-6 font-nunito">Dashboard SDM FIF</h1>
            <a href="{{ route('welcome') }}" class="flex items-center justify-center bg-red-600 font-nunito text-white font-medium px-5 py-2 rounded-3xl
          shadow-md hover:bg-red-700 hover:shadow-lg 
          transition-all duration-300 ease-in-out">Login
            </a>



        </div>


        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-red-600 font-semibold">Total Dosen</h2>
                <p class="text-4xl font-bold">150</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-red-600 font-semibold">Total TPA</h2>
                <p class="text-4xl font-bold">125</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-red-600 font-semibold">Total Mahasiswa</h2>
                <p class="text-4xl font-bold">200</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 px-4 md:px-60 pb-6 shadow-md ">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4">Distribusi Dosen</h3>
                <canvas id="chartDosen"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4">Distribusi TPA</h3>
                <canvas id="chartTPA"></canvas>
            </div>
        </div>

        <div class="bg-white px-4 md:px-60 pb-6 pt-6 rounded-lg shadow ">
            <h3 class="font-semibold mb-4">Distribusi Kompetisi</h3>
            <canvas id="chartKompetisi"></canvas>
        </div>
    </main>
</body>

</html>
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
    document.addEventListener('DOMContentLoaded', function () {
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