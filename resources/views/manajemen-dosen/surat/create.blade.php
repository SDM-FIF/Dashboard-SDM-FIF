<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Surat Tugas / SK - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .select2-container--default .select2-selection--multiple {
            background-color: #F8FAFC;
            border-color: #E2E8F0;
            border-radius: 0.75rem;
            min-height: 46px;
            padding: 4px 8px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #C41E3A;
            background-color: #FFFFFF;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #C41E3A;
            color: #FFFFFF;
            border: none;
            border-radius: 0.5rem;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #FFFFFF;
            margin-right: 6px;
        }
        .select2-container--default .select2-selection--multiple .select2-search__field {
            font-size: 0.8125rem !important;
            color: #334155 !important;
            margin-top: 6px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search__field::placeholder {
            font-size: 0.8125rem !important;
            color: #94A3B8 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            font-size: 0.8125rem !important;
            color: #94A3B8 !important;
        }
        .select2-dropdown {
            border-color: #E2E8F0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 4px 8px !important;
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Tambah Surat Tugas / SK</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Unggah berkas dan rekam data Surat Tugas atau Surat Keputusan dosen.</p>
            </div>
            <a href="{{ route('manajemen-dosen.surat.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-4xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('manajemen-dosen.surat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Dosen Penerima (Multi-Select Searchable) --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="dosen_ids" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Dosen Penerima (Bisa Lebih Dari Satu) <span class="text-red-500">*</span>
                        </label>
                        <select name="dosen_ids[]" id="dosen_ids" multiple="multiple" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ (is_array(old('dosen_ids')) && in_array($d->id, old('dosen_ids'))) || (!old('dosen_ids') && $selectedDosenId == $d->id) ? 'selected' : '' }}>
                                {{ $d->nama_lengkap }} (NIP: {{ $d->nip ?? '-' }} | Kode: {{ $d->kode_dosen ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-gray-400 font-medium">Ketik nama/NIP dosen untuk mencari & memilih lebih dari satu dosen.</span>
                        @error('dosen_ids')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror

                        {{-- Container for dynamic lecturer positions (jabatan) --}}
                        <div id="dosen-jabatan-container" class="mt-4 space-y-3"></div>
                    </div>

                    {{-- Jenis Surat --}}
                    <div class="flex flex-col gap-2">
                        <label for="jenis_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Jenis Surat <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_surat" id="jenis_surat" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="Surat Tugas" {{ old('jenis_surat') == 'Surat Tugas' ? 'selected' : '' }}>Surat Tugas (ST)</option>
                            <option value="Surat Keputusan" {{ old('jenis_surat') == 'Surat Keputusan' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
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
                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
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
                        <input type="text" name="kategori_lainnya" id="kategori_lainnya" value="{{ old('kategori_lainnya') }}"
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
                        <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat') }}" required
                            placeholder="Contoh: 045/ST/FIF/2026 atau SK-102/TEL-U/2026"
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
                        <input type="text" name="judul_surat" id="judul_surat" value="{{ old('judul_surat') }}" required
                            placeholder="Contoh: Surat Tugas Dosen Pembimbing Utama Skripsi Semester Ganjil 2025/2026"
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
                        <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        @error('tanggal_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File Surat --}}
                    <div class="flex flex-col gap-2">
                        <label for="file_surat" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Unggah Berkas Dokumen (PDF/DOC/DOCX) <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file_surat" id="file_surat" accept=".pdf,.doc,.docx" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        <span class="text-[11px] text-gray-400 font-medium">Maksimal 10 MB (Format PDF disarankan untuk pratinjau interaktif)</span>
                        @error('file_surat')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Masa Berlaku Mulai --}}
                    <div class="flex flex-col gap-2">
                        <label for="berlaku_mulai" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Masa Berlaku Mulai (Opsional)
                        </label>
                        <input type="date" name="berlaku_mulai" id="berlaku_mulai" value="{{ old('berlaku_mulai') }}"
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
                        <input type="date" name="berlaku_selesai" id="berlaku_selesai" value="{{ old('berlaku_selesai') }}"
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
                        <textarea name="keterangan" id="keterangan" rows="3" placeholder="Masukkan keterangan tambahan jika ada..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">{{ old('keterangan') }}</textarea>
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
                        <span>Simpan Surat</span>
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
            $('#dosen_ids').select2({
                placeholder: '-- Pilih Dosen Penerima (Bisa Pilih Banyak) --',
                allowClear: true,
                width: '100%'
            });

            // Generate jabatan inputs dynamically when lecturers are selected
            $('#dosen_ids').on('change', function() {
                const selectedData = $(this).select2('data');
                const container = $('#dosen-jabatan-container');
                
                // Save current typed values to avoid clearing them
                const existingValues = {};
                container.find('input').each(function() {
                    const id = $(this).data('dosen-id');
                    existingValues[id] = $(this).val();
                });
                
                container.empty();
                
                if (selectedData.length > 0) {
                    container.append('<div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan / Kedudukan Dosen (Opsional)</div>');
                }
                
                selectedData.forEach(function(item) {
                    const dosenId = item.id;
                    const dosenName = item.text.trim();
                    const val = existingValues[dosenId] || '';
                    
                    const row = $(`
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="sm:w-1/2 text-xs font-semibold text-gray-700 truncate">${dosenName}</div>
                            <div class="sm:w-1/2">
                                <input type="text" name="jabatan[${dosenId}]" data-dosen-id="${dosenId}" value="${val}" placeholder="Contoh: Ketua, Anggota, Tim Reviewer, dll."
                                    class="w-full h-8 px-3 border border-gray-200 rounded-lg text-xs bg-white focus:ring-1 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            </div>
                        </div>
                    `);
                    container.append(row);
                });
            });

            // Trigger change on load to initialize old values if any
            $('#dosen_ids').trigger('change');

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
