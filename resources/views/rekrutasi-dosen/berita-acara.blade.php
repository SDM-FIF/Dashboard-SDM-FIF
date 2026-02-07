<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Berita Acara - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Berita Acara Microteaching & Interview</h1>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            {{-- Header Section --}}
            <div class="mb-6">
                <p class="text-sm mb-4">
                    Pada hari ini, <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('l') }}</strong> tanggal 
                    <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('d F Y') }}</strong>, 
                    pukul <strong>{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</strong> WIB secara 
                    <strong>{{ $jadwal->metode_pelaksanaan }}</strong> di Fakultas Informatika, Universitas Telkom 
                    Jalan Telekomunikasi No.1 Terusan Buah Batu Bandung, telah dilaksanakan kegiatan Microteaching & Interview 
                    untuk calon dosen profesional :
                </p>
                <div class="ml-8">
                    <table class="text-sm">
                        <tr>
                            <td class="py-1 pr-8">Nama</td>
                            <td class="py-1 pr-2">:</td>
                            <td class="py-1 font-semibold">{{ $calonDosen->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 pr-8">Bidang Keahlian</td>
                            <td class="py-1 pr-2">:</td>
                            <td class="py-1 font-semibold">{{ $calonDosen->bidang_keahlian ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="mb-6">
                <p class="text-sm mb-4">Dengan nilai sebagai berikut :</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-400">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 px-4 py-2 text-center text-sm font-semibold w-16">NO.</th>
                                <th class="border border-gray-400 px-4 py-2 text-center text-sm font-semibold">PENGUJI</th>
                                <th class="border border-gray-400 px-4 py-2 text-center text-sm font-semibold">NILAI RATA-RATA<br>BERBOBOT</th>
                                <th class="border border-gray-400 px-4 py-2 text-center text-sm font-semibold">CATATAN PENILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penilaianList as $index => $penilaian)
                            <tr>
                                <td class="border border-gray-400 px-4 py-3 text-center text-sm">{{ $index + 1 }}.</td>
                                <td class="border border-gray-400 px-4 py-3 text-sm">
                                    @if($penilaian->dosen)
                                        {{ $penilaian->dosen->front_title }} {{ $penilaian->dosen->nama_lengkap }}, {{ $penilaian->dosen->back_title }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border border-gray-400 px-4 py-3 text-center text-sm font-semibold">{{ number_format($penilaian->rata_nilai, 2) }}</td>
                                <td class="border border-gray-400 px-4 py-3 text-sm">{{ $penilaian->catatan_penilai ?? '-' }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="2" class="border border-gray-400 px-4 py-3 text-center text-sm font-bold">NILAI RATA-RATA AKHIR</td>
                                <td class="border border-gray-400 px-4 py-3 text-center text-sm font-bold">{{ number_format($nilaiRataAkhir, 2) }}</td>
                                <td class="border border-gray-400 px-4 py-3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Section --}}
            <form id="formBeritaAcara" action="{{ route('rekrutasi-dosen.berita-acara.store', $jadwal->id) }}" method="POST">
                @csrf
                
                {{-- Hidden field for rata_akhir --}}
                <input type="hidden" name="rata_akhir" value="{{ $nilaiRataAkhir }}">

                {{-- Rekomendasi Section --}}
                <div class="mb-6">
                    <p class="text-sm mb-3">Rekomendasi akhir dinyatakan : <span class="text-red-600">*</span></p>
                    <div class="ml-8">
                        <div class="mb-4">
                            <select name="rekomendasi_akhir" id="rekomendasi_akhir" 
                                    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
                                    {{ $isSubmitted ? 'disabled' : '' }} required>
                                <option value="">-- Pilih Rekomendasi --</option>
                                <option value="1" {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') == '1' ? 'selected' : '' }}>Direkomendasikan</option>
                                <option value="0" {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') === '0' || old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') === 0 ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                            </select>
                        </div>

                        {{-- Detail Rekomendasi (Hidden by default) --}}
                        <div id="detailRekomendasi" class="space-y-4 {{ old('rekomendasi_akhir', $penilaianDosenPenguji1->rekomendasi_akhir ?? '') == '1' ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Prodi --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">Prodi <span class="text-red-600">*</span></label>
                                    <select name="prodi_rekomendasi" id="prodi_rekomendasi" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
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
                                <div>
                                    <label class="block text-sm font-medium mb-2">Status <span class="text-red-600">*</span></label>
                                    <select name="status_rekomendasi" id="status_rekomendasi" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
                                            {{ $isSubmitted ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Full Time" {{ old('status_rekomendasi', $penilaianDosenPenguji1->status_rekomendasi ?? '') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Part Time" {{ old('status_rekomendasi', $penilaianDosenPenguji1->status_rekomendasi ?? '') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                    </select>
                                </div>

                                {{-- JFA yang diakui --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">JFA yang diakui <span class="text-red-600">*</span></label>
                                    <select name="jfa_rekomendasi" id="jfa_rekomendasi" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
                                            {{ $isSubmitted ? 'disabled' : '' }}>
                                        <option value="">-- Pilih JFA --</option>
                                        <option value="NJFA" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                        <option value="Asisten Ahli" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                        <option value="Lektor" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                        <option value="Lektor Kepala" {{ old('jfa_rekomendasi', $penilaianDosenPenguji1->jfa_rekomendasi ?? '') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                    </select>
                                </div>

                                {{-- Pendidikan yang diakui --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">Pendidikan yang diakui <span class="text-red-600">*</span></label>
                                    <select name="pendidikan_rekomendasi" id="pendidikan_rekomendasi" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
                                            {{ $isSubmitted ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Pendidikan --</option>
                                        <option value="S2" {{ old('pendidikan_rekomendasi', $penilaianDosenPenguji1->pendidikan_rekomendasi ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_rekomendasi', $penilaianDosenPenguji1->pendidikan_rekomendasi ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>

                                {{-- Kelompok Keahlian --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2">Kelompok Keahlian <span class="text-red-600">*</span></label>
                                    <select name="kk_rekomendasi" id="kk_rekomendasi" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]"
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
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-4 mt-8">
                    @if(!$isSubmitted)
                    <button type="submit" class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-8 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>Submit
                    </button>
                    @else
                    <a href="{{ route('rekrutasi-dosen.berita-acara.download', $jadwal->id) }}" 
                       target="_blank"
                       class="bg-[#C41E3A] hover:bg-red-700 text-white font-semibold px-8 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-download mr-2"></i>Download
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
                    // Set required for detail fields
                    detailSection.find('select').attr('required', true);
                } else {
                    detailSection.addClass('hidden');
                    // Remove required for detail fields
                    detailSection.find('select').attr('required', false);
                }
            });

            // Form submission
            $('#formBeritaAcara').on('submit', function(e) {
                e.preventDefault();
                
                const rekomendasi = $('#rekomendasi_akhir').val();
                
                // Validate detail rekomendasi if "Direkomendasikan"
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
                            title: 'Validasi Error',
                            text: 'Mohon lengkapi semua data rekomendasi!',
                            confirmButtonColor: '#C41E3A'
                        });
                        return;
                    }
                }
                
                // Submit form
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan berita acara ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#FBB03B',
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
