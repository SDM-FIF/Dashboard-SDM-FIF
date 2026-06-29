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
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Lokasi Kerja</label>
                        <select name="lokasi_kerja"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            @if(isset($filterData['lokasi_kerja']))
                            @foreach($filterData['lokasi_kerja'] as $lokasi)
                            <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>
                                {{ $lokasi }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Status Pegawai Filter --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Status Pegawai</label>
                        <select name="status_pegawai"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            @if(isset($filterData['status_pegawai']))
                            @foreach($filterData['status_pegawai'] as $status)
                            <option value="{{ $status }}" {{ request('status_pegawai') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Sort Selection --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Urutkan</label>
                        <select name="sort"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nip-asc" {{ request('sort') == 'nip-asc' ? 'selected' : '' }}>NIP Terkecil</option>
                        </select>
                    </div>

                {{-- Filter Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Pencarian --}}
                    <div class="md:col-span-2">
                        <label class="block text-lg font-semibold text-red-600 mb-3">Pencarian</label>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama TPA..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    </div>
                </div>

                    {{-- Buttons --}}
                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('manajemen-tpa.kelola-data') }}"
                            class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">Data TPA</h2>
                    <div class="text-sm text-gray-600">
                        @if(isset($tpa))
                        Total: {{ $tpa->total() }} TPA
                        @endif
                    </div>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    <a href="{{ route('manajemen-tpa.create') }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Data</span>
                    </a>

                    <div class="flex flex-wrap items-center space-x-4">
                        <div class="relative">
                            <select id="exportDropdown"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <option value="">Export</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    {{-- Table Header --}}
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Pangkat/Gol.</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Pendidikan Terakhir</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
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
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
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

                                    <a href="{{ route('manajemen-tpa.edit', $item->id) }}"
                                        class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('manajemen-tpa.destroy', $item->id) }}"
                                        method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data TPA ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-4">
                                    <i class="fas fa-users text-4xl text-gray-300"></i>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data TPA</h3>
                                        <p class="text-sm text-gray-500">Belum ada data TPA yang tersedia atau sesuai dengan filter yang dipilih.</p>
                                    </div>
                                    <a href="{{ route('manajemen-tpa.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah TPA Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($tpa) && $tpa->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $tpa->firstItem() }} sampai {{ $tpa->lastItem() }}
                        dari {{ $tpa->total() }} hasil
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $tpa->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

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
                    text: '{{ session('success') }}',
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
                    text: '{{ session('error') }}',
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