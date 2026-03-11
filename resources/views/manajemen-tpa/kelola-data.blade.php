<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data TPA - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 font-nunito">
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- Sidebar Navigation --}}
        <x-navbar />

        {{-- Main Content --}}
        <main class="flex-1 p-4 md:p-8">
            {{-- Top Search Bar --}}
            <x-topbar />

            {{-- Page Title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Kelola Data TPA</h1>
                <p class="text-gray-500 mt-1">Manajemen data Tenaga Kependidikan dan Profesional.</p>
            </div>

            {{-- Filter Section Card (Sama dengan desain Dosen) --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('manajemen-tpa.kelola-data') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        {{-- Lokasi Kerja --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider">Lokasi Kerja</label>
                            <select name="lokasi_kerja" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                                <option value="">Semua Lokasi</option>
                                @isset($filterData['lokasi_kerja'])
                                    @foreach($filterData['lokasi_kerja'] as $lokasi)
                                        <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        {{-- Status Pegawai --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider">Status Pegawai</label>
                            <select name="status_pegawai" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                                <option value="">Semua Status</option>
                                @isset($filterData['status_pegawai'])
                                    @foreach($filterData['status_pegawai'] as $status)
                                        <option value="{{ $status }}" {{ request('status_pegawai') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        {{-- Urutkan --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider">Urutkan</label>
                            <select name="sort" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="nip-asc" {{ request('sort') == 'nip-asc' ? 'selected' : '' }}>NIP Terkecil</option>
                            </select>
                        </div>

                        {{-- Pencarian --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider">Pencarian</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..."
                                       class="w-full pl-11 pr-4 py-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-red-500 transition-all">
                                <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('manajemen-tpa.kelola-data') }}" 
                           class="px-6 py-2.5 rounded-lg text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all font-semibold">
                            Reset
                        </a>
                        <button type="submit" 
                                class="px-8 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-all font-semibold flex items-center shadow-sm">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Data Table Section --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-visible">
                {{-- Table Header Section --}}
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-[#C41E3A]">Data TPA</h2>
                    </div>

                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                        @can('kelola-data-tpa.create')
                        <a href="{{ route('manajemen-tpa.create') }}"
                           class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-plus mr-2"></i>Tambah Data
                        </a>
                        @endcan

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Custom Export Dropdown ala Dosen --}}
                            <div class="relative inline-block text-left">
                                <button type="button" id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                                    <i class="fas fa-download"></i>
                                    <span>Export</span>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>
                                <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200" style="z-index: 9999;">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg"><i class="fas fa-file-excel text-green-600 mr-2"></i> Export Excel</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-file-pdf text-red-600 mr-2"></i> Export PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">NIP</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jabatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Lokasi Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tpa as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $item->nip }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $item->nama_lengkap }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->jabatan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->lokasi_kerja }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $item->status_pegawai }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('manajemen-tpa.show', $item->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('manajemen-tpa.edit', $item->id) }}" class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></a>
                                            
                                            <form action="{{ route('manajemen-tpa.destroy', $item->id) }}" method="POST" class="inline-block delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-800 delete-btn" 
                                                        data-nama="{{ $item->nama_lengkap }}" 
                                                        data-nip="{{ $item->nip }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-2"></i>
                                        <p>Tidak ada data TPA</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown Export Logic
            const exportBtn = document.getElementById('exportBtn');
            const exportDropdown = document.getElementById('exportDropdown');
            if (exportBtn) {
                exportBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    exportDropdown.classList.toggle('hidden');
                });
            }
            document.addEventListener('click', () => exportDropdown?.classList.add('hidden'));

            // SWEETALERT DELETE CONFIRMATION (Persis gaya Dosen)
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');
                    const nip = this.getAttribute('data-nip');

                    Swal.fire({
                        title: 'Hapus Data TPA?',
                        html: `
                            <div class="text-left space-y-2">
                                <p class="text-gray-600">Anda akan menghapus data pegawai TPA:</p>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                    <p class="font-semibold text-red-800">${nama}</p>
                                    <p class="text-sm text-red-600">NIP: ${nip}</p>
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
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
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