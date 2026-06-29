<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Laporan Dosen - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="flex flex-col md:flex-row bg-[#F8FAFC] min-h-screen text-[#1E293B]">
    {{-- Sidebar Navigation --}}
    <x-navbar class="no-print" />
    
    {{-- Main Content --}}
    <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 no-print">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Laporan Kepegawaian Dosen</h1>
                <p class="text-sm text-gray-500 mt-1">Statistik, sebaran program studi, kepangkatan jabatan, dan ekspor laporan.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('manajemen-dosen.laporan.export-pdf') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                    <i class="fas fa-file-pdf"></i>
                    <span>Ekspor PDF</span>
                </a>
                
                <button onclick="window.print()"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold border border-gray-200 rounded-xl transition-all duration-300 shadow-sm text-sm">
                    <i class="fas fa-print"></i>
                    <span>Cetak Halaman</span>
                </button>
            </div>
        </div>

        {{-- Print Header (Only visible on print) --}}
        <div class="hidden print:block border-b-2 border-gray-800 pb-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">LAPORAN DATA DOSEN</h1>
                    <p class="text-xs text-gray-500 mt-1">Sistem Dashboard SDM FIF - Universitas Telkom</p>
                </div>
                <div class="text-right text-xs text-gray-400">
                    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Metrics Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            {{-- Total Dosen --}}
            <div class="bg-gradient-to-br from-white to-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Dosen</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $statistik['total_dosen'] }}</p>
                    </div>
                    <div class="p-3 bg-red-50 text-[#C41E3A] rounded-xl">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Aktif --}}
            <div class="bg-gradient-to-br from-white to-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktif</p>
                        <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $statistik['per_status_dosen']['aktif'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Tugas Belajar --}}
            <div class="bg-gradient-to-br from-white to-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tugas Belajar</p>
                        <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $statistik['per_status_dosen']['tugas_belajar'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Izin Belajar --}}
            <div class="bg-gradient-to-br from-white to-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Izin Belajar</p>
                        <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $statistik['per_status_dosen']['izin_belajar'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <i class="fas fa-book-reader text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen CLTY --}}
            <div class="bg-gradient-to-br from-white to-[#F8FAFC] rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">CLTY</p>
                        <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $statistik['per_status_dosen']['clty'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <i class="fas fa-user-clock text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Status Pegawai Chart --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 bg-[#C41E3A] h-4 rounded"></span>
                    <span>Distribusi Status Pegawai</span>
                </h3>
                <div class="h-64 relative">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- JFA Chart --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 bg-[#C41E3A] h-4 rounded"></span>
                    <span>Distribusi Jabatan Fungsional Akademik</span>
                </h3>
                <div class="h-64 relative">
                    <canvas id="jfaChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Detailed Tables (Prodi & KK) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Lokasi Kerja Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 bg-[#C41E3A] h-4 rounded"></span>
                    <span>Dosen per Lokasi Kerja (Program Studi)</span>
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 border-b border-gray-100">
                                <th class="px-4 py-3 text-left font-bold uppercase text-xs">Lokasi Kerja (Prodi)</th>
                                <th class="px-4 py-3 text-right font-bold uppercase text-xs w-20">Jumlah</th>
                                <th class="px-4 py-3 text-right font-bold uppercase text-xs w-48">Persentase & Distribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($statistik['per_prodi'] as $prodi)
                            @php
                                $percent = $statistik['total_dosen'] > 0 ? round(($prodi['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <td class="px-4 py-3 text-gray-700 font-medium">{{ $prodi['nama'] }}</td>
                                <td class="px-4 py-3 text-right font-extrabold text-gray-950">{{ $prodi['jumlah'] }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <span class="text-xs font-bold text-gray-500">{{ $percent }}%</span>
                                        <div class="w-24 bg-gray-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-[#C41E3A] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-xs">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kelompok Keahlian Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 bg-[#C41E3A] h-4 rounded"></span>
                    <span>Dosen per Kelompok Keahlian</span>
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 border-b border-gray-100">
                                <th class="px-4 py-3 text-left font-bold uppercase text-xs">Kelompok Keahlian</th>
                                <th class="px-4 py-3 text-right font-bold uppercase text-xs w-20">Jumlah</th>
                                <th class="px-4 py-3 text-right font-bold uppercase text-xs w-48">Persentase & Distribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($statistik['per_kelompok_keahlian'] as $kelompok)
                            @php
                                $percent = $statistik['total_dosen'] > 0 ? round(($kelompok['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <td class="px-4 py-3 text-gray-700 font-medium">{{ $kelompok['nama'] }}</td>
                                <td class="px-4 py-3 text-right font-extrabold text-gray-950">{{ $kelompok['jumlah'] }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <span class="text-xs font-bold text-gray-500">{{ $percent }}%</span>
                                        <div class="w-24 bg-gray-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-[#FBB03B] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-xs">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @php
        $jsStatistikData = [
            'perStatus' => [
                'tetap' => $statistik['per_status']['tetap'] ?? 0,
                'perbantuan' => $statistik['per_status']['perbantuan'] ?? 0,
                'profesionalFull' => $statistik['per_status']['profesional_full'] ?? 0,
                'profesionalPart' => $statistik['per_status']['profesional_part'] ?? 0
            ],
            'perJfa' => [
                'njfa' => $statistik['per_jfa']['njfa'] ?? 0,
                'asistenAhli' => $statistik['per_jfa']['asisten_ahli'] ?? 0,
                'lektor' => $statistik['per_jfa']['lektor'] ?? 0,
                'lektorKepala' => $statistik['per_jfa']['lektor_kepala'] ?? 0,
                'guruBesar' => $statistik['per_jfa']['guru_besar'] ?? 0
            ]
        ];
    @endphp

    <script id="statistik-data" type="application/json">@json($jsStatistikData)</script>

    <script>
        const statistikData = JSON.parse(document.getElementById('statistik-data').textContent);

        document.addEventListener('DOMContentLoaded', function() {
            // Status Pegawai Chart (Doughnut)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tetap', 'Perbantuan', 'Profesional Full Time', 'Profesional Part Time'],
                    datasets: [{
                        data: [
                            statistikData.perStatus.tetap,
                            statistikData.perStatus.perbantuan,
                            statistikData.perStatus.profesionalFull,
                            statistikData.perStatus.profesionalPart
                        ],
                        backgroundColor: [
                            '#C41E3A', // Telkom Crimson Red
                            '#FBB03B', // Telkom Gold
                            '#3B82F6', // Blue
                            '#8B5CF6'  // Purple
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 16,
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 11,
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });

            // JFA Chart (Bar)
            const jfaCtx = document.getElementById('jfaChart').getContext('2d');
            new Chart(jfaCtx, {
                type: 'bar',
                data: {
                    labels: ['NJFA', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'],
                    datasets: [{
                        label: 'Jumlah Dosen',
                        data: [
                            statistikData.perJfa.njfa,
                            statistikData.perJfa.asistenAhli,
                            statistikData.perJfa.lektor,
                            statistikData.perJfa.lektorKepala,
                            statistikData.perJfa.guruBesar
                        ],
                        backgroundColor: [
                            '#C41E3A', // Crimson
                            '#E09A2A', // Amber/Dark Gold
                            '#10B981', // Green
                            '#3B82F6', // Blue
                            '#EC4899'  // Pink
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: '#F1F5F9'
                            },
                            ticks: {
                                stepSize: 1,
                                font: {
                                    family: "'Outfit', sans-serif"
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Outfit', sans-serif",
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>