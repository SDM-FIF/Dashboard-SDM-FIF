<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Prodi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        <div class="mb-8">
            <a href="{{ route('prodi.index') }}" class="text-gray-600 hover:text-red-600 transition flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Edit Program Studi</h1>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden max-w-2xl">
            <div class="p-6 bg-red-600 text-white font-bold">
                <i class="fas fa-edit mr-2"></i> Form Perubahan Prodi
            </div>
            <form action="{{ route('prodi.update', $prodi->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Nama Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none @error('nama_prodi') border-red-500 @enderror">
                    @error('nama_prodi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Fakultas Naungan <span class="text-red-500">*</span></label>
                    <select name="fakultas_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultas as $f)
                            <option value="{{ $f->id }}" {{ old('fakultas_id', $prodi->fakultas_id) == $f->id ? 'selected' : '' }}>
                                {{ $f->nama_fakultas }}
                            </option>
                        @endforeach
                    </select>
                    @error('fakultas_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition shadow-lg font-bold">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>