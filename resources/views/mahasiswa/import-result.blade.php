<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Import Mahasiswa - Dashboard SDM FIF</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Data Mahasiswa</h1>
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
                    <span class="text-xs">Import File</span>
                </div>

                {{-- Line 2 --}}
                <div class="flex-1 h-1 bg-green-500 mx-2"></div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center flex-1 relative text-green-600">
                    <div
                        class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 shadow-lg shadow-green-200">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Import</span>
                </div>
            </div>
        </div>

        {{-- Success Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <div class="text-center">
                <a href="{{ route('mahasiswa.import.view', ['step' => 1, 'reset' => 1]) }}"
                    class="px-8 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-bold rounded-lg transition-all shadow-md">
                    <i class="fas fa-redo mr-2"></i> Import Mahasiswa Baru
                </a>
            </div>