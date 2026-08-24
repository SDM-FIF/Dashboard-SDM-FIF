<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SDM - FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex bg-[#F8FAFC] text-slate-800 font-nunito min-h-screen overflow-x-hidden">
    <x-navbarguest />

    <main class="flex-1 flex flex-col min-h-screen p-6 md:p-8 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Dashboard SDM FIF</h1>
                <p class="text-slate-500 mt-1 text-sm md:text-base">Ringkasan data sumber daya manusia dan statistik mahasiswa.</p>
            </div>
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 self-start md:self-auto">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-[#C41E3A]">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="text-xs font-semibold text-slate-600" id="current-date-span"></span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">




            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Dosen Aktif -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Dosen Aktif</h2>
                            <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $dosenAktif }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-user-check text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-blue-600 font-semibold gap-1">
                        <span class="bg-blue-50 px-2.5 py-0.5 rounded-full">Status Aktif</span>
                    </div>
                </div>

                <!-- Card 2: Dosen Tugas Belajar -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-purple-500"></div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Tugas Belajar</h2>
                            <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $dosenTugasBelajar }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-inner group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-book-open-reader text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-purple-600 font-semibold gap-1">
                        <span class="bg-purple-50 px-2.5 py-0.5 rounded-full">Studi Lanjut</span>
                    </div>
                </div>

                <!-- Card 3: Dosen Izin Belajar -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Izin Belajar</h2>
                            <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $dosenIzinBelajar }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-file-signature text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-amber-600 font-semibold gap-1">
                        <span class="bg-amber-50 px-2.5 py-0.5 rounded-full">Izin Khusus</span>
                    </div>
                </div>

                <!-- Card 4: Total Dosen -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Total Dosen</h2>
                            <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalDosen }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-emerald-600 font-semibold gap-1">
                        <span class="bg-emerald-50 px-2.5 py-0.5 rounded-full">Seluruh Status</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Chart Dosen Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-chart-pie text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Pendidikan Terakhir Dosen</h3>
                                <p class="text-xs text-slate-400">Berdasarkan kualifikasi pendidikan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                        <canvas id="chartDosen"></canvas>
                    </div>
                </div>

                <!-- Chart TPA Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-chart-pie text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Jabatan Akademik Dosen (JAD)</h3>
                                <p class="text-xs text-slate-400">Berdasarkan jenjang fungsional dosen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                        <canvas id="chartTPA"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart Dosen Prodi Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-chalkboard-user text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Jumlah Dosen Prodi</h3>
                            <p class="text-xs text-slate-400">Berdasarkan penempatan program studi.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[350px] flex items-center justify-center">
                    <canvas id="chartDosenProdi"></canvas>
                </div>
            </div>

            <!-- Nisbah Table Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mt-8">

                <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-5">

                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#C41E3A]">
                        <i class="fa-solid fa-table"></i>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-800">
                            Tabel Nisbah Fakultas Informatika
                        </h3>

                        <p class="text-xs text-slate-500">
                            Berdasarkan jumlah dosen dan jumlah mahasiswa setiap program studi.
                        </p>
                    </div>

                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">

                    <table class="min-w-full text-sm">

                        <thead class="bg-[#C41E3A] text-white">

                            <tr>

                                <th class="px-4 py-3 text-left">
                                    Program Studi
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Jumlah Dosen
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Jumlah Mahasiswa
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Nisbah
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Hasil Nisbah
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-200">

                            @php
                            $totalDosen = 0;
                            $totalMahasiswa = 0;
                            @endphp

                            @foreach($nisbah as $item)

                            @php
                            $totalDosen += $item['jumlah_dosen'];
                            $totalMahasiswa += $item['jumlah_mahasiswa'];
                            @endphp

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-4 py-3 font-medium">
                                    {{ $item['nama_prodi'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $item['jumlah_dosen'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $item['jumlah_mahasiswa'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    1 : {{ $item['batas_nisbah'] }}
                                </td>

                                <td class="px-4 py-3 text-center">

                                    @if($item['jumlah_dosen']==0)

                                    -

                                    @else

                                    {{ number_format($item['hasil_nisbah'],2) }}

                                    @endif

                                </td>

                                <td class="px-4 py-3 text-center">

                                    @if($item['jumlah_dosen']==0)

                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        Belum Ada Dosen
                                    </span>

                                    @elseif($item['status']=="Sesuai")

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Sesuai
                                    </span>

                                    @else

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Melebihi
                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="bg-slate-100 font-bold">

                            <tr>

                                <td class="px-4 py-3">
                                    TOTAL
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $totalDosen }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $totalMahasiswa }}
                                </td>

                                <td class="text-center">
                                    -
                                </td>

                                <td class="text-center">
                                    -
                                </td>

                                <td class="text-center">
                                    -
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>
    </main>

    <script>
        window.dashboardData = {
            pendidikan: @json($pendidikanDosen),
            jad: @json($jadDosen),
            prodi: @json($jumlahDosenProdi)
        };
    </script>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/dashboardSDM.js'
    ])
</body>

</html>
