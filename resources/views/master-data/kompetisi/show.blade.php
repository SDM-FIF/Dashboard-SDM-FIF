<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detail Kompetisi - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Detail Kompetisi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Informasi lengkap kompetisi dan daftar mahasiswa yang berpartisipasi.</p>
            </div>
            <a href="{{ route('kompetisi.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 hover:shadow-md transition-shadow duration-300">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 pb-2 border-b border-gray-100">
                <i class="fas fa-trophy text-[#C41E3A]"></i>
                <span>Informasi Kompetisi</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Column 1 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Kompetisi</span>
                        <span class="text-base font-bold text-gray-800 mt-1">{{ $kompetisi->nama_kompetisi }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jenis</span>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-lg border font-bold text-xs bg-blue-50 text-blue-700 border-blue-100 uppercase w-max">
                            {{ $kompetisi->jenis }}
                        </span>
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penyelenggara</span>
                        <span class="text-base font-semibold text-gray-700 mt-1">{{ $kompetisi->nama_penyelenggara }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tingkat Kompetisi</span>
                        <span class="text-base font-semibold text-gray-700 mt-1 capitalize">{{ str_replace('_', ' ', $kompetisi->tingkat_kompetisi) }}</span>
                    </div>
                </div>

                {{-- Column 3 --}}
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Pelaksanaan</span>
                        <span class="text-base font-bold text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($kompetisi->tanggal_kompetisi)->format('d F Y') }}
                        </span>
                    </div>
                    <div class="flex flex-col items-start">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi Cepat</span>
                        @can('master-data-kompetisi.edit')
                        <a href="{{ route('kompetisi.edit', $kompetisi->id) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-800 mt-1 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                            <span>Ubah Data</span>
                        </a>
                        @else
                        <span class="text-sm font-semibold text-gray-400 mt-1">Tidak ada aksi</span>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Overall List Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-users text-[#C41E3A]"></i>
                        <span>Daftar Mahasiswa Berpartisipasi</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Total: {{ $kompetisi->mahasiswa->count() }} Mahasiswa</p>
                </div>
            </div>

            {{-- Table View --}}
            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-16">No.</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Capaian (Juara)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($kompetisi->mahasiswa as $index => $mhs)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $mhs->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-600">{{ $mhs->nim }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-600">{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $mhs->pivot->juara ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-user-times text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada mahasiswa yang berpartisipasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
