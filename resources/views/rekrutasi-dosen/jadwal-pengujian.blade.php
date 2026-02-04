<!DOCTYPE html>
<html lang="en">

<style>
    /* Custom styling untuk SweetAlert2 form */
    .swal2-input,
    .swal2-textarea,
    .swal2-select {
        margin: 0 !important;
        box-sizing: border-box !important;
    }
    
    .swal2-html-container {
        overflow-y: auto !important;
        max-height: 70vh !important;
    }

    /* Select2 styling inside SweetAlert2 */
    .select2-container {
        z-index: 9999 !important;
    }
    
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
    }
    
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 12px !important;
    }
    
    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    
    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
    }
    
    .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 4px 8px !important;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Jadwal Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Jadwal Pengujian</h1>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Metode Pelaksanaan Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Metode Pelaksanaan</label>
                        <select name="metode" id="filterMetode" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Metode</option>
                            @foreach($metodeList as $metode)
                                <option value="{{ $metode }}" {{ request('metode') == $metode ? 'selected' : '' }}>{{ $metode }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Cari</label>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama calon dosen atau penguji..." 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    {{-- Filter & Reset Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit" id="applyFilterBtn"
                            class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-sliders-h text-black"></i>
                            <span>Filter</span>
                        </button>

                        @if(request('metode') || request('search'))
                        <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" id="resetBtn"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center space-x-2">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Data Jadwal Pengujian</h2>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                    <button type="button" id="btnTambahData"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </button>
                    @endif

                    {{-- Export Dropdown --}}
                    <div class="relative">
                        <button type="button" id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]">
                            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-excel') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                Export Excel
                            </a>
                            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-csv') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                Export CSV
                            </a>
                            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian.export-pdf') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-16">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Calon Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Dosen Penguji</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Metode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Gedung</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Ruangan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-40">Waktu</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($jadwalList as $index => $jadwal)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwalList->firstItem() + $index }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $jadwal->calonDosen->nama ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                @foreach($jadwal->dosenPenguji as $dosen)
                                    <div class="mb-1">{{ $loop->iteration }}. {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->metode_pelaksanaan == 'Online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $jadwal->metode_pelaksanaan }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->gedung ?? '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->ruangan ?? '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" class="btn-detail text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                                    <button type="button" class="btn-edit text-green-600 hover:text-green-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif
                                    @if(Auth::check() && Auth::user()->hasRole(['Super Admin', 'Dosen Penguji 1', 'Dosen Penguji 2', 'Dosen Penguji 3']))
                                    <button type="button" class="btn-penilaian text-purple-600 hover:text-purple-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Penilaian Calon Dosen">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                    @endif
                                    @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                                    <button type="button" class="btn-berita-acara text-orange-600 hover:text-orange-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Berita Acara">
                                        <i class="fas fa-file-signature"></i>
                                    </button>
                                    <button type="button" class="btn-delete text-red-600 hover:text-red-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                <p>Tidak ada data jadwal pengujian</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            @if($jadwalList->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-end">
                    <div class="flex items-center space-x-2">
                        {{ $jadwalList->links() }}
                    </div>
                </div>
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
            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
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
                        title: '<strong class="text-[#C41E3A]">Detail Jadwal Pengujian</strong>',
                        html: `
                            <div class="text-left space-y-3">
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Calon Dosen</p>
                                    <p class="text-base font-semibold">${data.calon_dosen_nama}</p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Dosen Penguji</p>
                                    <div class="text-base font-semibold">
                                        ${data.dosen_penguji_list.map((dosen, index) => 
                                            `<div class="mb-1">${dosen.urutan}. ${dosen.nama}</div>`
                                        ).join('')}
                                    </div>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Tahun Ajar</p>
                                    <p class="text-base font-semibold">${data.tahun_ajar}</p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Tanggal Ujian</p>
                                    <p class="text-base font-semibold">${data.jadwal_ujian}</p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Metode Pelaksanaan</p>
                                    <p class="text-base font-semibold">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.metode_pelaksanaan === 'Online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                                            ${data.metode_pelaksanaan}
                                        </span>
                                    </p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Gedung</p>
                                    <p class="text-base font-semibold">${data.gedung || '-'}</p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Ruangan</p>
                                    <p class="text-base font-semibold">${data.ruangan || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Waktu</p>
                                    <p class="text-base font-semibold">${data.waktu}</p>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#C41E3A',
                        width: '500px'
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat detail jadwal pengujian.',
                        confirmButtonColor: '#C41E3A'
                    });
                });
        }

        // Open Create Modal
        document.getElementById('btnTambahData').addEventListener('click', function() {
            openCreateModal();
        });

        function openCreateModal() {
            Swal.fire({
                title: '<strong class="text-[#C41E3A]">Tambah Jadwal Pengujian</strong>',
                html: `
                    <form id="createForm" class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajar <span class="text-red-500">*</span></label>
                            <select name="tahun_ajar_id" required class="swal2-input w-full">
                                @foreach($tahunAjarList as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Calon Dosen <span class="text-red-500">*</span></label>
                            <select id="calonDosenSelect" name="calon_dosen_id" required class="swal2-input w-full" style="width: 100%;">
                                <option value="">Pilih Calon Dosen</option>
                                @foreach($calonDosenList as $calon)
                                <option value="{{ $calon->id }}">{{ $calon->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji (Pilih 2-3) <span class="text-red-500">*</span></label>
                            <select id="dosenPengujiSelect" name="dosen_penguji_id[]" required multiple class="swal2-input w-full" style="width: 100%;">
                                <option value="">Pilih Dosen Penguji</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}</option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">Minimal 2, maksimal 3 dosen penguji</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span class="text-red-500">*</span></label>
                            <input type="date" name="jadwal_ujian" required class="swal2-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pelaksanaan <span class="text-red-500">*</span></label>
                            <select id="metodeSelect" name="metode_pelaksanaan" required class="swal2-input w-full">
                                @foreach($metodeList as $metode)
                                <option value="{{ $metode }}">{{ $metode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="gedungField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gedung <span id="gedungRequired" class="text-red-500">*</span></label>
                            <input type="text" id="gedungInput" name="gedung" class="swal2-input w-full" placeholder="Contoh: Gedung A">
                        </div>
                        <div id="ruanganField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span id="ruanganRequired" class="text-red-500">*</span></label>
                            <input type="text" id="ruanganInput" name="ruangan" class="swal2-input w-full" placeholder="Contoh: R.201">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu" required class="swal2-input w-full">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                width: '600px',
                didOpen: () => {
                    // Initialize Select2 for Calon Dosen (single select)
                    $('#calonDosenSelect').select2({
                        placeholder: 'Cari dan pilih calon dosen...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });

                    // Initialize Select2 for Dosen Penguji (multiple select with min 2, max 3)
                    $('#dosenPengujiSelect').select2({
                        placeholder: 'Cari dan pilih 2-3 dosen penguji...',
                        allowClear: true,
                        dropdownParent: $('.swal2-container'),
                        multiple: true,
                        maximumSelectionLength: 3,
                        minimumResultsForSearch: 0
                    });

                    // Validate minimum 2 dosen penguji
                    $('#dosenPengujiSelect').on('change', function() {
                        const selected = $(this).val();
                        if (selected && selected.length < 2) {
                            // Will be validated on submit
                        }
                    });

                    // Handle metode pelaksanaan change
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
                            gedungInput.style.backgroundColor = '#e5e7eb';
                            ruanganInput.style.backgroundColor = '#e5e7eb';
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
                    toggleGedungRuangan(); // Initialize on load
                },
                preConfirm: () => {
                    const form = document.getElementById('createForm');
                    const dosenPenguji = $('#dosenPengujiSelect').val();
                    
                    // Validate minimum 2 dosen penguji
                    if (!dosenPenguji || dosenPenguji.length < 2) {
                        Swal.showValidationMessage('Pilih minimal 2 dosen penguji');
                        return false;
                    }
                    
                    // Validate maximum 3 dosen penguji
                    if (dosenPenguji.length > 3) {
                        Swal.showValidationMessage('Maksimal 3 dosen penguji');
                        return false;
                    }
                    
                    const formData = new FormData(form);
                    
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
                        title: '<strong class="text-[#C41E3A]">Edit Jadwal Pengujian</strong>',
                        html: `
                            <form id="editForm" class="text-left space-y-4">
                                <input type="hidden" name="_method" value="PUT">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajar <span class="text-red-500">*</span></label>
                                    <select name="tahun_ajar_id" required class="swal2-input w-full">
                                        @foreach($tahunAjarList as $ta)
                                        <option value="{{ $ta->id }}" ${data.tahun_ajar_id == {{ $ta->id }} ? 'selected' : ''}>{{ $ta->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Calon Dosen <span class="text-red-500">*</span></label>
                                    <select id="editCalonDosenSelect" name="calon_dosen_id" required class="swal2-input w-full" style="width: 100%;">
                                        @foreach($calonDosenList as $calon)
                                        <option value="{{ $calon->id }}" ${data.calon_dosen_id == {{ $calon->id }} ? 'selected' : ''}>{{ $calon->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji (Pilih 2-3) <span class="text-red-500">*</span></label>
                                    <select id="editDosenPengujiSelect" name="dosen_penguji_id[]" required multiple class="swal2-input w-full" style="width: 100%;">
                                        @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-gray-500">Minimal 2, maksimal 3 dosen penguji</small>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span class="text-red-500">*</span></label>
                                    <input type="date" name="jadwal_ujian" value="${data.jadwal_ujian_raw}" required class="swal2-input w-full">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pelaksanaan <span class="text-red-500">*</span></label>
                                    <select id="editMetodeSelect" name="metode_pelaksanaan" required class="swal2-input w-full">
                                        @foreach($metodeList as $metode)
                                        <option value="{{ $metode }}" ${data.metode_pelaksanaan == '{{ $metode }}' ? 'selected' : ''}>{{ $metode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="editGedungField">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gedung <span id="editGedungRequired" class="text-red-500">*</span></label>
                                    <input type="text" id="editGedungInput" name="gedung" value="${data.gedung || ''}" class="swal2-input w-full" placeholder="Contoh: Gedung A">
                                </div>
                                <div id="editRuanganField">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span id="editRuanganRequired" class="text-red-500">*</span></label>
                                    <input type="text" id="editRuanganInput" name="ruangan" value="${data.ruangan || ''}" class="swal2-input w-full" placeholder="Contoh: R.201">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu <span class="text-red-500">*</span></label>
                                    <input type="time" name="waktu" value="${data.waktu_raw}" required class="swal2-input w-full">
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6B7280',
                        width: '600px',
                        didOpen: () => {
                            // Initialize Select2 for Calon Dosen (single select)
                            $('#editCalonDosenSelect').select2({
                                placeholder: 'Cari dan pilih calon dosen...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            // Initialize Select2 for Dosen Penguji (multiple select with min 2, max 3)
                            $('#editDosenPengujiSelect').select2({
                                placeholder: 'Cari dan pilih 2-3 dosen penguji...',
                                allowClear: true,
                                dropdownParent: $('.swal2-container'),
                                multiple: true,
                                maximumSelectionLength: 3,
                                minimumResultsForSearch: 0
                            });

                            // Pre-select multiple dosen penguji
                            if (data.dosen_penguji_ids && data.dosen_penguji_ids.length > 0) {
                                $('#editDosenPengujiSelect').val(data.dosen_penguji_ids).trigger('change');
                            }

                            // Handle metode pelaksanaan change
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
                                    gedungInput.style.backgroundColor = '#e5e7eb';
                                    ruanganInput.style.backgroundColor = '#e5e7eb';
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
                            toggleGedungRuangan(); // Initialize on load
                        },
                        preConfirm: () => {
                            const form = document.getElementById('editForm');
                            const dosenPenguji = $('#editDosenPengujiSelect').val();
                            
                            // Validate minimum 2 dosen penguji
                            if (!dosenPenguji || dosenPenguji.length < 2) {
                                Swal.showValidationMessage('Pilih minimal 2 dosen penguji');
                                return false;
                            }
                            
                            // Validate maximum 3 dosen penguji
                            if (dosenPenguji.length > 3) {
                                Swal.showValidationMessage('Maksimal 3 dosen penguji');
                                return false;
                            }
                            
                            const formData = new FormData(form);
                            
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
                        title: 'Error!',
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
                            title: 'Error!',
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
                // TODO: Navigate to berita acara page or open berita acara modal
                Swal.fire({
                    icon: 'info',
                    title: 'Berita Acara',
                    text: `Halaman berita acara untuk jadwal ID: ${id} akan segera tersedia.`,
                    confirmButtonColor: '#C41E3A'
                });
                // Future implementation:
                // window.location.href = `/rekrutasi-dosen/berita-acara/${id}`;
            });
        });
    </script>
</body>
</html>
