<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex bg-[#F8FAFC] text-slate-800 font-nunito min-h-screen overflow-x-hidden">
    <x-navbarguest /> 
    
    <main class="flex-1 flex flex-col min-h-screen p-6 md:p-8 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Dashboard Dosen FIF</h1>
                <p class="text-slate-500 mt-1 text-sm md:text-base">Statistik dosen berdasarkan Program Studi, Kelompok Keahlian, Pendidikan, JFA, dan Status Pegawai (Akses Publik).</p>
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
            <!-- Dosen per Prodi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Jumlah Dosen Per Prodi</h3>
                            <p class="text-xs text-slate-400">Distribusi dosen di setiap program studi.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="dosenProdi"></canvas>
                </div>
            </div>

            <!-- Dosen per KK -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-cubes text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Dosen Per Kelompok Keahlian</h3>
                            <p class="text-xs text-slate-400">Kelompok keahlian / fokus riset dosen.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="dosenKK"></canvas>
                </div>
            </div>

            <!-- Pendidikan Dosen -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-award text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Kualifikasi Pendidikan</h3>
                            <p class="text-xs text-slate-400">Jenjang pendidikan terakhir dosen.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="pendDosen"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Charts (Row 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- JFA Dosen -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-chart-column text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Distribusi JFA Dosen</h3>
                            <p class="text-xs text-slate-400">Jabatan Fungsional Akademik dosen aktif.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="jfaDosen"></canvas>
                </div>
            </div>

            <!-- Status Pegawai -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-briefcase text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Status Pegawai - Dosen</h3>
                            <p class="text-xs text-slate-400">Status kepegawaian dosen di lingkungan FIF.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="statusDosen"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.dosenData = {
            prodi: @json($dosenProdi),
            kk: @json($dosenKK),
            pendidikan: @json($pendDosen),
            jfa: @json($jfaDosen),
            status: @json($statusDosen)
        };

        document.addEventListener('DOMContentLoaded', () => {
            const dateSpan = document.getElementById('current-date-span');
            if (dateSpan) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateSpan.textContent = new Date().toLocaleDateString('id-ID', options);
            }
        });
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dosenChart.js'])
</body>
</html>