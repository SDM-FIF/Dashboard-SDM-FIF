<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Fakultas - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Premium Select2 Custom Styling to match rounded-xl Inputs */
        .select2-container--default .select2-selection--single {
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.75rem !important;
            height: 46px !important;
            background-color: #F8FAFC !important;
            display: flex;
            align-items: center;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #4A5568 !important;
            font-size: 0.875rem !important;
            padding-left: 1rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single:focus-within {
            border-color: #C41E3A !important;
            background-color: #FFFFFF !important;
            box-shadow: 0 0 0 2px rgba(196, 30, 58, 0.1) !important;
        }
        .select2-dropdown {
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.5rem !important;
            padding: 6px 12px !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #C41E3A !important;
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
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Data Fakultas</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Perbarui nama fakultas serta struktur pejabat pimpinan fakultas.</p>
            </div>
            <a href="{{ route('fakultas.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('fakultas.update', $fakultas->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Nama Fakultas --}}
                <div class="flex flex-col gap-1.5 max-w-xl">
                    <label for="nama_fakultas" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Fakultas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_fakultas" id="nama_fakultas"
                           value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nama_fakultas') border-red-500 @enderror"
                           placeholder="Contoh: Fakultas Informatika">
                    @error('nama_fakultas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pimpinan Section --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 pb-2 border-b border-gray-100">
                        <i class="fas fa-user-shield text-[#C41E3A]"></i>
                        <span>Struktur Pimpinan</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Dekan --}}
                        <div class="col-span-1 md:col-span-2 flex flex-col gap-1.5">
                            <label for="id_dekan" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dekan</label>
                            <select name="id_dekan" id="id_dekan" class="w-full select-search">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosenList as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('id_dekan', $fakultas->dekan_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_lengkap }} ({{ $dosen->kode_dosen ?? $dosen->nip }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Wakil Dekan 1 --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="id_wadek1" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Wakil Dekan 1 (Akademik)</label>
                            <select name="id_wadek1" id="id_wadek1" class="w-full select-search">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosenList as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('id_wadek1', $fakultas->wadek1_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Wakil Dekan 2 --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="id_wadek2" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Wakil Dekan 2 (Sumber Daya)</label>
                            <select name="id_wadek2" id="id_wadek2" class="w-full select-search">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosenList as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('id_wadek2', $fakultas->wadek2_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Action Panel --}}
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-100 flex-wrap">
                    <p class="text-xs text-gray-400 font-semibold"><span class="text-red-500">*</span> Data wajib diisi dengan benar.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('fakultas.index') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select-search').select2({
                placeholder: "Cari nama dosen...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>