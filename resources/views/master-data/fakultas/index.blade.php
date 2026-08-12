<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data Fakultas - Dashboard SDM FIF</title>
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
        <div class="mb-8 mt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Kelola Data Fakultas</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Kelola data fakultas dan struktur pimpinan di lingkungan institusi.</p>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300 hidden">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                    <i class="fas fa-filter text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Filter Pencarian</h2>
                    <p class="text-xs text-gray-500 font-medium">Saring data fakultas berdasarkan kriteria</p>
                </div>
            </div>

            <form method="GET" action="{{ route('fakultas.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Pencarian --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama Fakultas..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Urutkan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutkan</label>
                        <select name="sort" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                        </select>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex justify-end gap-3 border-t border-gray-50 pt-4">
                    <a href="{{ route('fakultas.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-6 py-2.5 rounded-xl flex items-center space-x-2 transition-all duration-300 text-sm">
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </a>
                    
                    <button type="submit"
                        class="bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-6 py-2.5 rounded-xl flex items-center space-x-2 transition-all duration-300 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-sliders-h"></i>
                        <span>Terapkan</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Fakultas</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $fakultas->total() }} fakultas</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleExportDropdown(event)" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('fakultas.export-excel', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('fakultas.export-pdf', request()->query()) }}"
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
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Fakultas</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Dekan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Wakil Dekan 1</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Wakil Dekan 2</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($fakultas as $item)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{{ $item->nama_fakultas }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $item->dekan->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $item->wadek1->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $item->wadek2->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <div class="flex items-center justify-center gap-2.5">
                                        @can('master-data-fakultas.edit')
                                            <a href="{{ route('fakultas.edit', $item->id) }}"
                                               class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                               title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-university text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada data fakultas ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $fakultas->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    {{-- Notification Toast --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
            });
        </script>
    @endif
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
    </script>
</body>
</html>