<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data Mahasiswa - Dashboard SDM</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Kelola Data Mahasiswa</h1>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('mahasiswa.kelola-data') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Program Studi Filter --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Program Studi</label>
                        <select name="prodi_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Prodi</option>
                            @if(isset($filterData['prodi']))
                                @foreach($filterData['prodi'] as $prodi)
                                    <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Status Mahasiswa</label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Status</option>
                            @php
                                $statuses = ['aktif', 'cuti', 'nonaktif', 'lulus', 'resign', 'dikeluarkan'];
                            @endphp
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort Selection --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Urutkan Berdasarkan</label>
                        <select name="sort"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Pendaftaran
                                Terbaru</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="nim-asc" {{ request('sort') == 'nim-asc' ? 'selected' : '' }}>NIM Terkecil
                            </option>
                            <option value="nim-desc" {{ request('sort') == 'nim-desc' ? 'selected' : '' }}>NIM Terbesar
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Filter Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Pencarian --}}
                    <div class="md:col-span-2">
                        <label class="block text-lg font-semibold text-red-600 mb-3">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama atau NIM Mahasiswa..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('mahasiswa.kelola-data') }}"
                            class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times"></i>
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
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Mahasiswa</h2>
                    <div class="text-sm text-gray-600">
                        @if(isset($mahasiswa))
                            Total: <strong>{{ $mahasiswa->total() }}</strong> Mahasiswa
                        @endif
                    </div>
                </div>

                {{-- Action Buttons Row --}}
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    @can('kelola-data-mahasiswa.create')
                        <a href="{{ route('mahasiswa.create') }}"
                            class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa
                        </a>
                    @endcan

                    {{-- Right Side Controls (Export) --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Export Button --}}
                        @can('kelola-data-mahasiswa.view')
                            <div class="relative inline-block text-left">
                                <button type="button" id="exportBtn"
                                    class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                    <i class="fas fa-download"></i>
                                    <span>Export</span>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>

                                <!-- Dropdown Export -->
                                <div id="exportDropdown"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200"
                                    style="z-index: 9999;">
                                    <a href="{{ route('mahasiswa.export-excel', request()->query()) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                        <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                        Export Excel
                                    </a>

                                    <a href="{{ route('mahasiswa.export-pdf', request()->query()) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                        <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                <a href="{{ route('mahasiswa.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'nim', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center space-x-1 hover:text-gray-200">
                                    <span>NIM</span>
                                    <i class="fas fa-sort text-gray-300"></i>
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Lengkap
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Program Studi
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Angkatan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($mahasiswa) && $mahasiswa->count() > 0)
                            @foreach($mahasiswa as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $item->nim }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div class="font-medium">{{ $item->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500 italic">{{ $item->email_telkom ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $item->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $item->angkatan }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @php
                                            $statusClass = match (strtolower($item->status)) {
                                                'aktif' => 'bg-green-100 text-green-800',
                                                'lulus' => 'bg-blue-100 text-blue-800',
                                                'nonaktif' => 'bg-red-100 text-red-800',
                                                'cuti' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('mahasiswa.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mahasiswa.edit', $item->id) }}"
                                                class="text-green-600 hover:text-green-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST"
                                                class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-800 delete-btn"
                                                    data-nama="{{ $item->nama_lengkap }}" data-nim="{{ $item->nim }}">
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
                                    <i class="fas fa-user-graduate text-4xl mb-2"></i>
                                    <p>Tidak ada data mahasiswa</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($mahasiswa) && $mahasiswa->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $mahasiswa->links() }}
                </div>
            @endif
        </div>


        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div id="toast"
                class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        // SWEETALERT DELETE CONFIRMATION
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.delete-form');
                const nama = this.getAttribute('data-nama');
                
                // PERBAIKAN: Mengambil data-nim, bukan data-nip
                const nim = this.getAttribute('data-nim'); 

                // Mencegah error jika CDN SweetAlert gagal dimuat
                if (typeof Swal === 'undefined') {
                    if(confirm(`Yakin ingin menghapus ${nama}?`)) {
                        form.submit();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Hapus Data Mahasiswa?', // PERBAIKAN: Disesuaikan jadi Mahasiswa
                    html: `
                    <div class="text-left space-y-2">
                        <p class="text-gray-600">Anda akan menghapus data Mahasiswa:</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                            <p class="font-semibold text-red-800">${nama} - ${nim}</p>
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