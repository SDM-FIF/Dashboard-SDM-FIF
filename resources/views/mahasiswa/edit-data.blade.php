<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Data Mahasiswa - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />

    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                <a href="{{ route('mahasiswa.kelola-data') }}" class="hover:text-red-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Kelola Data
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Edit Data Mahasiswa</h1>
            <p class="text-gray-600">Perbarui informasi akademik untuk mahasiswa: <strong>{{ $mahasiswa->nama_lengkap }}</strong></p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 md:p-8">
            {{-- Perhatikan: Route menggunakan mahasiswa.update sesuai controller Anda --}}
            <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Informasi Identitas --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold mb-6 flex items-center text-gray-800">
                        <i class="fas fa-id-card mr-3 text-red-600"></i>
                        Identitas Mahasiswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                            <input type="text" name="nim"
                                   value="{{ old('nim', $mahasiswa->nim) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('nim') border-red-500 @enderror">
                            @error('nim') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Akademik --}}
                <div class="pb-4">
                    <h3 class="text-xl font-semibold mb-6 flex items-center text-gray-800">
                        <i class="fas fa-university mr-3 text-red-600"></i>
                        Status Akademik
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Program Studi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <select name="prodi_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('prodi_id') border-red-500 @enderror">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodi as $p)
                                    <option value="{{ $p->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Mahasiswa</label>
                            <select name="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('status') border-red-500 @enderror">
                                @foreach(['AKTIF', 'TIDAK AKTIF', 'CUTI'] as $st)
                                    <option value="{{ $st }}" {{ old('status', $mahasiswa->status) == $st ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-4 border-t border-gray-200 pt-6">
                    <a href="{{ route('mahasiswa.kelola-data') }}"
                       class="px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i> Update Data Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Error Alert Catch dari Controller --}}
    @if($errors->has('error'))
        <div id="errorNotification" class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3">
            <i class="fas fa-exclamation-triangle"></i>
            <p>{{ $errors->first('error') }}</p>
        </div>
    @endif

    <script>
        // Auto-hide notifikasi error
        setTimeout(() => {
            const errorNotif = document.getElementById('errorNotification');
            if(errorNotif) {
                errorNotif.style.opacity = '0';
                setTimeout(() => errorNotif.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>