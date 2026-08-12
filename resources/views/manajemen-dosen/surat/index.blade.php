<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Surat Tugas & SK Dosen - Dashboard SDM FIF</title>
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

        {{-- Flash Alerts --}}
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Surat Tugas & SK Dosen</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola dokumen Surat Tugas (ST) dan Surat Keputusan (SK) resmi untuk dosen.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('kelola-data-dosen.create')
                <a href="{{ route('manajemen-dosen.surat.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Surat Baru</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Filter & Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
            <form method="GET" action="{{ route('manajemen-dosen.surat.index') }}" class="space-y-4">
                {{-- Quick Filter Pills --}}
                <div class="flex flex-wrap items-center gap-2 pb-4 border-b border-gray-100">
                    <a href="{{ route('manajemen-dosen.surat.index', array_merge(request()->except('jenis_surat'), ['jenis_surat' => ''])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ request('jenis_surat') == '' ? 'bg-[#C41E3A] text-white border-[#C41E3A] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        Semua Jenis Surat
                    </a>
                    <a href="{{ route('manajemen-dosen.surat.index', array_merge(request()->except('jenis_surat'), ['jenis_surat' => 'Surat Tugas'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ request('jenis_surat') == 'Surat Tugas' ? 'bg-[#C41E3A] text-white border-[#C41E3A] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <i class="fas fa-file-contract mr-1"></i> Surat Tugas (ST)
                    </a>
                    <a href="{{ route('manajemen-dosen.surat.index', array_merge(request()->except('jenis_surat'), ['jenis_surat' => 'Surat Keputusan'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ request('jenis_surat') == 'Surat Keputusan' ? 'bg-[#C41E3A] text-white border-[#C41E3A] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <i class="fas fa-[#certificate] fa-certificate mr-1"></i> Surat Keputusan (SK)
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Search Input --}}
                    <div class="flex flex-col gap-1.5">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, judul, atau nama dosen..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Dosen Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <select name="dosen_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ request('dosen_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_lengkap }} ({{ $d->kode_dosen ?? $d->nip }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori Filter --}}
                    <div class="flex flex-col gap-1.5">
                        <select name="kategori" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route('manajemen-dosen.surat.index') }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-4 py-3 rounded-xl flex items-center justify-center transition-all text-sm">
                            Reset
                        </a>
                        <button type="submit"
                            class="flex-1 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-4 py-3 rounded-xl flex items-center justify-center space-x-2 transition-all duration-300 shadow-sm text-sm">
                            <i class="fas fa-search"></i>
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Surat Tugas & SK</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $suratList->total() }} dokumen surat</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nomor & Judul Surat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Dosen Penerima</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal & Masa Berlaku</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($suratList as $item)
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            {{-- Jenis --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->jenis_surat == 'Surat Tugas')
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg border font-bold text-xs bg-blue-50 text-blue-700 border-blue-100">
                                        <i class="fas fa-file-contract mr-1.5 text-xs"></i> ST
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg border font-bold text-xs bg-purple-50 text-purple-700 border-purple-100">
                                        <i class="fas fa-award mr-1.5 text-xs"></i> SK
                                    </span>
                                @endif
                            </td>

                            {{-- Nomor & Judul Surat --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('manajemen-dosen.surat.show', $item->id) }}" class="font-extrabold text-gray-800 hover:text-[#C41E3A] transition-colors text-sm block">
                                    {{ $item->nomor_surat }}
                                </a>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1 font-medium">{{ $item->judul_surat }}</p>
                            </td>

                            {{-- Dosen Penerima --}}
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                @php
                                    $recipients = $item->dosenList->count() > 0 ? $item->dosenList : collect([$item->dosen])->filter();
                                @endphp
                                @if($recipients->count() > 0)
                                    @if($recipients->count() == 1)
                                        <a href="{{ route('manajemen-dosen.show', $recipients->first()->id) }}" class="hover:text-[#C41E3A] transition-colors font-bold block text-xs">
                                            {{ $recipients->first()->nama_lengkap }}
                                        </a>
                                        <span class="text-[11px] text-gray-400 font-normal">NIP: {{ $recipients->first()->nip ?? '-' }}</span>
                                    @else
                                        <div class="flex flex-col gap-1 max-w-xs">
                                            <span class="font-bold text-xs text-gray-800">{{ $recipients->count() }} Dosen Penerima:</span>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($recipients->take(2) as $d)
                                                <a href="{{ route('manajemen-dosen.show', $d->id) }}" class="inline-block px-2 py-0.5 bg-gray-100 hover:bg-red-50 hover:text-[#C41E3A] text-gray-700 rounded text-[11px] font-medium transition-colors">
                                                    {{ $d->nama_lengkap }}
                                                </a>
                                                @endforeach
                                                @if($recipients->count() > 2)
                                                <span class="inline-block px-2 py-0.5 bg-red-50 text-[#C41E3A] rounded text-[11px] font-bold">
                                                    +{{ $recipients->count() - 2 }} lainnya
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Tanggal & Masa Berlaku --}}
                            <td class="px-6 py-4 text-xs font-semibold text-gray-700 whitespace-nowrap">
                                <div>
                                    <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_surat)->locale('id')->translatedFormat('d M Y') }}
                                </div>
                                @if($item->berlaku_selesai)
                                    <div class="text-[11px] text-gray-400 font-normal mt-0.5">
                                        s/d {{ \Carbon\Carbon::parse($item->berlaku_selesai)->locale('id')->translatedFormat('d M Y') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Kategori --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border font-semibold text-xs bg-slate-50 text-slate-700 border-slate-200">
                                    {{ $item->kategori }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Detail / Viewer --}}
                                    <a href="{{ route('manajemen-dosen.surat.show', $item->id) }}"
                                        class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all"
                                        title="Pratinjau Dokumen">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Download --}}
                                    <a href="{{ route('manajemen-dosen.surat.download', $item->id) }}"
                                        class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"
                                        title="Unduh Berkas">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    @can('kelola-data-dosen.edit')
                                    <a href="{{ route('manajemen-dosen.surat.edit', $item->id) }}"
                                        class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"
                                        title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('kelola-data-dosen.delete')
                                    <button type="button"
                                        onclick="confirmDelete('{{ $item->id }}', '{{ addslashes($item->nomor_surat) }}')"
                                        class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('manajemen-dosen.surat.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3"></i>
                                <p class="text-sm font-semibold">Belum ada Surat Tugas atau SK dosen yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($suratList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $suratList->links() }}
            </div>
            @endif
        </div>
    </main>

    <script>
        function confirmDelete(id, nomor) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus surat nomor "${nomor}"? Dokumen berkas fisik juga akan dihapus.`,
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
