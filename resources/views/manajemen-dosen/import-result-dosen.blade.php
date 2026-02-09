<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Import - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Import Data Dosen</h1>
        </div>

        {{-- Progress Steps - SELALU TAMPIL --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between relative">
                {{-- Step 1: Template --}}
                <a href="{{ route('manajemen-dosen.import.view', ['step' => 1]) }}" 
                   class="flex flex-col items-center flex-1 relative text-green-600 cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Template</span>
                    <span class="text-xs">Unduh Template</span>
                </a>

                {{-- Line 1 --}}
                <div class="flex-1 h-1 bg-green-500 mx-2"></div>

                {{-- Step 2: Import --}}
                <a href="{{ route('manajemen-dosen.import.view', ['step' => 2]) }}" 
                   class="flex flex-col items-center flex-1 relative text-green-600 cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Import</span>
                    <span class="text-xs">Import File</span>
                </a>

                {{-- Line 2 --}}
                <div class="flex-1 h-1 bg-green-500 mx-2"></div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center flex-1 relative text-green-600">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check-circle text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Import</span>
                </div>
            </div>
        </div>

        {{-- Success Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <div class="text-center">
                <div class="mb-6">
                    <i class="fas fa-check-circle text-6xl text-green-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-600 mb-4">Proses Import Selesai</h2>
                <p class="text-gray-600 mb-2">Berikut adalah hasil import data dosen</p>
                
                @if(isset($result['success']) && isset($result['failed']))
                <div class="flex justify-center space-x-8 mb-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $result['success'] }}</div>
                        <div class="text-sm text-gray-600">Data Berhasil</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $result['failed'] }}</div>
                        <div class="text-sm text-gray-600">Data Gagal</div>
                    </div>
                </div>
                @endif
                
                <div class="flex justify-center space-x-4">
                    @if(isset($result['data']) && count($result['data']) > 0)
                    <a href="{{ route('manajemen-dosen.import.download-result') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Download Data
                    </a>
                    @else
                    <button disabled
                       class="inline-flex items-center px-6 py-3 bg-gray-400 text-gray-700 font-semibold rounded-lg cursor-not-allowed">
                        <i class="fas fa-download mr-2"></i>
                        Download Data (Tidak Ada Data Berhasil)
                    </button>
                    @endif
                    
                    <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#C41E3A] hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Dosen
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
