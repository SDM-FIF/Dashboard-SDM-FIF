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
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">Daftar Mahasiswa</h2>
                    <div class="text-sm text-gray-600">
                        @if(isset($mahasiswa))
                            Total: {{ $mahasiswa->total() }} Mahasiswa
                        @endif
                    </div>
                </div>

                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    <a href="{{ route('mahasiswa.create') }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Tambah Mahasiswa</span>
                    </a>

                    <div class="flex flex-wrap items-center space-x-4">
                        <select id="exportDropdown"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                            <option value="">Export Data</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIM</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Program Studi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Angkatan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($mahasiswa) && $mahasiswa->count() > 0)
                            @foreach($mahasiswa as $item)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                                            {{ $item->nim }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-900">
                                                            <div class="flex flex-col">
                                                                <span class="font-medium">{{ $item->nama_lengkap }}</span>
                                                                <span class="text-xs text-gray-500 italic">
                                                                    {{ $item->email_telkom ?? '-' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-900">
                                                            {{ $item->prodi->nama_prodi ?? '-' }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-900">
                                                            {{ $item->angkatan ?? '-' }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm">
                                                            @php
                                                                $statusClass = match ($item->status) {
                                                                    'AKTIF' => 'bg-green-100 text-green-800',
                                                                    'LULUS' => 'bg-blue-100 text-blue-800',
                                                                    'NON-AKTIF' => 'bg-red-100 text-red-800',
                                                                    'CUTI' => 'bg-yellow-100 text-yellow-800',
                                                                    'RESIGN' => 'bg-yellow-100 text-yellow-800',
                                                                    'DIKELUARKAN' => 'bg-yellow-100 text-yellow-800',
                                                                    default => 'bg-gray-100 text-gray-800'
                                                                };
                                                            @endphp
                                 <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                                {{ $item->status ?? 'Unknown' }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-center">
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
                                                                    class="inline"
                                                                    onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
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
                                        <i class="fas fa-graduation-cap text-4xl text-gray-300"></i>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Data Mahasiswa Kosong</h3>
                                            <p class="text-sm text-gray-500">Tidak ditemukan data mahasiswa yang sesuai.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($mahasiswa) && $mahasiswa->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $mahasiswa->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div id="toast"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto hide toast
            const toast = document.getElementById('toast');
            if (toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            // Spinner on Filter
            const filterForm = document.querySelector('form');
            filterForm.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                btn.disabled = true;
            });
        });
    </script>
</body>

</html>