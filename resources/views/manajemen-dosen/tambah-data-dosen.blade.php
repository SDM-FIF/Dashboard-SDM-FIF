<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data Dosen - Dashboard SDM FIF</title>
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
        {{-- Breadcrumbs & Header --}}
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kelola Data
                </a>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Tambah Data Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi form di bawah ini untuk mendaftarkan dosen baru.</p>
        </div>

        {{-- Form Section --}}
        <form id="createDosenForm" action="{{ route('manajemen-dosen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 max-w-5xl">
            @csrf
            
            {{-- Card 1: Data Personal --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 font-nunito">Data Personal</h2>
                        <p class="text-xs text-gray-500">Identitas utama dosen</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- NIP --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nip" 
                               value="{{ old('nip') }}"
                               placeholder="Contoh: 19900101XXXXXXXX"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Kode Dosen --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Dosen <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="kode_dosen" 
                               value="{{ old('kode_dosen') }}"
                               placeholder="Contoh: DSN"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nama_lengkap" 
                               value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap tanpa gelar"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Gelar Depan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gelar Depan</label>
                        <input type="text" 
                               name="front_title" 
                               value="{{ old('front_title') }}"
                               placeholder="Contoh: Dr., Prof."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Gelar Belakang --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gelar Belakang</label>
                        <input type="text" 
                               name="back_title" 
                               value="{{ old('back_title') }}"
                               placeholder="Contoh: S.Kom., M.T."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- Card 2: Data Akademik & Jabatan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 font-nunito">Data Akademik & Jabatan</h2>
                        <p class="text-xs text-gray-500">Informasi penugasan, kepangkatan, dan institusi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fakultas --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fakultas <span class="text-red-500">*</span></label>
                        <select name="fakultas_id" 
                                id="fakultas_id"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Fakultas</option>
                            @if(isset($fakultas))
                                @foreach($fakultas as $fak)
                                    <option value="{{ $fak->id }}" {{ old('fakultas_id') == $fak->id ? 'selected' : '' }}>
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Program Studi --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi / Lokasi Kerja <span class="text-red-500">*</span></label>
                        <select name="prodi_id" 
                                id="prodi_id"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Program Studi</option>
                            @if(isset($prodi))
                                @foreach($prodi as $p)
                                    <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }} class="prodi-option" data-fakultas="{{ $p->fakultas_id }}">
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Kelompok Keahlian --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelompok Keahlian <span class="text-red-500">*</span></label>
                        <select name="kelompok_keahlian_id" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Kelompok Keahlian</option>
                            @if(isset($kelompokKeahlian))
                                @foreach($kelompokKeahlian as $kk)
                                    <option value="{{ $kk->id }}" {{ old('kelompok_keahlian_id') == $kk->id ? 'selected' : '' }}>
                                        {{ $kk->nama_kelompok_keahlian }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- JFA (Jabatan Fungsional Akademik) --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">JFA (Jabatan Akademik) <span class="text-red-500">*</span></label>
                        <select name="jabatan" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Jabatan</option>
                            <option value="NJFA" {{ old('jabatan') == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                            <option value="Asisten Ahli" {{ old('jabatan') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                            <option value="Lektor" {{ old('jabatan') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                            <option value="Lektor Kepala" {{ old('jabatan') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                            <option value="Profesor" {{ old('jabatan') == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                            <option value="Guru Besar" {{ old('jabatan') == 'Guru Besar' ? 'selected' : '' }}>Guru Besar</option>
                        </select>
                    </div>

                    {{-- Status Pegawai --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Pegawai <span class="text-red-500">*</span></label>
                        <select name="status_pegawai" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Status Pegawai</option>
                            <option value="Tetap" {{ old('status_pegawai') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="Perbantuan" {{ old('status_pegawai') == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
                            <option value="Profesional Full Time" {{ old('status_pegawai') == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                            <option value="Profesional Part Time" {{ old('status_pegawai') == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                        </select>
                    </div>

                    {{-- Status Dosen --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Dosen</label>
                        <select name="status_dosen" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="Aktif" {{ old('status_dosen') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tugas Belajar" {{ old('status_dosen') == 'Tugas Belajar' ? 'selected' : '' }}>Tugas Belajar</option>
                            <option value="Izin Belajar" {{ old('status_dosen') == 'Izin Belajar' ? 'selected' : '' }}>Izin Belajar</option>
                            <option value="CLTY" {{ old('status_dosen') == 'CLTY' ? 'selected' : '' }}>CLTY</option>
                        </select>
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select name="pendidikan_terakhir" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Pendidikan Terakhir</option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Card 3: Riwayat Pendidikan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-university text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 font-nunito">Riwayat Pendidikan</h2>
                        <p class="text-xs text-gray-500">Jenjang pendidikan tinggi dosen (S1 wajib diisi)</p>
                    </div>
                </div>

                <div class="space-y-8">
                    {{-- Pendidikan S1 --}}
                    <div class="p-5 rounded-xl border border-gray-100 bg-[#F8FAFC] space-y-4">
                        <h3 class="text-sm font-bold text-[#C41E3A] flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#C41E3A] text-white flex items-center justify-center text-xs">S1</span>
                            <span>Sarjana / S1 <span class="text-red-500">*</span></span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Universitas <span class="text-red-500">*</span></label>
                                <input type="text" name="riwayat[s1][nama_universitas]" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi <span class="text-red-500">*</span></label>
                                <input type="text" name="riwayat[s1][prodi_pendidikan]" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Lulus <span class="text-red-500">*</span></label>
                                <input type="date" name="riwayat[s1][tanggal_lulus]" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Ijazah (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s1][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-200 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Transkrip Nilai (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s1][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-200 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- Pendidikan S2 --}}
                    <div class="p-5 rounded-xl border border-gray-100 bg-[#F8FAFC] space-y-4">
                        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-gray-500 text-white flex items-center justify-center text-xs">S2</span>
                            <span>Magister / S2 (Opsional)</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Universitas</label>
                                <input type="text" name="riwayat[s2][nama_universitas]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi</label>
                                <input type="text" name="riwayat[s2][prodi_pendidikan]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Lulus</label>
                                <input type="date" name="riwayat[s2][tanggal_lulus]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Ijazah (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s2][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-200 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Transkrip Nilai (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s2][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-200 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- Pendidikan S3 --}}
                    <div class="p-5 rounded-xl border border-gray-100 bg-[#F8FAFC] space-y-4">
                        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-gray-500 text-white flex items-center justify-center text-xs">S3</span>
                            <span>Doktor / S3 (Opsional)</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Universitas</label>
                                <input type="text" name="riwayat[s3][nama_universitas]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi</label>
                                <input type="text" name="riwayat[s3][prodi_pendidikan]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Lulus</label>
                                <input type="date" name="riwayat[s3][tanggal_lulus]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] outline-none transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Ijazah (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s3][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen Transkrip Nilai (PDF/JPG/PNG)</label>
                                <input type="file" name="riwayat[s3][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-white text-xs text-gray-600 focus:ring-2 focus:ring-red-200 outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 max-w-5xl">
                <p class="text-xs text-gray-500"><span class="text-red-500">*</span> Field wajib diisi.</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                       class="flex-1 sm:flex-none text-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl text-sm transition-all shadow-sm">
                        Simpan Data Dosen
                    </button>
                </div>
            </div>
        </form>
    </main>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fakultas-Prodi Dependency
            const fakultasSelect = document.getElementById('fakultas_id');
            const prodiSelect = document.getElementById('prodi_id');
            const prodiOptions = prodiSelect.querySelectorAll('.prodi-option');
            
            if (fakultasSelect && prodiSelect) {
                fakultasSelect.addEventListener('change', function() {
                    const selectedFakultasId = this.value;
                    
                    // Reset prodi selection
                    prodiSelect.value = '';
                    
                    // Filter options
                    prodiOptions.forEach(opt => {
                        const fakId = opt.getAttribute('data-fakultas');
                        if (!selectedFakultasId || fakId === selectedFakultasId) {
                            opt.style.display = 'block';
                        } else {
                            opt.style.display = 'none';
                        }
                    });
                });
                
                // Trigger change on load if value exists
                if(fakultasSelect.value) {
                    fakultasSelect.dispatchEvent(new Event('change'));
                }
            }

            // Form Submit handler via AJAX for beautiful feedback
            const form = document.getElementById('createDosenForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Check required fields
                    const requiredInputs = form.querySelectorAll('[required]');
                    let isValid = true;
                    requiredInputs.forEach(input => {
                        if(!input.value.trim()) {
                            isValid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if(!isValid) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Mohon lengkapi semua field yang wajib diisi (*)',
                            confirmButtonColor: '#C41E3A',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                            }
                        });
                        return;
                    }

                    // S2 & S3 consistency validation
                    const s2Univ = form.querySelector('[name="riwayat[s2][nama_universitas]"]').value.trim();
                    const s2Prodi = form.querySelector('[name="riwayat[s2][prodi_pendidikan]"]').value.trim();
                    const s2Lulus = form.querySelector('[name="riwayat[s2][tanggal_lulus]"]').value.trim();
                    if((s2Univ || s2Prodi || s2Lulus) && (!s2Univ || !s2Prodi || !s2Lulus)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Riwayat S2!',
                            text: 'Jika mengisi salah satu data riwayat S2, universitas, program studi, dan tanggal lulus wajib diisi lengkap.',
                            confirmButtonColor: '#C41E3A',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                            }
                        });
                        return;
                    }

                    const s3Univ = form.querySelector('[name="riwayat[s3][nama_universitas]"]').value.trim();
                    const s3Prodi = form.querySelector('[name="riwayat[s3][prodi_pendidikan]"]').value.trim();
                    const s3Lulus = form.querySelector('[name="riwayat[s3][tanggal_lulus]"]').value.trim();
                    if((s3Univ || s3Prodi || s3Lulus) && (!s3Univ || !s3Prodi || !s3Lulus)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Riwayat S3!',
                            text: 'Jika mengisi salah satu data riwayat S3, universitas, program studi, dan tanggal lulus wajib diisi lengkap.',
                            confirmButtonColor: '#C41E3A',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                            }
                        });
                        return;
                    }

                    // Show loader
                    Swal.fire({
                        title: 'Menyimpan Data...',
                        text: 'Silakan tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });

                    // Send AJAX Fetch
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok && response.status !== 422) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Data dosen baru berhasil ditambahkan',
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            }).then(() => {
                                window.location.href = '{{ route("manajemen-dosen.kelola-data") }}';
                            });
                        } else {
                            let errorMessage = 'Gagal menambahkan data dosen';
                            if (data.message) {
                                errorMessage = data.message;
                            }
                            if (data.errors) {
                                errorMessage += '<br><br>' + Object.values(data.errors).flat().join('<br>');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan!',
                                html: `<div class="text-left">${errorMessage}</div>`,
                                confirmButtonColor: '#C41E3A',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Submit error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Sistem',
                            text: 'Terjadi kesalahan sistem saat menyimpan data: ' + error.message,
                            confirmButtonColor: '#C41E3A',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                            }
                        });
                    });
                });
            }
        });
    </script>
</body>
</html>