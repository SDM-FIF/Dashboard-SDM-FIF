<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SDM</title>
    <script>
        const chartData = {
            statusTpa: {
                labels: @json($statusTpa->keys()),
                data: @json($statusTpa->values()),
            },
            lokasiTpa: {
                labels: @json($lokasiTpa->keys()),
                data: @json($lokasiTpa->values()),
            },
            pendidikanTpa: {
                labels: @json($pendidikanTpa->keys()),
                data: @json($pendidikanTpa->values()),
            },
            pangkatTpa: {
                labels: @json($pangkatTpa->keys()),
                data: @json($pangkatTpa->values()),
            },
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboardTPA.js'])

</head>

<body class="flex min-h-full font-nunito">
    <x-navbar />
    <main class="flex-1 p-6">


        <!-- Title -->
        <h1 class="text-4xl  font-bold mb-6 font-nunito">Dashboard TPA FIF</h1>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 px-4 md:px-60 pb-6 shadow-md ">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Lokasi Kerja TPA</h3>
                <canvas id="lokasiTpa"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Pangkat/Golongan TPA</h3>
                <canvas id="pangkatTpa"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold mb-4 text-center text-2xl">Pendidikan TPA</h3>
                <canvas id="pendidikanTpa"></canvas>
            </div>
        </div>

        <div class="bg-white px-4 md:px-60 pb-6 pt-6 rounded-lg shadow ">
            <h3 class="font-semibold mb-4 text-center text-2xl">Status Pegawai - TPA</h3>
            <canvas id="statusTpa"></canvas>
        </div>

    </main>
</body>

</html>