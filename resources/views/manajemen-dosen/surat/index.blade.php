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
        /* Custom sleek scrollbar for lecturer letters list */
        .surat-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .surat-scroll::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 8px;
        }
        .surat-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 8px;
        }
        .surat-scroll::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
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

        @php
            $activeTab = request('tab', 'dokumen');
        @endphp

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-2 mb-6 border-b border-gray-200">
            <button onclick="switchTab('dokumen')"
                class="px-5 py-3 border-b-2 font-bold text-sm transition-all duration-200 focus:outline-none {{ $activeTab === 'dokumen' ? 'border-[#C41E3A] text-[#C41E3A]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-file-alt mr-2"></i> Daftar Dokumen
            </button>
            <button onclick="switchTab('dosen')"
                class="px-5 py-3 border-b-2 font-bold text-sm transition-all duration-200 focus:outline-none {{ $activeTab === 'dosen' ? 'border-[#C41E3A] text-[#C41E3A]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-users mr-2"></i> Daftar Dosen & Surat
            </button>
            <button onclick="switchTab('tpa')"
                class="px-5 py-3 border-b-2 font-bold text-sm transition-all duration-200 focus:outline-none {{ $activeTab === 'tpa' ? 'border-[#C41E3A] text-[#C41E3A]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-user-gear mr-2"></i> Daftar TPA & Surat
            </button>
        </div>

        @if($activeTab === 'dokumen')
        {{-- Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Surat Tugas & SK</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $suratList->total() }} dokumen surat</p>
                </div>

                {{-- Export Button --}}
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleExportDropdown(event)" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-[#F8FAFC] border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-download text-gray-500"></i>
                        <span>Export Data</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1 text-gray-400"></i>
                    </button>

                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('manajemen-dosen.surat.export-excel', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('manajemen-dosen.surat.export-pdf', request()->query()) }}"
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
        @elseif($activeTab === 'dosen')
        {{-- Table Section Card: Tab Daftar Dosen & Surat --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Dosen & Surat</h2>
                <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $dosenSuratList->total() }} dosen penerima surat</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-80">Dosen</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Surat Tugas & SK yang Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dosenSuratList as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-3">
                                    @php
                                        $words = explode(' ', $d->nama_lengkap);
                                        $initials = '';
                                        foreach ($words as $w) {
                                            $initials .= strtoupper(substr($w, 0, 1));
                                            if (strlen($initials) >= 2) break;
                                        }
                                    @endphp
                                    <div class="w-10 h-10 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-[#C41E3A] font-bold text-sm">
                                        {{ $initials ?: 'D' }}
                                    </div>
                                    <div>
                                        <a href="{{ route('manajemen-dosen.show', $d->id) }}" class="text-sm font-bold text-gray-800 hover:text-[#C41E3A] transition-colors">
                                            {{ $d->nama_lengkap }}
                                        </a>
                                        <div class="text-[11px] text-gray-500 font-semibold mt-0.5">
                                            NIP: {{ $d->nip ?? '-' }} | Kode: {{ $d->kode_dosen ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">
                                            Prodi: {{ $d->prodi->nama_prodi ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <button class="lecturer-letters-btn inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-[#C41E3A] text-[#C41E3A] hover:text-white font-bold rounded-xl border border-red-100 transition-all duration-200 text-xs shadow-sm hover:shadow"
                                        data-lecturer="{{ json_encode([
                                            'dosen_name' => $d->nama_lengkap,
                                            'letters' => $d->suratDosen->map(function($s) {
                                                return [
                                                    'jenis_surat' => $s->jenis_surat,
                                                    'nomor_surat' => $s->nomor_surat,
                                                    'judul_surat' => $s->judul_surat,
                                                    'tanggal_surat' => \Carbon\Carbon::parse($s->tanggal_surat)->locale('id')->translatedFormat('d F Y'),
                                                    'berlaku_mulai' => $s->berlaku_mulai ? \Carbon\Carbon::parse($s->berlaku_mulai)->locale('id')->translatedFormat('d F Y') : null,
                                                    'berlaku_selesai' => $s->berlaku_selesai ? \Carbon\Carbon::parse($s->berlaku_selesai)->locale('id')->translatedFormat('d F Y') : null,
                                                    'kategori' => $s->kategori,
                                                    'keterangan' => $s->keterangan ?? '-',
                                                    'jabatan' => $s->pivot->jabatan ?? '-',
                                                    'file_url' => Storage::url($s->file_surat),
                                                    'detail_url' => route('manajemen-dosen.surat.show', $s->id)
                                                ];
                                            })
                                        ]) }}">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    <span>Lihat Daftar Surat ({{ $d->suratDosen->count() }})</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3"></i>
                                <p class="text-sm font-semibold">Belum ada dosen dengan Surat Tugas atau SK.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($dosenSuratList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $dosenSuratList->links() }}
            </div>
            @endif
        </div>
        @else
        {{-- Table Section Card: Tab Daftar TPA & Surat --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-[#C41E3A]">Daftar TPA & Surat</h2>
                <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $tpaSuratList->total() }} TPA penerima surat</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-80">Tenaga Kependidikan (TPA)</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Surat Tugas & SK yang Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tpaSuratList as $t)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-3">
                                    @php
                                        $words = explode(' ', $t->nama_lengkap);
                                        $initials = '';
                                        foreach ($words as $w) {
                                            $initials .= strtoupper(substr($w, 0, 1));
                                            if (strlen($initials) >= 2) break;
                                        }
                                    @endphp
                                    <div class="w-10 h-10 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-[#C41E3A] font-bold text-sm">
                                        {{ $initials ?: 'T' }}
                                    </div>
                                    <div>
                                        <a href="{{ route('manajemen-tpa.show', $t->id) }}" class="text-sm font-bold text-gray-800 hover:text-[#C41E3A] transition-colors">
                                            {{ $t->nama_lengkap }}
                                        </a>
                                        <div class="text-[11px] text-gray-500 font-semibold mt-0.5">
                                            NIP: {{ $t->nip ?? '-' }} | Jabatan: {{ $t->jabatan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <button class="lecturer-letters-btn inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-[#C41E3A] text-[#C41E3A] hover:text-white font-bold rounded-xl border border-red-100 transition-all duration-200 text-xs shadow-sm hover:shadow"
                                        data-lecturer="{{ json_encode([
                                            'dosen_name' => $t->nama_lengkap,
                                            'letters' => $t->suratDosen->map(function($s) {
                                                return [
                                                    'jenis_surat' => $s->jenis_surat,
                                                    'nomor_surat' => $s->nomor_surat,
                                                    'judul_surat' => $s->judul_surat,
                                                    'tanggal_surat' => \Carbon\Carbon::parse($s->tanggal_surat)->locale('id')->translatedFormat('d F Y'),
                                                    'berlaku_mulai' => $s->berlaku_mulai ? \Carbon\Carbon::parse($s->berlaku_mulai)->locale('id')->translatedFormat('d F Y') : null,
                                                    'berlaku_selesai' => $s->berlaku_selesai ? \Carbon\Carbon::parse($s->berlaku_selesai)->locale('id')->translatedFormat('d F Y') : null,
                                                    'kategori' => $s->kategori,
                                                    'keterangan' => $s->keterangan ?? '-',
                                                    'jabatan' => $s->pivot->jabatan ?? '-',
                                                    'file_url' => Storage::url($s->file_surat),
                                                    'detail_url' => route('manajemen-dosen.surat.show', $s->id)
                                                ];
                                            })
                                        ]) }}">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    <span>Lihat Daftar Surat ({{ $t->suratDosen->count() }})</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-folder-open text-4xl mb-3"></i>
                                <p class="text-sm font-semibold">Belum ada TPA dengan Surat Tugas atau SK.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($tpaSuratList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $tpaSuratList->links() }}
            </div>
            @endif
        </div>
        @endif
    </main>

    <script>
        function switchTab(tab) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            window.location.href = url.toString();
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.lecturer-letters-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const dataStr = this.getAttribute('data-lecturer');
                    if (dataStr) {
                        try {
                            const data = JSON.parse(dataStr);
                            showLecturerLettersModal(data);
                        } catch (e) {
                            console.error('Error parsing data-lecturer:', e);
                        }
                    }
                });
            });
        });

        function showLecturerLettersModal(data) {
            let listHTML = '';
            if (data.letters.length === 0) {
                listHTML = '<div class="text-center text-xs text-gray-400 py-6">Belum ada surat yang diterbitkan.</div>';
            } else {
                listHTML = `
                    <div class="overflow-x-auto max-h-[350px] overflow-y-auto pr-1 surat-scroll mt-2 border border-slate-100 rounded-xl text-left">
                        <table class="w-full text-left border-collapse text-xs table-fixed">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-150 sticky top-0 z-10 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                    <th class="px-2 py-3 text-center w-2/12" style="width: 12%;">Jenis</th>
                                    <th class="px-4 py-3 text-left w-5/12" style="width: 38%;">Judul & Nomor Surat</th>
                                    <th class="px-4 py-3 text-left w-3/12" style="width: 25%;">Jabatan / Kedudukan</th>
                                    <th class="px-4 py-3 text-center w-3/12" style="width: 25%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                ${data.letters.map(s => `
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-2 py-3 text-center align-middle" style="width: 12%;">
                                            <span class="inline-flex items-center justify-center w-9 h-5 rounded text-[9px] font-bold ${s.jenis_surat === 'Surat Tugas' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100'}">
                                                ${s.jenis_surat === 'Surat Tugas' ? 'ST' : 'SK'}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-middle whitespace-normal break-words" style="width: 38%;">
                                            <div class="font-bold text-slate-800 leading-snug">${s.judul_surat}</div>
                                            <div class="text-[10px] text-gray-400 mt-1 font-medium font-mono">No: ${s.nomor_surat} | Tgl: ${s.tanggal_surat}</div>
                                        </td>
                                        <td class="px-4 py-3 align-middle whitespace-normal break-words" style="width: 25%;">
                                            <span class="font-bold text-[#C41E3A] text-[11px]">${s.jabatan !== '-' ? s.jabatan : '<span class="text-gray-400 font-normal italic">-</span>'}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center align-middle" style="width: 25%;">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="${s.detail_url}" class="inline-flex items-center justify-center w-14 h-7 bg-red-50 hover:bg-[#C41E3A] text-[#C41E3A] hover:text-white rounded-lg text-[10px] font-bold border border-red-100 transition-colors">
                                                    Detail
                                                </a>
                                                <a href="${s.file_url}" target="_blank" class="inline-flex items-center justify-center w-14 h-7 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg text-[10px] font-bold border border-emerald-100 transition-colors">
                                                    Unduh
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            Swal.fire({
                title: `<span class="text-slate-800 font-bold text-base md:text-lg">Surat Tugas & SK: ${data.dosen_name}</span>`,
                html: listHTML,
                confirmButtonColor: '#C41E3A',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-2xl w-full max-w-3xl p-4 md:p-6',
                    confirmButton: 'text-xs px-4 py-2.5 rounded-xl font-bold focus:ring-0 border-0 shadow-sm'
                }
            });
        }
    </script>
</body>

</html>
