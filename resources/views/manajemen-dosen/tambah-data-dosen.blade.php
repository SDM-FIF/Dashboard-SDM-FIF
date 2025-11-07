<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data Dosen - Dashboard SDM</title>
    <!-- Font Awesome for icons -->
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
                <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Kelola Data
                </a>
            </nav>
            
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Tambah Data Dosen</h1>
            <p class="text-gray-600">Menambahkan data dosen baru ke sistem manajemen SDM</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-red-50">
                <h2 class="text-2xl font-bold text-red-600 flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    Form Tambah Dosen
                </h2>
                <p class="text-gray-600 mt-2">Lengkapi semua data yang diperlukan dengan benar</p>
            </div>

            <form action="{{ route('manajemen-dosen.store') }}" method="POST" class="p-6">
                @csrf
                
                {{-- Row 1: Data Personal --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user mr-2 text-red-600"></i>
                        Data Personal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Front Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Gelar Depan <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   name="front_title" 
                                   value="{{ old('front_title') }}"
                                   placeholder="Dr., Prof., Prof. Dr."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('front_title') border-red-500 @enderror">
                            @error('front_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap') }}"
                                   placeholder="Nama lengkap dosen"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Back Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Gelar Belakang <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   name="back_title" 
                                   value="{{ old('back_title') }}"
                                   placeholder="S.Kom, M.Kom, Ph.D"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('back_title') border-red-500 @enderror">
                            @error('back_title')
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

                        {{-- Kode Dosen --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode Dosen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kode_dosen" 
                                   value="{{ old('kode_dosen') }}"
                                   placeholder="DSN001, DSN002, ..."
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('kode_dosen') border-red-500 @enderror">
                            @error('kode_dosen')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 2: Data Akademik --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-graduation-cap mr-2 text-red-600"></i>
                        Data Akademik
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Fakultas --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <select name="fakultas_id" 
                                    id="fakultas_id"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('fakultas_id') border-red-500 @enderror">
                                <option value="">Pilih Fakultas</option>
                                @if(isset($fakultas))
                                    @foreach($fakultas as $fak)
                                        <option value="{{ $fak->id }}" {{ old('fakultas_id') == $fak->id ? 'selected' : '' }}>
                                            {{ $fak->nama_fakultas }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('fakultas_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Program Studi --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <select name="prodi_id" 
                                    id="prodi_id"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('prodi_id') border-red-500 @enderror">
                                <option value="">Pilih Program Studi</option>
                                @if(isset($prodi))
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('prodi_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kelompok Keahlian --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kelompok Keahlian <span class="text-red-500">*</span>
                            </label>
                            <select name="kelompok_keahlian_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('kelompok_keahlian_id') border-red-500 @enderror">
                                <option value="">Pilih Kelompok Keahlian</option>
                                @if(isset($kelompokKeahlian))
                                    @foreach($kelompokKeahlian as $kk)
                                        <option value="{{ $kk->id }}" {{ old('kelompok_keahlian_id') == $kk->id ? 'selected' : '' }}>
                                            {{ $kk->nama_kelompok_keahlian }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('kelompok_keahlian_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- JFA (Jabatan) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                JFA (Jabatan Fungsional Akademik) <span class="text-red-500">*</span>
                            </label>
                            <select name="jabatan" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('jabatan') border-red-500 @enderror">
                                <option value="">Pilih JFA</option>
                                <option value="NJFA" {{ old('jabatan') == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                <option value="Asisten Ahli" {{ old('jabatan') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                <option value="Lektor" {{ old('jabatan') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                <option value="Lektor Kepala" {{ old('jabatan') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                <option value="Profesor" {{ old('jabatan') == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                            </select>
                            @error('jabatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi Kerja --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lokasi Kerja <span class="text-red-500">*</span>
                            </label>
                            <select name="lokasi_kerja" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('lokasi_kerja') border-red-500 @enderror">
                                <option value="">Pilih Lokasi Kerja</option>
                                <option value="Informatika" {{ old('lokasi_kerja') == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                                <option value="Rekayasa Perangkat Lunak" {{ old('lokasi_kerja') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                <option value="Data Sains" {{ old('lokasi_kerja') == 'Data Sains' ? 'selected' : '' }}>Data Sains</option>
                                <option value="Teknologi Informasi" {{ old('lokasi_kerja') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                            </select>
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
                                <option value="">Pilih Status Pegawai</option>
                                <option value="Tetap" {{ old('status_pegawai') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Perbantuan" {{ old('status_pegawai') == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
                                <option value="Profesional Full Time" {{ old('status_pegawai') == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                                <option value="Profesional Part Time" {{ old('status_pegawai') == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
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
                        {{-- Cancel Button --}}
                        <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                           class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        
                        {{-- Submit Button --}}
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data Dosen
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
            // Auto-hide success/error messages
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

            // Fakultas-Prodi dependency
            const fakultasSelect = document.getElementById('fakultas_id');
            const prodiSelect = document.getElementById('prodi_id');
            
            if (fakultasSelect && prodiSelect) {
                fakultasSelect.addEventListener('change', function() {
                    const fakultasId = this.value;
                    
                    // Clear prodi options
                    prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
                    
                    if (fakultasId) {
                        // Show loading
                        prodiSelect.innerHTML = '<option value="">Memuat...</option>';
                        
                        // Fetch prodi by fakultas
                        fetch(`/api/prodi-by-fakultas/${fakultasId}`)
                            .then(response => response.json())
                            .then(data => {
                                prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
                                data.forEach(prodi => {
                                    const option = document.createElement('option');
                                    option.value = prodi.id;
                                    option.textContent = prodi.nama_prodi;
                                    prodiSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                prodiSelect.innerHTML = '<option value="">Error memuat data</option>';
                            });
                    }
                });
            }

            // Form validation enhancement
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
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