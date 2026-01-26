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
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Jadwal Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Gedung Filter --}}
                <div>
                    <label class="block text-base font-semibold text-[#C41E3A] mb-2">Gedung</label>
                    <select id="filterGedung" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Gedung</option>
                        @foreach($gedungList as $gedung)
                            <option value="{{ $gedung }}">{{ $gedung }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ruangan Filter --}}
                <div>
                    <label class="block text-base font-semibold text-[#C41E3A] mb-2">Ruangan</label>
                    <select id="filterRuangan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruanganList as $ruangan)
                            <option value="{{ $ruangan }}">{{ $ruangan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Input --}}
                <div>
                    <label class="block text-base font-semibold text-[#C41E3A] mb-2">Cari</label>
                    <input type="text" id="searchInput" placeholder="Cari nama calon dosen atau penguji..." 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Filter & Reset Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="button" id="applyFilterBtn"
                        class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-sliders-h text-black"></i>
                        <span>Filter</span>
                    </button>

                    <button type="button" id="resetBtn" style="display: none;"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center space-x-2">
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
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
                    <button type="button" id="btnTambahData"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </button>

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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Gedung</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Ruangan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-40">Waktu</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($jadwalList as $index => $jadwal)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $jadwal->calonDosen->nama }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                {{ $jadwal->dosenPenguji->front_title }} {{ $jadwal->dosenPenguji->nama_lengkap }}, {{ $jadwal->dosenPenguji->back_title }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->gedung }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->ruangan }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center justify-center space-x-3">
                                    <button type="button" class="btn-detail text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn-edit text-green-600 hover:text-green-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete text-red-600 hover:text-red-800 transition-colors duration-200" 
                                            data-id="{{ $jadwal->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
        // Apply and Reset Filter Functions
        document.getElementById('applyFilterBtn').addEventListener('click', function() {
            filterTable();
            // Show reset button when any filter is active
            const gedung = document.getElementById('filterGedung').value;
            const ruangan = document.getElementById('filterRuangan').value;
            const search = document.getElementById('searchInput').value;
            
            if (gedung || ruangan || search) {
                document.getElementById('resetBtn').style.display = 'flex';
            }
        });

        document.getElementById('resetBtn').addEventListener('click', function() {
            document.getElementById('filterGedung').value = '';
            document.getElementById('filterRuangan').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('resetBtn').style.display = 'none';
            filterTable();
        });

        // Filter and Search Functions
        function filterTable() {
            const gedungFilter = document.getElementById('filterGedung').value.toLowerCase();
            const ruanganFilter = document.getElementById('filterRuangan').value.toLowerCase();
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#jadwalTableBody tr');
            
            rows.forEach(row => {
                if (row.cells.length > 1) {
                    const gedung = row.cells[3].textContent.toLowerCase();
                    const ruangan = row.cells[4].textContent.toLowerCase();
                    const calonDosen = row.cells[1].textContent.toLowerCase();
                    const dosenPenguji = row.cells[2].textContent.toLowerCase();
                    
                    const matchGedung = !gedungFilter || gedung.includes(gedungFilter);
                    const matchRuangan = !ruanganFilter || ruangan.includes(ruanganFilter);
                    const matchSearch = !searchInput || calonDosen.includes(searchInput) || dosenPenguji.includes(searchInput);
                    
                    if (matchGedung && matchRuangan && matchSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Event listeners for real-time filtering
        document.getElementById('searchInput').addEventListener('input', filterTable);

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
                                    <p class="text-base font-semibold">${data.dosen_penguji_nama}</p>
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
                                    <p class="text-sm text-gray-600">Gedung</p>
                                    <p class="text-base font-semibold">${data.gedung}</p>
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-sm text-gray-600">Ruangan</p>
                                    <p class="text-base font-semibold">${data.ruangan}</p>
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
                            <select name="calon_dosen_id" required class="swal2-input w-full">
                                @foreach($calonDosenList as $calon)
                                <option value="{{ $calon->id }}">{{ $calon->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji <span class="text-red-500">*</span></label>
                            <select name="dosen_penguji_id" required class="swal2-input w-full">
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span class="text-red-500">*</span></label>
                            <input type="date" name="jadwal_ujian" required class="swal2-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gedung <span class="text-red-500">*</span></label>
                            <select name="gedung" required class="swal2-input w-full">
                                <option value="Gedung A">Gedung A</option>
                                <option value="Gedung B">Gedung B</option>
                                <option value="Gedung C">Gedung C</option>
                                <option value="Gedung Teknik">Gedung Teknik</option>
                                <option value="Gedung Rektorat">Gedung Rektorat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                            <select name="ruangan" required class="swal2-input w-full">
                                <option value="Aula">Aula</option>
                                <option value="R.201">R.201</option>
                                <option value="R.301">R.301</option>
                                <option value="Lab Komputer 1">Lab Komputer 1</option>
                                <option value="Lab Komputer 2">Lab Komputer 2</option>
                                <option value="Ruang Sidang">Ruang Sidang</option>
                            </select>
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
                preConfirm: () => {
                    const form = document.getElementById('createForm');
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
                                    <select name="calon_dosen_id" required class="swal2-input w-full">
                                        @foreach($calonDosenList as $calon)
                                        <option value="{{ $calon->id }}" ${data.calon_dosen_id == {{ $calon->id }} ? 'selected' : ''}>{{ $calon->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji <span class="text-red-500">*</span></label>
                                    <select name="dosen_penguji_id" required class="swal2-input w-full">
                                        @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}" ${data.dosen_penguji_id == {{ $dosen->id }} ? 'selected' : ''}>{{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span class="text-red-500">*</span></label>
                                    <input type="date" name="jadwal_ujian" value="${data.jadwal_ujian_raw}" required class="swal2-input w-full">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gedung <span class="text-red-500">*</span></label>
                                    <select name="gedung" required class="swal2-input w-full">
                                        <option value="Gedung A" ${data.gedung == 'Gedung A' ? 'selected' : ''}>Gedung A</option>
                                        <option value="Gedung B" ${data.gedung == 'Gedung B' ? 'selected' : ''}>Gedung B</option>
                                        <option value="Gedung C" ${data.gedung == 'Gedung C' ? 'selected' : ''}>Gedung C</option>
                                        <option value="Gedung Teknik" ${data.gedung == 'Gedung Teknik' ? 'selected' : ''}>Gedung Teknik</option>
                                        <option value="Gedung Rektorat" ${data.gedung == 'Gedung Rektorat' ? 'selected' : ''}>Gedung Rektorat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                                    <select name="ruangan" required class="swal2-input w-full">
                                        <option value="Aula" ${data.ruangan == 'Aula' ? 'selected' : ''}>Aula</option>
                                        <option value="R.201" ${data.ruangan == 'R.201' ? 'selected' : ''}>R.201</option>
                                        <option value="R.301" ${data.ruangan == 'R.301' ? 'selected' : ''}>R.301</option>
                                        <option value="Lab Komputer 1" ${data.ruangan == 'Lab Komputer 1' ? 'selected' : ''}>Lab Komputer 1</option>
                                        <option value="Lab Komputer 2" ${data.ruangan == 'Lab Komputer 2' ? 'selected' : ''}>Lab Komputer 2</option>
                                        <option value="Ruang Sidang" ${data.ruangan == 'Ruang Sidang' ? 'selected' : ''}>Ruang Sidang</option>
                                    </select>
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
                        preConfirm: () => {
                            const form = document.getElementById('editForm');
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
    </script>
</body>
</html>
