<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Search Bar --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Kelola Data Dosen</h1>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('manajemen-dosen.kelola-data') }}" class="space-y-6">
                {{-- Filter Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Lokasi Kerja (Prodi) Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Lokasi Kerja</label>
                        <select name="prodi_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Lokasi Kerja</option>
                            @if(isset($filterData['prodi']))
                                @foreach($filterData['prodi'] as $prodi)
                                    <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- JFA (Jabatan) Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">JFA</label>
                        <select name="jabatan" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua JFA</option>
                            @if(isset($filterData['jabatan']))
                                @foreach($filterData['jabatan'] as $jab)
                                    <option value="{{ $jab }}" {{ request('jabatan') == $jab ? 'selected' : '' }}>
                                        {{ $jab }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Kelompok Keahlian Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Kelompok Keahlian</label>
                        <select name="kelompok_keahlian_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Kelompok Keahlian</option>
                            @if(isset($filterData['kelompok_keahlian']))
                                @foreach($filterData['kelompok_keahlian'] as $kelompok)
                                    <option value="{{ $kelompok->id }}" {{ request('kelompok_keahlian_id') == $kelompok->id ? 'selected' : '' }}>
                                        {{ $kelompok->nama_kelompok_keahlian }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Filter Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Status Pegawai Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Status Pegawai</label>
                        <select name="status_pegawai" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Status</option>
                            @if(isset($filterData['status_pegawai']))
                                @foreach($filterData['status_pegawai'] as $status)
                                    <option value="{{ $status }}" {{ request('status_pegawai') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Cari</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari NIP, Kode Dosen, atau Nama..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    {{-- Filter & Reset Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit" id="applyFilterBtn"
                                class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-sliders-h text-black"></i>
                            <span>Filter</span>
                        </button>
                        
                        <a href="{{ route('manajemen-dosen.kelola-data') }}" id="resetFilterBtn"
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-visible">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Data Dosen</h2>
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
                        @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                        <div class="relative inline-block text-left">
                            <button type="button" id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200" style="z-index: 9999;">
                                <a href="{{ route('manajemen-dosen.export-excel', request()->query()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="{{ route('manajemen-dosen.export-csv', request()->query()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="{{ route('manajemen-dosen.export-pdf', request()->query()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    {{-- Table Header --}}
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('manajemen-dosen.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'nip', 'sort_direction' => request('sort_field') == 'nip' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>NIP</span>
                                    @if(request('sort_field') == 'nip')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('manajemen-dosen.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'kode_dosen', 'sort_direction' => request('sort_field') == 'kode_dosen' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>Kode Dosen</span>
                                    @if(request('sort_field') == 'kode_dosen')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('manajemen-dosen.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'nama_lengkap', 'sort_direction' => request('sort_field') == 'nama_lengkap' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>Nama</span>
                                    @if(request('sort_field') == 'nama_lengkap')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('manajemen-dosen.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'jabatan', 'sort_direction' => request('sort_field') == 'jabatan' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>JFA</span>
                                    @if(request('sort_field') == 'jabatan')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <span>Kelompok Keahlian</span>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <span>Lokasi Kerja</span>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('manajemen-dosen.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'status_pegawai', 'sort_direction' => request('sort_field') == 'status_pegawai' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>Status</span>
                                    @if(request('sort_field') == 'status_pegawai')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- Table Body --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($dosen) && $dosen->count() > 0)
                            @foreach($dosen as $index => $dosenItem)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                        {{ $dosenItem->nip }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $dosenItem->kode_dosen }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div class="font-medium">
                                            @if($dosenItem->front_title){{ $dosenItem->front_title }} @endif{{ $dosenItem->nama_lengkap }}@if($dosenItem->back_title), {{ $dosenItem->back_title }}@endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $dosenItem->jabatan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $dosenItem->kelompokKeahlian->nama_kelompok_keahlian ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $dosenItem->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @php
                                            $statusClass = match($dosenItem->status_pegawai) {
                                                'Tetap' => 'bg-green-100 text-green-800',
                                                'Perbantuan' => 'bg-blue-100 text-blue-800',
                                                'Profesional Full Time' => 'bg-purple-100 text-purple-800',
                                                'Profesional Part Time' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $dosenItem->status_pegawai ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- View Button --}}
                                            <button type="button" class="btn-detail text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                                                    data-id="{{ $dosenItem->id }}" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if(Auth::check() && !Auth::user()->hasRole('User Biasa'))
                                            {{-- Edit Button --}}
                                            <button type="button" class="btn-edit text-green-600 hover:text-green-800 transition-colors duration-200" 
                                                    data-id="{{ $dosenItem->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            {{-- Delete Button --}}
                                            <button type="button" class="btn-delete text-red-600 hover:text-red-800 transition-colors duration-200" 
                                                    data-id="{{ $dosenItem->id }}" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2"></i>
                                    <p>Tidak ada data dosen</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    {{-- Success/Error Messages --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Export dropdown toggle
            const exportBtn = document.getElementById('exportBtn');
            const exportDropdown = document.getElementById('exportDropdown');

            console.log('Export Button:', exportBtn);
            console.log('Export Dropdown:', exportDropdown);

            if (exportBtn && exportDropdown) {
                exportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Button clicked, toggling dropdown');
                    
                    // Toggle visibility
                    if (exportDropdown.classList.contains('hidden')) {
                        exportDropdown.classList.remove('hidden');
                    } else {
                        exportDropdown.classList.add('hidden');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!exportBtn.contains(e.target) && !exportDropdown.contains(e.target)) {
                        exportDropdown.classList.add('hidden');
                    }
                });

                // Prevent dropdown links from being blocked
                const exportLinks = exportDropdown.querySelectorAll('a');
                exportLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // Link will navigate normally
                    });
                });
            } else {
                console.error('Export button or dropdown not found!');
            }

            // Success/Error Messages with SweetAlert2
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#FBB03B',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#C41E3A',
                    confirmButtonText: 'OK'
                });
            @endif

            // Detail Button
            document.querySelectorAll('.btn-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    window.location.href = `/manajemen-dosen/${id}`;
                });
            });

            // Edit Button
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    window.location.href = `/manajemen-dosen/${id}/edit`;
                });
            });

            // Delete Button with SweetAlert2
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus data dosen ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create and submit form for DELETE request
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/manajemen-dosen/${id}`;
                            
                            const csrfToken = document.querySelector('meta[name="csrf-token"]');
                            if (csrfToken) {
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken.content;
                                form.appendChild(csrfInput);
                            }
                            
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);
                            
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });

        // Modal Functions - Global scope
        function openCreateModal() {
                Swal.fire({
                    title: '<i class="fas fa-user-plus mr-2"></i>Tambah Data Dosen',
                    html: `
                        <form id="swalCreateForm" class="text-left">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIP <span class="text-red-500">*</span></label>
                                    <input type="text" name="nip" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Dosen <span class="text-red-500">*</span></label>
                                    <input type="text" name="kode_dosen" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gelar Depan</label>
                                    <input type="text" name="front_title" placeholder="Dr., Prof. Dr., dll" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_lengkap" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gelar Belakang</label>
                                    <input type="text" name="back_title" placeholder="S.Kom, M.Kom, M.T, dll" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Fungsional Akademik <span class="text-red-500">*</span></label>
                                    <select name="jabatan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                        <option value="">Pilih Jabatan</option>
                                        <option value="NJFA">NJFA</option>
                                        <option value="Asisten Ahli">Asisten Ahli</option>
                                        <option value="Lektor">Lektor</option>
                                        <option value="Lektor Kepala">Lektor Kepala</option>
                                        <option value="Profesor">Profesor</option>
                                        <option value="Guru Besar">Guru Besar</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai <span class="text-red-500">*</span></label>
                                    <select name="status_pegawai" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                        <option value="">Pilih Status</option>
                                        <option value="Tetap">Tetap</option>
                                        <option value="Perbantuan">Perbantuan</option>
                                        <option value="Profesional Full Time">Profesional Full Time</option>
                                        <option value="Profesional Part Time">Profesional Part Time</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                                    <select name="pendidikan_terakhir" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Prodi/Lokasi Kerja <span class="text-red-500">*</span></label>
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
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Keahlian <span class="text-red-500">*</span></label>
                                    <select name="kelompok_keahlian_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                        <option value="">Pilih Kelompok Keahlian</option>
                                        @if(isset($filterData['kelompok_keahlian']))
                                            @foreach($filterData['kelompok_keahlian'] as $kelompok)
                                                <option value="{{ $kelompok->id }}">{{ $kelompok->nama_kelompok_keahlian }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Dosen</label>
                                    <select name="status_dosen" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" style="margin: 0; height: 38px;">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tugas Belajar">Tugas Belajar</option>
                                        <option value="Izin Belajar">Izin Belajar</option>
                                        <option value="CLTY">CLTY</option>
                                    </select>
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
                        if (!formData.get('nip') || !formData.get('kode_dosen') || !formData.get('nama_lengkap') || 
                            !formData.get('jabatan') || !formData.get('status_pegawai') || !formData.get('pendidikan_terakhir') ||
                            !formData.get('prodi_id') || !formData.get('kelompok_keahlian_id')) {
                            Swal.showValidationMessage('Mohon lengkapi semua field yang wajib diisi (*)');
                            return false;
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

                        fetch('{{ route("manajemen-dosen.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok && response.status !== 422) {
                                throw new Error('HTTP error! status: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Data dosen berhasil ditambahkan',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => location.reload());
                            } else {
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
    </script>
</body>
</html>