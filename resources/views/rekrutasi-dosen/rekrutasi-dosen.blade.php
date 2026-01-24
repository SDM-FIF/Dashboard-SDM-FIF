<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Rekrutasi Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Search Bar --}}
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Rekrutasi Dosen</h1>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('rekrutasi-dosen') }}">
                {{-- Filter Row --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Jenjang Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Jenjang</label>
                        <select name="jenjang"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Jenjang</option>
                            @if(isset($filterData['jenjang']))
                            @foreach($filterData['jenjang'] as $jenjang)
                            <option value="{{ $jenjang }}" {{ request('jenjang') == $jenjang ? 'selected' : '' }}>
                                {{ $jenjang }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Prodi Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Prodi</label>
                        <select name="prodi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Prodi</option>
                            @if(isset($filterData['prodi']))
                            @foreach($filterData['prodi'] as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Tahun Ajar Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Tahun Ajar</label>
                        <select name="tahun_ajar"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Tahun Ajar</option>
                            @if(isset($filterData['tahun_ajar']))
                            @foreach($filterData['tahun_ajar'] as $ta)
                            <option value="{{ $ta->id }}" {{ request('tahun_ajar') == $ta->id ? 'selected' : '' }}>
                                {{ $ta->label }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Status Penerimaan Filter --}}
                    <div>
                        <label class="block text-base font-semibold text-[#C41E3A] mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Status</option>
                            @if(isset($filterData['status']))
                            @foreach($filterData['status'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Filter & Reset Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-black font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-sliders-h text-black"></i>
                            <span>Filter</span>
                        </button>

                        @if(request()->has('jenjang') || request()->has('prodi') || request()->has('tahun_ajar') || request()->has('status'))
                        <a href="{{ route('rekrutasi-dosen') }}"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center space-x-2">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-visible">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Data Rekrutasi Dosen</h2>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    <a href="{{ route('rekrutasi-dosen.create') }}"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md inline-block">
                        Tambah Data
                    </a>

                    {{-- Right Side Controls --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Export Button --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]">
                                <a href="{{ route('rekrutasi-dosen.export-excel', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="{{ route('rekrutasi-dosen.export-csv', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="{{ route('rekrutasi-dosen.export-pdf', request()->all()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                {{-- Table Header --}}
                <thead>
                    <tr class="bg-[#C41E3A] text-white">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            <a href="{{ route('rekrutasi-dosen', array_merge(request()->all(), ['sort' => 'no_registrasi', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-yellow-300">
                                No. Registrasi
                                @if(request('sort') == 'no_registrasi')
                                <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-xs opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            <a href="{{ route('rekrutasi-dosen', array_merge(request()->all(), ['sort' => 'nama', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-yellow-300">
                                Nama
                                @if(request('sort') == 'nama')
                                <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-xs opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenjang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Prodi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tahun Ajar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($rekrutasi) && $rekrutasi->count() > 0)
                    @foreach($rekrutasi as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->no_registrasi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ strtoupper($item->prodi->jenjang ?? '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->prodi->nama_prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tahunAjar->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                            $statusClass = match($item->status_penerimaan) {
                                'Diterima' => 'bg-green-100 text-green-800',
                                'Ditolak' => 'bg-red-100 text-red-800',
                                'Seleksi' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $item->status_penerimaan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center space-x-3">
                                {{-- View Button --}}
                                <a href="{{ route('rekrutasi-dosen.show', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Edit Button --}}
                                <a href="{{ route('rekrutasi-dosen.edit', $item->id) }}"
                                    class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Delete Button - IMPROVED WITH SWEETALERT --}}
                                <form action="{{ route('rekrutasi-dosen.destroy', $item->id) }}"
                                    method="POST"
                                    class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200 delete-btn"
                                        data-nama="{{ $item->nama }}"
                                        data-no-reg="{{ $item->no_registrasi }}"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    {{-- Empty State --}}
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center space-y-4">
                                <i class="fas fa-users text-4xl text-gray-300"></i>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data rekrutasi</h3>
                                    <p class="text-sm text-gray-500">Belum ada data rekrutasi yang tersedia.</p>
                                </div>
                                <a href="{{ route('rekrutasi-dosen.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-[#C41E3A] hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Data Rekrutasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($rekrutasi) && $rekrutasi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan {{ $rekrutasi->firstItem() }} sampai {{ $rekrutasi->lastItem() }}
                    dari {{ $rekrutasi->total() }} hasil
                </div>
                <div class="flex items-center space-x-2">
                    {{ $rekrutasi->links() }}
                </div>
            </div>
        </div>
        @endif
        </div>
    </main>

    {{-- Success/Error Messages with SweetAlert2 --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                showConfirmButton: true,
                confirmButtonColor: '#C41E3A'
            });
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Export dropdown toggle
            const exportBtn = document.getElementById('exportBtn');
            const exportDropdown = document.getElementById('exportDropdown');

            if (exportBtn && exportDropdown) {
                exportBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    exportDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    exportDropdown.classList.add('hidden');
                });
            }

            // ============================================
            // SWEETALERT DELETE CONFIRMATION
            // ============================================
            const deleteBtns = document.querySelectorAll('.delete-btn');

            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');
                    const noReg = this.getAttribute('data-no-reg');

                    Swal.fire({
                        title: 'Hapus Data Rekrutasi?',
                        html: `
                        <div class="text-left space-y-2">
                            <p class="text-gray-600">Anda akan menghapus data rekrutasi:</p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="font-semibold text-red-800">${nama}</p>
                                <p class="text-sm text-red-600">No. Registrasi: ${noReg}</p>
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
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'px-6 py-2.5 rounded-lg font-semibold',
                            cancelButton: 'px-6 py-2.5 rounded-lg font-semibold'
                        },
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus...',
                                html: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Submit form
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>