<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Laporan Rekrutasi Dosen - Dashboard SDM</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Laporan Rekrutasi Dosen</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Statistik dan ringkasan data penerimaan calon dosen.</p>
            </div>
            <a href="{{ route('rekrutasi-dosen.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Pendaftar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendaftar</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statistik['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Diterima --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Diterima</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $statistik['per_status']['LULUS'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <i class="fas fa-user-check text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Tidak Diterima --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Gagal</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1">{{ $statistik['per_status']['TIDAK LULUS'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-xl border border-rose-100">
                        <i class="fas fa-user-times text-rose-600 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Proses / Menunggu --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $statistik['per_status']['MENUNGGU'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Program Studi Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-building text-[#C41E3A]"></i> 
                    Pendaftar Berdasarkan Prodi
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="text-left py-3 px-4 font-semibold rounded-l-lg">Program Studi</th>
                                <th class="text-right py-3 px-4 font-semibold">Jumlah</th>
                                <th class="text-right py-3 px-4 font-semibold rounded-r-lg">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_prodi'] as $prodi => $jumlah)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $prodi }}</td>
                                <td class="text-right py-3 px-4 font-semibold">{{ $jumlah }}</td>
                                <td class="text-right py-3 px-4 text-sm text-gray-500 font-medium">
                                    {{ $statistik['total'] > 0 ? round(($jumlah / $statistik['total']) * 100, 1) : 0 }}%
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

            {{-- Jenjang Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-[#C41E3A]"></i> 
                    Pendaftar Berdasarkan Jenjang
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="text-left py-3 px-4 font-semibold rounded-l-lg">Jenjang</th>
                                <th class="text-right py-3 px-4 font-semibold">Jumlah</th>
                                <th class="text-right py-3 px-4 font-semibold rounded-r-lg">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_jenjang'] as $jenjang => $jumlah)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-gray-800 font-medium">{{ $jenjang }}</td>
                                <td class="text-right py-3 px-4 font-semibold">{{ $jumlah }}</td>
                                <td class="text-right py-3 px-4 text-sm text-gray-500 font-medium">
                                    {{ $statistik['total'] > 0 ? round(($jumlah / $statistik['total']) * 100, 1) : 0 }}%
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
