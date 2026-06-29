<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Import Data Dosen - Dashboard SDM FIF</title>
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

        @php
            if(request()->has('step')) {
                $currentStep = (int) request()->get('step');
            } else {
                $currentStep = 1;
                if(session()->has('import_data_dosen')) {
                    $currentStep = 2;
                }
                if(session()->has('import_result_dosen')) {
                    $currentStep = 3;
                }
            }
            $fileUploaded = session()->has('file_uploaded_dosen');
        @endphp

        {{-- Progress Steps Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-shadow">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-0">
                
                {{-- Step 1 --}}
                <a href="{{ route('manajemen-dosen.import.view', ['step' => 1]) }}" 
                   class="flex flex-col items-center text-center md:flex-1 group">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all border-2 
                        {{ $currentStep == 1 ? 'bg-[#C41E3A] text-white border-[#C41E3A]' : (($currentStep > 1 || $fileUploaded) ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-400 border-gray-200') }}">
                        @if($currentStep > 1 || $fileUploaded)
                            <i class="fas fa-check"></i>
                        @else
                            1
                        @endif
                    </div>
                    <span class="text-sm font-bold {{ $currentStep == 1 ? 'text-[#C41E3A]' : 'text-gray-700' }}">Unduh Template</span>
                    <span class="text-xs text-gray-400 mt-0.5">Siapkan format berkas</span>
                </a>

                <div class="hidden md:block flex-1 h-0.5 {{ ($currentStep >= 2 || $fileUploaded) ? 'bg-emerald-500' : 'bg-gray-100' }} mx-4"></div>

                {{-- Step 2 --}}
                <a href="{{ route('manajemen-dosen.import.view', ['step' => 2]) }}" 
                   class="flex flex-col items-center text-center md:flex-1 group">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all border-2 
                        {{ $currentStep == 2 ? 'bg-[#C41E3A] text-white border-[#C41E3A]' : ($currentStep > 2 ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-400 border-gray-200') }}">
                        @if($currentStep > 2)
                            <i class="fas fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <span class="text-sm font-bold {{ $currentStep == 2 ? 'text-[#C41E3A]' : 'text-gray-700' }}">Unggah & Validasi</span>
                    <span class="text-xs text-gray-400 mt-0.5">Unggah spreadsheet Anda</span>
                </a>

                <div class="hidden md:block flex-1 h-0.5 {{ $currentStep >= 3 ? 'bg-emerald-500' : 'bg-gray-100' }} mx-4"></div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center text-center md:flex-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all border-2 
                        {{ $currentStep == 3 ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-400 border-gray-200' }}">
                        3
                    </div>
                    <span class="text-sm font-bold {{ $currentStep == 3 ? 'text-emerald-500' : 'text-gray-700' }}">Selesai</span>
                    <span class="text-xs text-gray-400 mt-0.5">Lihat statistik akhir</span>
                </div>

            </div>
        </div>

        {{-- STEP 1: Download Template --}}
        @if($currentStep == 1)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center max-w-2xl mx-auto hover:shadow-md transition-shadow">
            <div class="w-20 h-20 bg-red-50 text-[#C41E3A] rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-download text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Template Import Data Dosen</h2>
            <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">Untuk menghindari kesalahan pembacaan sistem, harap gunakan berkas template Excel resmi yang telah disediakan di bawah ini.</p>
            
            <a href="{{ route('manajemen-dosen.import.template') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-download"></i>
                <span>Unduh Template (.xlsx)</span>
            </a>
        </div>
        @endif

        {{-- STEP 2: Import Section --}}
        @if($currentStep == 2)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow">
            
            {{-- Upload Section --}}
            <div class="max-w-2xl mx-auto mb-8 text-center">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Unggah File Spreadsheet</h2>
                
                <form action="{{ route('manajemen-dosen.import.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="border-2 border-dashed border-gray-200 hover:border-[#C41E3A] rounded-2xl p-6 transition-colors bg-[#F8FAFC] relative">
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div class="flex flex-col items-center justify-center py-4">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3" id="uploadIcon"></i>
                            <p class="text-sm font-semibold text-gray-700" id="fileNamePlaceholder">Pilih atau Seret file Excel Anda di sini</p>
                            <p class="text-xs text-gray-400 mt-1">Mendukung format file .xlsx, .xls, atau .csv (maksimal 10MB)</p>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 mx-auto">
                        <i class="fas fa-upload"></i>
                        <span>Unggah File</span>
                    </button>
                </form>
            </div>

            {{-- Preview Import Table --}}
            <div class="pt-6 border-t border-gray-50">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Preview Data Dosen</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Sistem memvalidasi format data di bawah ini secara real-time</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 border-b border-gray-100">
                                <th class="px-3 py-3 text-center font-bold uppercase w-14">Valid</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Nama Lengkap</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">NIP</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Kode</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Prodi</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Keahlian</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">JFA</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Status</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Pendidikan S1</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Pendidikan S2</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Pendidikan S3</th>
                                <th class="px-3 py-3 text-left font-bold uppercase">Kesalahan / Error</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if(session()->has('import_data_dosen') && count(session('import_data_dosen')) > 0)
                                @foreach(session('import_data_dosen') as $row)
                                <tr class="{{ $row['is_valid'] ? 'bg-white hover:bg-gray-50' : 'bg-red-50/40 hover:bg-red-50/60' }} transition-colors">
                                    <td class="px-3 py-3.5 text-center">
                                        @if($row['is_valid'])
                                            <span class="inline-flex p-1 bg-emerald-100 text-emerald-700 rounded-full">
                                                <i class="fas fa-check text-[10px]"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex p-1 bg-red-100 text-red-700 rounded-full">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 font-bold text-gray-800">
                                        @if($row['front_title']){{ $row['front_title'] }} @endif{{ $row['nama_lengkap'] }}@if($row['back_title']), {{ $row['back_title'] }}@endif
                                    </td>
                                    <td class="px-3 py-3.5 font-semibold text-gray-500">{{ $row['nip'] }}</td>
                                    <td class="px-3 py-3.5 font-semibold text-gray-600">{{ $row['kode_dosen'] }}</td>
                                    <td class="px-3 py-3.5 text-gray-600">{{ $row['prodi_name'] ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-gray-600">{{ $row['kelompok_keahlian_name'] ?? '-' }}</td>
                                    <td class="px-3 py-3.5 font-bold text-blue-700">{{ $row['jabatan'] ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-gray-600">{{ $row['status_pegawai'] ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-gray-500">
                                        @if(isset($row['universitas_s1']))
                                            <div class="font-bold text-gray-700">{{ $row['universitas_s1'] }}</div>
                                            <div>{{ $row['prodi_s1'] }} (Lulus: {{ $row['tanggal_lulus_s1'] }})</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-gray-500">
                                        @if(isset($row['universitas_s2']))
                                            <div class="font-bold text-gray-700">{{ $row['universitas_s2'] }}</div>
                                            <div>{{ $row['prodi_s2'] }} (Lulus: {{ $row['tanggal_lulus_s2'] }})</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-gray-500">
                                        @if(isset($row['universitas_s3']))
                                            <div class="font-bold text-gray-700">{{ $row['universitas_s3'] }}</div>
                                            <div>{{ $row['prodi_s3'] }} (Lulus: {{ $row['tanggal_lulus_s3'] }})</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5">
                                        @if(!empty($row['errors']))
                                            <div class="text-red-600 font-medium flex flex-col gap-0.5">
                                                @foreach($row['errors'] as $err)
                                                    <span>• {{ $err }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-emerald-600 font-bold">Valid</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" class="px-4 py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                                <i class="fas fa-file-excel text-4xl"></i>
                                            </div>
                                            <p class="font-medium text-gray-500">Berkas spreadsheet belum diunggah</p>
                                            <p class="text-xs text-gray-400 max-w-xs">Silakan unggah file Excel di atas untuk menampilkan hasil tinjauan data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Action Panel --}}
                @if(session()->has('import_data_dosen') && count(session('import_data_dosen')) > 0)
                <div class="mt-6 flex items-center justify-between gap-4 border-t border-gray-100 pt-6">
                    <p class="text-xs text-gray-400">Pastikan memeriksa seluruh pesan kesalahan berwarna merah sebelum menyimpan.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('manajemen-dosen.import.view', ['step' => 2, 'reset' => 1]) }}" 
                           class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-all">
                            <i class="fas fa-redo mr-1"></i> Upload Ulang
                        </a>
                        <form action="{{ route('manajemen-dosen.import.save') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl text-sm transition-all shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Data Valid
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

        </div>
        @endif
    </main>

    {{-- SweetAlert2 / Toast scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Drag and drop / file input listener
            const fileInput = document.getElementById('fileInput');
            const filePlaceholder = document.getElementById('fileNamePlaceholder');
            const uploadIcon = document.getElementById('uploadIcon');

            if(fileInput && filePlaceholder) {
                fileInput.addEventListener('change', function() {
                    if(this.files && this.files.length > 0) {
                        filePlaceholder.innerText = this.files[0].name;
                        filePlaceholder.classList.add('text-[#C41E3A]');
                        if(uploadIcon) {
                            uploadIcon.className = 'fas fa-file-excel text-3xl text-green-600 mb-3';
                        }
                    } else {
                        filePlaceholder.innerText = 'Pilih atau Seret file Excel Anda di sini';
                        filePlaceholder.classList.remove('text-[#C41E3A]');
                        if(uploadIcon) {
                            uploadIcon.className = 'fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3';
                        }
                    }
                });
            }

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#C41E3A'
                });
            @endif
        });
    </script>
</body>
</html>
