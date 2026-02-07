<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Laporan Dosen - Dashboard SDM</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Page Title --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Laporan Dosen</h1>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            {{-- Total Dosen --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Dosen</p>
                        <p class="text-3xl font-bold text-red-600">{{ $statistik['total_dosen'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-users text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Aktif --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dosen Aktif</p>
                        <p class="text-3xl font-bold text-green-600">{{ $statistik['per_status_dosen']['aktif'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Tugas Belajar --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tugas Belajar</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $statistik['per_status_dosen']['tugas_belajar'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen Izin Belajar --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Izin Belajar</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $statistik['per_status_dosen']['izin_belajar'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-book-reader text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Dosen CLTY --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dosen CLTY</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $statistik['per_status_dosen']['clty'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-user-clock text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Status Pegawai Chart --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Distribusi Status Pegawai</h3>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- JFA Chart --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Distribusi Jabatan Fungsional Akademik</h3>
                <div class="h-64">
                    <canvas id="jfaChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Detail Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Lokasi Kerja Table --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Dosen per Lokasi Kerja</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-semibold text-gray-700">Lokasi Kerja</th>
                                <th class="text-right py-2 font-semibold text-gray-700">Jumlah</th>
                                <th class="text-right py-2 font-semibold text-gray-700">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_prodi'] as $prodi)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-800">{{ $prodi['nama'] }}</td>
                                <td class="text-right py-3 font-medium">{{ $prodi['jumlah'] }}</td>
                                <td class="text-right py-3 text-sm text-gray-600">
                                    {{ $statistik['total_dosen'] > 0 ? round(($prodi['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kelompok Keahlian Table --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Dosen per Kelompok Keahlian</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-semibold text-gray-700">Kelompok Keahlian</th>
                                <th class="text-right py-2 font-semibold text-gray-700">Jumlah</th>
                                <th class="text-right py-2 font-semibold text-gray-700">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_kelompok_keahlian'] as $kelompok)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-800">{{ $kelompok['nama'] }}</td>
                                <td class="text-right py-3 font-medium">{{ $kelompok['jumlah'] }}</td>
                                <td class="text-right py-3 text-sm text-gray-600">
                                    {{ $statistik['total_dosen'] > 0 ? round(($kelompok['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Export Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Export Laporan</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('manajemen-dosen.laporan.export-pdf') }}"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
                <button onclick="printReport()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
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
            // Status Pegawai Chart
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
                            '#10B981', // Green
                            '#F59E0B', // Yellow
                            '#3B82F6', // Blue
                            '#8B5CF6' // Purple
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
                            position: 'bottom'
                        }
                    }
                }
            });

            // JFA Chart
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
                            '#EF4444', // Red
                            '#F59E0B', // Orange
                            '#10B981', // Green
                            '#3B82F6', // Blue
                            '#EC4899'  // Pink
                        ],
                        borderRadius: 4,
                        borderSkipped: false,
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
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });

        // Export Functions
        function printReport() {
            window.print();
        }
    </script>

    {{-- Print Styles --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            main,
            main * {
                visibility: visible;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</body>

</html>