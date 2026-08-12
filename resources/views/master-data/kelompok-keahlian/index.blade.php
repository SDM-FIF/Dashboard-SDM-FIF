<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Kelompok Keahlian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        {{-- Flash Alert Messages --}}
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#C41E3A',
                    timer: 3000
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#C41E3A'
                });
            });
        </script>
        @endif

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Data Kelompok Keahlian</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola data kelompok keahlian dosen di lingkungan Fakultas Informatika.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('master-data-kelompok-keahlian.create')
                <a href="{{ route('kelompok-keahlian.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kelompok Keahlian</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Filter & Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
            <form method="GET" action="{{ route('kelompok-keahlian.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2 flex flex-col gap-1.5">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari singkatan atau nama kelompok keahlian..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                </div>
                <div class="flex flex-col gap-1.5">
                    <select name="sort" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama (A - Z)</option>
                        <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama (Z - A)</option>
                        <option value="singkatan-az" {{ request('sort') == 'singkatan-az' ? 'selected' : '' }}>Singkatan (A - Z)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('kelompok-keahlian.index') }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-4 py-3 rounded-xl flex items-center justify-center space-x-2 transition-all text-sm">
                        <span>Reset</span>
                    </a>
                    <button type="submit"
                        class="flex-1 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-4 py-3 rounded-xl flex items-center justify-center space-x-2 transition-all duration-300 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Kelompok Keahlian</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $kelompokKeahlian->total() }} kelompok keahlian</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleExportDropdown(event)" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('kelompok-keahlian.export-excel', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('kelompok-keahlian.export-pdf', request()->query()) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-50">
                            <i class="fas fa-file-pdf text-[#C41E3A] text-lg"></i>
                            <span>Export PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">Singkatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Kelompok Keahlian</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($kelompokKeahlian as $item)
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg border font-bold text-xs bg-red-50 text-[#C41E3A] border-red-100">
                                    {{ $item->singkatan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                {{ $item->nama_kelompok_keahlian }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @can('master-data-kelompok-keahlian.edit')
                                    <a href="{{ route('kelompok-keahlian.edit', $item->id) }}"
                                        class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"
                                        title="Edit Data">
                                        <i class="fas fa-[#edit] fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('master-data-kelompok-keahlian.delete')
                                    <button type="button"
                                        onclick="confirmDelete('{{ $item->id }}', '{{ addslashes($item->nama_kelompok_keahlian) }}')"
                                        class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('kelompok-keahlian.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3"></i>
                                <p class="text-sm font-semibold">Belum ada data kelompok keahlian yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($kelompokKeahlian->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $kelompokKeahlian->links() }}
            </div>
            @endif
        </div>
    </main>

    <script>
        function toggleExportDropdown(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('exportDropdown');
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('exportDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus kelompok keahlian "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</body>

</html>
