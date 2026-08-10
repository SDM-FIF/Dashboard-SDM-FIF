<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Jadwal Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom styling untuk SweetAlert2 form */
        .swal2-input,
        .swal2-textarea,
        .swal2-select {
            margin: 0 !important;
            box-sizing: border-box !important;
            border-radius: 12px !important;
            border: 1px solid #E2E8F0 !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            padding: 10px 14px !important;
            background-color: #F8FAFC !important;
            outline: none !important;
            height: auto !important;
        }
        .swal2-input:focus,
        .swal2-textarea:focus,
        .swal2-select:focus {
            background-color: #FFFFFF !important;
            border-color: #C41E3A !important;
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.15) !important;
        }
        .swal2-html-container {
            overflow-y: auto !important;
            max-height: 75vh !important;
            padding: 1rem 1.5rem !important;
        }
        .swal2-popup {
            border-radius: 20px !important;
        }
        /* Select2 styling inside SweetAlert2 */
        .select2-container {
            z-index: 9999 !important;
        }
        .select2-container .select2-selection--single {
            height: 44px !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            background-color: #F8FAFC !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 14px !important;
            color: #475569 !important;
            font-size: 14px !important;
            font-family: 'Outfit', sans-serif !important;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
        }
        .select2-dropdown {
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
        }
        .select2-search__field {
            border: 1px solid #E2E8F0 !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            outline: none !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
        }
        .select2-results__option {
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
        }
        /* Select2 Multiple styling inside SweetAlert2 */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            background-color: #F8FAFC !important;
            padding: 4px 8px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search__field {
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            color: #475569 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search__field::placeholder {
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            color: #94A3B8 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #C41E3A !important;
            background-color: #FFFFFF !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #F1F5F9 !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 8px !important;
            padding: 2px 8px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 13px !important;
            color: #1E293B !important;
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
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Jadwal Pengujian</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium font-medium">Kelola agenda, waktu pelaksanaan, gedung, ruangan, dan pembagian dosen penguji calon dosen.</p>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-shadow duration-300">
            <form method="GET" action="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Metode Pelaksanaan Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Metode Pelaksanaan</label>
                        <select name="metode" id="filterMetode" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Semua Metode</option>
                            @foreach($metodeList as $metode)
                                <option value="{{ $metode }}" {{ request('metode') == $metode ? 'selected' : '' }}>{{ $metode }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cari Kata Kunci</label>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Nama calon dosen atau penguji..." 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Filter & Reset Buttons --}}
                    <div class="flex items-end gap-3">
                        <button type="submit" id="applyFilterBtn"
                            class="flex-1 px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-bold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-sliders-h"></i>
                            <span>Filter</span>
                        </button>

                        @if(request('metode') || request('search'))
                        <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" id="resetBtn"
                            class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-lg font-bold text-gray-800">Daftar Agenda Pengujian</h2>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Tambah Data Button --}}
                        @can('jadwal-pengujian.create')
                        <button type="button" id="btnTambahData"
                            class="px-5 py-2.5 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-bold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Jadwal</span>
                        </button>
                        @endcan

                        {{-- Export Dropdown --}}
                        <div class="relative">
                            <button type="button" id="exportBtn" class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                <span>Ekspor</span>
                                <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1 overflow-hidden">
                                <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-excel') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-file-excel text-emerald-600 w-4"></i>
                                    <span>Ekspor Excel</span>
                                </a>
                                <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-csv') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-file-csv text-blue-600 w-4"></i>
                                    <span>Ekspor CSV</span>
                                </a>
                                <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-pdf') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-file-pdf text-red-600 w-4"></i>
                                    <span>Ekspor PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Nama Calon Dosen</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Dosen Penguji</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider w-32">Metode</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Gedung</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider w-40">Waktu</th>
                            <th class="px-6 py-4 font-bold uppercase tracking-wider text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalTableBody" class="divide-y divide-gray-100 bg-white">
                        @forelse($jadwalList as $index => $jadwal)
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-bold text-center">
                                {{ $jadwalList->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $jadwal->calonDosen->nama_lengkap ?? $jadwal->calonDosen->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 space-y-0.5">
                                @foreach($jadwal->dosenPenguji as $dosen)
                                    <div class="font-semibold">{{ $loop->iteration }}. {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $metodeClass = $jadwal->metode_pelaksanaan == 'Online' 
                                        ? 'bg-blue-50 text-blue-700 border-blue-100' 
                                        : 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold border {{ $metodeClass }}">
                                    {{ $jadwal->metode_pelaksanaan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">{{ $jadwal->gedung ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">{{ $jadwal->ruangan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-700">
                                <i class="far fa-calendar-alt text-[#C41E3A] mr-1"></i>
                                {{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') }}
                                <div class="text-[10px] text-gray-400 font-semibold mt-0.5 ml-4">Pukul {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @can('jadwal-pengujian.detail')
                                    <button type="button" class="btn-detail w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-colors flex items-center justify-center" 
                                            data-id="{{ $jadwal->id }}" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    @endcan
                                    
                                    @can('jadwal-pengujian.edit')
                                    <button type="button" class="btn-edit w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 text-gray-600 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-colors flex items-center justify-center" 
                                            data-id="{{ $jadwal->id }}" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    @endcan

                                    @can('penilaian-dosen.access')
                                        @if(Auth::user()->hasRole('Super Admin') || in_array($jadwal->id, $jadwalWithPenilaianAccess))
                                        <button type="button" class="btn-penilaian w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 text-gray-600 hover:text-purple-600 hover:border-purple-200 hover:bg-purple-50 transition-colors flex items-center justify-center" 
                                                data-id="{{ $jadwal->id }}" title="Penilaian Calon Dosen">
                                            <i class="fas fa-clipboard-check text-xs"></i>
                                        </button>
                                        @endif
                                    @endcan

                                    @can('berita-acara.access')
                                        @php
                                            $currentUserId = Auth::id();
                                            $isDosenPenguji1 = false;
                                            
                                            if (Auth::check()) {
                                                foreach ($jadwal->dosenPenguji as $dosen) {
                                                    if ($dosen->user_id == $currentUserId && $dosen->pivot->urutan == 1) {
                                                        $isDosenPenguji1 = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            $beritaAcaraSubmitted = false;
                                            $dosenPenguji1 = $jadwal->dosenPenguji->firstWhere('pivot.urutan', 1);
                                            if ($dosenPenguji1) {
                                                $penilaianDosenPenguji1 = $jadwal->penilaianDetails->firstWhere('user_id', $dosenPenguji1->user_id);
                                                if ($penilaianDosenPenguji1 && $penilaianDosenPenguji1->rata_akhir !== null) {
                                                    $beritaAcaraSubmitted = true;
                                                }
                                            }
                                            
                                            $jumlahDosenPenguji = $jadwal->dosenPenguji->count();
                                            $jumlahPenilaian = $jadwal->penilaianDetails->count();
                                            $allPenilaianSubmitted = $jumlahPenilaian >= $jumlahDosenPenguji && $jumlahDosenPenguji > 0;
                                        @endphp
                                        @if($beritaAcaraSubmitted)
                                            @if(Auth::user()->hasRole('Super Admin') || $isDosenPenguji1)
                                            <button type="button" class="btn-berita-acara w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 text-orange-600 hover:bg-orange-100 transition-colors flex items-center justify-center" 
                                                    data-id="{{ $jadwal->id }}" title="Lihat Berita Acara">
                                                <i class="fas fa-file-signature text-xs"></i>
                                            </button>
                                            @endif
                                        @elseif($allPenilaianSubmitted && (Auth::user()->hasRole('Super Admin') || $isDosenPenguji1))
                                            <button type="button" class="btn-berita-acara w-8 h-8 rounded-lg bg-yellow-50 border border-yellow-100 text-yellow-600 hover:bg-yellow-100 transition-colors flex items-center justify-center" 
                                                    data-id="{{ $jadwal->id }}" title="Buat Berita Acara (Belum Submit)">
                                                <i class="fas fa-file-signature text-xs"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('jadwal-pengujian.delete')
                                    <button type="button" class="btn-delete w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 text-gray-600 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-colors flex items-center justify-center" 
                                            data-id="{{ $jadwal->id }}" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="far fa-calendar-times text-4xl text-gray-300"></i>
                                    <span class="text-sm font-semibold">Tidak ada agenda jadwal pengujian yang ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            @if($jadwalList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $jadwalList->links() }}
            </div>
            @endif
        </div>
    </main>

    <script>
        // Export Dropdown Toggle
        document.getElementById('exportBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('exportDropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('exportDropdown');
            const button = document.getElementById('exportBtn');
            if (button && dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Show Detail Modal
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                showDetail(id);
            });
        });

        function showDetail(id) {
            fetch(`/rekrutasi-dosen/jadwal-pengujian/${id}`)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: '<h3 class="text-lg font-extrabold text-[#C41E3A] border-b pb-2">Detail Jadwal Pengujian</h3>',
                        html: `
                            <div class="text-left space-y-4 pt-2 font-medium">
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Calon Dosen</span>
                                    <span class="text-sm font-bold text-gray-800">${data.calon_dosen_nama}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Dosen Penguji</span>
                                    <div class="text-sm font-semibold text-gray-700 space-y-0.5">
                                        ${data.dosen_penguji_list.map((dosen, index) => 
                                            `<div>${dosen.urutan}. ${dosen.nama}</div>`
                                        ).join('')}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Tahun Ajar</span>
                                        <span class="text-sm font-semibold text-gray-800">${data.tahun_ajar}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Tanggal Ujian</span>
                                        <span class="text-sm font-bold text-gray-800">${data.jadwal_ujian}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Metode</span>
                                        <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded bg-blue-50 text-blue-700 border border-blue-100 mt-1">${data.metode_pelaksanaan}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Gedung</span>
                                        <span class="text-sm font-semibold text-gray-800">${data.gedung || '-'}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Ruangan</span>
                                        <span class="text-sm font-semibold text-gray-800">${data.ruangan || '-'}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Waktu Pelaksanaan</span>
                                    <span class="text-sm font-bold text-gray-800">${data.waktu} WIB</span>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#C41E3A',
                        width: '450px'
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memuat detail jadwal pengujian.',
                        confirmButtonColor: '#C41E3A'
                    });
                });
        }

        // Open Create Modal
        const btnTambahData = document.getElementById('btnTambahData');
        if (btnTambahData) {
            btnTambahData.addEventListener('click', function() {
                openCreateModal();
            });
        }

        function openCreateModal() {
            Swal.fire({
                title: '<h3 class="text-lg font-extrabold text-[#C41E3A] border-b pb-2">Tambah Jadwal Pengujian</h3>',
                html: `
                    <form id="createForm" class="text-left space-y-4 pt-2 font-medium">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajar <span class="text-red-500">*</span></label>
                            <select name="tahun_ajar_id" required class="swal2-select w-full">
                                @foreach($tahunAjarList as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Calon Dosen <span class="text-red-500">*</span></label>
                            <select id="calonDosenSelect" name="calon_dosen_id" required class="w-full" style="width: 100%;">
                                <option value="">Pilih Calon Dosen</option>
                                @foreach($calonDosenList as $calon)
                                <option value="{{ $calon->id }}">{{ $calon->nama_lengkap ?? $calon->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 1 <span class="text-red-500">*</span></label>
                            <select id="dosenPenguji1Select" name="dosen_penguji_1" required class="w-full" style="width: 100%;">
                                <option value="">Pilih Dosen Penguji 1</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 2 <span class="text-red-500">*</span></label>
                            <select id="dosenPenguji2Select" name="dosen_penguji_2" required class="w-full" style="width: 100%;">
                                <option value="">Pilih Dosen Penguji 2</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 3 <span class="text-gray-400">(Opsional)</span></label>
                            <select id="dosenPenguji3Select" name="dosen_penguji_3" class="w-full" style="width: 100%;">
                                <option value="">Pilih Dosen Penguji 3 (Opsional)</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Ujian <span class="text-red-500">*</span></label>
                                <input type="date" name="jadwal_ujian" required class="swal2-input w-full">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu <span class="text-red-500">*</span></label>
                                <input type="time" name="waktu" required class="swal2-input w-full">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode Pelaksanaan <span class="text-red-500">*</span></label>
                            <select id="metodeSelect" name="metode_pelaksanaan" required class="swal2-select w-full">
                                @foreach($metodeList as $metode)
                                <option value="{{ $metode }}">{{ $metode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div id="gedungField" class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gedung <span id="gedungRequired" class="text-red-500">*</span></label>
                                <input type="text" id="gedungInput" name="gedung" class="swal2-input w-full" placeholder="Contoh: Gedung A">
                            </div>
                            <div id="ruanganField" class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ruangan <span id="ruanganRequired" class="text-red-500">*</span></label>
                                <input type="text" id="ruanganInput" name="ruangan" class="swal2-input w-full" placeholder="Contoh: R.201">
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                width: '550px',
                didOpen: () => {
                    $('#calonDosenSelect').select2({
                        placeholder: 'Cari dan pilih calon dosen...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });

                    $('#dosenPenguji1Select').select2({
                        placeholder: 'Cari dan pilih dosen penguji 1...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });

                    $('#dosenPenguji2Select').select2({
                        placeholder: 'Cari dan pilih dosen penguji 2...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });

                    $('#dosenPenguji3Select').select2({
                        placeholder: 'Cari dan pilih dosen penguji 3 (opsional)...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });

                    // Enforce unique examiner selection in real-time
                    const createIds = ['dosenPenguji1Select', 'dosenPenguji2Select', 'dosenPenguji3Select'];
                    const createOnChangeHandler = function() {
                        createIds.forEach(id => $('#' + id).off('change.unique'));
                        
                        const val1 = $('#dosenPenguji1Select').val();
                        const val2 = $('#dosenPenguji2Select').val();
                        const val3 = $('#dosenPenguji3Select').val();
                        const selections = {
                            'dosenPenguji1Select': val1,
                            'dosenPenguji2Select': val2,
                            'dosenPenguji3Select': val3
                        };

                        createIds.forEach(id => {
                            const select = $('#' + id);
                            select.find('option').each(function() {
                                const optVal = $(this).val();
                                if (!optVal) return;
                                
                                let shouldDisable = false;
                                createIds.forEach(otherId => {
                                    if (otherId !== id && selections[otherId] === optVal) {
                                        shouldDisable = true;
                                    }
                                });
                                $(this).prop('disabled', shouldDisable);
                            });
                            
                            select.select2({
                                placeholder: id === 'dosenPenguji3Select' ? 'Cari dan pilih dosen penguji 3 (opsional)...' : (id === 'dosenPenguji2Select' ? 'Cari dan pilih dosen penguji 2...' : 'Cari dan pilih dosen penguji 1...'),
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });
                        });
                        
                        createIds.forEach(id => $('#' + id).on('change.unique', createOnChangeHandler));
                    };
                    createIds.forEach(id => $('#' + id).on('change.unique', createOnChangeHandler));

                    const metodeSelect = document.getElementById('metodeSelect');
                    const gedungInput = document.getElementById('gedungInput');
                    const ruanganInput = document.getElementById('ruanganInput');
                    const gedungRequired = document.getElementById('gedungRequired');
                    const ruanganRequired = document.getElementById('ruanganRequired');

                    function toggleGedungRuangan() {
                        if (metodeSelect.value === 'Online') {
                            gedungInput.disabled = true;
                            ruanganInput.disabled = true;
                            gedungInput.value = '';
                            ruanganInput.value = '';
                            gedungInput.style.backgroundColor = '#f1f5f9';
                            ruanganInput.style.backgroundColor = '#f1f5f9';
                            gedungRequired.style.display = 'none';
                            ruanganRequired.style.display = 'none';
                        } else {
                            gedungInput.disabled = false;
                            ruanganInput.disabled = false;
                            gedungInput.style.backgroundColor = '';
                            ruanganInput.style.backgroundColor = '';
                            gedungRequired.style.display = 'inline';
                            ruanganRequired.style.display = 'inline';
                        }
                    }

                    metodeSelect.addEventListener('change', toggleGedungRuangan);
                    toggleGedungRuangan();
                },
                preConfirm: () => {
                    const form = document.getElementById('createForm');
                    const dp1 = $('#dosenPenguji1Select').val();
                    const dp2 = $('#dosenPenguji2Select').val();
                    const dp3 = $('#dosenPenguji3Select').val();
                    
                    if (!dp1) {
                        Swal.showValidationMessage('Dosen Penguji 1 wajib dipilih');
                        return false;
                    }
                    if (!dp2) {
                        Swal.showValidationMessage('Dosen Penguji 2 wajib dipilih');
                        return false;
                    }
                    
                    const selectedDosen = [dp1, dp2];
                    if (dp3) selectedDosen.push(dp3);
                    const hasDuplicates = new Set(selectedDosen).size !== selectedDosen.length;
                    if (hasDuplicates) {
                        Swal.showValidationMessage('Setiap Dosen Penguji harus berbeda');
                        return false;
                    }
                    
                    const formData = new FormData(form);
                    formData.append('dosen_penguji_id[]', dp1);
                    formData.append('dosen_penguji_id[]', dp2);
                    if (dp3) {
                        formData.append('dosen_penguji_id[]', dp3);
                    }
                    
                    return fetch('/rekrutasi-dosen/jadwal-pengujian', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            return data;
                        } else {
                            throw new Error(data.message || 'Gagal menyimpan data');
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Jadwal pengujian berhasil ditambahkan.',
                        confirmButtonColor: '#C41E3A'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Open Edit Modal
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditModal(id);
            });
        });

        function openEditModal(id) {
            fetch(`/rekrutasi-dosen/jadwal-pengujian/${id}`)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: '<h3 class="text-lg font-extrabold text-[#C41E3A] border-b pb-2">Edit Jadwal Pengujian</h3>',
                        html: `
                            <form id="editForm" class="text-left space-y-4 pt-2 font-medium">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajar <span class="text-red-500">*</span></label>
                                    <select name="tahun_ajar_id" required class="swal2-select w-full">
                                        @foreach($tahunAjarList as $ta)
                                        <option value="{{ $ta->id }}" ${data.tahun_ajar_id == {{ $ta->id }} ? 'selected' : ''}>{{ $ta->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Calon Dosen <span class="text-red-500">*</span></label>
                                    <select id="editCalonDosenSelect" name="calon_dosen_id" required class="w-full" style="width: 100%;">
                                        @foreach($calonDosenList as $calon)
                                        <option value="{{ $calon->id }}" ${data.calon_dosen_id == {{ $calon->id }} ? 'selected' : ''}>{{ $calon->nama_lengkap ?? $calon->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 1 <span class="text-red-500">*</span></label>
                                    <select id="editDosenPenguji1Select" name="dosen_penguji_1" required class="w-full" style="width: 100%;">
                                        <option value="">Pilih Dosen Penguji 1</option>
                                        @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 2 <span class="text-red-500">*</span></label>
                                    <select id="editDosenPenguji2Select" name="dosen_penguji_2" required class="w-full" style="width: 100%;">
                                        <option value="">Pilih Dosen Penguji 2</option>
                                        @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Penguji 3 <span class="text-gray-400">(Opsional)</span></label>
                                    <select id="editDosenPenguji3Select" name="dosen_penguji_3" class="w-full" style="width: 100%;">
                                        <option value="">Pilih Dosen Penguji 3 (Opsional)</option>
                                        @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Ujian <span class="text-red-500">*</span></label>
                                        <input type="date" name="jadwal_ujian" value="${data.jadwal_ujian_raw}" required class="swal2-input w-full">
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu <span class="text-red-500">*</span></label>
                                        <input type="time" name="waktu" value="${data.waktu_raw}" required class="swal2-input w-full">
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode Pelaksanaan <span class="text-red-500">*</span></label>
                                    <select id="editMetodeSelect" name="metode_pelaksanaan" required class="swal2-select w-full">
                                        @foreach($metodeList as $metode)
                                        <option value="{{ $metode }}" ${data.metode_pelaksanaan == '{{ $metode }}' ? 'selected' : ''}>{{ $metode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div id="editGedungField" class="flex flex-col gap-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gedung <span id="editGedungRequired" class="text-red-500">*</span></label>
                                        <input type="text" id="editGedungInput" name="gedung" value="${data.gedung || ''}" class="swal2-input w-full" placeholder="Contoh: Gedung A">
                                    </div>
                                    <div id="editRuanganField" class="flex flex-col gap-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ruangan <span id="editRuanganRequired" class="text-red-500">*</span></label>
                                        <input type="text" id="editRuanganInput" name="ruangan" value="${data.ruangan || ''}" class="swal2-input w-full" placeholder="Contoh: R.201">
                                    </div>
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6B7280',
                        width: '550px',
                        didOpen: () => {
                            $('#editCalonDosenSelect').select2({
                                placeholder: 'Cari dan pilih calon dosen...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            $('#editDosenPenguji1Select').select2({
                                placeholder: 'Cari dan pilih dosen penguji 1...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            $('#editDosenPenguji2Select').select2({
                                placeholder: 'Cari dan pilih dosen penguji 2...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            $('#editDosenPenguji3Select').select2({
                                placeholder: 'Cari dan pilih dosen penguji 3 (opsional)...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            if (data.dosen_penguji_ids && data.dosen_penguji_ids.length > 0) {
                                $('#editDosenPenguji1Select').val(data.dosen_penguji_ids[0]).trigger('change');
                                if (data.dosen_penguji_ids.length > 1) {
                                    $('#editDosenPenguji2Select').val(data.dosen_penguji_ids[1]).trigger('change');
                                }
                                if (data.dosen_penguji_ids.length > 2) {
                                    $('#editDosenPenguji3Select').val(data.dosen_penguji_ids[2]).trigger('change');
                                }
                            }

                            // Enforce unique examiner selection in real-time for edit modal
                            const editIds = ['editDosenPenguji1Select', 'editDosenPenguji2Select', 'editDosenPenguji3Select'];
                            const editOnChangeHandler = function() {
                                editIds.forEach(id => $('#' + id).off('change.unique'));
                                
                                const val1 = $('#editDosenPenguji1Select').val();
                                const val2 = $('#editDosenPenguji2Select').val();
                                const val3 = $('#editDosenPenguji3Select').val();
                                const selections = {
                                    'editDosenPenguji1Select': val1,
                                    'editDosenPenguji2Select': val2,
                                    'editDosenPenguji3Select': val3
                                };

                                editIds.forEach(id => {
                                    const select = $('#' + id);
                                    select.find('option').each(function() {
                                        const optVal = $(this).val();
                                        if (!optVal) return;
                                        
                                        let shouldDisable = false;
                                        editIds.forEach(otherId => {
                                            if (otherId !== id && selections[otherId] === optVal) {
                                                shouldDisable = true;
                                            }
                                        });
                                        $(this).prop('disabled', shouldDisable);
                                    });
                                    
                                    select.select2({
                                        placeholder: id === 'editDosenPenguji3Select' ? 'Cari dan pilih dosen penguji 3 (opsional)...' : (id === 'editDosenPenguji2Select' ? 'Cari dan pilih dosen penguji 2...' : 'Cari dan pilih dosen penguji 1...'),
                                        allowClear: true,
                                        dropdownParent: $('.swal2-container')
                                    });
                                });
                                
                                editIds.forEach(id => $('#' + id).on('change.unique', editOnChangeHandler));
                            };
                            editIds.forEach(id => $('#' + id).on('change.unique', editOnChangeHandler));
                            
                            // Trigger initial enforcement after pre-population
                            editOnChangeHandler();

                            const metodeSelect = document.getElementById('editMetodeSelect');
                            const gedungInput = document.getElementById('editGedungInput');
                            const ruanganInput = document.getElementById('editRuanganInput');
                            const gedungRequired = document.getElementById('editGedungRequired');
                            const ruanganRequired = document.getElementById('editRuanganRequired');

                            function toggleGedungRuangan() {
                                if (metodeSelect.value === 'Online') {
                                    gedungInput.disabled = true;
                                    ruanganInput.disabled = true;
                                    gedungInput.value = '';
                                    ruanganInput.value = '';
                                    gedungInput.style.backgroundColor = '#f1f5f9';
                                    ruanganInput.style.backgroundColor = '#f1f5f9';
                                    gedungRequired.style.display = 'none';
                                    ruanganRequired.style.display = 'none';
                                } else {
                                    gedungInput.disabled = false;
                                    ruanganInput.disabled = false;
                                    gedungInput.style.backgroundColor = '';
                                    ruanganInput.style.backgroundColor = '';
                                    gedungRequired.style.display = 'inline';
                                    ruanganRequired.style.display = 'inline';
                                }
                            }

                            metodeSelect.addEventListener('change', toggleGedungRuangan);
                            toggleGedungRuangan();
                        },
                        preConfirm: () => {
                            const form = document.getElementById('editForm');
                            const dp1 = $('#editDosenPenguji1Select').val();
                            const dp2 = $('#editDosenPenguji2Select').val();
                            const dp3 = $('#editDosenPenguji3Select').val();
                            
                            if (!dp1) {
                                Swal.showValidationMessage('Dosen Penguji 1 wajib dipilih');
                                return false;
                            }
                            if (!dp2) {
                                Swal.showValidationMessage('Dosen Penguji 2 wajib dipilih');
                                return false;
                            }
                            
                            const selectedDosen = [dp1, dp2];
                            if (dp3) selectedDosen.push(dp3);
                            const hasDuplicates = new Set(selectedDosen).size !== selectedDosen.length;
                            if (hasDuplicates) {
                                Swal.showValidationMessage('Setiap Dosen Penguji harus berbeda');
                                return false;
                            }
                            
                            const formData = new FormData(form);
                            formData.append('dosen_penguji_id[]', dp1);
                            formData.append('dosen_penguji_id[]', dp2);
                            if (dp3) {
                                formData.append('dosen_penguji_id[]', dp3);
                            }
                            
                            return fetch(`/rekrutasi-dosen/jadwal-pengujian/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    return data;
                                } else {
                                    throw new Error(data.message || 'Gagal mengupdate data');
                                }
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Jadwal pengujian berhasil diupdate.',
                                confirmButtonColor: '#C41E3A'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memuat data jadwal pengujian.',
                        confirmButtonColor: '#C41E3A'
                    });
                });
        }

        // Confirm Delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmDelete(id);
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jadwal pengujian akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/rekrutasi-dosen/jadwal-pengujian/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Jadwal pengujian berhasil dihapus.',
                                confirmButtonColor: '#C41E3A'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Gagal menghapus data');
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message || 'Gagal menghapus jadwal pengujian.',
                            confirmButtonColor: '#C41E3A'
                        });
                    });
                }
            });
        }

        // Penilaian Calon Dosen Button
        document.querySelectorAll('.btn-penilaian').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                window.location.href = `/rekrutasi-dosen/penilaian/${id}`;
            });
        });

        // Berita Acara Button
        document.querySelectorAll('.btn-berita-acara').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                window.location.href = `/rekrutasi-dosen/berita-acara/${id}`;
            });
        });
    </script>
</body>
</html>
