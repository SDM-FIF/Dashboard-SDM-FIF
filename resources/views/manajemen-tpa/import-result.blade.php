<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Import TPA - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Bar --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Manajemen TPA</h1>
        </div>

        {{-- Progress Steps --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between relative">
                {{-- Step 1: Template --}}
                <div class="flex flex-col items-center flex-1 relative text-green-600">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2">
                        <i class="fas fa-check text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Template</span>
                    <span class="text-xs">Unduh Template</span>
                </div>

                {{-- Line 1 --}}
                <div class="flex-1 h-1 bg-green-500 mx-2"></div>

                {{-- Step 2: Import --}}
                <div class="flex flex-col items-center flex-1 relative text-green-600">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2">
                        <i class="fas fa-check text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Import</span>
                    <span class="text-xs">Upload & Preview</span>
                </div>

                {{-- Line 2 --}}
                <div class="flex-1 h-1 bg-green-500 mx-2"></div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center flex-1 relative text-green-600">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 shadow-lg shadow-green-200">
                        <i class="fas fa-user-tie text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Simpan</span>
                </div>
            </div>
        </div>

        {{-- Result Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 text-center">
            <div class="mb-6">
                <div class="inline-block p-6 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-check-circle text-6xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Proses Import Selesai!</h2>
                <p class="text-gray-600">Data Tenaga Pendukung Akademik telah berhasil diproses ke sistem.</p>
            </div>

            {{-- Statistik Ringkas --}}
            <div class="grid grid-cols-2 gap-4 max-w-md mx-auto mb-8">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <span class="block text-2xl font-bold text-green-700">{{ $result['success'] ?? 0 }}</span>
                    <span class="text-sm text-green-600 font-semibold">Berhasil</span>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <span class="block text-2xl font-bold text-red-700">{{ $result['failed'] ?? 0 }}</span>
                    <span class="text-sm text-red-600 font-semibold">Gagal/Duplikat</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                <a href="{{ route('manajemen-tpa.kelola-data') }}"
                    class="w-full md:w-auto px-8 py-3 bg-gray-800 hover:bg-black text-white font-bold rounded-lg transition-all shadow-md">
                    <i class="fas fa-users mr-2"></i> Lihat Data TPA
                </a>
                
                <a href="{{ route('manajemen-tpa.import-data', ['step' => 1, 'reset' => 1]) }}"
                    class="w-full md:w-auto px-8 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-bold rounded-lg transition-all shadow-md">
                    <i class="fas fa-redo mr-2"></i> Import Data Lagi
                </a>
            </div>
        </div>
    </main>
</body>
</html>