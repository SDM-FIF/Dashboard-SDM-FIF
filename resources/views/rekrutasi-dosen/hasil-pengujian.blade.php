<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hasil Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Bar --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Hasil Pengujian Calon Dosen</h1>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Data Hasil Pengujian</h2>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-16">No.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Calon Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Dosen Penguji</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Hasil Penilaian</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($calonDosenList as $index => $calon)
                                @php
                                    $jadwal = $calon->jadwalPengujian->first();
                                    $dosenPengujiList = [];
                                    $penilaianList = [];
                                    
                                    if ($jadwal) {
                                        // Get all dosen penguji sorted by urutan
                                        $allDosenPenguji = $jadwal->dosenPenguji;
                                        
                                        // Ensure we have exactly 3 slots
                                        for ($i = 1; $i <= 3; $i++) {
                                            $dosen = $allDosenPenguji->firstWhere('pivot.urutan', $i);
                                            $dosenPengujiList[] = $dosen;
                                            
                                            // Get penilaian for this dosen
                                            if ($dosen) {
                                                $penilaian = $jadwal->penilaianDetails->firstWhere('user_id', $dosen->user_id);
                                                $penilaianList[] = $penilaian;
                                            } else {
                                                $penilaianList[] = null;
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900"><strong>{{ $calon->nama }}</strong></td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @if(!empty($dosenPengujiList))
                                            @foreach($dosenPengujiList as $key => $dosen)
                                                @if($dosen)
                                                    <div class="mb-1">{{ $key + 1 }}. {{ $dosen->nama_lengkap }}</div>
                                                @else
                                                    <div class="mb-1 text-gray-400">{{ $key + 1 }}. -</div>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">Belum ada jadwal</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @if(!empty($penilaianList))
                                            @foreach($penilaianList as $key => $penilaian)
                                                <div class="mb-1">
                                                    {{ $key + 1 }}. 
                                                    @if($penilaian)
                                                        <strong>{{ number_format($penilaian->rata_nilai, 2) }}</strong>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @if(!empty($penilaianList))
                                            @foreach($penilaianList as $key => $penilaian)
                                                <div class="mb-1">
                                                    {{ $key + 1 }}. 
                                                    @if($penilaian)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($penilaian->keterangan_berbobot == 'Sangat Baik') bg-green-100 text-green-800
                                                            @elseif($penilaian->keterangan_berbobot == 'Baik') bg-blue-100 text-blue-800
                                                            @elseif($penilaian->keterangan_berbobot == 'Cukup') bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ $penilaian->keterangan_berbobot }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                        @php
                                            $hasPenilaian = collect($penilaianList)->filter()->isNotEmpty();
                                        @endphp
                                        @if($hasPenilaian)
                                            <a href="{{ route('rekrutasi-dosen.hasil-pengujian.combined-pdf', $calon->id) }}" 
                                               target="_blank"
                                               class="text-purple-600 hover:text-purple-800 transition-colors duration-200" 
                                               title="Lihat Hasil Pengujian">
                                                <i class="fas fa-clipboard-check text-xl"></i>
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">Tidak ada data calon dosen</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
</body>
</html>