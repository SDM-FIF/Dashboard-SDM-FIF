<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Dashboard Surat Tugas & SK - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="flex flex-col md:flex-row bg-[#F8FAFC] min-h-screen text-[#1E293B]">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        {{-- Topbar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Dashboard Surat Tugas & SK</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Ringkasan penerbitan dokumen administrasi dan penyebaran surat dosen.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('manajemen-dosen.surat.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-file-invoice"></i>
                    <span>Kelola Surat Tugas / SK</span>
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Total Surat Tugas -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Total Surat Tugas</h2>
                        <p class="text-3xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalST }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                        <i class="fa-solid fa-file-signature text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Surat Keputusan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Total Surat Keputusan</h2>
                        <p class="text-3xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalSK }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-file-circle-check text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Surat Bulan Ini -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-purple-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Terbit Bulan Ini</h2>
                        <p class="text-3xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $suratBulanIni }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                        <i class="fa-solid fa-calendar-check text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Dosen Penerima -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Dosen Penerima</h2>
                        <p class="text-3xl font-black text-slate-800 mt-2 tracking-tight group-hover:scale-105 transition-transform duration-300 origin-left">{{ $totalDosenPenerima }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Left Chart: Trend Penerbitan (ST vs SK) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#C41E3A]">
                            <i class="fa-solid fa-chart-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Tren Penerbitan Surat</h3>
                            <p class="text-xs text-slate-400 font-medium">Grafik perbandingan terbit ST vs SK tahun {{ now()->year }}</p>
                        </div>
                    </div>
                </div>
                <div style="position: relative; height:320px; width:100%">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Right Chart: Distribusi Kategori -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-pie-chart text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Kategori Surat</h3>
                            <p class="text-xs text-slate-400 font-medium">Proporsi berdasarkan kategori perihal</p>
                        </div>
                    </div>
                </div>
                <div style="position: relative; height:320px; width:100%; display: flex; align-items: center; justify-content: center;">
                    @if(count($categoryStats) > 0)
                        <canvas id="categoryChart"></canvas>
                    @else
                        <div class="text-center text-xs text-slate-400 py-12">
                            <i class="fa-solid fa-circle-question text-3xl mb-2 block"></i>
                            Belum ada data kategori
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Second Charts Row: Dosen Penerima -->
        <div class="grid grid-cols-1 gap-8 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Penerima Surat Dosen</h3>
                            <p class="text-xs text-slate-400 font-medium">Distribusi jumlah Surat Tugas & SK berdasarkan Kode Dosen (Geser horizontal jika data penuh)</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto w-full pb-2">
                    <div style="position: relative; height:320px; min-width: {{ max(600, count($dosenStats) * 45) }}px;">
                        @if(count($dosenStats) > 0)
                            <canvas id="dosenChart"></canvas>
                        @else
                            <div class="text-center text-xs text-slate-400 py-12">
                                <i class="fa-solid fa-circle-question text-3xl mb-2 block"></i>
                                Belum ada data penerima surat
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Letters Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-history text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Surat Terbaru Terbit</h3>
                        <p class="text-xs text-slate-400 font-medium">Daftar 5 dokumen surat tugas atau surat keputusan terakhir</p>
                    </div>
                </div>
                <a href="{{ route('manajemen-dosen.surat.index') }}" class="text-xs font-bold text-[#C41E3A] hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                            <th class="px-6 py-3.5 text-center" width="5%">No</th>
                            <th class="px-6 py-3.5" width="15%">Jenis Surat</th>
                            <th class="px-6 py-3.5" width="25%">Nomor Surat</th>
                            <th class="px-6 py-3.5" width="30%">Judul / Perihal</th>
                            <th class="px-6 py-3.5" width="15%">Tanggal Terbit</th>
                            <th class="px-6 py-3.5 text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentSurat as $index => $surat)
                            <tr class="hover:bg-slate-50/55 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg font-bold {{ $surat->jenis_surat === 'Surat Tugas' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $surat->jenis_surat }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $surat->nomor_surat }}</td>
                                <td class="px-6 py-4 font-medium text-slate-600 max-w-xs truncate">{{ $surat->judul_surat }}</td>
                                <td class="px-6 py-4 text-slate-500 font-semibold">
                                    {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('manajemen-dosen.surat.show', $surat->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 hover:bg-[#C41E3A]/10 text-slate-400 hover:text-[#C41E3A] transition-colors"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <i class="fa-solid fa-folder-open text-3xl mb-2 block text-slate-200"></i>
                                    Belum ada data dokumen surat tugas atau SK terbit.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Render Charts script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Trend Chart (Line Chart)
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const stData = @json($stMonthlyArray);
            const skData = @json($skMonthlyArray);

            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Surat Tugas (ST)',
                            data: stData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Surat Keputusan (SK)',
                            data: skData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 15,
                                font: {
                                    family: "'Outfit', sans-serif",
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // 2. Category Chart (Doughnut Chart)
            @if(count($categoryStats) > 0)
                const ctxCategory = document.getElementById('categoryChart').getContext('2d');
                const categoryStats = @json($categoryStats);
                
                const labels = categoryStats.map(item => item.kategori || 'Lainnya');
                const counts = categoryStats.map(item => item.count);

                new Chart(ctxCategory, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                '#C41E3A', // Tel-U Red
                                '#3b82f6', // Blue
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#8b5cf6', // Violet
                                '#ec4899', // Pink
                                '#64748b'  // Slate
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        family: "'Outfit', sans-serif",
                                        size: 10,
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            @endif

            // 3. Dosen Chart (Bar Chart)
            @if(count($dosenStats) > 0)
                const ctxDosen = document.getElementById('dosenChart').getContext('2d');
                const dosenStats = @json($dosenStats);
                
                const dosenLabels = dosenStats.map(item => item.kode_dosen || 'N/A');
                const dosenCounts = dosenStats.map(item => item.count);

                new Chart(ctxDosen, {
                    type: 'bar',
                    data: {
                        labels: dosenLabels,
                        datasets: [{
                            label: 'Jumlah Surat Diterima',
                            data: dosenCounts,
                            backgroundColor: '#C41E3A', // Tel-U Red
                            borderRadius: 8,
                            borderWidth: 0,
                            maxBarThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.raw + ' Surat';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            @endif
        });
    </script>
</body>

</html>
