<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Mahasiswa Kompetisi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 style for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Custom Premium Styling */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            height: 3rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            outline: none !important;
            background-color: #fff !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3rem !important;
            right: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }
        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
            background-color: #fff !important;
            z-index: 9999 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #dc2626 !important;
            color: #fff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            outline: none !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15) !important;
        }
    </style>
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('mahasiswa.kompetisi.index') }}"
                        class="hover:text-red-600 transition-colors duration-200 flex items-center gap-1.5 font-medium">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke List
                    </a>
                </nav>
                <h1 class="text-3xl font-extrabold text-gray-950 tracking-tight">Hubungkan Mahasiswa & Kompetisi</h1>
                <p class="text-gray-500 mt-1">Daftarkan partisipasi mahasiswa dan raihan juaranya dalam ajang kompetisi</p>
            </div>
            
            {{-- Quick Stats Card --}}
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-xl shadow-md p-4 flex items-center gap-4 max-w-xs ml-auto">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-trophy animate-bounce"></i>
                </div>
                <div>
                    <div class="text-xs opacity-75 font-semibold uppercase tracking-wider">Apresiasi Prestasi</div>
                    <div class="text-sm font-bold mt-0.5">Sains, Seni, Olahraga & Teknologi</div>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Column --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-orange-50/50">
                    <h2 class="text-xl font-bold text-red-700 flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Formulir Registrasi Kompetisi
                    </h2>
                </div>

                <form action="{{ route('mahasiswa.kompetisi.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="space-y-6">
                        {{-- Mahasiswa Select --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">
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
                                <p class="text-red-600 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kompetisi Select --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">
                                Kompetisi <span class="text-red-500">*</span>
                            </label>
                            <select name="kompetisi_id" required id="select-kompetisi" class="w-full">
                                <option value="">Cari berdasarkan Nama Kompetisi</option>
                                @foreach($kompetisi as $item)
                                    <option value="{{ $item->id }}" {{ old('kompetisi_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kompetisi }} - (Penyelenggara: {{ $item->nama_penyelenggara ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kompetisi_id')
                                <p class="text-red-600 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Juara Input --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">
                                Juara / Penghargaan <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <input type="text" name="juara" value="{{ old('juara') }}"
                                    placeholder="Misal: Juara 1, Harapan 2, Best Presentation (Kosongkan jika hanya Peserta)"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('juara') border-red-500 @enderror text-sm">
                            </div>
                            @error('juara')
                                <p class="text-red-600 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika mahasiswa terdaftar sebagai peserta tanpa raihan juara spesifik.</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                        <div class="text-xs text-gray-400 font-medium">
                            <span class="text-red-500">*</span> Field wajib diisi
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('mahasiswa.kompetisi.index') }}"
                                class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 font-bold rounded-lg transition-all duration-200 text-sm">
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 sm:flex-none px-6 py-2.5 bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-save"></i> Hubungkan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Guide Column --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-orange-500"></i>
                        Panduan Pengisian
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Cari mahasiswa pada drop-down menggunakan <strong>NIM</strong> atau <strong>Nama Lengkap</strong> mereka.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Cari kompetisi menggunakan kata kunci nama ajang kompetisi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Jika mahasiswa meraih juara, ketikkan nama kategori juara tersebut. Jika tidak, kosongkan input Juara.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            <span>Satu hubungan mahasiswa dan kompetisi bersifat unik. Kombinasi yang sama tidak dapat didaftarkan dua kali.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-orange-50/50 rounded-2xl border border-red-100 p-6">
                    <h4 class="font-bold text-red-800 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i>
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
