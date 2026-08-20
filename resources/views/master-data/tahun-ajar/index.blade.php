<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Tahun Ajaran - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Data Tahun Ajaran</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola data tahun ajaran dan semester aktif untuk operasional akademik.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('master-data-tahun-ajar.create')
                <a href="{{ route('tahun-ajar.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Tahun Ajaran</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Filter & Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
            <form method="GET" action="{{ route('tahun-ajar.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2 flex flex-col gap-1.5">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tahun atau semester..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                </div>
                <div class="flex flex-col gap-1.5">
                    <select name="sort" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('tahun-ajar.index') }}"
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
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Tahun Ajaran</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $tahunAjar->total() }} tahun ajaran</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleExportDropdown(event)" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('tahun-ajar.export-excel', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('tahun-ajar.export-pdf', request()->query()) }}"
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
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tahun Awal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Label Tahun Ajaran</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($tahunAjar as $item)
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $item->tahun }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                @if($item->semester == '1')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs bg-emerald-50 text-emerald-700 border-emerald-100">Ganjil</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs bg-blue-50 text-blue-700 border-blue-100">Genap</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{{ $item->label }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs bg-green-50 text-green-700 border-green-100"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs bg-gray-50 text-gray-500 border-gray-200">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <div class="flex items-center justify-center gap-2.5">
                                    @can('master-data-tahun-ajar.edit')
                                    <a href="{{ route('tahun-ajar.edit', $item->id) }}"
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                        title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @endcan

                                    @can('master-data-tahun-ajar.delete')
                                    <form action="{{ route('tahun-ajar.destroy', $item->id) }}" method="POST"
                                        class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                            data-nama="{{ $item->label }}"
                                            title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                        <i class="fas fa-calendar-alt text-4xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Tidak ada data tahun ajaran ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $tahunAjar->links() }}
            </div>
        </div>
    </main>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast Success
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

            // Toast Error / Warning
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#C41E3A'
                });
            @endif

            // Delete Confirm
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');

                    Swal.fire({
                        title: 'Hapus Tahun Ajaran?',
                        text: `Apakah Anda yakin ingin menghapus Tahun Ajaran ${nama}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#64748B',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
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
