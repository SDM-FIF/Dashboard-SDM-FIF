<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        <div class="mb-8 mt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Hasil Pengujian Calon Dosen</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Lihat dan unduh rekapitulasi nilai pengujian beserta berkas berita acara dari masing-masing dosen penguji.</p>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#C41E3A'
                });
            });
        </script>
        @endif

        {{-- Data Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Daftar Hasil Nilai Pengujian</h2>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Nama Calon Dosen</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Dosen Penguji</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-40">Hasil Penilaian</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider w-40">Kategori Kelayakan</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($calonDosenList as $index => $calon)
                        @php
                            $jadwal = $calon->jadwalPengujian->first();
                            $dosenPengujiList = [];
                            $penilaianList = [];
                            
                            if ($jadwal) {
                                $allDosenPenguji = $jadwal->dosenPenguji;
                                
                                for ($i = 1; $i <= 3; $i++) {
                                    $dosen = $allDosenPenguji->firstWhere('pivot.urutan', $i);
                                    $dosenPengujiList[] = $dosen;
                                    
                                    if ($dosen) {
                                        $penilaian = $jadwal->penilaianDetails->firstWhere('user_id', $dosen->user_id);
                                        $penilaianList[] = $penilaian;
                                    } else {
                                        $penilaianList[] = null;
                                    }
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-bold text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $calon->nama_lengkap ?? $calon->nama }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 space-y-1">
                                @if(!empty($dosenPengujiList))
                                    @foreach($dosenPengujiList as $key => $dosen)
                                        @if($dosen)
                                            <div class="font-semibold">{{ $key + 1 }}. {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</div>
                                        @else
                                            <div class="text-gray-400 font-medium">{{ $key + 1 }}. -</div>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-gray-400 font-medium">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-800 space-y-1">
                                @if(!empty($penilaianList))
                                    @foreach($penilaianList as $key => $penilaian)
                                        <div>
                                            {{ $key + 1 }}. 
                                            @if($penilaian)
                                                <span class="text-sm font-extrabold text-[#C41E3A]">{{ number_format($penilaian->rata_nilai, 2) }}</span>
                                            @else
                                                <span class="text-gray-400 font-semibold">-</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs space-y-1">
                                @if(!empty($penilaianList))
                                    @foreach($penilaianList as $key => $penilaian)
                                        <div>
                                            @if($penilaian)
                                                @php
                                                    $badgeClass = match($penilaian->keterangan_berbobot) {
                                                        'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                        'Baik' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                        'Cukup' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                        default => 'bg-rose-50 text-rose-700 border-rose-100'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg font-bold border {{ $badgeClass }}">
                                                    {{ $penilaian->keterangan_berbobot }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-semibold">-</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $hasPenilaian = collect($penilaianList)->filter()->isNotEmpty();
                                    $hasBeritaAcara = false;
                                    if ($jadwal && $penilaianList[0] && $penilaianList[0]->rata_akhir !== null) {
                                        $hasBeritaAcara = true;
                                    }
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    @if($hasPenilaian)
                                        <a href="{{ route('rekrutasi-dosen.hasil-pengujian.combined-pdf', $calon->id) }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-lg bg-purple-50 border border-purple-100 text-purple-600 hover:bg-purple-100 transition-colors flex items-center justify-center" 
                                           title="Unduh Rekap Nilai (PDF)">
                                            <i class="fas fa-clipboard-check text-xs"></i>
                                        </a>
                                    @else
                                        <button disabled
                                                class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 text-gray-300 flex items-center justify-center cursor-not-allowed">
                                            <i class="fas fa-clipboard-check text-xs"></i>
                                        </button>
                                    @endif
                                    
                                    @if($hasBeritaAcara)
                                        <a href="{{ route('rekrutasi-dosen.hasil-pengujian.berita-acara', $jadwal->id) }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 text-orange-600 hover:bg-orange-100 transition-colors flex items-center justify-center" 
                                           title="Unduh Berita Acara (PDF)">
                                            <i class="fas fa-file-signature text-xs"></i>
                                        </a>
                                    @else
                                        <button disabled
                                                class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 text-gray-300 flex items-center justify-center cursor-not-allowed">
                                            <i class="fas fa-file-signature text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <span class="text-sm font-semibold">Tidak ada data hasil pengujian calon dosen.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>