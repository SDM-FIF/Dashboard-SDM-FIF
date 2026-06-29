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
        {{-- Topbar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="mb-8 mt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Rekrutasi Dosen</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Unggah data calon dosen secara massal menggunakan template spreadsheet Excel/CSV.</p>
        </div>

        {{-- Progress Steps --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between relative max-w-3xl mx-auto">
                {{-- Step 1: Template --}}
                <a href="{{ route('rekrutasi-dosen.import.view', ['step' => 1]) }}" 
                   class="flex flex-col items-center flex-1 relative text-center text-emerald-600 hover:opacity-85 transition-all">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-2 font-bold transition-all">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider">Template</span>
                    <span class="text-[10px] font-semibold text-gray-400 mt-0.5">Unduh Template</span>
                </a>

                {{-- Connector Line 1 --}}
                <div class="flex-1 h-1 bg-emerald-500 -mt-6 mx-2 rounded-full"></div>

                {{-- Step 2: Import --}}
                <a href="{{ route('rekrutasi-dosen.import.view', ['step' => 2]) }}" 
                   class="flex flex-col items-center flex-1 relative text-center text-emerald-600 hover:opacity-85 transition-all">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-2 font-bold transition-all">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider">Unggah File</span>
                    <span class="text-[10px] font-semibold text-gray-400 mt-0.5">Import & Validasi</span>
                </a>

                {{-- Connector Line 2 --}}
                <div class="flex-1 h-1 bg-emerald-500 -mt-6 mx-2 rounded-full"></div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center flex-1 relative text-center text-emerald-600">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white ring-4 ring-emerald-100 flex items-center justify-center mb-2 font-bold transition-all">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider">Selesai</span>
                    <span class="text-[10px] font-semibold text-gray-400 mt-0.5">Hasil Import</span>
                </div>
            </div>
        </div>

        {{-- Success Card Result --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow duration-300 max-w-2xl mx-auto">
            <div class="text-center py-6">
                <div class="mb-6 inline-flex p-6 bg-emerald-50 text-emerald-500 rounded-full border border-emerald-100">
                    <i class="fas fa-check-circle text-5xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Proses Import Selesai</h2>
                <p class="text-sm text-gray-500 mb-8 font-medium">Sistem telah selesai memproses data spreadsheet yang Anda unggah.</p>
                
                @if(isset($result['success']) && isset($result['failed']))
                <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto mb-8">
                    <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                        <div class="text-3xl font-extrabold text-emerald-600">{{ $result['success'] }}</div>
                        <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider mt-1">Berhasil</div>
                    </div>
                    <div class="p-4 bg-rose-50/50 rounded-2xl border border-rose-100">
                        <div class="text-3xl font-extrabold text-rose-600">{{ $result['failed'] }}</div>
                        <div class="text-xs font-bold text-rose-700 uppercase tracking-wider mt-1">Gagal</div>
                    </div>
                </div>
                @endif
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @if(isset($result['data']) && count($result['data']) > 0)
                    <a href="{{ route('rekrutasi-dosen.import.download-result') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-bold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm w-full sm:w-auto justify-center">
                        <i class="fas fa-download"></i>
                        <span>Unduh Hasil Import</span>
                    </a>
                    @else
                    <button disabled
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed w-full sm:w-auto justify-center border border-gray-200">
                        <i class="fas fa-download"></i>
                        <span>Lihat Data (Tidak Ada Data Berhasil)</span>
                    </button>
                    @endif
                    
                    <a href="{{ route('rekrutasi-dosen') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm w-full sm:w-auto justify-center">
                        <i class="fas fa-list"></i>
                        <span>Kembali ke Rekrutasi</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>