<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Dashboard SDM</title>
</head>
<body class="flex flex-col md:flex-row">
    <x-navbar />
    
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <!-- Title -->
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-semibold mb-4 md:mb-6 text-center md:text-left">
            Data Dosen FIF
        </h1>

        <!-- Card Container -->
        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-4 md:p-6">

            <!-- Filter -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <select class="px-4 py-2 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>JFA</option>
                </select>
                <select class="px-4 py-2 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>Lokasi Kerja</option>
                </select>
                <select class="px-4 py-2 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>Kelompok Keahlian</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm md:text-base">
                    <thead class="bg-redmain text-white">
                        <tr>
                            <th class="px-2 md:px-4 py-2 text-left">No.</th>
                            <th class="px-2 md:px-4 py-2 text-left">Nama Dosen</th>
                            <th class="px-2 md:px-4 py-2 text-left">Back Title</th>
                            <th class="px-2 md:px-4 py-2 text-left">NIP</th>
                            <th class="px-2 md:px-4 py-2 text-left">Kode Dosen</th>
                            <th class="px-2 md:px-4 py-2 text-left">JFA</th>
                            <th class="px-2 md:px-4 py-2 text-left">Lokasi Kerja</th>
                            <th class="px-2 md:px-4 py-2 text-left">Kelompok Keahlian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-2 md:px-4 py-2">1</td>
                            <td class="px-2 md:px-4 py-2">Nama Dosen</td>
                            <td class="px-2 md:px-4 py-2">Dr.</td>
                            <td class="px-2 md:px-4 py-2">12345678</td>
                            <td class="px-2 md:px-4 py-2">DS001</td>
                            <td class="px-2 md:px-4 py-2">Lektor</td>
                            <td class="px-2 md:px-4 py-2">Bandung</td>
                            <td class="px-2 md:px-4 py-2">Informatika</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
