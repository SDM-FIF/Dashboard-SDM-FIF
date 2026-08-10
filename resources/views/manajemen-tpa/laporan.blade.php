<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Laporan TPA - Dashboard SDM</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        {{-- Top Search Bar --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Laporan TPA</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Statistik dan ringkasan data Tenaga Pendukung Akademik.</p>
            </div>
            <a href="{{ route('manajemen-tpa.kelola-data') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total TPA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total TPA</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statistik['totalTPA'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Pendidikan Tinggi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendidikan Tinggi</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $statistik['pendidikanTinggiCount'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <i class="fas fa-graduation-cap text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            {{-- Persen Pendidikan Tinggi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">% Pendidikan Tinggi</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $statistik['persenPendidikanTinggi'] ?? 0 }}%</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <i class="fas fa-percentage text-amber-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Lokasi Kerja Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-[#C41E3A]"></i> 
                    Sebaran Lokasi Kerja
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="text-left py-3 px-4 font-semibold rounded-l-lg">Lokasi Kerja</th>
                                <th class="text-right py-3 px-4 font-semibold">Jumlah</th>
                                <th class="text-right py-3 px-4 font-semibold rounded-r-lg">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['lokasiCounts'] as $lokasi => $jumlah)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $lokasi }}</td>
                                <td class="text-right py-3 px-4 font-semibold">{{ $jumlah }}</td>
                                <td class="text-right py-3 px-4 text-sm text-gray-500 font-medium">
                                    {{ $statistik['totalTPA'] > 0 ? round(($jumlah / $statistik['totalTPA']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pendidikan Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-graduate text-[#C41E3A]"></i> 
                    Sebaran Tingkat Pendidikan
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="text-left py-3 px-4 font-semibold rounded-l-lg">Pendidikan</th>
                                <th class="text-right py-3 px-4 font-semibold">Jumlah</th>
                                <th class="text-right py-3 px-4 font-semibold rounded-r-lg">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['pendidikanCountsMap'] as $pendidikan => $jumlah)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $pendidikan }}</td>
                                <td class="text-right py-3 px-4 font-semibold">{{ $jumlah }}</td>
                                <td class="text-right py-3 px-4 text-sm text-gray-500 font-medium">
                                    {{ $statistik['totalTPA'] > 0 ? round(($jumlah / $statistik['totalTPA']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Jabatan Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-briefcase text-[#C41E3A]"></i> 
                    Sebaran Jabatan
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="text-left py-3 px-4 font-semibold rounded-l-lg">Jabatan</th>
                                <th class="text-right py-3 px-4 font-semibold">Jumlah</th>
                                <th class="text-right py-3 px-4 font-semibold rounded-r-lg">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['jabatanCounts'] as $jabatan => $jumlah)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $jabatan }}</td>
                                <td class="text-right py-3 px-4 font-semibold">{{ $jumlah }}</td>
                                <td class="text-right py-3 px-4 text-sm text-gray-500 font-medium">
                                    {{ $statistik['totalTPA'] > 0 ? round(($jumlah / $statistik['totalTPA']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>