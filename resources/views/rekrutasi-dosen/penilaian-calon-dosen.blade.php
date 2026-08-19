<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Penilaian Calon Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Form Penilaian</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Calon Dosen Profesional Full / Part Time Universitas Telkom</p>
            </div>
            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Candidate Profile Summary Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
                <div class="w-12 h-12 bg-red-50 text-[#C41E3A] rounded-xl flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Profil Calon Dosen</h3>
                    <p class="text-xs text-gray-400 font-semibold mt-0.5">Informasi kandidat untuk membantu penilaian kualifikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nama Kandidat</span>
                    <span class="text-sm font-bold text-gray-800">{{ $calonDosen->nama }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Program Studi</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $calonDosen->prodi->nama_prodi ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Jalur Lamaran</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $calonDosen->jalur_lamaran ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">JFA Terakhir</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $calonDosen->jabatan_fungsional_akademik ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">H-Index</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $calonDosen->h_index ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Penjelasan Skala Nilai --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-sm">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 text-lg"></i>
            <div>
                <h4 class="text-sm font-bold text-blue-800">Panduan Pengisian Nilai (Skala 1 - 5)</h4>
                <p class="text-xs text-blue-700 mt-1">
                    Silakan isi setiap kriteria penilaian dengan angka pada rentang 1 hingga 5.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mt-2">
                    <div class="bg-white/60 px-3 py-1.5 rounded text-[11px] font-semibold text-blue-800 border border-blue-100"><span class="font-extrabold text-blue-600 mr-1">1:</span> Sangat Kurang</div>
                    <div class="bg-white/60 px-3 py-1.5 rounded text-[11px] font-semibold text-blue-800 border border-blue-100"><span class="font-extrabold text-blue-600 mr-1">2:</span> Kurang</div>
                    <div class="bg-white/60 px-3 py-1.5 rounded text-[11px] font-semibold text-blue-800 border border-blue-100"><span class="font-extrabold text-blue-600 mr-1">3:</span> Cukup</div>
                    <div class="bg-white/60 px-3 py-1.5 rounded text-[11px] font-semibold text-blue-800 border border-blue-100"><span class="font-extrabold text-blue-600 mr-1">4:</span> Baik</div>
                    <div class="bg-white/60 px-3 py-1.5 rounded text-[11px] font-semibold text-blue-800 border border-blue-100"><span class="font-extrabold text-blue-600 mr-1">5:</span> Sangat Baik</div>
                </div>
            </div>
        </div>

        {{-- Form Penilaian --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <form id="formPenilaian" action="#" method="POST" class="space-y-8">
                @csrf
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-6 py-4 font-bold uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Kriteria Penilaian</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider w-36 text-center">Nilai (1-5)</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider w-64 text-center">Rata-Rata / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            {{-- SECTION A: Kualifikasi --}}
                            <tr class="bg-red-50/50">
                                <td colspan="4" class="px-6 py-3.5 font-bold text-[#C41E3A] text-xs uppercase tracking-wider">
                                    A. Kualifikasi Akademik (Bobot 40%)
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">1</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Jalur Lamaran / Pendidikan Terakhir</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kandidat terdaftar sebagai: <strong class="text-gray-600">{{ $calonDosen->jalur_lamaran ?? '-' }}</strong></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-bold outline-none" 
                                           name="nilai_jalur_lamaran" id="nilai_jalur_lamaran" 
                                           value="{{ 
                                               $calonDosen->jalur_lamaran == 'S3 Prof Full time' ? 5 :
                                               ($calonDosen->jalur_lamaran == 'S3 Praktisi Part time' ? 4 :
                                               ($calonDosen->jalur_lamaran == 'S3 OnGoing' ? 3 :
                                               ($calonDosen->jalur_lamaran == 'S2 Praktisi Part time' ? 2 :
                                               ($calonDosen->jalur_lamaran == 'S2 Prof Full time' ? 1 : 0))))
                                           }}" readonly>
                                </td>
                                <td rowspan="3" class="px-6 py-4 text-center border-l border-gray-100 bg-gray-50/20" style="vertical-align: middle;">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rata-Rata A</div>
                                    <div id="rata_a_text" class="text-2xl font-extrabold text-gray-800">
                                        <span id="rata_a_value">0.00</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">2</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Jabatan Fungsional Akademik (JFA)</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kandidat memiliki JFA: <strong class="text-gray-600">{{ $calonDosen->jabatan_fungsional_akademik ?? 'NJFA' }}</strong></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-bold outline-none" 
                                           name="nilai_jfa" id="nilai_jfa" 
                                           value="{{ 
                                               $calonDosen->jabatan_fungsional_akademik == 'Guru Besar' ? 5 :
                                               ($calonDosen->jabatan_fungsional_akademik == 'Lektor Kepala' ? 4 :
                                               ($calonDosen->jabatan_fungsional_akademik == 'Lektor' ? 3 :
                                               ($calonDosen->jabatan_fungsional_akademik == 'Asisten Ahli' ? 2 : 1)))
                                           }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">3</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">H-Index</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kandidat memiliki H-Index: <strong class="text-gray-600">{{ $calonDosen->h_index ?? '0.00' }}</strong></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-bold outline-none" 
                                           name="nilai_h_index" id="nilai_h_index" 
                                           value="{{ 
                                               ($calonDosen->h_index ?? 0) > 10 ? 5 :
                                               (($calonDosen->h_index ?? 0) >= 5 ? 4 :
                                               (($calonDosen->h_index ?? 0) >= 2 ? 3 :
                                               (($calonDosen->h_index ?? 0) >= 1 ? 2 : 1)))
                                           }}" readonly>
                                </td>
                            </tr>

                            {{-- SECTION B: Micro Teaching --}}
                            <tr class="bg-emerald-50/50">
                                <td colspan="4" class="px-6 py-3.5 font-bold text-emerald-700 text-xs uppercase tracking-wider">
                                    B. Micro Teaching (Bobot 20%)
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">1</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Penguasaan Materi & Audiens</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kombinasi kedalaman materi pengajaran dan engagement audiens.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_b1" id="nilai_b1" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_pma ?? '' }}" required>
                                </td>
                                <td rowspan="3" class="px-6 py-4 text-center border-l border-gray-100 bg-gray-50/20" style="vertical-align: middle;">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rata-Rata B</div>
                                    <div id="rata_b_text" class="text-2xl font-extrabold text-gray-800">
                                        <span id="rata_b_value">{{ $existingPenilaian ? number_format($existingPenilaian->rata_b, 2) : '0.00' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">2</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Sistematika Pengajaran</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kemudahan penyampaian materi agar mudah dipahami terstruktur.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_b2" id="nilai_b2" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_sistematika ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">3</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kejelasan Suara & Media Pembelajaran</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kualitas suara pengajaran serta media bantu visual/tulisan.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_b3" id="nilai_b3" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kst ?? '' }}" required>
                                </td>
                            </tr>

                            {{-- SECTION C: Wawancara --}}
                            <tr class="bg-amber-50/50">
                                <td colspan="4" class="px-6 py-3.5 font-bold text-amber-700 text-xs uppercase tracking-wider">
                                    C. Wawancara (Bobot 40%)
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">1</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Motivasi</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Dorongan internal, antusiasme, serta determinasi melamar posisi dosen.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c1" id="nilai_c1" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_motivasi ?? '' }}" required>
                                </td>
                                <td rowspan="8" class="px-6 py-4 text-center border-l border-gray-100 bg-gray-50/20" style="vertical-align: middle;">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rata-Rata C</div>
                                    <div id="rata_c_text" class="text-2xl font-extrabold text-gray-800">
                                        <span id="rata_c_value">{{ $existingPenilaian ? number_format($existingPenilaian->rata_c, 2) : '0.00' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">2</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kemampuan Mengajar (Pedagogis)</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kemampuan menyusun silabus, metode belajar aktif, evaluasi mahasiswa.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c2" id="nilai_c2" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmp_mengajar ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">3</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kemampuan Mengembangkan Kurikulum Pengajaran</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Perencanaan mata kuliah, pemutakhiran pustaka & Rencana Pembelajaran Semester.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c3" id="nilai_c3" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmp_mkp ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">4</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kemampuan Penelitian & Publikasi Jurnal</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Potensi riset mandiri / kelompok, luaran jurnal nasional/internasional bereputasi.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c4" id="nilai_c4" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmp_pp ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">5</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kemampuan Abdimas (Pengabdian Masyarakat)</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Kesiapan implementasi keilmuan ke masyarakat industri / umum.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c5" id="nilai_c5" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmp_abdimas ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">6</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Kemampuan Bekerjasama dalam Tim</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Aspek softskill kolaborasi lintas disiplin prodi / fakultas.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c6" id="nilai_c6" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmp_bdt ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">7</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Keahlian Lainnya (Sertifikasi Profesional)</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Nilai tambah berupa kepemilikan sertifikasi industri nasional/global.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c7" id="nilai_c7" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_keahlian_lainnya ?? '' }}" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-bold">8</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">Komitmen Waktu & Tugas Non-Akademik</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Ketersediaan beban kerja penuh waktu / kepanitiaan universitas.</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" class="w-24 px-3 py-2 text-center border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 font-bold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none nilai-input" 
                                           name="nilai_c8" id="nilai_c8" min="1" max="5" step="0.1" placeholder="1-5" 
                                           value="{{ $existingPenilaian->nilai_kmt_wkm ?? '' }}" required>
                                </td>
                            </tr>

                            {{-- TOTAL ACCUMULATOR ROW --}}
                            <tr class="bg-blue-50/70">
                                <td colspan="2" class="px-6 py-4 text-center font-bold text-blue-900 text-sm">
                                    TOTAL NILAI (Rata-Rata Berbobot)
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="text" class="w-24 px-3 py-2 text-center border border-blue-200 rounded-xl bg-white text-blue-800 font-extrabold outline-none" id="total_nilai" 
                                           value="{{ $existingPenilaian ? number_format($existingPenilaian->rata_nilai, 2) : '' }}" 
                                           readonly placeholder="0.00">
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-blue-900 text-sm">
                                    <input type="text" class="w-full px-3 py-2 text-center border border-blue-200 rounded-xl bg-white text-blue-800 font-extrabold outline-none" id="keterangan_berbobot" 
                                           value="{{ $existingPenilaian->keterangan_berbobot ?? '' }}" 
                                           readonly placeholder="-">
                                </td>
                            </tr>
                            
                            {{-- Kesiapan Join --}}
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center font-bold text-gray-700">
                                    Kesiapan bergabung segera?
                                </td>
                                <td colspan="2" class="px-6 py-4">
                                    <div class="flex items-center gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700 text-sm">
                                            <input type="radio" name="kesiapan" value="YA" class="w-4 h-4 text-[#C41E3A] focus:ring-red-200 border-gray-300"
                                                   {{ ($existingPenilaian && $existingPenilaian->kesiapan == 1) ? 'checked' : '' }} required> 
                                            <span>YA</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700 text-sm">
                                            <input type="radio" name="kesiapan" value="TIDAK/PIKIR-PIKIR" class="w-4 h-4 text-[#C41E3A] focus:ring-red-200 border-gray-300"
                                                   {{ ($existingPenilaian && $existingPenilaian->kesiapan == 0) ? 'checked' : '' }} required> 
                                            <span>TIDAK/PIKIR-PIKIR</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            
                            {{-- Gaji Agreement --}}
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center font-bold text-gray-700">
                                    Bersedia dengan standard gaji?
                                </td>
                                <td colspan="2" class="px-6 py-4">
                                    <div class="flex items-center gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700 text-sm">
                                            <input type="radio" name="kesediaan" value="YA" class="w-4 h-4 text-[#C41E3A] focus:ring-red-200 border-gray-300"
                                                   {{ ($existingPenilaian && $existingPenilaian->kesediaan == 1) ? 'checked' : '' }} required> 
                                            <span>YA</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-gray-700 text-sm">
                                            <input type="radio" name="kesediaan" value="TIDAK/PIKIR-PIKIR" class="w-4 h-4 text-[#C41E3A] focus:ring-red-200 border-gray-300"
                                                   {{ ($existingPenilaian && $existingPenilaian->kesediaan == 0) ? 'checked' : '' }} required> 
                                            <span>TIDAK/PIKIR-PIKIR</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Scale legend notification --}}
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl flex items-start gap-3 text-blue-800">
                    <i class="fas fa-info-circle text-base mt-0.5"></i>
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider mb-1">Skala Penilaian Kriteria</div>
                        <p class="text-xs font-medium leading-relaxed"><strong>5</strong>: Sangat Baik | <strong>4</strong>: Baik | <strong>3</strong>: Cukup | <strong>2</strong>: Kurang | <strong>1</strong>: Sangat Kurang</p>
                    </div>
                </div>

                {{-- Catatan / Komentar --}}
                <div class="flex flex-col gap-1.5">
                    <label for="catatan" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Catatan/Komentar Penilai</label>
                    <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none" 
                              id="catatan" name="catatan" rows="4" 
                              placeholder="Tulis saran, ulasan mendalam, atau catatan spesifik kelayakan kandidat... (opsional)">{{ $existingPenilaian->catatan_penilai ?? '' }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50">
                    @if($existingPenilaian)
                    <a href="{{ route('rekrutasi-dosen.penilaian.export-pdf', $existingPenilaian->id) }}" 
                       class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span>Unduh PDF</span>
                    </a>
                    @endif
                    
                    @if($beritaAcaraSubmitted)
                    <button type="button" class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed flex items-center gap-2 border border-gray-200" disabled>
                        <i class="fas fa-lock"></i>
                        <span>Penilaian Terkunci (Berita Acara Selesai)</span>
                    </button>
                    @else
                        @can('penilaian-dosen.submit')
                        <button type="button" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2" id="btnKalkulasiNilai">
                            <i class="fas fa-calculator"></i>
                            <span>Kalkulasi Rata-Rata</span>
                        </button>
                        <button type="submit" class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2" id="btnSimpanPenilaian">
                            <i class="fas fa-save"></i>
                            <span>{{ $existingPenilaian ? 'Simpan Perubahan' : 'Simpan Penilaian' }}</span>
                        </button>
                        @else
                        <button type="button" class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed flex items-center gap-2 border border-gray-200" disabled>
                            <i class="fas fa-eye"></i>
                            <span>Mode Monitor (Read-Only)</span>
                        </button>
                        @endcan
                    @endif
                </div>
            </form>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Calculate rata_a on page load (Section A is auto-calculated)
            function calculateRataA() {
                const nilaiJalurLamaran = parseFloat($('#nilai_jalur_lamaran').val() || 0);
                const nilaiJFA = parseFloat($('#nilai_jfa').val() || 0);
                const nilaiHIndex = parseFloat($('#nilai_h_index').val() || 0);
                
                const rataA = (nilaiJalurLamaran + nilaiJFA + nilaiHIndex) / 3;
                $('#rata_a_value').text(rataA.toFixed(2));
                
                return rataA;
            }
            
            // Calculate rata_b (will be shown after save)
            function calculateRataB() {
                const nilaiB1 = parseFloat($('#nilai_b1').val() || 0);
                const nilaiB2 = parseFloat($('#nilai_b2').val() || 0);
                const nilaiB3 = parseFloat($('#nilai_b3').val() || 0);
                
                const rataB = (nilaiB1 + nilaiB2 + nilaiB3) / 3;
                return rataB;
            }
            
            // Calculate rata_c (will be shown after save)
            function calculateRataC() {
                const nilaiC1 = parseFloat($('#nilai_c1').val() || 0);
                const nilaiC2 = parseFloat($('#nilai_c2').val() || 0);
                const nilaiC3 = parseFloat($('#nilai_c3').val() || 0);
                const nilaiC4 = parseFloat($('#nilai_c4').val() || 0);
                const nilaiC5 = parseFloat($('#nilai_c5').val() || 0);
                const nilaiC6 = parseFloat($('#nilai_c6').val() || 0);
                const nilaiC7 = parseFloat($('#nilai_c7').val() || 0);
                const nilaiC8 = parseFloat($('#nilai_c8').val() || 0);
                
                const rataC = (nilaiC1 + nilaiC2 + nilaiC3 + nilaiC4 + nilaiC5 + nilaiC6 + nilaiC7 + nilaiC8) / 8;
                return rataC;
            }
            
            // Get keterangan based on total nilai
            function getKeterangan(nilai) {
                if (nilai >= 4.5) return 'Sangat Baik';
                if (nilai >= 3.5) return 'Baik';
                if (nilai >= 2.5) return 'Cukup';
                if (nilai >= 1.5) return 'Kurang';
                return 'Sangat Kurang';
            }
            
            // Calculate rata_a immediately on page load
            calculateRataA();
            
            // Limit input to max 5 and min 1
            $('.nilai-input').on('input', function() {
                let val = parseFloat($(this).val());
                if (val > 5) {
                    $(this).val(5);
                } else if (val < 1 && $(this).val() !== '') {
                    $(this).val(1);
                }
            });
            
            // Disable all form inputs if Berita Acara has been submitted
            @if($beritaAcaraSubmitted)
            $('#formPenilaian input, #formPenilaian select, #formPenilaian textarea').prop('disabled', true);
            $('#formPenilaian').find('.form-control, .form-check-input').css({
                'background-color': '#f8fafc',
                'cursor': 'not-allowed'
            });
            @endif
            
            // Form submission with validation
            $('#formPenilaian').on('submit', function(e) {
                e.preventDefault();
                
                // Validation: Check all nilai inputs (B and C sections)
                let isValid = true;
                let errorMessage = '';
                
                // Check Section B inputs
                for (let i = 1; i <= 3; i++) {
                    const nilai = parseFloat($(`#nilai_b${i}`).val());
                    if (!nilai || nilai < 1 || nilai > 5) {
                        isValid = false;
                        errorMessage = 'Semua kriteria penilaian Section B harus diisi dengan angka 1-5';
                        break;
                    }
                }
                
                // Check Section C inputs
                if (isValid) {
                    for (let i = 1; i <= 8; i++) {
                        const nilai = parseFloat($(`#nilai_c${i}`).val());
                        if (!nilai || nilai < 1 || nilai > 5) {
                            isValid = false;
                            errorMessage = 'Semua kriteria penilaian Section C harus diisi dengan angka 1-5';
                            break;
                        }
                    }
                }
                
                // Check kesiapan
                if (isValid && !$('input[name="kesiapan"]:checked').val()) {
                    isValid = false;
                    errorMessage = 'Pilihan "Kesiapan bergabung segera?" harus dipilih';
                }
                
                // Check kesediaan
                if (isValid && !$('input[name="kesediaan"]:checked').val()) {
                    isValid = false;
                    errorMessage = 'Pilihan "Bersedia dengan standard gaji?" harus dipilih';
                }
                
                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        text: errorMessage,
                        confirmButtonColor: '#C41E3A'
                    });
                    return;
                }
                
                // Calculate all averages
                const rataA = calculateRataA();
                const rataB = calculateRataB();
                const rataC = calculateRataC();
                const totalNilai = (rataA + rataB + rataC) / 3;
                const keterangan = getKeterangan(totalNilai);
                
                // Update displays
                $('#rata_b_value').text(rataB.toFixed(2));
                $('#rata_c_value').text(rataC.toFixed(2));
                $('#total_nilai').val(totalNilai.toFixed(2));
                $('#keterangan_berbobot').val(keterangan);
                
                // Prepare data to send
                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    jadwal_pengujian_id: {{ $jadwal->id }},
                    calon_dosen_id: {{ $calonDosen->id }},
                    
                    // Section A - Kualifikasi (auto-calculated)
                    nilai_jalur_lamaran: parseFloat($('#nilai_jalur_lamaran').val()),
                    nilai_jfa: parseFloat($('#nilai_jfa').val()),
                    nilai_h_index: parseFloat($('#nilai_h_index').val()),
                    rata_a: rataA,
                    
                    // Section B - Micro Teaching
                    nilai_pma: parseFloat($('#nilai_b1').val()),
                    nilai_sistematika: parseFloat($('#nilai_b2').val()),
                    nilai_kst: parseFloat($('#nilai_b3').val()),
                    rata_b: rataB,
                    
                    // Section C - Wawancara
                    nilai_motivasi: parseFloat($('#nilai_c1').val()),
                    nilai_kmp_mengajar: parseFloat($('#nilai_c2').val()),
                    nilai_kmp_mkp: parseFloat($('#nilai_c3').val()),
                    nilai_kmp_pp: parseFloat($('#nilai_c4').val()),
                    nilai_kmp_abdimas: parseFloat($('#nilai_c5').val()),
                    nilai_kmp_bdt: parseFloat($('#nilai_c6').val()),
                    nilai_keahlian_lainnya: parseFloat($('#nilai_c7').val()),
                    nilai_kmt_wkm: parseFloat($('#nilai_c8').val()),
                    rata_c: rataC,
                    
                    // Total
                    rata_nilai: totalNilai,
                    keterangan_berbobot: keterangan,
                    
                    // Kesiapan & Kesediaan
                    kesiapan: $('input[name="kesiapan"]:checked').val(),
                    kesediaan: $('input[name="kesediaan"]:checked').val(),
                    
                    // Additional fields
                    catatan_penilai: $('#catatan').val()
                };
                
                // Send AJAX request
                $.ajax({
                    url: '{{ route("rekrutasi-dosen.penilaian.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Penilaian berhasil disimpan.',
                            confirmButtonColor: '#C41E3A'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan penilaian: ' + (xhr.responseJSON?.message || 'Unknown error'),
                            confirmButtonColor: '#C41E3A'
                        });
                    }
                });
            });

            // Click event for Kalkulasi Rata-Rata button
            $('#btnKalkulasiNilai').on('click', function() {
                // Validation: Check all nilai inputs (B and C sections)
                let isValid = true;
                let errorMessage = '';
                
                // Check Section B inputs
                for (let i = 1; i <= 3; i++) {
                    const nilai = parseFloat($(`#nilai_b${i}`).val());
                    if (!nilai || nilai < 1 || nilai > 5) {
                        isValid = false;
                        errorMessage = 'Semua kriteria penilaian Section B harus diisi dengan angka 1-5';
                        break;
                    }
                }
                
                // Check Section C inputs
                if (isValid) {
                    for (let i = 1; i <= 8; i++) {
                        const nilai = parseFloat($(`#nilai_c${i}`).val());
                        if (!nilai || nilai < 1 || nilai > 5) {
                            isValid = false;
                            errorMessage = 'Semua kriteria penilaian Section C harus diisi dengan angka 1-5';
                            break;
                        }
                    }
                }
                
                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Kalkulasi Gagal',
                        text: errorMessage,
                        confirmButtonColor: '#C41E3A'
                    });
                    return;
                }
                
                // Calculate all averages
                const rataA = calculateRataA();
                const rataB = calculateRataB();
                const rataC = calculateRataC();
                const totalNilai = (rataA + rataB + rataC) / 3;
                const keterangan = getKeterangan(totalNilai);
                
                // Update displays
                $('#rata_b_value').text(rataB.toFixed(2));
                $('#rata_c_value').text(rataC.toFixed(2));
                $('#total_nilai').val(totalNilai.toFixed(2));
                $('#keterangan_berbobot').val(keterangan);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Kalkulasi Sukses',
                    text: 'Nilai rata-rata berhasil dihitung: ' + totalNilai.toFixed(2) + ' (' + keterangan + ')',
                    confirmButtonColor: '#C41E3A'
                });
            });
        });
    </script>
</body>
</html>
