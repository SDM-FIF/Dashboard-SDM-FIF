<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Rekrutasi Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Detail Rekrutasi Dosen</h1>
            <a href="{{ route('rekrutasi-dosen') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 max-w-4xl">
            <div class="space-y-6">
                {{-- No. Registrasi --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">No. Registrasi</label>
                    <p class="text-lg font-bold text-gray-900">{{ $rekrutasi->no_registrasi }}</p>
                </div>

                {{-- Nama Calon --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-lg text-gray-900">{{ $rekrutasi->nama_lengkap }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Prodi --}}
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Program Studi</label>
                        <p class="text-lg text-gray-900">{{ $rekrutasi->prodi->nama_prodi ?? '-' }}</p>
                    </div>

                    {{-- Tahun Ajar --}}
                    <div class="border-b border-gray-200 pb-4">
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Tahun Ajar</label>
                        <p class="text-lg text-gray-900">{{ $rekrutasi->tahun_ajar ?? '-' }}</p>
                    </div>
                </div>

                {{-- Tanggal Pengujian --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">Tanggal Pengujian</label>
                    <p class="text-lg text-gray-900">{{ $rekrutasi->tanggal_pengujian ? $rekrutasi->tanggal_pengujian->format('d F Y') : '-' }}</p>
                </div>

                {{-- Jadwal --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">Jadwal</label>
                    <p class="text-lg text-gray-900">{{ $rekrutasi->jadwal ?? '-' }}</p>
                </div>

                {{-- Status --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">Status</label>
                    @php
                        $statusClass = match($rekrutasi->status) {
                            'Diterima' => 'bg-green-100 text-green-800',
                            'Ditolak' => 'bg-red-100 text-red-800',
                            'Diproses' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ $rekrutasi->status }}
                    </span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center space-x-4 pt-6">
                    <a href="{{ route('rekrutasi-dosen.edit', $rekrutasi->id) }}" 
                       class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-center flex items-center justify-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit Data</span>
                    </a>
                    
                    <form action="{{ route('rekrutasi-dosen.destroy', $rekrutasi->id) }}" 
                          method="POST" 
                          class="flex-1"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-trash"></i>
                            <span>Hapus Data</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>