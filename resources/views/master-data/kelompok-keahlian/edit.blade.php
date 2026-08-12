<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Kelompok Keahlian - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Kelompok Keahlian</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Perbarui informasi kelompok keahlian dosen.</p>
            </div>
            <a href="{{ route('kelompok-keahlian.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-3xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('kelompok-keahlian.update', $kelompokKeahlian->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Singkatan --}}
                <div class="flex flex-col gap-2">
                    <label for="singkatan" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Singkatan KK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan', $kelompokKeahlian->singkatan) }}" required
                        placeholder="Contoh: SIDE, SE, IC, Cyber"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    @error('singkatan')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Kelompok Keahlian --}}
                <div class="flex flex-col gap-2">
                    <label for="nama_kelompok_keahlian" class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Nama Kelompok Keahlian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kelompok_keahlian" id="nama_kelompok_keahlian" value="{{ old('nama_kelompok_keahlian', $kelompokKeahlian->nama_kelompok_keahlian) }}" required
                        placeholder="Contoh: Sistem Informasi dan Rekayasa Domain"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    @error('nama_kelompok_keahlian')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('kelompok-keahlian.index') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl transition-all duration-200 text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
