<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SDM</title>
     @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dosenChart.js'])

</head>
<body class ="flex min-h-full font-nunito">
    <x-navbarguest /> 
    <main class="flex-1 p-6">
        

        <!-- Title -->
        <h1 class="text-4xl  font-bold mb-6 font-nunito">Dashboard Dosen FIF</h1>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 px-4 md:px-60 pb-6 shadow-md ">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Jumlah Dosen Per Prodi</h3>
                <canvas id="dosenProdi"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Jumlah Dosen Per KK</h3>
                <canvas id="dosenKK"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Pendidikan Dosen</h3>
                <canvas id="pendDosen"></canvas>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 px-4 md:px-20 pb-6 shadow-md ">
            
            <!-- Chart JFA Dosen -->
            <div class="bg-white rounded-lg  p-6">
                <h3 class="font-semibold mb-4 text-center text-2xl">Distribusi JFA Dosen</h3>
                <canvas id="jfaDosen"></canvas>
            </div>
            
            <!-- Chart Status Pegawai -->
            <div class="bg-white rounded-lg p-6">
                <h3 class="font-semibold mb-4 text-center text-2xl">Status Pegawai - Dosen</h3>
                <canvas id="statusDosen"></canvas>
            </div>
        </div>

    </main>
</body>

</html>