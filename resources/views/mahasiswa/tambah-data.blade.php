<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data Mahasiswa - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Header Section --}}
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ route('mahasiswa.kelola-data') }}"
                    class="hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Kelola Data
                </a>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Tambah Data Mahasiswa</h1>
            <p class="text-gray-600">Menambahkan entri mahasiswa baru ke dalam database sistem</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-red-50">
                <h2 class="text-2xl font-bold text-red-600 flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    Form Data Mahasiswa
                </h2>
            </div>

            <form action="{{ route('mahasiswa.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            placeholder="Masukkan nama lengkap" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none transition-all @error('nama_lengkap') border-red-500 @enderror">
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIM --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            NIM <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="nim" value="{{ old('nim') }}"
                            placeholder="Masukkan nomor induk mahasiswa" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none transition-all @error('nim') border-red-500 @enderror">
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Program Studi --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select name="prodi_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none transition-all @error('prodi_id') border-red-500 @enderror">
                            <option value="">Pilih Program Studi</option>
                            @foreach($prodi as $item)
                                <option value="{{ $item->id }}" {{ old('prodi_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    {{-- Status --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none transition-all @error('status') border-red-500 @enderror">
                            @php
                                $statuses = ['aktif', 'cuti', 'nonaktif', 'lulus', 'resign', 'dikeluarkan'];
                            @endphp
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : ($status == 'aktif' ? 'selected' : '') }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col md:flex-row items-center justify-between pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-4 md:mb-0">
                            <span class="text-red-500">*</span> Data ini akan tersimpan langsung ke tabel mahasiswa.
                        </p>

                        <div class="flex space-x-4 w-full md:w-auto">
                            <a href="{{ route('mahasiswa.kelola-data') }}"
                                class="flex-1 md:flex-none text-center px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 md:flex-none px-8 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 shadow-lg transform hover:scale-105 transition-all">
                                <i class="fas fa-save mr-2"></i> Simpan Mahasiswa
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </main>

    {{-- Error Alert From Controller Catch --}}
    @if($errors->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-bounce">
            <div class="flex items-center space-x-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
                <p>{{ $errors->first('error') }}</p>
            </div>
        </div>
    @endif

    <script>
        // Loading effect on submit
        document.querySelector('form').addEventListener('submit', function () {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</body>

</html>