<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Mahasiswa Kompetisi - Dashboard SDM</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Mahasiswa Kompetisi</h1>
            <p class="text-gray-600">Manajemen hubungan mahasiswa dengan kompetisi yang diikuti</p>
        </div>

        {{-- Filter Section Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('mahasiswa.kompetisi.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Program Studi Filter --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Program Studi</label>
                        <select name="prodi_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Prodi</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenis Kompetisi Filter --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Jenis Kompetisi</label>
                        <select name="jenis"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pencarian --}}
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Mahasiswa, NIM, Kompetisi..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>

                    <a href="{{ route('mahasiswa.kompetisi.index') }}"
                        class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-visible">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Mahasiswa Kompetisi</h2>
                    <div class="text-sm text-gray-600">
                        Total: <strong>{{ $mahasiswaKompetisi->total() }}</strong> Entri
                    </div>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex items-center justify-between">
                    @can('kelola-data-mahasiswa.create')
                        <a href="{{ route('mahasiswa.kompetisi.create') }}"
                            class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa Kompetisi
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Program Studi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Kompetisi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Juara / Penghargaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tingkat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                            @can('kelola-data-mahasiswa.delete')
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-24">Aksi</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($mahasiswaKompetisi->count() > 0)
                            @foreach($mahasiswaKompetisi as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                        {{ $item->mahasiswa->nim ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                        {{ $item->mahasiswa->nama_lengkap ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ $item->mahasiswa->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                        {{ $item->kompetisi->nama_kompetisi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                        @if($item->juara)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 shadow-sm">
                                                <i class="fas fa-trophy mr-1 text-amber-500"></i> {{ $item->juara }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Peserta / Partisipan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ ucfirst($item->kompetisi->jenis ?? '-') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ ucfirst($item->kompetisi->tingkat_kompetisi ?? '-') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        {{ isset($item->kompetisi->tanggal_kompetisi) ? $item->kompetisi->tanggal_kompetisi->format('d-m-Y') : '-' }}
                                    </td>
                                    @can('kelola-data-mahasiswa.delete')
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                            <form action="{{ route('mahasiswa.kompetisi.destroy', $item->id) }}" method="POST"
                                                class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-800 delete-btn"
                                                    data-mahasiswa="{{ $item->mahasiswa->nama_lengkap ?? '' }}"
                                                    data-kompetisi="{{ $item->kompetisi->nama_kompetisi ?? '' }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-trophy text-4xl mb-2"></i>
                                    <p>Tidak ada data kompetisi mahasiswa</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($mahasiswaKompetisi->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $mahasiswaKompetisi->links() }}
                </div>
            @endif
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div id="toast"
                class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert delete confirmation
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    const mahasiswa = this.getAttribute('data-mahasiswa');
                    const kompetisi = this.getAttribute('data-kompetisi');

                    if (typeof Swal === 'undefined') {
                        if(confirm(`Yakin ingin menghapus partisipasi ${mahasiswa} pada kompetisi ${kompetisi}?`)) {
                            form.submit();
                        }
                        return;
                    }

                    Swal.fire({
                        title: 'Hapus Partisipasi Kompetisi?',
                        html: `
                        <div class="text-left space-y-2">
                            <p class="text-gray-600">Anda akan menghapus partisipasi:</p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="font-semibold text-red-800">${mahasiswa}</p>
                                <p class="text-xs text-red-600 mt-1">Kompetisi: ${kompetisi}</p>
                            </div>
                            <p class="text-sm text-red-600 mt-3">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Tindakan ini tidak dapat dikembalikan!
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

            // Toast timeout
            const toast = document.getElementById('toast');
            if (toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        });
    </script>
</body>

</html>
