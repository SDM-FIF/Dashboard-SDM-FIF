<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Rekrutasi Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Tambah Data Rekrutasi Dosen</h1>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 max-w-4xl">
            <form action="{{ route('rekrutasi-dosen.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Gelar Depan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Gelar Depan
                        </label>
                        <input type="text" 
                               name="front_title" 
                               value="{{ old('front_title') }}"
                               placeholder="Dr., Prof."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('front_title') border-red-500 @enderror">
                        @error('front_title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               name="nama" 
                               value="{{ old('nama') }}"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama') border-red-500 @enderror"
                               placeholder="Nama lengkap calon dosen">
                        @error('nama')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gelar Belakang --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Gelar Belakang
                        </label>
                        <input type="text" 
                               name="back_title" 
                               value="{{ old('back_title') }}"
                               placeholder="S.Kom, M.Kom, Ph.D"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('back_title') border-red-500 @enderror">
                        @error('back_title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Prodi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Program Studi <span class="text-red-600">*</span>
                    </label>
                    <select name="prodi_id" 
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('prodi_id') border-red-500 @enderror">
                        <option value="">-- Pilih Program Studi --</option>
                        @if(isset($prodi))
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('prodi_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tahun Ajar --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Ajar <span class="text-red-600">*</span>
                        </label>
                        <select name="tahun_ajar" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('tahun_ajar') border-red-500 @enderror">
                            <option value="">-- Pilih Tahun Ajar --</option>
                            @if(isset($tahunAjar))
                                @foreach($tahunAjar as $ta)
                                    <option value="{{ $ta->label }}" {{ old('tahun_ajar') == $ta->label ? 'selected' : '' }}>
                                        {{ $ta->label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('tahun_ajar')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Pengujian --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Pengujian <span class="text-red-600">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pengujian" 
                               value="{{ old('tanggal_pengujian') }}"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('tanggal_pengujian') border-red-500 @enderror">
                        @error('tanggal_pengujian')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Jadwal --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jadwal <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="jadwal" 
                              rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Contoh: Ruang 201, Pukul 09.00-12.00 WIB">{{ old('jadwal') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Masukkan detail ruang, waktu, dan informasi tambahan lainnya</p>
                    @error('jadwal')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-600">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" 
                                   name="status" 
                                   value="Diproses" 
                                   {{ old('status', 'Diproses') == 'Diproses' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm">Diproses</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" 
                                   name="status" 
                                   value="Diterima" 
                                   {{ old('status') == 'Diterima' ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <span class="text-sm">Diterima</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" 
                                   name="status" 
                                   value="Ditolak" 
                                   {{ old('status') == 'Ditolak' ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 focus:ring-red-500">
                            <span class="text-sm">Ditolak</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-[#FBB03B] hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Data</span>
                    </button>
                    
                    <a href="{{ route('rekrutasi-dosen') }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-center flex items-center justify-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>