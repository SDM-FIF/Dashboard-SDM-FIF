<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data TPA - Dashboard SDM FIF</title>
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

        {{-- Breadcrumbs & Header --}}
        <div class="mb-8 mt-4">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                <a href="{{ route('manajemen-tpa.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kelola Data
                </a>
            </nav>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Tambah Data TPA</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi form di bawah ini untuk mendaftarkan Tenaga Kependidikan dan Profesional baru.</p>
        </div>

        {{-- Form Section --}}
        <form action="{{ route('manajemen-tpa.store') }}" method="POST" class="space-y-8 max-w-5xl">
            @csrf

            {{-- Card 1: Data Personal --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Data Personal</h2>
                        <p class="text-xs text-gray-500">Identitas utama pegawai TPA</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Nama Lengkap --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nama_lengkap" 
                               value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap beserta gelar"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nama_lengkap') border-red-500 @enderror">
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nip" 
                               value="{{ old('nip') }}"
                               placeholder="Nomor Induk Pegawai"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan <span class="text-gray-400">(Opsional)</span></label>
                        <input type="text" 
                               name="jabatan" 
                               value="{{ old('jabatan') }}"
                               placeholder="Contoh: Admin Prodi, Sekretaris"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('jabatan') border-red-500 @enderror">
                        @error('jabatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select name="pendidikan_terakhir" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('pendidikan_terakhir') border-red-500 @enderror">
                            <option value="">Pilih Pendidikan</option>
                            @foreach(['SMA','D3','S1','S2','S3'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_terakhir') == $edu ? 'selected' : '' }}>
                                    {{ $edu }}
                                </option>
                            @endforeach
                        </select>
                        @error('pendidikan_terakhir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Data Kepegawaian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-briefcase text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Data Kepegawaian</h2>
                        <p class="text-xs text-gray-500">Informasi penempatan dan status kerja</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Lokasi Kerja --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Kerja / Unit <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="lokasi_kerja" 
                               value="{{ old('lokasi_kerja') }}"
                               placeholder="Contoh: Informatika, DKK, Keuangan"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('lokasi_kerja') border-red-500 @enderror">
                        @error('lokasi_kerja')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Pegawai --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Pegawai <span class="text-red-500">*</span></label>
                        <select name="status_pegawai" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('status_pegawai') border-red-500 @enderror">
                            <option value="">Pilih Status</option>
                            @foreach(['Pegawai Tetap','Perbantuan LLDIKTI','Profesional Full Time','Profesional Part Time'] as $status)
                                <option value="{{ $status }}" {{ old('status_pegawai') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_pegawai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Data Akun (Hidden) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300 hidden">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2.5 bg-red-50 text-[#C41E3A] rounded-lg">
                        <i class="fas fa-key text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informasi Akun</h2>
                        <p class="text-xs text-gray-500">Akun kredensial untuk login ke dalam sistem</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Username --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</label>
                        <input type="text" 
                               name="username" 
                               value="{{ old('username') }}"
                               placeholder="Username unik"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Password</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Konfirmasi Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               placeholder="Ketik ulang password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 max-w-5xl">
                <p class="text-xs text-gray-500"><span class="text-red-500">*</span> Field wajib diisi.</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('manajemen-tpa.kelola-data') }}" 
                       class="flex-1 sm:flex-none text-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-6 py-3 bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold rounded-xl text-sm transition-all shadow-sm">
                        Simpan Data TPA
                    </button>
                </div>
            </div>
        </form>
    </main>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // General Form Submit loading handler
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

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#C41E3A'
                });
            @endif
        });
    </script>
</body>
</html>
