<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data TPA - Dashboard SDM</title>
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
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Kelola Data TPA</h1>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('manajemen-tpa.kelola-data') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="nip-asc" {{ request('sort') == 'nip-asc' ? 'selected' : '' }}>NIP Naik</option>
                            <option value="nip-desc" {{ request('sort') == 'nip-desc' ? 'selected' : '' }}>NIP Turun</option>
                        </select>
                    </div>
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
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Jabatan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($tpa) && $tpa->count() > 0)
                            @foreach($tpa as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $item->nama_lengkap }}</span>
                                            <span class="text-xs text-gray-500">
                                                Username: {{ optional($item->user)->username ?? '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                        {{ $item->nip }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->jabatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->lokasi_kerja }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @php
                                            $statusClass = match($item->status_pegawai) {
                                                'Pegawai Tetap' => 'bg-green-100 text-green-800',
                                                'Profesional Full Time' => 'bg-blue-100 text-blue-800',
                                                'Profesional Part Time' => 'bg-yellow-100 text-yellow-800',
                                                'Perbantuan LLDIKTI' => 'bg-purple-100 text-purple-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $item->status_pegawai ?? 'Tidak Diketahui' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('manajemen-tpa.show', $item->id) }}"
                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

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
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transform = 'translateX(100%)';
                    setTimeout(() => successMessage.remove(), 300);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transform = 'translateX(100%)';
                    setTimeout(() => errorMessage.remove(), 300);
                }, 5000);
            }

            const exportDropdown = document.getElementById('exportDropdown');
            if (exportDropdown) {
                exportDropdown.addEventListener('change', function() {
                    if (this.value) {
                        alert(`Export ${this.value.toUpperCase()} akan segera tersedia`);
                        this.value = '';
                    }
                });
            }

            const filterForm = document.querySelector('form');
            if (filterForm) {
                filterForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memfilter...</span>';
                        submitBtn.disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>
