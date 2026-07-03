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
                <p class="text-slate-500 mt-1 text-sm md:text-base">Ringkasan data sumber daya manusia dan statistik mahasiswa (Akses Publik).</p>
            </div>
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 self-start md:self-auto">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-[#C41E3A]">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="text-xs font-semibold text-slate-600" id="current-date-span"></span>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Total Dosen</h2>
                        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalDosen }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-user-tie text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-blue-600 font-semibold gap-1">
                    <span class="bg-blue-50 px-2.5 py-0.5 rounded-full">Fakultas Informatika</span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Total TPA</h2>
                        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalTPA }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-semibold gap-1">
                    <span class="bg-emerald-50 px-2.5 py-0.5 rounded-full">Tenaga Pendukung Akademik</span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Total Mahasiswa</h2>
                        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalMahasiswa }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-amber-600 font-semibold gap-1">
                    <span class="bg-amber-50 px-2.5 py-0.5 rounded-full">Mahasiswa Aktif & Kompetisi</span>
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
                            <h3 class="font-bold text-slate-800 text-lg">Distribusi Dosen</h3>
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
                            <h3 class="font-bold text-slate-800 text-lg">Distribusi TPA</h3>
                            <p class="text-xs text-slate-400">Berdasarkan unit kerja pendukung.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                    <canvas id="chartTPA"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Mahasiswa Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-chart-column text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Distribusi Mahasiswa</h3>
                        <p class="text-xs text-slate-400">Berdasarkan program studi pilihan.</p>
                    </div>
                </div>
            </div>
            <div class="relative min-h-[350px] flex items-center justify-center">
                <canvas id="chartKompetisi"></canvas>
            </div>
        </div>
    </main>

    <script>
        window.dashboardData = {!! json_encode([
            'pendidikan' => $pendidikanDosen,
            'tpa' => $lokasiTPA,
            'mahasiswa' => $mahasiswaProdi
        ]) !!};

        document.addEventListener('DOMContentLoaded', () => {
            const dateSpan = document.getElementById('current-date-span');
            if (dateSpan) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateSpan.textContent = new Date().toLocaleDateString('id-ID', options);
            }
        });
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboardSDM.js'])
</body>
</html>