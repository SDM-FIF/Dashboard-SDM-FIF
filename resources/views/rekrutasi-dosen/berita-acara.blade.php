<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Berita Acara - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Berita Acara</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Sidang Evaluasi Hasil Uji Kelayakan Microteaching & Interview Calon Dosen.</p>
            </div>
            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Main Content Wrapper --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            {{-- Statement Box --}}
            <div class="p-6 bg-slate-50 border border-slate-100 rounded-xl mb-8 flex items-start gap-4">
                <div class="w-12 h-12 bg-white text-[#C41E3A] rounded-xl flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-scroll"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pada hari ini, <strong class="text-gray-800">{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('l') }}</strong> tanggal 
                        <strong class="text-gray-800">{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('d F Y') }}</strong>, 
                        pukul <strong class="text-gray-800">{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</strong> WIB secara 
                        <strong class="text-gray-800">{{ $jadwal->metode_pelaksanaan }}</strong> di Fakultas Informatika, Universitas Telkom 
                        Jalan Telekomunikasi No.1 Terusan Buah Batu Bandung, telah dilaksanakan kegiatan Microteaching & Interview 
                        untuk calon dosen profesional :
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 max-w-xl">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nama Calon Dosen</span>
                            <span class="text-sm font-bold text-gray-800">{{ $calonDosen->nama }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Bidang Keahlian</span>
                            <span class="text-sm font-bold text-gray-800">{{ $calonDosen->bidang_keahlian ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="mb-8">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list-ol text-[#C41E3A]"></i>
                    <span>Hasil Penilaian Masing-Masing Penguji</span>
                </h3>
                
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-16">No</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Dosen Penguji</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-48">Nilai Rata-Rata Berbobot</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Catatan/Rekomendasi Ulasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($penilaianList as $index => $penilaian)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-bold text-center">
                                    {{ $index + 1 }}.
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    @if($penilaian->dosen)
                                        {{ $penilaian->dosen->front_title }} {{ $penilaian->dosen->nama_lengkap }}{{ $penilaian->dosen->back_title ? ', ' . $penilaian->dosen->back_title : '' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-[#C41E3A] text-sm whitespace-nowrap">
                                    {{ number_format($penilaian->rata_nilai, 2) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 font-medium">
                                    {{ $penilaian->catatan_penilai ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-blue-50/50">
                                <td colspan="2" class="px-6 py-4 text-center font-bold text-blue-900">
                                    NILAI RATA-RATA AKHIR
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-blue-900 text-base whitespace-nowrap">
                                    {{ number_format($nilaiRataAkhir, 2) }}
                                </td>
                                <td class="px-6 py-4"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Section --}}
            <form id="formBeritaAcara" action="{{ route('rekrutasi-dosen.berita-acara.store', $jadwal->id) }}" method="POST" class="space-y-6 border-t border-gray-100 pt-6">
                @csrf
                <input type="hidden" name="rata_akhir" value="{{ $nilaiRataAkhir }}">

                {{-- Rekomendasi Box --}}
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <label for="rekomendasi_akhir" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rekomendasi Akhir Calon Dosen <span class="text-red-500">*</span></label>
                        <select name="rekomendasi_akhir" id="rekomendasi_akhir" 
                                class="w-full md:w-1/2 px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                {{ $isSubmitted ? 'disabled' : '' }} required>
                            <option value="">-- Pilih Status Kelayakan --</option>
                            <option value="1" {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') == '1' ? 'selected' : '' }}>Direkomendasikan</option>
                            <option value="0" {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') === '0' || old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') === 0 ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                        </select>
                    </div>

                    {{-- Detail Rekomendasi (Hidden by default) --}}
                    <div id="detailRekomendasi" class="bg-gray-50 border border-gray-100 rounded-2xl p-6 space-y-6 {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') == '1' ? '' : 'hidden' }}">
                        <h4 class="text-sm font-bold text-gray-800 border-b border-gray-200 pb-2">Ketentuan Penugasan Calon Dosen</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Prodi --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rekomendasi Program Studi <span class="text-red-500">*</span></label>
                                <select name="prodi_rekomendasi" id="prodi_rekomendasi" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_rekomendasi', $penilaianDosenPenguji1->prodi_rekomendasi ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status Dosen <span class="text-red-500">*</span></label>
                                <select name="status_rekomendasi" id="status_rekomendasi" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Full Time" {{ old('status_rekomendasi', $penilaianDosenPenguji1->status_rekomendasi ?? '') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="Part Time" {{ old('status_rekomendasi', $penilaianDosenPenguji1->status_rekomendasi ?? '') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                </select>
                            </div>

                            {{-- JFA yang diakui --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan Fungsional yang Diakui <span class="text-red-500">*</span></label>
                                <select name="jfa_rekomendasi" id="jfa_rekomendasi" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    <option value="">-- Pilih JFA --</option>
                                    <option value="NJFA" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                    <option value="Asisten Ahli" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                    <option value="Lektor" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                    <option value="Lektor Kepala" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                </select>
                            </div>

                            {{-- Pendidikan yang diakui --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jenjang Pendidikan yang Diakui <span class="text-red-500">*</span></label>
                                <select name="pendidikan_rekomendasi" id="pendidikan_rekomendasi" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Pendidikan --</option>
                                    <option value="S2" {{ old('pendidikan_rekomendasi', $penilaianDosenPenguji1->pendidikan_rekomendasi ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_rekomendasi', $penilaianDosenPenguji1->pendidikan_rekomendasi ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>

                            {{-- Kelompok Keahlian --}}
                            <div class="md:col-span-2 flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kelompok Keahlian (KK) <span class="text-red-500">*</span></label>
                                <select name="kk_rekomendasi" id="kk_rekomendasi" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Kelompok Keahlian --</option>
                                    @foreach($kelompokKeahlianList as $kk)
                                    <option value="{{ $kk->id }}" {{ old('kk_rekomendasi', $penilaianDosenPenguji1->kk_rekomendasi ?? '') == $kk->id ? 'selected' : '' }}>
                                        {{ $kk->nama_kelompok_keahlian }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    @if(!$isSubmitted)
                        @can('berita-acara.submit')
                        <button type="submit" class="px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Berita Acara</span>
                        </button>
                        @else
                        <button type="button" class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed flex items-center gap-2 border border-gray-200" disabled>
                            <i class="fas fa-eye"></i>
                            <span>Mode Monitor (Read-Only)</span>
                        </button>
                        @endcan
                    @else
                    <a href="{{ route('rekrutasi-dosen.berita-acara.download', $jadwal->id) }}" 
                       target="_blank"
                       class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span>Unduh Berita Acara (PDF)</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Toggle detail rekomendasi
            $('#rekomendasi_akhir').on('change', function() {
                const value = $(this).val();
                const detailSection = $('#detailRekomendasi');
                
                if (value === '1') {
                    detailSection.removeClass('hidden');
                    detailSection.find('select').attr('required', true);
                } else {
                    detailSection.addClass('hidden');
                    detailSection.find('select').attr('required', false);
                }
            });

            // Form submission
            $('#formBeritaAcara').on('submit', function(e) {
                e.preventDefault();
                
                const rekomendasi = $('#rekomendasi_akhir').val();
                
                if (rekomendasi === '1') {
                    let isValid = true;
                    $('#detailRekomendasi select').each(function() {
                        if ($(this).val() === '') {
                            isValid = false;
                            return false;
                        }
                    });
                    
                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Mohon lengkapi semua data rekomendasi kelayakan!',
                            confirmButtonColor: '#C41E3A'
                        });
                        return;
                    }
                }
                
                Swal.fire({
                    title: 'Konfirmasi Simpan',
                    text: 'Apakah Anda yakin ingin menyimpan berita acara ini? Setelah disimpan data tidak dapat diubah.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#C41E3A',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
