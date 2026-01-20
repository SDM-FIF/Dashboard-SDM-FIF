<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Pastikan @vite sesuai konfigurasi project Anda --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Import Data Mahasiswa - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    
    {{-- Sidebar (Asumsi komponen sudah ada) --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Bar (Asumsi komponen sudah ada) --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Import Mahasiswa</h1>
                <p class="text-gray-600 mt-2">Upload data mahasiswa secara massal menggunakan Excel</p>
            </div>
            <a href="{{ route('mahasiswa.kelola-data') }}" class="text-gray-500 hover:text-[#C41E3A]">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        {{-- Progress Steps - LOGIC PENENTUAN STEP --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between relative">
                @php
                    // Logika penentuan step otomatis
                    if(request()->has('step')) {
                        $currentStep = (int) request()->get('step');
                    } else {
                        $currentStep = 1; // Default: Download Template
                        if(session()->has('import_data')) {
                            $currentStep = 2; // Preview & Upload
                        }
                        if(session()->has('import_result')) {
                            $currentStep = 3; // Selesai
                        }
                    }
                    
                    // Cek apakah file baru saja diupload
                    $fileUploaded = session()->has('file_uploaded');
                @endphp

                {{-- Step 1: Template --}}
                <a href="{{ route('mahasiswa.import.view', ['step' => 1]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 1 ? 'text-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 1 ? 'bg-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ ($currentStep > 1 || $fileUploaded) ? 'fa-check' : 'fa-file-excel' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Template</span>
                    <span class="text-xs">Unduh Format</span>
                </a>

                {{-- Line 1 --}}
                <div class="flex-1 h-1 {{ ($currentStep >= 2 || $fileUploaded) ? 'bg-[#FBB03B]' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 2: Import --}}
                <a href="{{ route('mahasiswa.import.view', ['step' => 2]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 2 ? 'text-[#FBB03B]' : ($currentStep > 2 ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 2 ? 'bg-[#FBB03B]' : ($currentStep > 2 ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ $currentStep > 2 ? 'fa-check' : 'fa-file-import' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Import</span>
                    <span class="text-xs">Upload & Preview</span>
                </a>

                {{-- Line 2 --}}
                <div class="flex-1 h-1 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 3: Selesai --}}
                <a href="{{ session()->has('import_result') ? route('mahasiswa.import.result') : 'javascript:void(0)' }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 3 ? 'text-green-600' : 'text-gray-400' }} {{ session()->has('import_result') ? 'cursor-pointer hover:opacity-80' : 'cursor-not-allowed' }}">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 3 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check-double text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Import</span>
                </a>
            </div>
        </div>

        {{-- ================================================= --}}
        {{-- STEP 1: Template Download (Tampil jika step=1) --}}
        {{-- ================================================= --}}
        @if($currentStep == 1)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 animate-fade-in">
            <div class="text-center">
                <div class="mb-6">
                    <div class="inline-block p-6 bg-red-100 rounded-full">
                        <i class="fas fa-file-excel text-6xl text-[#C41E3A]"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-[#C41E3A] mb-4">Template Import Mahasiswa</h2>
                <p class="text-gray-600 mb-6 max-w-lg mx-auto">
                    Silakan unduh template Excel di bawah ini. Pastikan Anda mengisi kolom 
                    <b>NIM, Nama Lengkap, Program Studi,</b> dan <b>Status</b> sesuai format.
                </p>
                
                <a href="{{ route('mahasiswa.download.template') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    Unduh Template Excel
                </a>
                
                <div class="mt-6">
                    <a href="{{ route('mahasiswa.import.view', ['step' => 2]) }}" class="text-sm text-blue-600 hover:underline">
                        Sudah punya file? Lanjut ke Upload <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ================================================= --}}
        {{-- STEP 2: Import Section (Tampil jika step=2) --}}
        {{-- ================================================= --}}
        @if($currentStep == 2)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 animate-fade-in">
            <h2 class="text-xl font-bold text-[#C41E3A] mb-4 flex items-center">
                <i class="fas fa-cloud-upload-alt mr-2"></i>
                Upload & Preview Data
            </h2>

            {{-- Form Upload --}}
            <form action="{{ route('mahasiswa.import.upload') }}" method="POST" enctype="multipart/form-data" class="mb-8 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                @csrf
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" 
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-red-50 file:text-[#C41E3A]
                                      hover:file:bg-red-100" required>
                    </div>
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-2.5 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md">
                        <i class="fas fa-upload mr-2"></i>
                        Preview File
                    </button>
                </div>
            </form>

            {{-- Table Preview --}}
            @if(session()->has('import_data'))
            <div>
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <h3 class="text-lg font-bold text-[#C41E3A]">Preview Data</h3>
                        <p class="text-sm text-gray-600">Periksa kembali data sebelum disimpan.</p>
                    </div>
                    {{-- Ringkasan Validasi --}}
                    <div class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Valid: {{ collect(session('import_data'))->where('is_valid', true)->count() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                            Invalid: {{ collect(session('import_data'))->where('is_valid', false)->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase w-16">Valid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">NIM</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Program Studi</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count(session('import_data')) > 0)
                                @foreach(session('import_data') as $row)
                                <tr class="{{ $row['is_valid'] ? 'hover:bg-gray-50' : 'bg-red-50' }}">
                                    {{-- Kolom Status Validasi --}}
                                    <td class="px-4 py-3 text-center">
                                        @if($row['is_valid'])
                                            <i class="fas fa-check-circle text-green-500 text-xl" title="Data Valid"></i>
                                        @else
                                            <i class="fas fa-times-circle text-red-500 text-xl" title="Data Invalid"></i>
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom NIM --}}
                                    <td class="px-4 py-3 text-sm font-mono text-gray-700">
                                        {{ $row['nim'] }}
                                    </td>
                                    
                                    {{-- Kolom Nama --}}
                                    <td class="px-4 py-3 text-sm text-gray-700 font-medium">
                                        {{ $row['nama_lengkap'] }}
                                    </td>
                                    
                                    {{-- Kolom Prodi + Error Message --}}
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        <div class="flex flex-col">
                                            <span>{{ $row['prodi_name'] }}</span>
                                            {{-- Tampilkan Error Spesifik di sini --}}
                                            @if(!empty($row['errors']))
                                                <span class="text-xs text-red-600 font-semibold mt-1">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    {{ implode(', ', $row['errors']) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="px-4 py-3 text-sm text-center">
                                        @php
                                            $badgeColor = match($row['status']) {
                                                'AKTIF' => 'bg-green-100 text-green-800',
                                                'CUTI' => 'bg-yellow-100 text-yellow-800',
                                                'TIDAK AKTIF' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
                                            <p>File kosong atau tidak ada data yang terbaca.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Action Buttons --}}
                @if(collect(session('import_data'))->where('is_valid', true)->count() > 0)
                <div class="mt-6 flex flex-col md:flex-row items-center gap-4">
                    <form action="{{ route('mahasiswa.import.save') }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit" 
                                class="w-full md:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data Valid
                        </button>
                    </form>

                    <a href="{{ route('mahasiswa.import.view', ['step' => 2, 'reset' => 1]) }}" 
                       class="w-full md:w-auto px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md text-center flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>
                        Upload Ulang
                    </a>
                </div>
                @else
                    <div class="mt-6">
                        <p class="text-red-500 mb-2 font-semibold">Tidak ada data valid yang bisa disimpan.</p>
                         <a href="{{ route('mahasiswa.import.view', ['step' => 2, 'reset' => 1]) }}" 
                           class="inline-flex px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                            <i class="fas fa-redo mr-2"></i>
                            Upload Ulang
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </main>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <div id="toast-success" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 flex items-center animate-fade-in-down">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <h4 class="font-bold">Berhasil!</h4>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 flex items-center animate-fade-in-down">
            <i class="fas fa-times-circle mr-3 text-xl"></i>
            <div>
                <h4 class="font-bold">Gagal!</h4>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Simple Script for Auto-hide Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const toasts = document.querySelectorAll('[id^="toast-"]');
                toasts.forEach(el => {
                    el.style.transition = "opacity 0.5s ease-out";
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 4000);
        });
    </script>
    
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out; }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>