<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pengaturan Periode CRUD - Dashboard SDM FIF</title>
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
        {{-- Topbar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Pengaturan Periode CRUD</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Batasi waktu pengisian dan perubahan (CRUD) data untuk setiap fitur/modul demi keamanan dan ketertiban administrasi.</p>
            </div>
        </div>

        {{-- Notification Alert if exists --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl text-emerald-700 text-sm font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-red-700 text-sm font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Form card --}}
        <div class="max-w-4xl bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-[#C41E3A]"></i>
                <span>Konfigurasi Waktu Akses Fitur</span>
            </h2>

            <form action="{{ route('pengaturan.periode.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6 divide-y divide-slate-100">
                    @foreach($settings as $setting)
                        @php
                            $fiturName = match ($setting->fitur) {
                                'dosen' => 'Manajemen Dosen',
                                'tpa' => 'Manajemen Tenaga Pendukung Akademik (TPA)',
                                'rekrutasi' => 'Rekrutasi Dosen',
                                'mahasiswa' => 'Mahasiswa & Kompetisi',
                                'master' => 'Master Data (Fakultas, Prodi, KK, Tahun Ajar)',
                                'surat' => 'Surat Tugas & SK Dosen',
                                default => strtoupper($setting->fitur)
                            };

                            $fiturIcon = match ($setting->fitur) {
                                'dosen' => 'fa-graduation-cap',
                                'tpa' => 'fa-user-gear',
                                'rekrutasi' => 'fa-user-plus',
                                'mahasiswa' => 'fa-users',
                                'master' => 'fa-database',
                                'surat' => 'fa-file-signature',
                                default => 'fa-cube'
                            };

                            $desc = match ($setting->fitur) {
                                'dosen' => 'Membatasi pengisian biodata dosen baru, pengubahan profil, status dosen, dan impor data dosen.',
                                'tpa' => 'Membatasi penambahan tenaga kependidikan baru, pengeditan info TPA, status, dan impor data TPA.',
                                'rekrutasi' => 'Membatasi pendaftaran calon dosen, plot jadwal ujian, penilaian kualifikasi, dan kelola berita acara.',
                                'mahasiswa' => 'Membatasi penambahan mahasiswa baru, pengajuan sertifikasi kompetisi, prestasi, dan impor mahasiswa.',
                                'master' => 'Membatasi modifikasi fakultas, program studi, kelompok keahlian, dan pengaturan semester/tahun ajar.',
                                'surat' => 'Membatasi penerbitan surat tugas baru, pengeditan kedudukan dosen, nomor surat, dan berkas SK.',
                                default => ''
                            };
                        @endphp

                        <div class="pt-6 first:pt-0" data-feature="{{ $setting->fitur }}">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                <div class="lg:w-1/3">
                                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fa-solid {{ $fiturIcon }} text-[#C41E3A] text-base w-5 text-center"></i>
                                        <span>{{ $fiturName }}</span>
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $desc }}</p>
                                </div>

                                <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl items-center">
                                    <!-- Mode Select -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Metode Batasan</label>
                                        <select name="mode_{{ $setting->fitur }}" class="mode-select w-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 rounded-lg px-3 py-2.5 focus:border-[#C41E3A] focus:ring-1 focus:ring-[#C41E3A] focus:outline-none transition-colors">
                                            <option value="selalu" {{ $setting->mode === 'selalu' ? 'selected' : '' }}>Selalu Diizinkan</option>
                                            <option value="rentang_tanggal" {{ $setting->mode === 'rentang_tanggal' ? 'selected' : '' }}>Rentang Tanggal</option>
                                        </select>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="date-container transition-opacity duration-200 {{ $setting->mode === 'selalu' ? 'opacity-40' : '' }}">
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Mulai</label>
                                        <input type="date" name="start_{{ $setting->fitur }}" value="{{ $setting->tanggal_mulai ? $setting->tanggal_mulai->format('Y-m-d') : '' }}" class="date-input w-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 rounded-lg px-3 py-2 focus:border-[#C41E3A] focus:ring-1 focus:ring-[#C41E3A] focus:outline-none transition-colors" {{ $setting->mode === 'selalu' ? 'disabled' : '' }}>
                                    </div>

                                    <!-- End Date -->
                                    <div class="date-container transition-opacity duration-200 {{ $setting->mode === 'selalu' ? 'opacity-40' : '' }}">
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                                        <input type="date" name="end_{{ $setting->fitur }}" value="{{ $setting->tanggal_selesai ? $setting->tanggal_selesai->format('Y-m-d') : '' }}" class="date-input w-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 rounded-lg px-3 py-2 focus:border-[#C41E3A] focus:ring-1 focus:ring-[#C41E3A] focus:outline-none transition-colors" {{ $setting->mode === 'selalu' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                    <a href="{{ route('pengaturan') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#C41E3A] hover:bg-[#A3162C] text-white rounded-xl font-bold text-xs flex items-center gap-2 transition-all shadow-sm hover:shadow active:scale-95 duration-200">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Javascript for dynamic state toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeSelects = document.querySelectorAll('.mode-select');
            
            modeSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const row = this.closest('.pt-6');
                    const dateContainers = row.querySelectorAll('.date-container');
                    const dateInputs = row.querySelectorAll('.date-input');
                    
                    if (this.value === 'selalu') {
                        dateContainers.forEach(container => {
                            container.classList.add('opacity-40');
                        });
                        dateInputs.forEach(input => {
                            input.disabled = true;
                            input.value = ''; // clear dates when set to always allowed
                        });
                    } else {
                        dateContainers.forEach(container => {
                            container.classList.remove('opacity-40');
                        });
                        dateInputs.forEach(input => {
                            input.disabled = false;
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
