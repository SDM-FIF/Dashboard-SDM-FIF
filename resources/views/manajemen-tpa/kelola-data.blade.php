<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data TPA - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        {{-- Top Search Bar / Topbar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Kelola Data TPA</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi profile, jabatan, lokasi kerja, dan status Tenaga Kependidikan dan Profesional.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('kelola-data-tpa.create')
                <a href="{{ route('manajemen-tpa.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Data</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                    <i class="fas fa-filter text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Filter Pencarian</h2>
                    <p class="text-xs text-gray-500">Saring data TPA berdasarkan kriteria tertentu</p>
                </div>
            </div>

            <form method="GET" action="{{ route('manajemen-tpa.kelola-data') }}" class="space-y-6">
                {{-- Filter Row --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {{-- Lokasi Kerja Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Kerja</label>
                        <select name="lokasi_kerja"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Semua Lokasi</option>
                            @isset($filterData['lokasi_kerja'])
                            @foreach($filterData['lokasi_kerja'] as $lokasi)
                            <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>
                                {{ $lokasi }}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Status Pegawai Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Pegawai</label>
                        <select name="status_pegawai"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Semua Status</option>
                            @isset($filterData['status_pegawai'])
                            @foreach($filterData['status_pegawai'] as $status)
                            <option value="{{ $status }}" {{ request('status_pegawai') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Urutkan Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutkan</label>
                        <select name="sort"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nip-asc" {{ request('sort') == 'nip-asc' ? 'selected' : '' }}>NIP Terkecil</option>
                        </select>
                    </div>

                    {{-- Kata Kunci Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kata Kunci</label>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari NIP atau Nama..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>
                </div>

                {{-- Filter & Reset Buttons --}}
                <div class="flex justify-end items-center gap-3 border-t border-gray-50 pt-4">
                    <a href="{{ route('manajemen-tpa.kelola-data') }}" id="resetFilterBtn"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-6 py-2.5 rounded-xl flex items-center space-x-2 transition-all duration-300 text-sm">
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </a>

                    <button type="submit" id="applyFilterBtn"
                        class="bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-6 py-2.5 rounded-xl flex items-center space-x-2 transition-all duration-300 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-sliders-h"></i>
                        <span>Terapkan</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            {{-- Table Header Info --}}
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar TPA</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $tpa->total() }} TPA terdaftar</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" id="exportBtn" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <!-- Dropdown Export -->
                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-50">
                            <i class="fas fa-file-pdf text-[#C41E3A] text-lg"></i>
                            <span>Export PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    {{-- Table Header --}}
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIP</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Pendidikan Terakhir</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($tpa as $item)
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            {{-- NIP --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-semibold">
                                {{ $item->nip }}
                            </td>

                            {{-- Nama Lengkap --}}
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 group-hover:text-[#C41E3A] transition-colors">
                                {{ $item->nama_lengkap }}
                            </td>

                            {{-- Jabatan --}}
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded border border-blue-100 bg-blue-50 text-blue-700 text-xs font-bold">
                                    {{ $item->jabatan ?? '-' }}
                                </span>
                            </td>

                            {{-- Pendidikan Terakhir --}}
                            <td class="px-6 py-4 text-sm">
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-100 text-xs font-semibold">
                                  {{ $item->pendidikan_terakhir ?? '-' }}
                                </span>
                            </td>

                            {{-- Lokasi Kerja --}}
                            <td class="px-6 py-4 text-sm text-gray-600 font-semibold">
                                {{ $item->lokasi_kerja }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-sm">
                                @php
                                $sc = match($item->status_pegawai) {
                                'Pegawai Tetap', 'Tetap' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'Perbantuan LLDIKTI', 'Perbantuan' => 'bg-sky-50 text-sky-700 border-sky-100',
                                'Profesional Full Time' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'Profesional Part Time' => 'bg-amber-50 text-amber-700 border-amber-100',
                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                                };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded border font-bold text-xs {{ $sc }}">
                                    {{ $item->status_pegawai ?? '-' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center text-sm">
                                <div class="flex items-center justify-center gap-2.5">
                                    {{-- Detail Button --}}
                                    @can('kelola-data-tpa.detail')
                                    <a href="{{ route('manajemen-tpa.show', $item->id) }}"
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @endcan

                                    {{-- Edit Button --}}
                                    @can('kelola-data-tpa.edit')
                                    <a href="{{ route('manajemen-tpa.edit', $item->id) }}"
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                        title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @endcan

                                    {{-- Delete Button --}}
                                    @can('kelola-data-tpa.delete')
                                    <form action="{{ route('manajemen-tpa.destroy', $item->id) }}"
                                        method="POST"
                                        class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                            data-nama="{{ $item->nama_lengkap }}"
                                            data-nip="{{ $item->nip }}"
                                            title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- Empty State --}}
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                        <i class="fas fa-users text-4xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Tidak ada data TPA ditemukan</p>
                                    <p class="text-xs text-gray-400 max-w-xs">Silakan sesuaikan filter pencarian atau tambahkan data TPA baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-semibold text-gray-800">{{ $tpa->firstItem() ?? 0 }}</span>
                        –
                        <span class="font-semibold text-gray-800">{{ $tpa->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-[#C41E3A]">{{ $tpa->total() }}</span>
                        data
                    </div>
                    <div>
                        {{ $tpa->appends(request()->query())->links('components.custom-pagination') }}
                    </div>
                </div>
            </div>


            {{-- SweetAlert2 JS --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Export dropdown toggle
                    const exportBtn = document.getElementById('exportBtn');
                    const exportDropdown = document.getElementById('exportDropdown');

                    if (exportBtn && exportDropdown) {
                        exportBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            exportDropdown.classList.toggle('hidden');
                        });

                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!exportBtn.contains(e.target) && !exportDropdown.contains(e.target)) {
                                exportDropdown.classList.add('hidden');
                            }
                        });
                    }

                    // Success/Error Messages with SweetAlert2 Toast
                    @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('
                        success ') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        toast: true,
                        position: 'top-end'
                    });
                    @endif

                    @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('
                        error ') }}',
                        showConfirmButton: true,
                        confirmButtonColor: '#C41E3A'
                    });
                    @endif

                    // SWEETALERT DELETE CONFIRMATION
                    const deleteBtns = document.querySelectorAll('.delete-btn');
                    deleteBtns.forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();

                            const form = this.closest('.delete-form');
                            const nama = this.getAttribute('data-nama');
                            const nip = this.getAttribute('data-nip');

                            Swal.fire({
                                title: 'Hapus Data TPA?',
                                html: `
                            <div class="text-left space-y-2">
                                <p class="text-gray-600">Apakah Anda yakin ingin menghapus data TPA:</p>
                                <div class="bg-red-50 border border-red-100 rounded-xl p-4 mt-3">
                                    <p class="font-bold text-[#C41E3A]">${nama}</p>
                                    <p class="text-xs text-red-600 mt-0.5">NIP: ${nip}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-3">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i>
                                    Tindakan ini permanen dan data tidak dapat dikembalikan!
                                </p>
                            </div>
                        `,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#C41E3A',
                                cancelButtonColor: '#64748B',
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Batal',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm',
                                    cancelButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        });
                    });
                });
            </script>
</body>

</html>