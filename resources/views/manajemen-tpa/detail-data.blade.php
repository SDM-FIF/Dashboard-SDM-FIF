<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data TPA - Dashboard SDM FIF</title>
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

        {{-- Breadcrumbs & Header --}}
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('manajemen-tpa.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kelola Data
                    </a>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Detail Data TPA</h1>
                <p class="text-sm text-gray-500 mt-1">Lihat profile lengkap, unit lokasi kerja, dan kredensial akun TPA.</p>
            </div>
            
            @can('kelola-data-tpa.edit')
            <div>
                <a href="{{ route('manajemen-tpa.edit', $tpa->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit Data TPA</span>
                </a>
            </div>
            @endcan
        </div>

        {{-- Detail Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 hover:shadow-md transition-shadow duration-300">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Avatar & Quick Info Panel --}}
                <div class="w-full lg:w-1/4 flex flex-col items-center text-center p-6 bg-[#F8FAFC] rounded-2xl border border-gray-100">
                    <div class="relative mb-4">
                        @php
                            $words = explode(' ', $tpa->nama_lengkap);
                            $initials = '';
                            foreach (array_slice($words, 0, 2) as $w) {
                                $initials .= strtoupper(substr($w, 0, 1));
                            }
                        @endphp
                        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-[#C41E3A] to-[#FBB03B] flex items-center justify-center text-white text-3xl font-bold shadow-md">
                            {{ $initials }}
                        </div>
                    </div>
                    
                    <h2 class="text-xl font-extrabold text-gray-800">
                        {{ $tpa->nama_lengkap }}
                    </h2>
                    <p class="text-xs text-gray-400 font-semibold mt-1">NIP: {{ $tpa->nip }}</p>
                    
                    <div class="mt-6 w-full space-y-2">
                        @if($tpa->jabatan)
                        <div class="py-2 px-4 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl border border-blue-100">
                            {{ $tpa->jabatan }}
                        </div>
                        @endif
                        
                        @php
                            $statusClass = match($tpa->status_pegawai) {
                                'Pegawai Tetap', 'Tetap' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'Perbantuan LLDIKTI', 'Perbantuan' => 'bg-sky-50 text-sky-700 border-sky-100',
                                'Profesional Full Time' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'Profesional Part Time' => 'bg-amber-50 text-amber-700 border-amber-100',
                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                            };
                        @endphp
                        <div class="py-2 px-4 font-bold text-xs rounded-xl border {{ $statusClass }}">
                            {{ $tpa->status_pegawai ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- Full Information Grid --}}
                <div class="flex-1 w-full space-y-8">
                    {{-- Bio and Academic info --}}
                    <div>
                        <h3 class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-id-card"></i> Informasi Utama
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Lokasi Kerja / Unit</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">{{ $tpa->lokasi_kerja ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pendidikan Terakhir</span>
                                <p class="text-sm font-bold mt-1">
                                    <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 border border-purple-100 rounded text-xs font-extrabold uppercase">
                                        {{ $tpa->pendidikan_terakhir ?? '-' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Jabatan Kerja</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">{{ $tpa->jabatan ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">User ID</span>
                                <p class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $tpa->user_id }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Account Info --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-user-lock"></i> Kredensial Akun
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Username Akun Portal</span>
                                <p class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $tpa->user->username ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Akun</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm font-bold text-gray-800">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List Data TPA (Other TPA) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar TPA Lainnya</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Navigasi cepat antar profile TPA</p>
                </div>
                
                {{-- Quick Filter/Search --}}
                <form method="GET" action="{{ route('manajemen-tpa.show', $tpa->id) }}" class="flex flex-wrap items-center gap-3">
                    <select name="filter_status" 
                            class="px-4 py-2 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 outline-none">
                        <option value="">Semua Status</option>
                        <option value="Pegawai Tetap" {{ request('filter_status') == 'Pegawai Tetap' ? 'selected' : '' }}>Pegawai Tetap</option>
                        <option value="Perbantuan LLDIKTI" {{ request('filter_status') == 'Perbantuan LLDIKTI' ? 'selected' : '' }}>Perbantuan LLDIKTI</option>
                        <option value="Profesional Full Time" {{ request('filter_status') == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                        <option value="Profesional Part Time" {{ request('filter_status') == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                    </select>
                    
                    <select name="sort" 
                            class="px-4 py-2 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 outline-none">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold text-xs rounded-xl transition-all shadow-sm">
                        Apply
                    </button>
                    
                    <a href="{{ route('manajemen-tpa.show', $tpa->id) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-xl font-medium">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NO.</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIP</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($allTpa as $index => $tpaItem)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group {{ $tpaItem->id == $tpa->id ? 'bg-red-50/40 border-l-4 border-[#C41E3A]' : '' }}">
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                    {{ $allTpa->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold group-hover:text-[#C41E3A] transition-colors {{ $tpaItem->id == $tpa->id ? 'text-[#C41E3A]' : 'text-gray-900' }}">
                                    {{ $tpaItem->nama_lengkap }}
                                    <span class="text-[10px] text-gray-400 block mt-0.5">Username: {{ optional($tpaItem->user)->username ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                    {{ $tpaItem->nip }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-bold">
                                        {{ $tpaItem->jabatan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                    {{ $tpaItem->lokasi_kerja }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    @php
                                        $sc = match($tpaItem->status_pegawai) {
                                            'Pegawai Tetap', 'Tetap' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'Perbantuan LLDIKTI', 'Perbantuan' => 'bg-sky-50 text-sky-700 border-sky-100',
                                            'Profesional Full Time' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'Profesional Part Time' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            default => 'bg-gray-50 text-gray-700 border-gray-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded border font-bold {{ $sc }}">
                                        {{ $tpaItem->status_pegawai ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('manajemen-tpa.show', $tpaItem->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        @can('kelola-data-tpa.edit')
                                        <a href="{{ route('manajemen-tpa.edit', $tpaItem->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                           title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        @endcan
                                        @can('kelola-data-tpa.delete')
                                        <form action="{{ route('manajemen-tpa.destroy', $tpaItem->id) }}" 
                                              method="POST" 
                                              class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                                    data-nama="{{ $tpaItem->nama_lengkap }}"
                                                    data-nip="{{ $tpaItem->nip }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-users text-3xl mb-2"></i>
                                    <p class="text-xs">Tidak ada data TPA lainnya</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($allTpa->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 bg-[#F8FAFC]">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-semibold text-gray-500">
                            Menampilkan {{ $allTpa->firstItem() }} - {{ $allTpa->lastItem() }} dari {{ $allTpa->total() }} TPA
                        </div>
                        <div class="flex items-center">
                            {{ $allTpa->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');
                    const nip = this.getAttribute('data-nip');

                    Swal.fire({
                        title: 'Hapus Data TPA?',
                        html: `
                            <div class="text-left space-y-2">
                                <p class="text-gray-600">Apakah Anda yakin ingin menghapus data TPA:</p>
                                <div class="bg-red-50 border border-red-100 rounded-xl p-4 mt-3">
                                    <p class="font-bold text-[#C41E3A]">${nama}</p>
                                    <p class="text-xs text-red-600 mt-0.5">NIP: ${nip}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-3">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i>
                                    Tindakan ini permanen dan data tidak dapat dikembalikan!
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
