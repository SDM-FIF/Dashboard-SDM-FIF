<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data Dosen - Dashboard SDM</title>
    <link class="no-print" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Rounded row cards for non-flat border-separate table */
        .premium-table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .premium-row td {
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .premium-row td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }
        .premium-row td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .premium-row:hover td {
            transform: translateY(-1px);
            border-color: #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            background-color: #fafbfc;
        }
        .premium-row:hover td:first-child {
            border-left-color: #C41E3A;
            border-left-width: 3px;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row bg-[#F8FAFC] min-h-screen text-[#1E293B]">
    {{-- Sidebar Navigation --}}
    <x-navbar class="no-print" />
    
    {{-- Main Content --}}
    <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        {{-- Breadcrumbs & Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kelola Data
                    </a>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Detail Data Dosen</h1>
                <p class="text-sm text-gray-500 mt-1">Lihat profile lengkap, kepangkatan akademik, dan ijazah dosen.</p>
            </div>
            
            @can('kelola-data-dosen.edit')
            <div>
                <a href="{{ route('manajemen-dosen.edit', $dosen->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl transition-all duration-300 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit Data Dosen</span>
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
                            $words = explode(' ', $dosen->nama_lengkap);
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
                        @if($dosen->front_title){{ $dosen->front_title }} @endif{{ $dosen->nama_lengkap }}@if($dosen->back_title), {{ $dosen->back_title }}@endif
                    </h2>
                    <p class="text-xs text-gray-400 font-semibold mt-1">NIP: {{ $dosen->nip }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Kode Dosen: {{ $dosen->kode_dosen }}</p>
                    
                    <div class="mt-6 w-full space-y-2">
                        <div class="py-2 px-4 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl border border-blue-100">
                            {{ $dosen->jabatan ?? 'NJFA' }}
                        </div>
                        
                        <div class="py-2 px-4 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100">
                            Pegawai {{ $dosen->status_pegawai ?? '-' }}
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
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Lokasi Kerja / Program Studi</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">{{ $dosen->prodi->nama_prodi ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Fakultas</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">{{ $dosen->prodi->fakultas->nama_fakultas ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Kelompok Keahlian</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">{{ $dosen->kelompokKeahlian->nama_kelompok_keahlian ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Dosen</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full {{ $dosen->status_dosen == 'Aktif' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span class="text-sm font-bold text-gray-800">{{ $dosen->status_dosen ?? 'Aktif' }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Username Akun Portal</span>
                                <p class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $dosen->user->username ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pendidikan Terakhir</span>
                                <p class="text-sm font-bold text-gray-800 mt-1">
                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-100 rounded text-xs font-extrabold uppercase">
                                        {{ $dosen->pendidikan_terakhir ?? '-' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Pendidikan --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-[#C41E3A] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-graduation-cap"></i> Dokumen & Riwayat Pendidikan
                        </h3>
                        
                        @if(isset($dosen->riwayatPendidikan) && $dosen->riwayatPendidikan->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($dosen->riwayatPendidikan as $riwayat)
                                    <div class="p-5 bg-[#F8FAFC] border border-gray-100 rounded-2xl relative">
                                        <span class="absolute top-4 right-4 px-2 py-0.5 bg-gray-200 text-gray-600 rounded text-[10px] font-extrabold uppercase">
                                            {{ $riwayat->jenjang }}
                                        </span>
                                        
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $riwayat->jenjang }} Pendidikan</p>
                                        <p class="text-base font-extrabold text-gray-800 mt-2">{{ $riwayat->universitas }}</p>
                                        <p class="text-xs text-gray-500 font-medium mt-0.5">Studi: {{ $riwayat->program_studi }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1.5 font-medium">Lulus: {{ isset($riwayat->tanggal_lulus) ? \Carbon\Carbon::parse($riwayat->tanggal_lulus)->translatedFormat('d F Y') : '-' }}</p>
                                        
                                        <div class="mt-4 flex flex-wrap gap-2 no-print">
                                            @if($riwayat->ijazah)
                                                <a href="/storage/{{ $riwayat->ijazah }}" target="_blank" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-100 transition-colors">
                                                    <i class="fas fa-file-pdf"></i> Ijazah
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs rounded-lg border border-gray-100">
                                                    <i class="fas fa-file-pdf"></i> Ijazah (Kosong)
                                                </span>
                                            @endif

                                            @if($riwayat->transkrip_nilai)
                                                <a href="/storage/{{ $riwayat->transkrip_nilai }}" target="_blank" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100 transition-colors">
                                                    <i class="fas fa-file-alt"></i> Transkrip
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs rounded-lg border border-gray-100">
                                                    <i class="fas fa-file-alt"></i> Transkrip (Kosong)
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 bg-[#F8FAFC] rounded-xl border border-dashed border-gray-200 text-center text-gray-400 text-xs">
                                <i class="fas fa-university text-2xl mb-2 text-gray-300"></i>
                                <p>Belum ada riwayat pendidikan terdaftar</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        {{-- Surat Tugas & SK Section --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8 hover:shadow-md transition-shadow duration-300 p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A] flex items-center gap-2">
                        <i class="fas fa-file-signature"></i>
                        <span>Surat Tugas (ST) & Surat Keputusan (SK)</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar dokumen resmi Surat Tugas dan SK yang diterbitkan untuk dosen ini.</p>
                </div>
                @can('kelola-data-dosen.create')
                <a href="{{ route('manajemen-dosen.surat.create', ['dosen_id' => $dosen->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold text-xs rounded-xl transition-all shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Surat</span>
                </a>
                @endcan
            </div>

            @php
                $listSuratDosen = $dosen->suratDosen()->orderBy('tanggal_surat', 'desc')->get();
            @endphp

            @if($listSuratDosen->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-y border-gray-100 text-gray-600">
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-28">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Nomor & Judul Surat</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal Terbit</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($listSuratDosen as $st)
                        <tr class="hover:bg-[#F8FAFC] transition-colors text-sm">
                            <td class="px-4 py-3">
                                @if($st->jenis_surat == 'Surat Tugas')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded font-bold text-xs bg-blue-50 text-blue-700 border border-blue-100">ST</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded font-bold text-xs bg-purple-50 text-purple-700 border border-purple-100">SK</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('manajemen-dosen.surat.show', $st->id) }}" class="font-bold text-gray-800 hover:text-[#C41E3A] transition-colors block text-xs">
                                    {{ $st->nomor_surat }}
                                </a>
                                <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $st->judul_surat }}</p>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-700 font-semibold whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($st->tanggal_surat)->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium text-[11px]">{{ $st->kategori }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('manajemen-dosen.surat.show', $st->id) }}" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('manajemen-dosen.surat.download', $st->id) }}" class="p-1.5 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded" title="Unduh">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 bg-[#F8FAFC] rounded-xl border border-dashed border-gray-200 text-center text-gray-400 text-xs">
                <i class="fas fa-folder-open text-3xl mb-2 text-gray-300"></i>
                <p class="font-semibold">Belum ada Surat Tugas atau SK yang didokumentasikan untuk dosen ini.</p>
            </div>
            @endif
        </div>

        {{-- List Data Dosen (Other Dosen) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Dosen Lainnya</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Navigasi cepat antar profile dosen</p>
                </div>
                
                {{-- Quick Filter/Search --}}
                <form method="GET" action="{{ route('manajemen-dosen.show', $dosen->id) }}" class="flex flex-wrap items-center gap-3">
                    <select name="filter_status" 
                            class="px-4 py-2 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 outline-none">
                        <option value="">Semua Status</option>
                        <option value="Tetap" {{ request('filter_status') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Perbantuan" {{ request('filter_status') == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
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
                    
                    <a href="{{ route('manajemen-dosen.show', $dosen->id) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-xl font-medium">
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
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">JFA</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($allDosen as $index => $dosenItem)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group {{ $dosenItem->id == $dosen->id ? 'bg-red-50/40 border-l-4 border-[#C41E3A]' : '' }}">
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                    {{ $allDosen->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold group-hover:text-[#C41E3A] transition-colors {{ $dosenItem->id == $dosen->id ? 'text-[#C41E3A]' : 'text-gray-900' }}">
                                        @if($dosenItem->front_title){{ $dosenItem->front_title }} @endif{{ $dosenItem->nama_lengkap }}@if($dosenItem->back_title), {{ $dosenItem->back_title }}@endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">Kode: {{ $dosenItem->kode_dosen }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                    {{ $dosenItem->nip }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-bold">
                                        {{ $dosenItem->jabatan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                    {{ $dosenItem->prodi->nama_prodi ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    @php
                                        $sc = match($dosenItem->status_pegawai) {
                                            'Tetap' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'Perbantuan' => 'bg-sky-50 text-sky-700 border-sky-100',
                                            'Profesional Full Time' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'Profesional Part Time' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            default => 'bg-gray-50 text-gray-700 border-gray-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded border font-bold {{ $sc }}">
                                        {{ $dosenItem->status_pegawai ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-3">
                                        @can('kelola-data-dosen.detail')
                                        <a href="{{ route('manajemen-dosen.show', $dosenItem->id) }}" 
                                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @can('kelola-data-dosen.edit')
                                        <a href="{{ route('manajemen-dosen.edit', $dosenItem->id) }}" 
                                           class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @endcan
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-xs">
                                    Tidak ada data dosen lainnya
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($allDosen->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-500">
                            Menampilkan {{ $allDosen->firstItem() }} - {{ $allDosen->lastItem() }} dari {{ $allDosen->total() }} Dosen
                        </p>
                        <div class="flex items-center">
                            {{ $allDosen->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Highlight row center scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeRow = document.querySelector('.bg-red-50\\/40');
            if (activeRow) {
                setTimeout(() => {
                    activeRow.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }, 400);
            }
        });
    </script>
</body>
</html>