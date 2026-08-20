<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Tahun Ajaran - Dashboard SDM FIF</title>
    <link class="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Tambah Tahun Ajaran</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Buat entri tahun ajaran dan semester akademik baru.</p>
            </div>
            <a href="{{ route('tahun-ajar.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-2xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('tahun-ajar.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Tahun --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tahun Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" placeholder="Contoh: 2024" required min="1900" max="2100"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('tahun') border-red-500 @enderror">
                    @error('tahun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Semester --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" required 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('semester') border-red-500 @enderror">
                        <option value="">-- Pilih Semester --</option>
                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ganjil</option>
                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex flex-col gap-2 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                   class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded focus:ring-2 focus:ring-red-200 checked:bg-[#C41E3A] checked:border-[#C41E3A] transition-all cursor-pointer">
                            <i class="fas fa-check text-white text-[10px] absolute opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-800">Jadikan Sebagai Tahun Ajaran Aktif</span>
                            <span class="text-xs text-gray-500 font-medium">Bisa terdapat lebih dari satu tahun ajaran yang aktif.</span>
                        </div>
                    </label>
                </div>

                {{-- Action Panel --}}
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-100 flex-wrap">
                    <p class="text-xs text-gray-400 font-semibold"><span class="text-red-500">*</span> Data wajib diisi dengan benar.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('tahun-ajar.index') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Tahun Ajaran</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
