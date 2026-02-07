<!DOCTYPE html>
<html lang="en">

<style>
    /* Custom styling untuk SweetAlert2 form */
    .swal2-input,
    .swal2-textarea {
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Rekrutasi Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Rekrutasi Dosen</h1>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('rekrutasi-dosen') }}">
                {{-- Filter Row --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Jenjang Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Jenjang</label>
                        <select name="jenjang"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Jenjang</option>
                            @if(isset($filterData['jenjang']))
                            @foreach($filterData['jenjang'] as $jenjang)
                            <option value="{{ $jenjang }}" {{ request('jenjang') == $jenjang ? 'selected' : '' }}>
                                {{ $jenjang }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Prodi Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Prodi Tujuan</label>
                        <select name="prodi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Prodi</option>
                            @if(isset($filterData['prodi']))
                            @foreach($filterData['prodi'] as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Tahun Ajar Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Tahun Ajar</label>
                        <select name="tahun_ajar"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Tahun Ajar</option>
                            @if(isset($filterData['tahun_ajar']))
                            @foreach($filterData['tahun_ajar'] as $ta)
                            <option value="{{ $ta->id }}" {{ request('tahun_ajar') == $ta->id ? 'selected' : '' }}>
                                {{ $ta->label }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Status Penerimaan Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Status</option>
                            @if(isset($filterData['status']))
                            @foreach($filterData['status'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Filter & Reset Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-sliders-h text-black"></i>
                            <span>Filter</span>
                        </button>

                        @if(request()->has('jenjang') || request()->has('prodi') || request()->has('tahun_ajar') || request()->has('status'))
                        <a href="{{ route('rekrutasi-dosen') }}"
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
        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-visible">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Data Rekrutasi Dosen</h2>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                    <button onclick="openCreateModal()"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </button>
                    @endif

                    {{-- Right Side Controls --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Export Button --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]">
                                <a href="{{ route('rekrutasi-dosen.export-excel', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="{{ route('rekrutasi-dosen.export-csv', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="{{ route('rekrutasi-dosen.export-pdf', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                {{-- Table Header --}}
                <thead>
                    <tr class="bg-[#C41E3A] text-white">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            <a href="{{ route('rekrutasi-dosen', array_merge(request()->all(), ['sort' => 'no_registrasi', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-yellow-300">
                                No. Registrasi
                                @if(request('sort') == 'no_registrasi')
                                <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-xs opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            <a href="{{ route('rekrutasi-dosen', array_merge(request()->all(), ['sort' => 'nama', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-yellow-300">
                                Nama
                                @if(request('sort') == 'nama')
                                <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-xs opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenjang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Prodi Tujuan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tahun Ajar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jalur Lamaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">H-Index</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($rekrutasi) && $rekrutasi->count() > 0)
                    @foreach($rekrutasi as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->no_registrasi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ strtoupper($item->prodi->jenjang ?? '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->prodi->nama_prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tahunAjar->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $item->jalur_lamaran ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $item->h_index ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                            $statusClass = match($item->status_penerimaan) {
                                'Diterima' => 'bg-green-100 text-green-800',
                                'Ditolak' => 'bg-red-100 text-red-800',
                                'Seleksi' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $item->status_penerimaan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center space-x-3">
                                {{-- View Button --}}
                                <button data-id="{{ $item->id }}" class="btn-detail text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                                {{-- Edit Button --}}
                                <button data-id="{{ $item->id }}" class="btn-edit text-green-600 hover:text-green-800 transition-colors duration-200"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Delete Button - IMPROVED WITH SWEETALERT --}}
                                <form action="{{ route('rekrutasi-dosen.destroy', $item->id) }}"
                                    method="POST"
                                    class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200 delete-btn"
                                        data-nama="{{ $item->nama }}"
                                        data-no-reg="{{ $item->no_registrasi }}"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    {{-- Empty State --}}
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center space-y-4">
                                <i class="fas fa-users text-4xl text-gray-300"></i>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data rekrutasi</h3>
                                    <p class="text-sm text-gray-500">Belum ada data rekrutasi yang tersedia.</p>
                                </div>
                                <button onclick="openCreateModal()"
                                    class="inline-flex items-center px-4 py-2 bg-[#C41E3A] hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Data Rekrutasi
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($rekrutasi) && $rekrutasi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-end">
                <div class="flex items-center space-x-2">
                    {{ $rekrutasi->links() }}
                </div>
            </div>
        </div>
        @endif
        </div>
    </main>

    {{-- Success/Error Messages with SweetAlert2 --}}
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
                title: 'Error!',
                text: '{{ session("error") }}',
                showConfirmButton: true,
                confirmButtonColor: '#C41E3A'
            });
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Export dropdown toggle
            const exportBtn = document.getElementById('exportBtn');
            const exportDropdown = document.getElementById('exportDropdown');

            if (exportBtn && exportDropdown) {
                exportBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    exportDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    exportDropdown.classList.add('hidden');
                });
            }

            // ============================================
            // SWEETALERT DELETE CONFIRMATION
            // ============================================
            const deleteBtns = document.querySelectorAll('.delete-btn');

            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');
                    const noReg = this.getAttribute('data-no-reg');

                    Swal.fire({
                        title: 'Hapus Data Rekrutasi?',
                        html: `
                        <div class="text-left space-y-2">
                            <p class="text-gray-600">Anda akan menghapus data rekrutasi:</p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="font-semibold text-red-800">${nama}</p>
                                <p class="text-sm text-red-600">No. Registrasi: ${noReg}</p>
                            </div>
                            <p class="text-sm text-red-600 mt-3">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Data yang dihapus tidak dapat dikembalikan!
                            </p>
                        </div>
                    `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'px-6 py-2.5 rounded-lg font-semibold',
                            cancelButton: 'px-6 py-2.5 rounded-lg font-semibold'
                        },
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus...',
                                html: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Submit form
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    
    <script>
        // Function to format date (remove ISO timestamp)
        function formatDate(dateString) {
            if (!dateString) return '-';
            // Remove time part if exists (e.g., "1985-03-15T00:00:00.000000Z" -> "1985-03-15")
            return dateString.split('T')[0];
        }

        // Function to format date for display (YYYY-MM-DD to DD-MM-YYYY)
        function formatDateDisplay(dateString) {
            if (!dateString) return '-';
            const date = dateString.split('T')[0];
            const [year, month, day] = date.split('-');
            return `${day}-${month}-${year}`;
        }

        // Modal Functions
        function openCreateModal() {
    Swal.fire({
        title: '<i class="fas fa-user-plus mr-2"></i>Tambah Data Rekrutasi Dosen',
        html: `
            <form id="swalCreateForm" class="text-left">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
<input type="text" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prodi Tujuan <span class="text-red-500">*</span></label>
<select name="prodi_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="">Pilih Prodi</option>
                            @if(isset($filterData['prodi']))
                                @foreach($filterData['prodi'] as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajar <span class="text-red-500">*</span></label>
<select name="tahun_ajar_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="">Pilih Tahun Ajar</option>
                            @if(isset($filterData['tahun_ajar']))
                                @foreach($filterData['tahun_ajar'] as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->label }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Penerimaan <span class="text-red-500">*</span></label>
<select name="status_penerimaan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="">Pilih Status</option>
                            <option value="Seleksi">Seleksi</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
<select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
<input type="text" name="tempat_lahir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
<input type="text" name="nomor_telepon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Fungsional Akademik</label>
<select name="jabatan_fungsional_akademik" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="NJFA">NJFA</option>
                            <option value="Asisten Ahli">Asisten Ahli</option>
                            <option value="Lektor">Lektor</option>
                            <option value="Lektor Kepala">Lektor Kepala</option>
                            <option value="Guru Besar">Guru Besar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Keahlian</label>
<input type="text" name="bidang_keahlian" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Lamaran</label>
<select name="jalur_lamaran" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            <option value="">Pilih Jalur Lamaran</option>
                            <option value="S3 Prof Full time">S3 Prof Full time</option>
                            <option value="S2 Praktisi Part time">S2 Praktisi Part time</option>
                            <option value="S3 Praktisi Part time">S3 Praktisi Part time</option>
                            <option value="S2 Prof Full time">S2 Prof Full time</option>
                            <option value="S3 OnGoing">S3 OnGoing</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">H-Index</label>
<input type="number" name="h_index" step="0.01" min="0" placeholder="Contoh: 12 atau 8.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
<textarea name="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm resize-none" style="margin: 0;"></textarea>
                    </div>

                    <!-- Divider -->
                    <div class="md:col-span-2">
                        <hr class="my-4 border-gray-300">
                        <h3 class="text-lg font-semibold text-[#C41E3A] mb-3">
                            <i class="fas fa-graduation-cap mr-2"></i>Riwayat Pendidikan
                        </h3>
                    </div>

                    <!-- S1 -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S1</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas</label>
<input type="text" name="riwayat[s1][nama_universitas]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
<input type="text" name="riwayat[s1][prodi_pendidikan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus</label>
<input type="date" name="riwayat[s1][tanggal_lulus]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s1][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s1][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>

                    <!-- S2 -->
                    <div class="md:col-span-2 mt-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S2 (Opsional)</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas</label>
<input type="text" name="riwayat[s2][nama_universitas]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
<input type="text" name="riwayat[s2][prodi_pendidikan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus</label>
<input type="date" name="riwayat[s2][tanggal_lulus]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s2][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s2][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>

                    <!-- S3 -->
                    <div class="md:col-span-2 mt-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S3 (Opsional)</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas</label>
<input type="text" name="riwayat[s3][nama_universitas]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
<input type="text" name="riwayat[s3][prodi_pendidikan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus</label>
<input type="date" name="riwayat[s3][tanggal_lulus]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s3][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip (PDF/JPG/PNG)</label>
<input type="file" name="riwayat[s3][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                    </div>
                </div>
            </form>
        `,
        width: '850px',
        showCancelButton: true,
        confirmButtonColor: '#C41E3A',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        customClass: {
            popup: 'rounded-lg',
            confirmButton: 'px-6 py-2.5 rounded-lg font-semibold',
            cancelButton: 'px-6 py-2.5 rounded-lg font-semibold',
            title: 'text-[#C41E3A]'
        },
        preConfirm: () => {
            const form = document.getElementById('swalCreateForm');
            const formData = new FormData(form);
            
            // Validasi required fields
            if (!formData.get('nama') || !formData.get('prodi_id') || !formData.get('tahun_ajar_id') || 
                !formData.get('status_penerimaan') || !formData.get('jenis_kelamin')) {
                Swal.showValidationMessage('Mohon lengkapi semua field yang wajib diisi (*)');
                return false;
            }
            
            // Debug: log FormData
            console.log('=== FormData Contents ===');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }
            
            return formData;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = result.value;
            
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route("rekrutasi-dosen.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Log response status untuk debugging
                console.log('Response status:', response.status);
                if (!response.ok && response.status !== 422) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => location.reload());
                } else {
                    // Tampilkan pesan error yang lebih detail
                    let errorMessage = 'Gagal menyimpan data';
                    if (data.message) {
                        errorMessage = data.message;
                    }
                    if (data.errors) {
                        errorMessage += '<br><br>' + Object.values(data.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire('Error', 'Terjadi kesalahan: ' + error.message, 'error');
            });
        }
    });
}

        function openEditModal(id) {
    Swal.fire({
        title: 'Memuat...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`/rekrutasi-dosen/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        const item = data.data;
        
        Swal.fire({
            title: '<i class="fas fa-edit mr-2"></i>Edit Data Rekrutasi Dosen',
            html: `
                <form id="swalEditForm" class="text-left">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
<input type="text" name="nama" value="${item.nama}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prodi Tujuan <span class="text-red-500">*</span></label>
<select name="prodi_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="">Pilih Prodi</option>
                                @if(isset($filterData['prodi']))
                                    @foreach($filterData['prodi'] as $prodi)
                                        <option value="{{ $prodi->id }}" ${item.prodi_id == '{{ $prodi->id }}' ? 'selected' : ''}>{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajar <span class="text-red-500">*</span></label>
<select name="tahun_ajar_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="">Pilih Tahun Ajar</option>
                                @if(isset($filterData['tahun_ajar']))
                                    @foreach($filterData['tahun_ajar'] as $ta)
                                        <option value="{{ $ta->id }}" ${item.tahun_ajar_id == '{{ $ta->id }}' ? 'selected' : ''}>{{ $ta->label }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Penerimaan <span class="text-red-500">*</span></label>
<select name="status_penerimaan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="">Pilih Status</option>
                                <option value="Seleksi" ${item.status_penerimaan == 'Seleksi' ? 'selected' : ''}>Seleksi</option>
                                <option value="Diterima" ${item.status_penerimaan == 'Diterima' ? 'selected' : ''}>Diterima</option>
                                <option value="Ditolak" ${item.status_penerimaan == 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
<select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" ${item.jenis_kelamin == 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                                <option value="Perempuan" ${item.jenis_kelamin == 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
<input type="text" name="tempat_lahir" value="${item.tempat_lahir || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" value="${item.tanggal_lahir ? item.tanggal_lahir.split('T')[0] : ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
<input type="text" name="nomor_telepon" value="${item.nomor_telepon || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Fungsional Akademik</label>
<select name="jabatan_fungsional_akademik" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="NJFA" ${item.jabatan_fungsional_akademik == 'NJFA' ? 'selected' : ''}>NJFA</option>
                                <option value="Asisten Ahli" ${item.jabatan_fungsional_akademik == 'Asisten Ahli' ? 'selected' : ''}>Asisten Ahli</option>
                                <option value="Lektor" ${item.jabatan_fungsional_akademik == 'Lektor' ? 'selected' : ''}>Lektor</option>
                                <option value="Lektor Kepala" ${item.jabatan_fungsional_akademik == 'Lektor Kepala' ? 'selected' : ''}>Lektor Kepala</option>
                                <option value="Guru Besar" ${item.jabatan_fungsional_akademik == 'Guru Besar' ? 'selected' : ''}>Guru Besar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Keahlian</label>
<input type="text" name="bidang_keahlian" value="${item.bidang_keahlian || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Lamaran</label>
<select name="jalur_lamaran" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                <option value="">Pilih Jalur Lamaran</option>
                                <option value="S3 Prof Full time" ${item.jalur_lamaran == 'S3 Prof Full time' ? 'selected' : ''}>S3 Prof Full time</option>
                                <option value="S2 Praktisi Part time" ${item.jalur_lamaran == 'S2 Praktisi Part time' ? 'selected' : ''}>S2 Praktisi Part time</option>
                                <option value="S3 Praktisi Part time" ${item.jalur_lamaran == 'S3 Praktisi Part time' ? 'selected' : ''}>S3 Praktisi Part time</option>
                                <option value="S2 Prof Full time" ${item.jalur_lamaran == 'S2 Prof Full time' ? 'selected' : ''}>S2 Prof Full time</option>
                                <option value="S3 OnGoing" ${item.jalur_lamaran == 'S3 OnGoing' ? 'selected' : ''}>S3 OnGoing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">H-Index</label>
<input type="number" name="h_index" value="${item.h_index || ''}" step="0.01" min="0" placeholder="Contoh: 12 atau 8.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
<textarea name="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm resize-none" style="margin: 0;">${item.alamat || ''}</textarea>
                        </div>

                        <!-- Divider untuk Riwayat Pendidikan -->
                        <div class="md:col-span-2">
                            <hr class="my-4 border-gray-300">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Riwayat Pendidikan
                            </h3>
                        </div>

                        <!-- S1 (Wajib) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S1 <span class="text-red-500">*</span></label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas <span class="text-red-500">*</span></label>
<input type="text" name="riwayat[s1][nama_universitas]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S1')?.nama_universitas || ''}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi <span class="text-red-500">*</span></label>
<input type="text" name="riwayat[s1][prodi_pendidikan]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S1')?.prodi_pendidikan || ''}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus <span class="text-red-500">*</span></label>
<input type="date" name="riwayat[s1][tanggal_lulus]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S1')?.tanggal_lulus?.split('T')[0] || ''}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s1][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S1')?.ijazah ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S1').ijazah.split('/').pop() + '</small>' : ''}
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s1][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S1')?.transkrip_nilai ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S1').transkrip_nilai.split('/').pop() + '</small>' : ''}
                        </div>

                        <!-- S2 (Opsional) -->
                        <div class="md:col-span-2 mt-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S2 (Opsional)</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas</label>
<input type="text" name="riwayat[s2][nama_universitas]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S2')?.nama_universitas || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
<input type="text" name="riwayat[s2][prodi_pendidikan]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S2')?.prodi_pendidikan || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus</label>
<input type="date" name="riwayat[s2][tanggal_lulus]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S2')?.tanggal_lulus?.split('T')[0] || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s2][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S2')?.ijazah ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S2').ijazah.split('/').pop() + '</small>' : ''}
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s2][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S2')?.transkrip_nilai ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S2').transkrip_nilai.split('/').pop() + '</small>' : ''}
                        </div>

                        <!-- S3 (Opsional) -->
                        <div class="md:col-span-2 mt-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan S3 (Opsional)</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Universitas</label>
<input type="text" name="riwayat[s3][nama_universitas]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S3')?.nama_universitas || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
<input type="text" name="riwayat[s3][prodi_pendidikan]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S3')?.prodi_pendidikan || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lulus</label>
<input type="date" name="riwayat[s3][tanggal_lulus]" value="${item.riwayat_pendidikan?.find(r => r.jenjang === 'S3')?.tanggal_lulus?.split('T')[0] || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ijazah Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s3][ijazah]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S3')?.ijazah ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S3').ijazah.split('/').pop() + '</small>' : ''}
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="riwayat[s3][transkrip_nilai]" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                            ${item.riwayat_pendidikan?.find(r => r.jenjang === 'S3')?.transkrip_nilai ? '<small class="text-gray-500">File saat ini: ' + item.riwayat_pendidikan.find(r => r.jenjang === 'S3').transkrip_nilai.split('/').pop() + '</small>' : ''}
                        </div>
                    </div>
                </form>
            `,
            width: '850px',
            showCancelButton: true,
            confirmButtonColor: '#C41E3A',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Update',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'px-6 py-2.5 rounded-lg font-semibold',
                cancelButton: 'px-6 py-2.5 rounded-lg font-semibold',
                title: 'text-[#C41E3A]'
            },
            preConfirm: () => {
                const form = document.getElementById('swalEditForm');
                const formData = new FormData(form);
                
                // Validasi required fields
                if (!formData.get('nama') || !formData.get('prodi_id') || !formData.get('tahun_ajar_id') || 
                    !formData.get('status_penerimaan') || !formData.get('jenis_kelamin')) {
                    Swal.showValidationMessage('Mohon lengkapi semua field yang wajib diisi (*)');
                    return false;
                }
                
                // Validasi S1 wajib
                if (!formData.get('riwayat[s1][nama_universitas]') || !formData.get('riwayat[s1][prodi_pendidikan]') || 
                    !formData.get('riwayat[s1][tanggal_lulus]')) {
                    Swal.showValidationMessage('Data Pendidikan S1 wajib diisi!');
                    return false;
                }
                
                return { formData, id: item.id };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { formData, id } = result.value;
                
                // Add _method for Laravel method spoofing
                formData.append('_method', 'PUT');
                
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/rekrutasi-dosen/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Update response status:', response.status);
                    if (!response.ok && response.status !== 422) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Update response data:', data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => location.reload());
                    } else {
                        let errorMessage = 'Gagal mengupdate data';
                        if (data.message) {
                            errorMessage = data.message;
                        }
                        if (data.errors) {
                            errorMessage += '<br><br>' + Object.values(data.errors).flat().join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage
                        });
                    }
                })
                .catch(error => {
                    console.error('Update error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan: ' + error.message, 'error');
                });
            }
        });
    })
    .catch(() => Swal.fire('Error', 'Gagal memuat data', 'error'));
}

        function showDetail(id) {
            Swal.fire({
                title: 'Memuat...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/rekrutasi-dosen/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                const item = data.data;
                console.log('Detail data:', item); // Debug log
                console.log('Tahun Ajar:', item.tahun_ajar); // Debug tahun_ajar
                console.log('TahunAjar (camelCase):', item.tahunAjar); // Debug tahunAjar
                let riwayatHTML = '<p class="text-gray-500 italic">Belum ada data riwayat pendidikan</p>';
                
                if (item.riwayat_pendidikan && item.riwayat_pendidikan.length > 0) {
                    riwayatHTML = '<div class="space-y-2">';
                    item.riwayat_pendidikan.forEach(riwayat => {
                        let filesHTML = '';
                        if (riwayat.ijazah || riwayat.transkrip_nilai) {
                            filesHTML = '<div class="mt-2 flex gap-2">';
                            if (riwayat.ijazah) {
                                const ijazahFilename = riwayat.ijazah.split('/').pop();
                                filesHTML += `<a href="/rekrutasi-dosen/riwayat-file/${ijazahFilename}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">
                                    <i class="fas fa-file-pdf mr-1"></i> Ijazah
                                </a>`;
                            }
                            if (riwayat.transkrip_nilai) {
                                const transkripFilename = riwayat.transkrip_nilai.split('/').pop();
                                filesHTML += `<a href="/rekrutasi-dosen/riwayat-file/${transkripFilename}" target="_blank" class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">
                                    <i class="fas fa-file-alt mr-1"></i> Transkrip
                                </a>`;
                            }
                            filesHTML += '</div>';
                        }
                        
                        riwayatHTML += `
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-purple-800">${riwayat.jenjang.toUpperCase()}</span>
                                    <span class="text-sm text-gray-600">${formatDateDisplay(riwayat.tanggal_lulus)}</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">${riwayat.prodi_pendidikan}</p>
                                <p class="text-sm text-gray-600">${riwayat.nama_universitas}</p>
                                ${filesHTML}
                            </div>
                        `;
                    });
                    riwayatHTML += '</div>';
                }

                const statusClass = item.status_penerimaan === 'Diterima' ? 'bg-green-100 text-green-800' :
                                   item.status_penerimaan === 'Ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800';

                Swal.fire({
    title: '<i class="fas fa-user-circle mr-2"></i>Detail Calon Dosen',
    html: `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">No. Registrasi</label>
                    <p class="text-gray-900 font-semibold">${item.no_registrasi}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                    <p class="text-gray-900 font-semibold">${item.nama}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jenis Kelamin</label>
                    <p class="text-gray-900">${item.jenis_kelamin}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</label>
                    <p class="text-gray-900">${item.tempat_lahir || '-'}, ${formatDateDisplay(item.tanggal_lahir)}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nomor Telepon</label>
                    <p class="text-gray-900">${item.nomor_telepon || '-'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Alamat</label>
                    <p class="text-gray-900">${item.alamat || '-'}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Prodi Tujuan</label>
                    <p class="text-gray-900">${item.prodi ? item.prodi.nama_prodi : '-'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jenjang</label>
                    <p class="text-gray-900"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">${item.prodi ? item.prodi.jenjang : '-'}</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tahun Ajar</label>
                    <p class="text-gray-900">${(item.tahun_ajar && item.tahun_ajar.label) || (item.tahunAjar && item.tahunAjar.label) || '-'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status Penerimaan</label>
                    <p><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">${item.status_penerimaan}</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jabatan Fungsional</label>
                    <p class="text-gray-900">${item.jabatan_fungsional_akademik || '-'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Bidang Keahlian</label>
                    <p class="text-gray-900">${item.bidang_keahlian || '-'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jalur Lamaran</label>
                    <p class="text-gray-900"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${item.jalur_lamaran || '-'}</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">H-Index</label>
                    <p class="text-gray-900"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">${item.h_index || '-'}</span></p>
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-2">Riwayat Pendidikan</label>
                ${riwayatHTML}
            </div>
        </div>
    `,
    width: '900px',
    confirmButtonText: '<i class="fas fa-times mr-2"></i>Tutup',
    confirmButtonColor: '#6B7280',
    customClass: {
        popup: 'rounded-lg',
        confirmButton: 'px-6 py-2.5 rounded-lg font-semibold',
        title: 'text-[#C41E3A]'
    }
});
            })
            .catch(error => {
                Swal.fire('Error', 'Gagal memuat detail data', 'error');
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside (on overlay)
        document.addEventListener('DOMContentLoaded', function() {
            const modals = ['createModal', 'editModal', 'detailModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        // Close if clicking on the overlay (not the content)
                        if (e.target === modal) {
                            closeModal(modalId);
                        }
                    });
                }
            });

            // Event listeners for detail buttons
            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', function() {
                    showDetail(this.getAttribute('data-id'));
                });
            });

            // Event listeners for edit buttons
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    openEditModal(this.getAttribute('data-id'));
                });
            });
        });
    </script>
</body>

</html>