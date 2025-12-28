<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Data Mahasiswa - Dashboard SDM</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Detail Data Mahasiswa</h1>
            <p class="text-gray-600">Informasi lengkap data akademik mahasiswa</p>
        </div>

        {{-- Back Button - Mengarah ke route mahasiswa.kelola-data --}}
        <div class="mb-6">
            <a href="{{ route('mahasiswa.kelola-data') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali ke Kelola Data</span>
            </a>
        </div>

        {{-- Detail Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Profil Mahasiswa</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Kolom 1 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">NIM</label>
                            <p class="text-gray-900 font-medium">{{ $mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $mahasiswa->nama_lengkap }}</p>
                        </div>
                    </div>

                    {{-- Kolom 2 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Program Studi</label>
                            <p class="text-gray-900 font-medium">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Status Akademik</label>
                            @php
                                $statusClass = match(strtoupper($mahasiswa->status)) {
                                    'AKTIF' => 'bg-green-100 text-green-800',
                                    'CUTI' => 'bg-yellow-100 text-yellow-800',
                                    'TIDAK AKTIF', 'LULUS', 'DO' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ $mahasiswa->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Kolom 3 --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Angkatan</label>
                            <p class="text-gray-900 font-medium">{{ $mahasiswa->angkatan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Aksi Cepat</label>
                            <div class="flex space-x-2">
                                <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="text-blue-600 hover:underline text-sm font-medium">Edit Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table List (Sesuai Desain Awal) --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">Daftar Mahasiswa Keseluruhan</h2>
                    <div class="text-sm text-gray-600">
                        Total: {{ $allMahasiswa->total() }} Mahasiswa
                    </div>
                </div>

                {{-- Filter menggunakan route mahasiswa.show --}}
                <form method="GET" action="{{ route('mahasiswa.show', $mahasiswa->id) }}" class="mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <select name="filter_status" class="px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                            <option value="">Semua Status</option>
                            <option value="AKTIF" {{ request('filter_status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="CUTI" {{ request('filter_status') == 'CUTI' ? 'selected' : '' }}>Cuti</option>
                        </select>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition-all shadow-md">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('mahasiswa.show', $mahasiswa->id) }}" class="text-gray-500 hover:text-gray-700 underline text-sm">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-600 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">NO.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">NIM</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allMahasiswa as $index => $item)
                        <tr class="{{ $item->id == $mahasiswa->id ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 text-sm">{{ $allMahasiswa->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-medium">{{ $item->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->nim }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $item->status == 'AKTIF' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-3">
                                    <a href="{{ route('mahasiswa.show', $item->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('mahasiswa.edit', $item->id) }}" class="text-green-600 hover:text-green-800" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-200" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50">
                {{ $allMahasiswa->appends(request()->query())->links() }}
            </div>
        </div>
    </main>
</body>
</html>