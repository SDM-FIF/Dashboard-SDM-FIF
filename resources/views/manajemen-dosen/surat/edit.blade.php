<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Surat Tugas / SK - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .select2-container--default .select2-selection--single {
            background-color: #F8FAFC;
            border-color: #E2E8F0;
            border-radius: 0.75rem;
            height: 46px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155;
            font-size: 0.875rem;
            padding-left: 1rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 10px;
        }
        .select2-dropdown {
            border-color: #E2E8F0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 6px 12px !important;
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit {{ $surat->jenis_surat }}</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Perbarui data dan berkas {{ $surat->nomor_surat }}.</p>
            </div>
            <a href="{{ route('manajemen-dosen.surat.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        @php
            $stdKategori = ['Pengajaran', 'Penelitian', 'Pengabdian Masyarakat', 'Jabatan Struktural', 'Panitia / Kegiatan'];
            $isCustomKategori = !in_array($surat->kategori, $stdKategori);
            $selectedKategori = $isCustomKategori ? 'Lainnya' : $surat->kategori;
            $customKategoriVal = $isCustomKategori ? $surat->kategori : '';
        @endphp

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-4xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('manajemen-dosen.surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Dosen Penerima (Searchable Select) --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="dosen_id" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Dosen Penerima <span class="text-red-500">*</span>
                        </label>
                        <select name="dosen_id" id="dosen_id" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ old('dosen_id', $surat->dosen_id) == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_lengkap }} (NIP: {{ $d->nip ?? '-' }} | Kode: {{ $d->kode_dosen ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                        @error('dosen_id')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Surat --}}
                    <div class="flex flex-col gap-2">
                        <label for="jenis_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Jenis Surat <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_surat" id="jenis_surat" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="Surat Tugas" {{ old('jenis_surat', $surat->jenis_surat) == 'Surat Tugas' ? 'selected' : '' }}>Surat Tugas (ST)</option>
                            <option value="Surat Keputusan" {{ old('jenis_surat', $surat->jenis_surat) == 'Surat Keputusan' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                        </select>
                        @error('jenis_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="flex flex-col gap-2">
                        <label for="kategori" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Kategori Perihal <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" id="kategori" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $selectedKategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kategori Lainnya (Custom Free Input) --}}
                    <div id="kategori_lainnya_wrapper" class="flex flex-col gap-2 md:col-span-2 hidden">
                        <label for="kategori_lainnya" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Spesifikasi Kategori Perihal (Lainnya) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kategori_lainnya" id="kategori_lainnya" value="{{ old('kategori_lainnya', $customKategoriVal) }}"
                            placeholder="Ketikkan perihal / kategori khusus..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('kategori_lainnya')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Surat --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="nomor_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Nomor Resmi Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('nomor_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Judul / Perihal Surat --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="judul_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Judul / Perihal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul_surat" id="judul_surat" value="{{ old('judul_surat', $surat->judul_surat) }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('judul_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Surat --}}
                    <div class="flex flex-col gap-2">
                        <label for="tanggal_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Tanggal Terbit Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('tanggal_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File Surat --}}
                    <div class="flex flex-col gap-2">
                        <label for="file_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Ganti Berkas Dokumen (Opsional)
                        </label>
                        <input type="file" name="file_surat" id="file_surat" accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @if($surat->file_surat)
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                            <i class="fas fa-paperclip text-red-500"></i>
                            <span>Berkas saat ini:</span>
                            <a href="{{ route('manajemen-dosen.surat.download', $surat->id) }}" class="text-[#C41E3A] font-bold hover:underline">Unduh Berkas Lama</a>
                        </div>
                        @endif
                        @error('file_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Masa Berlaku Mulai --}}
                    <div class="flex flex-col gap-2">
                        <label for="berlaku_mulai" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Masa Berlaku Mulai (Opsional)
                        </label>
                        <input type="date" name="berlaku_mulai" id="berlaku_mulai" value="{{ old('berlaku_mulai', $surat->berlaku_mulai ? $surat->berlaku_mulai->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('berlaku_mulai')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Masa Berlaku Selesai --}}
                    <div class="flex flex-col gap-2">
                        <label for="berlaku_selesai" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Masa Berlaku Selesai (Opsional)
                        </label>
                        <input type="date" name="berlaku_selesai" id="berlaku_selesai" value="{{ old('berlaku_selesai', $surat->berlaku_selesai ? $surat->berlaku_selesai->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('berlaku_selesai')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="keterangan" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Catatan / Keterangan Tambahan (Opsional)
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">{{ old('keterangan', $surat->keterangan) }}</textarea>
                        @error('keterangan')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('manajemen-dosen.surat.index') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl transition-all duration-200 text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Scripts for Select2 & Kategori Lainnya --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dosen_id').select2({
                placeholder: '-- Pilih Dosen --',
                allowClear: true,
                width: '100%'
            });

            function checkKategori() {
                const val = $('#kategori').val();
                if (val === 'Lainnya') {
                    $('#kategori_lainnya_wrapper').removeClass('hidden');
                    $('#kategori_lainnya').attr('required', true);
                } else {
                    $('#kategori_lainnya_wrapper').addClass('hidden');
                    $('#kategori_lainnya').removeAttr('required');
                }
            }

            $('#kategori').on('change', checkKategori);
            checkKategori();
        });
    </script>
</body>

</html>
