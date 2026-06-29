<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Import - Dashboard SDM FIF</title>
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
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Import Data Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Import data dosen secara masal menggunakan spreadsheet Excel/CSV.</p>
        </div>

        {{-- Progress Steps Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-0">
                
                {{-- Step 1 --}}
                <div class="flex flex-col items-center text-center md:flex-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 bg-[#F8FAFC] text-emerald-500 border-2 border-emerald-500">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-700">Unduh Template</span>
                    <span class="text-xs text-gray-400 mt-0.5">Siapkan format berkas</span>
                </div>

                <div class="hidden md:block flex-1 h-0.5 bg-emerald-500 mx-4"></div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center text-center md:flex-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 bg-[#F8FAFC] text-emerald-500 border-2 border-emerald-500">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-700">Unggah & Validasi</span>
                    <span class="text-xs text-gray-400 mt-0.5">Unggah spreadsheet Anda</span>
                </div>

                <div class="hidden md:block flex-1 h-0.5 bg-emerald-500 mx-4"></div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center text-center md:flex-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 bg-emerald-500 text-white border-2 border-emerald-500 shadow-sm">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-sm font-bold text-emerald-500">Selesai</span>
                    <span class="text-xs text-gray-400 mt-0.5">Lihat statistik akhir</span>
                </div>

            </div>
        </div>

        {{-- Success Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center max-w-2xl mx-auto hover:shadow-md transition-shadow">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Proses Import Selesai</h2>
            <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">Spreadsheet data dosen Anda telah berhasil diproses oleh sistem.</p>
            
            @if(isset($result['success']) && isset($result['failed']))
            <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto mb-8">
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100/50">
                    <div class="text-3xl font-extrabold text-emerald-600">{{ $result['success'] }}</div>
                    <div class="text-xs font-bold text-emerald-800/60 uppercase tracking-wider mt-1">Data Berhasil</div>
                </div>
                <div class="p-4 bg-red-50 rounded-2xl border border-red-100/50">
                    <div class="text-3xl font-extrabold text-red-600">{{ $result['failed'] }}</div>
                    <div class="text-xs font-bold text-red-800/60 uppercase tracking-wider mt-1">Data Gagal</div>
                </div>
            </div>
            @endif
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @if(isset($result['data']) && count($result['data']) > 0)
                <a href="{{ route('manajemen-dosen.import.download-result') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fas fa-download"></i>
                    <span>Unduh Laporan Hasil (.xlsx)</span>
                </a>
                @else
                <button disabled
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 font-semibold rounded-xl cursor-not-allowed">
                    <i class="fas fa-download"></i>
                    <span>Unduh Laporan (Kosong)</span>
                </button>
                @endif
                
                <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fas fa-users"></i>
                    <span>Kembali ke Kelola Data</span>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
