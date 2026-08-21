<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pengaturan Notifikasi - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Pengaturan Notifikasi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola notifikasi sistem terintegrasi yang dikirimkan ke pengguna saat terjadi peristiwa tertentu.</p>
            </div>
        </div>

        {{-- Notification Alert if exists --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl text-emerald-700 text-sm font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Form card --}}
        <div class="max-w-3xl bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-[#C41E3A]"></i>
                <span>Konfigurasi Notifikasi per Fitur</span>
            </h2>

            <form action="{{ route('pengaturan.notifikasi.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Notification toggles list --}}
                <div class="space-y-6 divide-y divide-slate-100">
                    
                    <!-- 1. Manajemen Dosen & Surat -->
                    <div class="pt-6 first:pt-0">
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-[#C41E3A]"></i>
                                <span>Manajemen Dosen & Surat</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Notifikasi terkait data dosen dan penerbitan Surat Tugas / Surat Keputusan (SK).</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <!-- Create -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Tambah / Terbit</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="dosen_create" value="1" class="sr-only peer" {{ ($settings['dosen_create'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Update -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Ubah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="dosen_update" value="1" class="sr-only peer" {{ ($settings['dosen_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Delete -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Hapus Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="dosen_delete" value="1" class="sr-only peer" {{ ($settings['dosen_delete'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Manajemen TPA -->
                    <div class="pt-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-user-gear text-[#C41E3A]"></i>
                                <span>Manajemen TPA</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Notifikasi terkait data Tenaga Pendukung Akademik (TPA).</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <!-- Create -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Tambah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="tpa_create" value="1" class="sr-only peer" {{ ($settings['tpa_create'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Update -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Ubah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="tpa_update" value="1" class="sr-only peer" {{ ($settings['tpa_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Delete -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Hapus Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="tpa_delete" value="1" class="sr-only peer" {{ ($settings['tpa_delete'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Rekrutasi Dosen -->
                    <div class="pt-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-[#C41E3A]"></i>
                                <span>Rekrutasi Dosen</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Notifikasi terkait pendaftaran calon dosen, jadwal ujian, penilaian, dan berita acara.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <!-- Create -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Tambah / Ujian</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="rekrutasi_create" value="1" class="sr-only peer" {{ ($settings['rekrutasi_create'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Update -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Ubah Data / Nilai</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="rekrutasi_update" value="1" class="sr-only peer" {{ ($settings['rekrutasi_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Delete -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Hapus Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="rekrutasi_delete" value="1" class="sr-only peer" {{ ($settings['rekrutasi_delete'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Mahasiswa & Kompetisi -->
                    <div class="pt-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-trophy text-[#C41E3A]"></i>
                                <span>Mahasiswa & Kompetisi</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Notifikasi terkait prestasi mahasiswa, kompetisi, dan data kemahasiswaan.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <!-- Create -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Tambah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="mahasiswa_create" value="1" class="sr-only peer" {{ ($settings['mahasiswa_create'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Update -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Ubah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="mahasiswa_update" value="1" class="sr-only peer" {{ ($settings['mahasiswa_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Delete -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Hapus Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="mahasiswa_delete" value="1" class="sr-only peer" {{ ($settings['mahasiswa_delete'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Master Data -->
                    <div class="pt-6 mb-2">
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-database text-[#C41E3A]"></i>
                                <span>Master Data</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Notifikasi terkait program studi, kelompok keahlian, tahun ajaran, dan fakultas naungan.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <!-- Create -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Tambah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="master_create" value="1" class="sr-only peer" {{ ($settings['master_create'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Update -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Ubah Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="master_update" value="1" class="sr-only peer" {{ ($settings['master_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                            <!-- Delete -->
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-100">
                                <span class="text-xs font-semibold text-slate-600">Hapus Data</span>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="master_delete" value="1" class="sr-only peer" {{ ($settings['master_delete'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit"
                        class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
