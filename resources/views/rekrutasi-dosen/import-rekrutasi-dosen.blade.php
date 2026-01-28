<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Import Rekrutasi Dosen - Dashboard SDM FIF</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Rekrutasi Dosen</h1>
        </div>

        {{-- Progress Steps - SELALU TAMPIL --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between relative">
                @php
                    // Prioritize step parameter from request
                    if(request()->has('step')) {
                        $currentStep = (int) request()->get('step');
                    } else {
                        // Auto-determine step based on session
                        $currentStep = 1; // Default to step 1 (Template)
                        if(session()->has('import_data')) {
                            $currentStep = 2; // Import
                        }
                        if(session()->has('import_result')) {
                            $currentStep = 3; // Selesai
                        }
                    }
                    
                    // Check if file has been uploaded (persists until user clicks upload ulang)
                    $fileUploaded = session()->has('file_uploaded');
                @endphp

                {{-- Step 1: Template --}}
                <a href="{{ route('rekrutasi-dosen.import.view', ['step' => 1]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 1 ? 'text-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 1 ? 'bg-[#FBB03B]' : (($currentStep > 1 || $fileUploaded) ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ ($currentStep > 1 || $fileUploaded) ? 'fa-check' : 'fa-file-download' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Template</span>
                    <span class="text-xs">Unduh Template</span>
                </a>

                {{-- Line 1 --}}
                <div class="flex-1 h-1 {{ ($currentStep >= 2 || $fileUploaded) ? 'bg-[#FBB03B]' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 2: Import --}}
                <a href="{{ route('rekrutasi-dosen.import.view', ['step' => 2]) }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 2 ? 'text-[#FBB03B]' : ($currentStep > 2 ? 'text-green-600' : 'text-gray-400') }} cursor-pointer hover:opacity-80">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 2 ? 'bg-[#FBB03B]' : ($currentStep > 2 ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas {{ $currentStep > 2 ? 'fa-check' : 'fa-cloud-upload-alt' }} text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Import</span>
                    <span class="text-xs">Import File</span>
                </a>

                {{-- Line 2 --}}
                <div class="flex-1 h-1 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>

                {{-- Step 3: Selesai --}}
                <a href="{{ session()->has('import_result') ? route('rekrutasi-dosen.import.result') : 'javascript:void(0)' }}" 
                   class="flex flex-col items-center flex-1 relative {{ $currentStep == 3 ? 'text-green-600' : 'text-gray-400' }} {{ session()->has('import_result') ? 'cursor-pointer hover:opacity-80' : 'cursor-not-allowed' }}">
                    <div class="w-16 h-16 rounded-full {{ $currentStep == 3 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mb-2 transition-all">
                        <i class="fas fa-check-circle text-2xl text-white"></i>
                    </div>
                    <span class="text-sm font-semibold">Selesai</span>
                    <span class="text-xs">Hasil Import</span>
                </a>
            </div>
        </div>

        {{-- STEP 1: Template Download (Tampil jika step=1) --}}
        @if($currentStep == 1)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <div class="text-center">
                <div class="mb-6">
                    <div class="inline-block p-6 bg-red-100 rounded-full">
                        <i class="fas fa-file-download text-6xl text-[#C41E3A]"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-[#C41E3A] mb-4">Template Import Rekrutasi Dosen</h2>
                <p class="text-gray-600 mb-6">Klik tombol di bawah ini</p>
                
                <a href="{{ route('rekrutasi-dosen.import.template') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    Unduh Template
                </a>
            </div>
        </div>
        @endif

        {{-- STEP 2: Import Section (Tampil jika step=2) --}}
        @if($currentStep == 2)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-[#C41E3A] mb-4 flex items-center">
                <i class="fas fa-cloud-upload-alt mr-2"></i>
                Import
            </h2>

            {{-- Upload Form --}}
            <form action="{{ route('rekrutasi-dosen.import.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div class="flex items-center space-x-4">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" required>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold rounded-lg transition-all duration-200 shadow-md">
                        <i class="fas fa-upload mr-2"></i>
                        Upload File
                    </button>
                </div>
            </form>

            {{-- Preview Import Table --}}
            <div>
                <h3 class="text-lg font-bold text-[#C41E3A] mb-2">Preview Import Rekrutasi Dosen</h3>
                <p class="text-sm text-gray-600 mb-4">Hanya data valid yang akan diproses</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-xs">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Valid</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Nama</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">JK</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Tahun Ajar</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Prodi</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Jalur Lamaran</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">H-Index</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300" colspan="3" style="text-align: center;">Pendidikan S1</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300" colspan="3" style="text-align: center;">Pendidikan S2</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300" colspan="3" style="text-align: center;">Pendidikan S3</th>
                                <th class="px-2 py-2 text-left text-xs font-semibold uppercase border border-gray-300">Error</th>
                            </tr>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Universitas</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Prodi</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Tgl Lulus</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Universitas</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Prodi</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Tgl Lulus</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Universitas</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Prodi</th>
                                <th class="px-2 py-2 text-center text-xs border border-gray-300">Tgl Lulus</th>
                                <th class="px-2 py-2 border border-gray-300"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @if(session()->has('import_data') && count(session('import_data')) > 0)
                                @foreach(session('import_data') as $row)
                                <tr class="{{ $row['is_valid'] ? 'hover:bg-gray-50' : 'bg-red-50' }} border-b border-gray-200">
                                    <td class="px-2 py-2 text-center border border-gray-300">
                                        @if($row['is_valid'])
                                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        @else
                                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['nama_calon'] }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['jenis_kelamin'] }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['tahun_ajar'] }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['prodi_name'] }}</td>
                                    <!-- Jalur Lamaran -->
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['jalur_lamaran']))
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                                {{ $row['jalur_lamaran'] }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- H-Index -->
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['h_index']))
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                                {{ $row['h_index'] }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- S1 -->
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['universitas_s1'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['prodi_s1'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['tanggal_lulus_s1']))
                                            {{ \Carbon\Carbon::parse($row['tanggal_lulus_s1'])->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- S2 -->
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['universitas_s2'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['prodi_s2'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['tanggal_lulus_s2']))
                                            {{ \Carbon\Carbon::parse($row['tanggal_lulus_s2'])->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- S3 -->
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['universitas_s3'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">{{ $row['prodi_s3'] ?? '-' }}</td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['tanggal_lulus_s3']))
                                            {{ \Carbon\Carbon::parse($row['tanggal_lulus_s3'])->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 text-xs border border-gray-300">
                                        @if(!empty($row['errors']))
                                            <span class="text-red-600">{{ implode(', ', $row['errors']) }}</span>
                                        @else
                                            <span class="text-green-600">Valid</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="17" class="px-4 py-12 text-center text-gray-500 border border-gray-300">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
                                            <p>No data shown available in table</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Save Button --}}
                @if(session()->has('import_data') && count(session('import_data')) > 0)
                <div class="mt-6 flex items-center space-x-4">
                    <form action="{{ route('rekrutasi-dosen.import.save') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data Valid
                        </button>
                    </form>

                    <a href="{{ route('rekrutasi-dosen.import.view', ['step' => 2, 'reset' => 1]) }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        <i class="fas fa-redo mr-2"></i>
                        Upload Ulang
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </main>

    {{-- Messages --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <script>
        // Auto-hide messages
        setTimeout(() => {
            document.querySelectorAll('.fixed.z-50').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            });
        }, 3000);
    </script>
</body>
</html>