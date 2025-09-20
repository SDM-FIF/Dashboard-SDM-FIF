<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SDM</title>
     

</head>
<body class ="flex min-h-full font-nunito">
    <x-navbar /> 
    <main class="flex-1 p-6">
        

        <!-- Title -->
        <h1 class="text-4xl  font-bold mb-6 font-nunito">Dashboard SDM FIF</h1>

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
                <h2 class="text-red-600 font-semibold">Total Kompetisi</h2>
                <p class="text-4xl font-bold">200</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 px-4 md:px-60 pb-6 shadow-md ">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Distribusi Dosen</h3>
                <canvas id="chartDosen"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Distribusi TPA</h3>
                <canvas id="chartTPA"></canvas>
            </div>
        </div>

        <div class="bg-white px-4 md:px-60 pb-6 pt-6 rounded-lg shadow ">
            <h3 class="font-semibold mb-4 text-center text-2xl">Distribusi Kompetisi</h3>
            <canvas id="chartKompetisi"></canvas>
        </div>
    </main>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboardSDM.js'])
</body>
</html>