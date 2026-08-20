<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Mahasiswa Kompetisi - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Mahasiswa Kompetisi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Manajemen hubungan mahasiswa dengan kompetisi yang diikuti.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('kelola-data-mahasiswa.create')
                <a href="{{ route('kompetisi.import.view') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-[#C41E3A] text-[#C41E3A] hover:bg-red-50 font-semibold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-file-import"></i>
                    <span>Import Data</span>
                </a>

                <a href="{{ route('mahasiswa.kompetisi.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Mahasiswa Kompetisi</span>
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
                    <p class="text-xs text-gray-500 font-medium">Saring partisipasi kompetisi berdasarkan kriteria</p>
                </div>
            </div>

            <form method="GET" action="{{ route('mahasiswa.kompetisi.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Program Studi Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Studi</label>
                        <select name="prodi_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Semua Prodi</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenis Kompetisi Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Kompetisi</label>
                        <select name="jenis"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pencarian --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Mahasiswa, NIM, Kompetisi..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-sm text-sm">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>

                    <a href="{{ route('mahasiswa.kompetisi.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-300 shadow-sm text-sm">
                        <i class="fas fa-times"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Mahasiswa Kompetisi</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $mahasiswaKompetisi->total() }} entri hubungan kompetisi</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleExportDropdown(event)" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('mahasiswa.kompetisi.export-excel', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('mahasiswa.kompetisi.export-pdf', request()->query()) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 transition-colors border-t border-gray-50">
                            <i class="fas fa-file-pdf text-[#C41E3A] text-lg"></i>
                            <span>Export PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Kompetisi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Juara / Penghargaan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Sertifikat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tingkat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            @can('kelola-data-mahasiswa.delete')
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-24">Aksi</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @if($mahasiswaKompetisi->count() > 0)
                            @foreach($mahasiswaKompetisi as $item)
                                <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                                    <td class="px-6 py-4 text-sm text-gray-500 font-semibold">
                                        {{ $item->mahasiswa->nim ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">
                                        {{ $item->mahasiswa->nama_lengkap ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        {{ $item->mahasiswa->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $item->kompetisi->nama_kompetisi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        @if($item->juara)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs bg-amber-50 text-amber-700 border-amber-100 shadow-sm">
                                                <i class="fas fa-trophy mr-1 text-amber-500"></i> {{ $item->juara }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic font-normal">Peserta / Partisipan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        @if($item->sertifikat_file)
                                            <a href="{{ Storage::url($item->sertifikat_file) }}" target="_blank"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                               title="Lihat Sertifikat">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ ucfirst($item->kompetisi->jenis ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ ucfirst($item->kompetisi->tingkat_kompetisi ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        {{ isset($item->kompetisi->tanggal_kompetisi) ? $item->kompetisi->tanggal_kompetisi->format('d-m-Y') : '-' }}
                                    </td>
                                    @can('kelola-data-mahasiswa.delete')
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <div class="flex items-center justify-center gap-2.5">
                                                <form action="{{ route('mahasiswa.kompetisi.destroy', $item->id) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                                        data-mahasiswa="{{ $item->mahasiswa->nama_lengkap ?? '' }}"
                                                        data-kompetisi="{{ $item->kompetisi->nama_kompetisi ?? '' }}">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-trophy text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada data kompetisi mahasiswa ditemukan</p>
                                        <p class="text-xs text-gray-400 max-w-xs">Silakan sesuaikan filter pencarian atau tambahkan partisipasi kompetisi baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($mahasiswaKompetisi) && $mahasiswaKompetisi->total() > 0)
                <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <p class="text-xs font-semibold text-gray-500">
                            Menampilkan {{ $mahasiswaKompetisi->firstItem() }} - {{ $mahasiswaKompetisi->lastItem() }} dari {{ $mahasiswaKompetisi->total() }} Entri
                        </p>
                        <div>
                            {{ $mahasiswaKompetisi->appends(request()->query())->links('components.custom-pagination') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div id="toast"
                class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
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
                            <p class="text-gray-600 text-sm">Anda akan menghapus hubungan partisipasi kompetisi:</p>
                            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mt-3">
                                <p class="font-bold text-red-800 text-base">${mahasiswa}</p>
                                <p class="text-xs text-red-600 font-medium mt-1">Kompetisi: ${kompetisi}</p>
                            </div>
                            <p class="text-xs text-red-500 mt-3 font-semibold">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Tindakan ini tidak dapat dikembalikan!
                            </p>
                        </div>
                        `,
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

            // Toast timeout
            const toast = document.getElementById('toast');
            if (toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
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
