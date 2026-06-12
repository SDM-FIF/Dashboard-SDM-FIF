<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data Mahasiswa - Dashboard SDM FIF</title>
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
        {{-- Topbar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Kelola Data Mahasiswa</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola data mahasiswa, angkatan, program studi, dan status akademik.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('kelola-data-mahasiswa.create')
                <a href="{{ route('mahasiswa.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Mahasiswa</span>
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
                    <p class="text-xs text-gray-500 font-medium">Saring data mahasiswa berdasarkan kriteria akademik</p>
                </div>
            </div>

            <form method="GET" action="{{ route('mahasiswa.kelola-data') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Program Studi Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi</label>
                        <select name="prodi_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
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
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Mahasiswa</label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
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
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutkan Berdasarkan</label>
                        <select name="sort"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Pendaftaran Terbaru</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="nim-asc" {{ request('sort') == 'nim-asc' ? 'selected' : '' }}>NIM Terkecil</option>
                            <option value="nim-desc" {{ request('sort') == 'nim-desc' ? 'selected' : '' }}>NIM Terbesar</option>
                        </select>
                    </div>
                </div>

                {{-- Filter Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Pencarian --}}
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama atau NIM Mahasiswa..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-3">
                        <a href="{{ route('mahasiswa.kelola-data') }}" id="resetFilterBtn"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-4 py-3 rounded-xl flex items-center justify-center space-x-2 transition-all duration-300">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>

                        <button type="submit" id="applyFilterBtn"
                            class="flex-1 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-4 py-3 rounded-xl flex items-center justify-center space-x-2 transition-all duration-300 shadow-sm hover:shadow">
                            <i class="fas fa-sliders-h"></i>
                            <span>Terapkan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            {{-- Table Header Info --}}
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Mahasiswa</h2>
                    @if(isset($mahasiswa))
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $mahasiswa->total() }} mahasiswa terdaftar</p>
                    @endif
                </div>

                {{-- Export Button --}}
                @can('kelola-data-mahasiswa.view')
                <div class="relative inline-block text-left">
                    <button type="button" id="exportBtn" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <!-- Dropdown Export -->
                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('mahasiswa.export-excel', request()->query()) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('mahasiswa.export-pdf', request()->query()) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-50">
                            <i class="fas fa-file-pdf text-[#C41E3A] text-lg"></i>
                            <span>Export PDF</span>
                        </a>
                    </div>
                </div>
                @endcan
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <a href="{{ route('mahasiswa.kelola-data', array_merge(request()->except(['sort_field', 'sort_direction']), ['sort_field' => 'nim', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center gap-1.5 hover:text-red-100 transition-colors">
                                    <span>NIM</span>
                                    <i class="fas fa-sort text-red-200"></i>
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Angkatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @if(isset($mahasiswa) && $mahasiswa->count() > 0)
                            @foreach($mahasiswa as $item)
                                <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                                    <td class="px-6 py-4 text-sm text-gray-500 font-semibold">{{ $item->nim }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 group-hover:text-[#C41E3A] transition-colors">
                                        <div class="font-bold">{{ $item->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-400 font-normal mt-0.5">{{ $item->email_telkom ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        {{ $item->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-semibold">
                                        {{ $item->angkatan }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $statusClass = match (strtolower($item->status)) {
                                                'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'lulus' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'nonaktif' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                'cuti' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs {{ $statusClass }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <div class="flex items-center justify-center gap-2.5">
                                            @can('kelola-data-mahasiswa.detail')
                                            <a href="{{ route('mahasiswa.show', $item->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @endcan
                                            @can('kelola-data-mahasiswa.edit')
                                            <a href="{{ route('mahasiswa.edit', $item->id) }}"
                                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                                title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            @endcan
                                            @can('kelola-data-mahasiswa.delete')
                                            <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST"
                                                class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                                    data-nama="{{ $item->nama_lengkap }}" data-nim="{{ $item->nim }}">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-user-graduate text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada data mahasiswa ditemukan</p>
                                        <p class="text-xs text-gray-400 max-w-xs">Silakan sesuaikan filter pencarian atau tambahkan data mahasiswa baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($mahasiswa) && $mahasiswa->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-500">
                            Menampilkan {{ $mahasiswa->firstItem() }} - {{ $mahasiswa->lastItem() }} dari {{ $mahasiswa->total() }} Mahasiswa
                        </p>
                        <div class="flex items-center">
                            {{ $mahasiswa->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Notification Handlers --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown Export Logic
            const exportBtn = document.getElementById('exportBtn');
            const exportDropdown = document.getElementById('exportDropdown');
            if (exportBtn && exportDropdown) {
                exportBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    exportDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', () => exportDropdown.classList.add('hidden'));
            }

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
                btn.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');
                    const nim = this.getAttribute('data-nim'); 

                    Swal.fire({
                        title: 'Hapus Data Mahasiswa?',
                        html: `
                        <div class="text-left space-y-2">
                            <p class="text-gray-600">Anda akan menghapus data Mahasiswa:</p>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-3">
                                <p class="font-bold text-red-800 text-base">${nama}</p>
                                <p class="text-xs text-red-600 mt-1">NIM: ${nim}</p>
                            </div>
                            <p class="text-xs text-red-600 mt-3 font-semibold">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Data yang dihapus tidak dapat dikembalikan!
                            </p>
                        </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6B7280',
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