<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kompetisi - FIF</title>
    <link class="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex bg-[#F8FAFC] text-slate-800 font-nunito min-h-screen overflow-x-hidden">
    <x-navbar /> 
    
    <main class="flex-1 flex flex-col min-h-screen p-6 md:p-8 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Dashboard Kompetisi FIF</h1>
                <p class="text-slate-500 mt-1 text-sm md:text-base">Statistik pencapaian prestasi mahasiswa dalam kompetisi akademik & non-akademik.</p>
            </div>
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 self-start md:self-auto">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-[#C41E3A]">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="text-xs font-semibold text-slate-600" id="current-date-span"></span>
            </div>
        </div>

        <!-- Doughnut Charts (Row 1) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Jumlah Juara -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-trophy text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Tingkat Prestasi Juara</h3>
                            <p class="text-xs text-slate-400">Jumlah juara 1, 2, dan 3.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="jumlahJuara"></canvas>
                </div>
            </div>

            <!-- Jumlah Mahasiswa -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Jenis Kompetisi</h3>
                            <p class="text-xs text-slate-400">Kompetisi akademik vs non-akademik.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="jumlahMahasiswa"></canvas>
                </div>
            </div>

            <!-- Status Mahasiswa -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-user-check text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Status Keaktifan</h3>
                            <p class="text-xs text-slate-400">Status mahasiswa dalam kompetisi.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="statusMahasiswa"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Charts (Row 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Jumlah Kompetisi per Prodi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-chart-bar text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Kompetisi per Prodi</h3>
                            <p class="text-xs text-slate-400">Jumlah kompetisi yang diikuti per program studi.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="jumlahKompetisi"></canvas>
                </div>
            </div>

            <!-- Jumlah Kompetisi per Kategori -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-list-check text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Kompetisi per Kategori</h3>
                            <p class="text-xs text-slate-400">Distribusi kompetisi per kategori kejuaraan.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="jumlahKompetisi2"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateSpan = document.getElementById('current-date-span');
            if (dateSpan) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateSpan.textContent = new Date().toLocaleDateString('id-ID', options);
            }
        });
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/kompetisiChart.js'])
</body>
</html>