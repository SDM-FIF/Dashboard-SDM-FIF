<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data Mahasiswa - Dashboard SDM FIF</title>
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
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Detail Data Mahasiswa</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium font-medium">Informasi akademik lengkap serta status pendaftaran mahasiswa.</p>
            </div>
            <a href="{{ route('mahasiswa.kelola-data') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 hover:shadow-md transition-shadow duration-300">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 pb-2 border-b border-gray-100">
                <i class="fas fa-user-graduate text-[#C41E3A]"></i>
                <span>Profil Akademik Mahasiswa</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Column 1 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">NIM</span>
                        <span class="text-base font-bold text-gray-800 mt-1">{{ $mahasiswa->nim }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Lengkap</span>
                        <span class="text-base font-bold text-gray-800 mt-1">{{ $mahasiswa->nama_lengkap }}</span>
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Program Studi</span>
                        <span class="text-base font-semibold text-gray-700 mt-1">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col items-start">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Akademik</span>
                        @php
                            $statusCurrent = strtolower($mahasiswa->status);
                            $statusClass = match ($statusCurrent) {
                                'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'cuti' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'lulus' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'nonaktif', 'resign', 'dikeluarkan' => 'bg-rose-50 text-rose-700 border-rose-100',
                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg border font-bold text-xs {{ $statusClass }}">
                            {{ ucfirst($mahasiswa->status) }}
                        </span>
                    </div>
                </div>

                {{-- Column 3 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Angkatan</span>
                        <span class="text-base font-bold text-gray-800 mt-1">{{ $mahasiswa->angkatan ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col items-start">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi Cepat</span>
                        <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-800 mt-1 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                            <span>Ubah Data</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overall List Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            {{-- Table Control Bar --}}
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-users text-[#C41E3A]"></i>
                        <span>Daftar Mahasiswa Keseluruhan</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Total terdaftar: {{ $allMahasiswa->total() }} Mahasiswa</p>
                </div>

                {{-- Filter Panel --}}
                <form method="GET" action="{{ route('mahasiswa.show', $mahasiswa->id) }}" class="flex items-center gap-3 flex-wrap">
                    <select name="filter_status"
                            class="px-4 py-2 bg-[#F8FAFC] border border-gray-200 rounded-xl text-gray-700 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        <option value="">Semua Status</option>
                        <option value="AKTIF" {{ request('filter_status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="CUTI" {{ request('filter_status') == 'CUTI' ? 'selected' : '' }}>Cuti</option>
                    </select>

                    <a href="{{ route('mahasiswa.show', $mahasiswa->id) }}" 
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-xs transition-all">
                        Reset
                    </a>

                    <button type="submit"
                            class="px-5 py-2 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-bold rounded-xl text-xs transition-all duration-300 shadow-sm">
                        <i class="fas fa-filter mr-1.5"></i>Terapkan
                    </button>
                </form>
            </div>

            {{-- Table View --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-16">No.</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($allMahasiswa as $index => $item)
                            <tr class="{{ $item->id == $mahasiswa->id ? 'bg-blue-50/70 border-l-4 border-blue-500' : 'hover:bg-[#F8FAFC] transition-colors duration-150' }}">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-500">{{ $allMahasiswa->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $item->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-600">{{ $item->nim }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $sc = match (strtoupper($item->status)) {
                                            'AKTIF' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'CUTI' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'LULUS' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            default => 'bg-gray-50 text-gray-700 border-gray-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-bold text-xs {{ $sc }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <div class="flex items-center justify-center gap-2.5">
                                        <a href="{{ route('mahasiswa.show', $item->id) }}"
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all"
                                           title="Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('mahasiswa.edit', $item->id) }}"
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST"
                                              class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                                    data-nama="{{ $item->nama_lengkap }}" 
                                                    data-nim="{{ $item->nim }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $allMahasiswa->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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