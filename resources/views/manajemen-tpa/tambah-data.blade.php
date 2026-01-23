<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data TPA - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Search Bar --}}
        <x-topbar />

        {{-- Header Section --}}
        <div class="mb-8">
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ route('manajemen-tpa.kelola-data') }}" class="hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Kelola Data
                </a>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Tambah Data TPA</h1>
            <p class="text-gray-600">Menambahkan data Tenaga Pendukung Akademik baru ke sistem manajemen SDM</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-red-50">
                <h2 class="text-2xl font-bold text-red-600 flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    Form Tambah TPA
                </h2>
                <p class="text-gray-600 mt-2">Lengkapi semua data yang diperlukan dengan benar</p>
            </div>

            <form action="{{ route('manajemen-tpa.store') }}" method="POST" class="p-6">
                @csrf

                {{-- Row 1: Data Personal --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user mr-2 text-red-600"></i>
                        Data Personal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_lengkap"
                                   value="{{ old('nama_lengkap') }}"
                                   placeholder="Nama lengkap TPA"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nip"
                                   value="{{ old('nip') }}"
                                   placeholder="Nomor Induk Pegawai"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pangkat/Golongan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pangkat / Golongan <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <input type="text"
                                   name="jabatan"
                                   value="{{ old('jabatan') }}"
                                   placeholder="admin prodi, skretaris prodi, dll."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('jabatan') border-red-500 @enderror">
                            @error('jabatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pendidikan Terakhir <span class="text-red-500">*</span>
                            </label>
                            <select name="pendidikan_terakhir"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('pendidikan_terakhir') border-red-500 @enderror">
                                <option value="">Pilih Pendidikan</option>
                                @foreach(['SMA','D3','S1','S2','S3'] as $edu)
                                    <option value="{{ $edu }}" {{ old('pendidikan_terakhir') == $edu ? 'selected' : '' }}>
                                        {{ $edu }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pendidikan_terakhir')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 2: Data Kepegawaian --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-briefcase mr-2 text-red-600"></i>
                        Data Kepegawaian
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Lokasi Kerja --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lokasi Kerja <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="lokasi_kerja"
                                   value="{{ old('lokasi_kerja') }}"
                                   placeholder="Contoh: Informatika / RPL / Data Sains / TI"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('lokasi_kerja') border-red-500 @enderror">
                            @error('lokasi_kerja')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Pegawai --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Pegawai <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pegawai"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('status_pegawai') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                @foreach(['Pegawai Tetap','Perbantuan LLDIKTI','Profesional Full Time','Profesional Part Time'] as $status)
                                    <option value="{{ $status }}" {{ old('status_pegawai') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_pegawai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 3: Data Account --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user-cog mr-2 text-red-600"></i>
                        Data Account
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Username untuk login sistem"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   placeholder="Password minimal 8 karakter"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   placeholder="Konfirmasi password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row items-center justify-between pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-4 md:mb-0">
                        <span class="text-red-500">*</span> Field wajib diisi
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('manajemen-tpa.kelola-data') }}"
                           class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data TPA
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-md">
            <div class="flex items-start space-x-2">
                <i class="fas fa-exclamation-circle mt-1"></i>
                <div>
                    <div class="font-semibold mb-1">Terdapat kesalahan:</div>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transform = 'translateX(100%)';
                    setTimeout(() => successMessage.remove(), 300);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transform = 'translateX(100%)';
                    setTimeout(() => errorMessage.remove(), 300);
                }, 5000);
            }

            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                        submitBtn.disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>
