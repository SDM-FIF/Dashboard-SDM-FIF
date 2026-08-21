<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail {{ $surat->jenis_surat }} - Dashboard SDM FIF</title>
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
                <div class="flex items-center gap-2 mb-2">
                    @if($surat->jenis_surat == 'Surat Tugas')
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-bold">Surat Tugas (ST)</span>
                    @else
                        <span class="px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-bold">Surat Keputusan (SK)</span>
                    @endif
                    <span class="px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold">{{ $surat->kategori }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">{{ $surat->nomor_surat }}</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">{{ $surat->judul_surat }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('manajemen-dosen.surat.download', $surat->id) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                    <i class="fas fa-download"></i>
                    <span>Unduh Dokumen</span>
                </a>
                @can('kelola-data-dosen.edit')
                <a href="{{ route('manajemen-dosen.surat.edit', $surat->id) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                @endcan
                <a href="{{ route('manajemen-dosen.surat.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- Metadata Card & Document Preview Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Information Panel --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 hover:shadow-md transition-shadow">
                    <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#C41E3A]"></i>
                        <span>Informasi Dokumen</span>
                    </h3>

                    {{-- Dosen Penerima --}}
                    <div class="flex flex-col gap-2">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Dosen Penerima</span>
                        @php
                            $recipients = $surat->dosenList->count() > 0 ? $surat->dosenList : collect([$surat->dosen])->filter();
                        @endphp
                        @forelse($recipients as $d)
                        <div class="p-3 bg-[#F8FAFC] rounded-xl border border-gray-100 flex flex-col gap-0.5">
                            <a href="{{ route('manajemen-dosen.show', $d->id) }}" class="text-sm font-bold text-gray-800 hover:text-[#C41E3A] transition-colors">
                                {{ $d->nama_lengkap }}
                            </a>
                            <span class="text-xs text-gray-500">NIP: {{ $d->nip ?? '-' }} | Kode: {{ $d->kode_dosen ?? '-' }}</span>
                            <span class="text-xs text-gray-500">Prodi: {{ $d->prodi->nama_prodi ?? '-' }}</span>
                            @if(isset($d->pivot->jabatan) && $d->pivot->jabatan)
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="px-2 py-0.5 bg-red-50 text-[10px] font-bold text-[#C41E3A] rounded border border-red-100 inline-block w-fit">
                                    {{ $d->pivot->jabatan }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @empty
                        <span class="text-sm text-gray-500">-</span>
                        @endforelse
                    </div>

                    {{-- Tanggal Terbit --}}
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tanggal Terbit Surat</span>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($surat->tanggal_surat)->locale('id')->translatedFormat('l, d F Y') }}
                        </span>
                    </div>

                    {{-- Masa Berlaku --}}
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Masa Berlaku</span>
                        @if($surat->berlaku_mulai || $surat->berlaku_selesai)
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $surat->berlaku_mulai ? \Carbon\Carbon::parse($surat->berlaku_mulai)->locale('id')->translatedFormat('d M Y') : 'Awal' }}
                            s/d
                            {{ $surat->berlaku_selesai ? \Carbon\Carbon::parse($surat->berlaku_selesai)->locale('id')->translatedFormat('d M Y') : 'Selesai' }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400 italic">Tidak ditentukan</span>
                        @endif
                    </div>

                    {{-- Keterangan --}}
                    @if($surat->keterangan)
                    <div class="flex flex-col gap-1 border-t border-gray-100 pt-3">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Catatan / Keterangan</span>
                        <p class="text-xs text-gray-600 leading-relaxed font-medium">{{ $surat->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Document Preview Panel --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[600px] flex flex-col hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-file-pdf text-[#C41E3A]"></i>
                            <span>Pratinjau Dokumen Berkas</span>
                        </h3>
                        <a href="{{ Storage::url($surat->file_surat) }}" target="_blank" class="text-xs text-[#C41E3A] font-bold hover:underline">
                            Buka di Tab Baru <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                        </a>
                    </div>

                    @php
                        $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
                    @endphp

                    @if($ext === 'pdf')
                    <div class="flex-1 w-full rounded-xl overflow-hidden border border-gray-200 bg-slate-100 min-h-[550px]">
                        <iframe src="{{ Storage::url($surat->file_surat) }}" class="w-full h-full min-h-[550px]" frameborder="0"></iframe>
                    </div>
                    @else
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <i class="fas fa-file-word text-blue-600 text-6xl mb-4"></i>
                        <h4 class="text-lg font-bold text-gray-800 mb-1">Dokumen {{ strtoupper($ext) }}</h4>
                        <p class="text-xs text-gray-500 max-w-sm mb-6">Pratinjau langsung hanya didukung untuk berkas PDF. Silakan unduh dokumen untuk membuka file Microsoft Word.</p>
                        <a href="{{ route('manajemen-dosen.surat.download', $surat->id) }}"
                            class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm shadow-md transition-all">
                            <i class="fas fa-download mr-2"></i> Unduh Dokumen {{ strtoupper($ext) }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>

</html>
