<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Mahasiswa Kompetisi - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Select2 style for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Select2 Custom Premium Styling to match other forms */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #e2e8f0 !important; /* gray-200 */
            border-radius: 0.75rem !important; /* rounded-xl */
            height: 3.25rem !important;
            padding: 0.5rem 1.25rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            outline: none !important;
            background-color: #F8FAFC !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #C41E3A !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 2px rgba(196, 30, 58, 0.15) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            font-weight: 500;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3.25rem !important;
            right: 1rem !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
            overflow: hidden !important;
            background-color: #fff !important;
            z-index: 9999 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #C41E3A !important;
            color: #fff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            outline: none !important;
            background-color: #F8FAFC !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #C41E3A !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 2px rgba(196, 30, 58, 0.15) !important;
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
        <div class="mb-8 mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('mahasiswa.kompetisi.index') }}"
                        class="hover:text-[#C41E3A] transition-colors duration-200 flex items-center gap-1.5 font-semibold">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke List
                    </a>
                </nav>
                <h1 class="text-3xl font-extrabold text-[#C41E3A] tracking-tight">Hubungkan Mahasiswa & Kompetisi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium font-medium">Daftarkan partisipasi mahasiswa dan raihan juaranya dalam ajang kompetisi.</p>
            </div>
            
            {{-- Quick Stats Card --}}
            <div class="bg-red-50 text-[#C41E3A] border border-red-100 rounded-2xl p-4 flex items-center gap-4 max-w-xs ml-auto shadow-sm">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-xl shadow-sm">
                    <i class="fas fa-trophy text-[#C41E3A]"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Apresiasi Prestasi</div>
                    <div class="text-xs text-gray-700 font-bold mt-0.5">Sains, Seni, Olahraga & Teknologi</div>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Column --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="p-6 border-b border-gray-50 bg-[#F8FAFC]/50">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-edit text-[#C41E3A]"></i>
                        Formulir Registrasi Kompetisi
                    </h2>
                </div>

                <form action="{{ route('mahasiswa.kompetisi.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    <div class="space-y-6">
                        {{-- Mahasiswa Select --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select name="mahasiswa_id" required id="select-mahasiswa" class="w-full">
                                <option value="">Cari berdasarkan NIM atau Nama Mahasiswa</option>
                                @foreach($mahasiswa as $item)
                                    <option value="{{ $item->id }}" {{ old('mahasiswa_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nim }} - {{ $item->nama_lengkap }} ({{ $item->prodi->nama_prodi ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mahasiswa_id')
                                <p class="text-red-600 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kompetisi Select --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kompetisi <span class="text-red-500">*</span>
                            </label>
                            <select name="kompetisi_id" required id="select-kompetisi" class="w-full">
                                <option value="">Cari berdasarkan Nama Kompetisi</option>
                                @foreach($kompetisi as $item)
                                    <option value="{{ $item->id }}" {{ old('kompetisi_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kompetisi }} (Penyelenggara: {{ $item->nama_penyelenggara ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kompetisi_id')
                                <p class="text-red-600 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Juara Input --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Juara / Penghargaan <span class="text-gray-400 font-normal normal-case">(Opsional)</span>
                            </label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <input type="text" name="juara" value="{{ old('juara') }}"
                                    placeholder="Misal: Juara 1, Harapan 2, Best Presentation (Kosongkan jika hanya Peserta)"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all @error('juara') border-red-500 @enderror">
                            </div>
                            @error('juara')
                                <p class="text-red-600 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400">Kosongkan jika mahasiswa terdaftar sebagai peserta tanpa raihan juara spesifik.</p>
                        </div>

                        {{-- File Sertifikat Input --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Upload Sertifikat <span class="text-gray-400 font-normal normal-case">(Opsional)</span>
                            </label>
                            <input type="file" name="sertifikat_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all @error('sertifikat_file') border-red-500 @enderror">
                            @error('sertifikat_file')
                                <p class="text-red-600 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400">Format: PDF, JPG, PNG. Maksimal 2MB.</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                        <div class="text-xs text-gray-400 font-semibold">
                            <span class="text-red-500">*</span> Field wajib diisi
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('mahasiswa.kompetisi.index') }}"
                                class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all duration-200 text-sm">
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 sm:flex-none px-6 py-2.5 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-save"></i> Hubungkan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Guide Column --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4 hover:shadow-md transition-all duration-300">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#C41E3A]"></i>
                        Panduan Pengisian
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-[#C41E3A] rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Cari mahasiswa pada drop-down menggunakan <strong>NIM</strong> atau <strong>Nama Lengkap</strong> mereka.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-[#C41E3A] rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Cari kompetisi menggunakan kata kunci nama ajang kompetisi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-[#C41E3A] rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Jika mahasiswa meraih juara, ketikkan nama kategori juara tersebut. Jika tidak, kosongkan input Juara.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-[#C41E3A] rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Satu hubungan mahasiswa dan kompetisi bersifat unik. Duplikasi data tidak dapat didaftarkan kembali.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-red-50/50 rounded-2xl border border-red-100 p-6">
                    <h4 class="font-bold text-red-800 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-[#C41E3A]"></i>
                        Validasi Otomatis
                    </h4>
                    <p class="text-xs text-red-700 mt-2 leading-relaxed">
                        Sistem secara otomatis memeriksa duplikasi relasi di database untuk mencegah terjadinya data ganda pada mahasiswa dan kompetisi yang sama.
                    </p>
                </div>
            </div>
        </div>
    </main>

    {{-- Error Alert From Controller Catch --}}
    @if($errors->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3 max-w-md animate-slide-in">
            <i class="fas fa-exclamation-triangle text-2xl"></i>
            <div>
                <div class="font-bold">Gagal Menyimpan!</div>
                <p class="text-sm text-red-100">{{ $errors->first('error') }}</p>
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for searchable dropdowns
            $('#select-mahasiswa').select2({
                placeholder: "Cari berdasarkan NIM atau nama...",
                allowClear: true
            });
            $('#select-kompetisi').select2({
                placeholder: "Cari berdasarkan nama kompetisi...",
                allowClear: true
            });

            // Loading effect on submit
            $('form').on('submit', function () {
                const btn = $(this).find('button[type="submit"]');
                btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                btn.addClass('opacity-75 cursor-not-allowed');
            });
        });
    </script>
</body>

</html>
