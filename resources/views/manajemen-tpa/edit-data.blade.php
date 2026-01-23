<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Data TPA - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />

    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Page Title --}}
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                <a href="{{ route('manajemen-tpa.kelola-data') }}" class="hover:text-red-600">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Kelola Data
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Edit Data TPA</h1>
            <p class="text-gray-600">Ubah informasi data Tenaga Pendukung Akademik</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 md:p-8">
            <form action="{{ route('manajemen-tpa.update', $tpa->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Informasi Akun --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold mb-6">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Username</label>
                            <input type="text" name="username"
                                   value="{{ old('username', $tpa->user->username) }}"
                                   class="w-full px-4 py-3 border rounded-lg @error('username') border-red-500 @enderror">
                            @error('username') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Password Baru</label>
                            <input type="password" name="password"
                                   placeholder="Kosongkan jika tidak diubah"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Informasi Personal --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold mb-6">Informasi Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $tpa->nama_lengkap) }}"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">NIP</label>
                            <input type="text" name="nip"
                                   value="{{ old('nip', $tpa->nip) }}"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Pangkat / Golongan</label>
                            <input type="text" name="jabatan"
                                   value="{{ old('jabatan', $tpa->jabatan) }}"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir"
                                    class="w-full px-4 py-3 border rounded-lg">
                                @foreach(['SMA','D3','S1','S2','S3'] as $edu)
                                    <option value="{{ $edu }}" {{ old('pendidikan_terakhir', $tpa->pendidikan_terakhir) == $edu ? 'selected' : '' }}>
                                        {{ $edu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Informasi Kepegawaian --}}
                <div class="pb-8">
                    <h3 class="text-xl font-semibold mb-6">Informasi Kepegawaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Lokasi Kerja</label>
                            <input type="text" name="lokasi_kerja"
                                   value="{{ old('lokasi_kerja', $tpa->lokasi_kerja) }}"
                                   class="w-full px-4 py-3 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status Pegawai</label>
                            <select name="status_pegawai"
                                    class="w-full px-4 py-3 border rounded-lg">
                                @foreach(['Pegawai Tetap','Perbantuan LLDIKTI','Profesional Full Time','Profesional Part Time'] as $status)
                                    <option value="{{ $status }}" {{ old('status_pegawai', $tpa->status_pegawai) == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="flex justify-end space-x-4 border-t pt-6">
                    <a href="{{ route('manajemen-tpa.kelola-data') }}"
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
