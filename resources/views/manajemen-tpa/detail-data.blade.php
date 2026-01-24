<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data TPA - Dashboard SDM</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Detail Data TPA</h1>
            <p class="text-gray-600">Informasi lengkap data Tenaga Pendukung Akademik</p>
        </div>

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('manajemen-tpa.kelola-data') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali</span>
            </a>
        </div>

        {{-- Detail Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            {{-- Biodata Section --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Biodata</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Column 1 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">NIP</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->nip }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Nama</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->nama_lengkap }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Lokasi Kerja</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->lokasi_kerja }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Pendidikan Terakhir</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->pendidikan_terakhir }}</p>
                        </div>
                    </div>

                    {{-- Column 2 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Jabatan</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->jabatan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Status Pegawai</label>
                            @php
                                $statusClass = match($tpa->status_pegawai) {
                                    'Pegawai Tetap' => 'bg-green-100 text-green-800',
                                    'Profesional Full Time' => 'bg-blue-100 text-blue-800',
                                    'Profesional Part Time' => 'bg-yellow-100 text-yellow-800',
                                    'Perbantuan LLDIKTI' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ $tpa->status_pegawai ?? 'Tidak Diketahui' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">User ID</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->user_id }}</p>
                        </div>
                    </div>

                    {{-- Column 3 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Username</label>
                            <p class="text-gray-900 font-medium">{{ $tpa->user->username ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Status Akun</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List Data TPA Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">List Data TPA</h2>
                    <div class="text-sm text-gray-600">
                        Total: {{ $allTpa->total() }} TPA
                    </div>
                </div>

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('manajemen-tpa.show', $tpa->id) }}" class="mb-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                        {{-- Left Side Controls --}}
                        <div class="flex flex-wrap items-center space-x-4">
                            {{-- Filter Dropdown --}}
                            <div class="relative">
                                <select name="filter_status"
                                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                    <option value="">Semua Status</option>
                                    <option value="Pegawai Tetap" {{ request('filter_status') == 'Pegawai Tetap' ? 'selected' : '' }}>Pegawai Tetap</option>
                                    <option value="Perbantuan LLDIKTI" {{ request('filter_status') == 'Perbantuan LLDIKTI' ? 'selected' : '' }}>Perbantuan LLDIKTI</option>
                                    <option value="Profesional Full Time" {{ request('filter_status') == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                                    <option value="Profesional Part Time" {{ request('filter_status') == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                                </select>
                            </div>
                            
                            {{-- Sort Dropdown --}}
                            <div class="relative">
                                <select name="sort"
                                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                    <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                                    <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                                </select>
                            </div>

                            {{-- Filter Button --}}
                            <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-filter"></i>
                                <span>Filter</span>
                            </button>
                        </div>

                        {{-- Right Side Controls --}}
                        <div class="flex items-center space-x-4">
                            {{-- Reset Filter Button --}}
                            <a href="{{ route('manajemen-tpa.show', $tpa->id) }}"
                               class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                Reset Filter
                            </a>
                        
                            {{-- Export Dropdown --}}
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
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">NO.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allTpa as $index => $tpaItem)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $tpaItem->id == $tpa->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $allTpa->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium {{ $tpaItem->id == $tpa->id ? 'text-blue-700' : '' }}">
                                            {{ $tpaItem->nama_lengkap }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            Username: {{ optional($tpaItem->user)->username ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $tpaItem->nip }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $tpaItem->lokasi_kerja }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php
                                        $statusClassRow = match($tpaItem->status_pegawai) {
                                            'Pegawai Tetap' => 'bg-green-100 text-green-800',
                                            'Profesional Full Time' => 'bg-blue-100 text-blue-800',
                                            'Profesional Part Time' => 'bg-yellow-100 text-yellow-800',
                                            'Perbantuan LLDIKTI' => 'bg-purple-100 text-purple-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClassRow }}">
                                        {{ $tpaItem->status_pegawai ?? 'Tidak Diketahui' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('manajemen-tpa.show', $tpaItem->id) }}"
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200 {{ $tpaItem->id == $tpa->id ? 'text-blue-800' : '' }}"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('manajemen-tpa.edit', $tpaItem->id) }}"
                                           class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('manajemen-tpa.destroy', $tpaItem->id) }}"
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center space-y-4">
                                        <i class="fas fa-users text-4xl text-gray-300"></i>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data TPA</h3>
                                            <p class="text-sm text-gray-500">Belum ada data TPA yang tersedia.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($allTpa->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $allTpa->firstItem() }} sampai {{ $allTpa->lastItem() }}
                            dari {{ $allTpa->total() }} hasil
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $allTpa->appends(request()->query())->links() }}
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
