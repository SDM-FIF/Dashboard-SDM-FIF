<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Import Data TPA - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    
    <x-navbar />
    
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Import TPA</h1>
                <p class="text-gray-600 mt-2">Upload data Tenaga Pendukung Akademik secara massal menggunakan Excel</p>
            </div>
            <a href="{{ route('manajemen-tpa.kelola-data') }}" class="text-gray-500 hover:text-[#C41E3A]">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        {{-- Progress Steps --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between relative">
                @php
                    if(request()->has('step')) {
                        $currentStep = (int) request()->get('step');
                    } else {
                        $currentStep = 1;
                        if(session()->has('tpa_import_data')) $currentStep = 2;
                        if(session()->has('import_result')) $currentStep = 3;
                    }
                    $fileUploaded = session()->has('file_uploaded');
                @endphp

                {{-- Step 1: Template --}}
                <a href="{{ route('manajemen-tpa.import-data', ['step' => 1]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 1 ? 'text-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 1 ? 'bg-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ ($currentStep > 1 || $fileUploaded) ? 'fa-check' : 'fa-file-excel' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Template</span>
                    <span class="text-xs">Unduh Format</span>
                </a>

                <div class="flex-1 h-1 {{ ($currentStep >= 2 || $fileUploaded) ? 'bg-[#FBB03B]' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 2: Import --}}
                <a href="{{ route('manajemen-tpa.import-data', ['step' => 2]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 2 ? 'text-[#FBB03B]' : ($currentStep > 2 ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 2 ? 'bg-[#FBB03B]' : ($currentStep > 2 ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ $currentStep > 2 ? 'fa-check' : 'fa-file-import' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Import</span>
                    <span class="text-xs">Upload & Preview</span>
                </a>

                <div class="flex-1 h-1 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center flex-1 relative {{ $currentStep == 3 ? 'text-green-600' : 'text-gray-400' }}">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 3 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check-double text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Import</span>
                </div>
            </div>
        </div>

        {{-- STEP 1: Template Download --}}
        @if($currentStep == 1)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 animate-fade-in text-center">
            <div class="mb-6">
                <div class="inline-block p-6 bg-red-100 rounded-full">
                    <i class="fas fa-user-tie text-6xl text-[#C41E3A]"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-[#C41E3A] mb-4">Template Import Data TPA</h2>
            <p class="text-gray-600 mb-6 max-w-lg mx-auto">
                Gunakan template standar untuk memastikan data <b>NIP, Nama, Golongan,</b> dan <b>Lokasi Kerja</b> tersimpan dengan benar di sistem.
            </p>
            
            <a href="{{ route('manajemen-tpa.download-template') }}" 
            class="inline-flex items-center px-6 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md">
                <i class="fas fa-download mr-2"></i> Unduh Template TPA
            </a>
            
            <div class="mt-6">
                <a href="{{ route('manajemen-tpa.import-data', ['step' => 2]) }}" class="text-sm text-blue-600 hover:underline">
                    Sudah punya file? Lanjut ke Upload <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- STEP 2: Import Section --}}
        @if($currentStep == 2)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 animate-fade-in">
            <h2 class="text-xl font-bold text-[#C41E3A] mb-4 flex items-center">
                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload & Preview Data TPA
            </h2>

            <form action="{{ route('manajemen-tpa.import-process') }}" method="POST" enctype="multipart/form-data" class="mb-8 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                @csrf
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Excel TPA</label>
                        <input type="file" name="file" accept=".xlsx,.xls" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#C41E3A] hover:file:bg-red-100" required>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md">
                        Preview Data
                    </button>
                </div>
            </form>

            @if(session()->has('tpa_import_data'))
            <div>
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-lg font-bold text-[#C41E3A]">Pratinjau Data TPA</h3>
                    <div class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Siap: {{ collect(session('tpa_import_data'))->where('is_duplicate', false)->count() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                            Duplikat/Invalid: {{ collect(session('tpa_import_data'))->where('is_duplicate', true)->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-300">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase w-16">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">NIP</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Golongan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Lokasi Kerja</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(session('tpa_import_data') as $row)
                            <tr class="{{ $row['is_duplicate'] ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                <td class="px-4 py-3 text-center">
                                    <i class="fas {{ $row['is_duplicate'] ? 'fa-times-circle text-red-500' : 'fa-check-circle text-green-500' }} text-xl"></i>
                                </td>
                                <td class="px-4 py-3 text-sm font-mono {{ $row['is_duplicate'] ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                    {{ $row['nip'] }}
                                    @if($row['is_duplicate']) <br><span class="text-[10px] uppercase">Sudah Ada</span> @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $row['nama_lengkap'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $row['jabatan'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $row['lokasi_kerja'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex gap-4">
                    <form action="{{ route('manajemen-tpa.import.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Data TPA
                        </button>
                    </form>
                    <a href="{{ route('manajemen-tpa.import-data', ['step' => 2, 'reset' => 1]) }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md text-center">
                        Batalkan
                    </a>
                </div>
            </div>
            @endif
        </div>
        @endif
    </main>

    {{-- Toast Notif --}}
    @if(session('success'))
        <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 flex items-center animate-fade-in-down">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div><h4 class="font-bold">Berhasil!</h4><p class="text-sm">{{ session('success') }}</p></div>
        </div>
    @endif

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if(toast) toast.remove();
        }, 4000);
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out; }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>