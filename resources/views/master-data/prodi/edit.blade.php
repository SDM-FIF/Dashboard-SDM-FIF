<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Program Studi - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Program Studi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Ubah informasi program studi serta penyesuaian fakultas naungan.</p>
            </div>
            <a href="{{ route('prodi.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-2xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('prodi.update', $prodi->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Program Studi --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nama_prodi') border-red-500 @enderror">
                    @error('nama_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Fakultas --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Fakultas Naungan <span class="text-red-500">*</span></label>
                    <select name="fakultas_id" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultas as $f)
                        <option value="{{ $f->id }}" {{ old('fakultas_id', $prodi->fakultas_id) == $f->id ? 'selected' : '' }}>
                            {{ $f->nama_fakultas }}
                        </option>
                        @endforeach
                    </select>
                    @error('fakultas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nisbah --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Nisbah <span class="text-red-500">*</span>
                    </label>

                    <div class="flex items-center gap-3">
                        <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600">
                            1 :
                        </div>

                        <input
                            type="number"
                            name="batas_nisbah"
                            value="{{ old('batas_nisbah', $prodi->batas_nisbah) }}"
                            min="1"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('batas_nisbah') border-red-500 @enderror">
                    </div>

                    <p class="text-xs text-gray-400">
                        Contoh: nilai 27 berarti 1 dosen berbanding maksimal 27 mahasiswa aktif.
                    </p>

                    @error('batas_nisbah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Kaprodi --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Kaprodi (Opsional)</label>
                    <input type="text" name="kaprodi" value="{{ old('kaprodi', $prodi->kaprodi) }}" placeholder="Contoh: Dr. Ir. Budi Santoso"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('kaprodi') border-red-500 @enderror">
                    @error('kaprodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Action Panel --}}
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-100 flex-wrap">
                    <p class="text-xs text-gray-400 font-semibold"><span class="text-red-500">*</span> Data wajib diisi dengan benar.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('prodi.index') }}"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>

</html>