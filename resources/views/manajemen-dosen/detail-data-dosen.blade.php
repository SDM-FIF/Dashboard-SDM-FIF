<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data Dosen - Dashboard SDM</title>
    <!-- Font Awesome for icons -->
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Detail Data Dosen</h1>
            <p class="text-gray-600">Informasi lengkap data dosen Fakultas Informatika dan Ilmu Komputer</p>
        </div>

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('manajemen-dosen.kelola-data') }}" 
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
                            <p class="text-gray-900 font-medium">{{ $dosen->nip }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Nama</label>
                            <p class="text-gray-900 font-medium">
                                {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Lokasi Kerja</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->lokasi_kerja }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">JFA</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->jabatan }}</p>
                        </div>
                    </div>

                    {{-- Column 2 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Kode Dosen</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->kode_dosen }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Kelompok Keahlian</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->kelompokKeahlian->nama_kelompok_keahlian ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Status Pegawai</label>
                            @php
                                $statusClass = match($dosen->status_pegawai) {
                                    'Tetap' => 'bg-green-100 text-green-800',
                                    'Perbantuan' => 'bg-blue-100 text-blue-800',
                                    'Profesional Full Time' => 'bg-purple-100 text-purple-800',
                                    'Profesional Part Time' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ $dosen->status_pegawai ?? 'Tidak Diketahui' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Program Studi</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->prodi->nama_prodi ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Column 3 - Additional Info --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Fakultas</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->prodi->fakultas->nama_fakultas ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Username</label>
                            <p class="text-gray-900 font-medium">{{ $dosen->user->username ?? 'N/A' }}</p>
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

        {{-- List Data Dosen Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">List Data Dosen</h2>
                    <div class="text-sm text-gray-600">
                        Total: {{ $allDosen->total() }} dosen
                    </div>
                </div>

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('manajemen-dosen.show', $dosen->id) }}" class="mb-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                        {{-- Left Side Controls --}}
                        <div class="flex flex-wrap items-center space-x-4">
                            {{-- Filter Dropdown --}}
                            <div class="relative">
                                <select name="filter_status"
                                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                    <option value="">Semua Status</option>
                                    <option value="Tetap" {{ request('filter_status') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                    <option value="Perbantuan" {{ request('filter_status') == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
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
                            <a href="{{ route('manajemen-dosen.show', $dosen->id) }}" 
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
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    {{-- Table Header --}}
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">NO.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">JFA</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- Table Body --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allDosen as $index => $dosenItem)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $dosenItem->id == $dosen->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $allDosen->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium {{ $dosenItem->id == $dosen->id ? 'text-blue-700' : '' }}">
                                            {{ $dosenItem->front_title }} {{ $dosenItem->nama_lengkap }}, {{ $dosenItem->back_title }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $dosenItem->kode_dosen }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $dosenItem->nip }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $dosenItem->jabatan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $dosenItem->lokasi_kerja }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php
                                        $statusClass = match($dosenItem->status_pegawai) {
                                            'Tetap' => 'bg-green-100 text-green-800',
                                            'Perbantuan' => 'bg-blue-100 text-blue-800',
                                            'Profesional Full Time' => 'bg-purple-100 text-purple-800',
                                            'Profesional Part Time' => 'bg-orange-100 text-orange-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $dosenItem->status_pegawai ?? 'Tidak Diketahui' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        {{-- View Button --}}
                                        <a href="{{ route('manajemen-dosen.show', $dosenItem->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200 {{ $dosenItem->id == $dosen->id ? 'text-blue-800' : '' }}" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        {{-- Edit Button --}}
                                        <a href="{{ route('manajemen-dosen.edit', $dosenItem->id) }}" 
                                           class="text-green-600 hover:text-green-800 transition-colors duration-200" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        {{-- Delete Button --}}
                                        <form action="{{ route('manajemen-dosen.destroy', $dosenItem->id) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
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
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center space-y-4">
                                        <i class="fas fa-users text-4xl text-gray-300"></i>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data dosen</h3>
                                            <p class="text-sm text-gray-500">Belum ada data dosen yang tersedia.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($allDosen->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $allDosen->firstItem() }} sampai {{ $allDosen->lastItem() }} 
                            dari {{ $allDosen->total() }} hasil
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $allDosen->appends(request()->query())->links() }}
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
            // Auto-hide success/error messages
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

            // Export functionality
            const exportDropdown = document.getElementById('exportDropdown');
            if (exportDropdown) {
                exportDropdown.addEventListener('change', function() {
                    if (this.value) {
                        alert(`Export ${this.value.toUpperCase()} akan segera tersedia`);
                        this.value = '';
                    }
                });
            }

            // Add loading state to filter form
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

            // Scroll to current dosen if highlighted
            const currentDosenRow = document.querySelector('.highlight-row');
            if (currentDosenRow) {
                setTimeout(() => {
                    currentDosenRow.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }, 500);
            }
        });
    </script>
</body>
</html>