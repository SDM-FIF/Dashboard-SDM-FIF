<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Data Dosen - Dashboard SDM</title>
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

        {{-- Page Title & Navigation --}}
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Kelola Data
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Edit Data Dosen</h1>
            <p class="text-gray-600">Ubah informasi data dosen Fakultas Informatika dan Ilmu Komputer</p>
        </div>

        {{-- Form Edit Dosen --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 md:p-8">
            <form action="{{ route('manajemen-dosen.update', $dosen->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Informasi Akun --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $dosen->user->username) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru (Opsional)</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Kosongkan jika tidak ingin mengubah"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi password baru"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                {{-- Informasi Akademik --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Akademik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fakultas_id" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                            <select id="fakultas_id" 
                                    name="fakultas_id" 
                                    required
                                    onchange="loadProdi(this.value)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('fakultas_id') border-red-500 @enderror">
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $fak)
                                    <option value="{{ $fak->id }}" {{ old('fakultas_id', $dosen->user->fakultas_id) == $fak->id ? 'selected' : '' }}>
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fakultas_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <select id="prodi_id" 
                                    name="prodi_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('prodi_id') border-red-500 @enderror">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodi as $p)
                                    <option value="{{ $p->id }}" 
                                            data-fakultas="{{ $p->fakultas_id }}"
                                            {{ old('prodi_id', $dosen->prodi_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kelompok_keahlian_id" class="block text-sm font-medium text-gray-700 mb-2">Kelompok Keahlian</label>
                            <select id="kelompok_keahlian_id" 
                                    name="kelompok_keahlian_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('kelompok_keahlian_id') border-red-500 @enderror">
                                <option value="">Pilih Kelompok Keahlian</option>
                                @foreach($kelompokKeahlian as $kelompok)
                                    <option value="{{ $kelompok->id }}" {{ old('kelompok_keahlian_id', $dosen->kelompok_keahlian_id) == $kelompok->id ? 'selected' : '' }}>
                                        {{ $kelompok->nama_kelompok_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelompok_keahlian_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Personal --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="front_title" class="block text-sm font-medium text-gray-700 mb-2">Gelar Depan</label>
                            <input type="text" 
                                   id="front_title" 
                                   name="front_title" 
                                   value="{{ old('front_title', $dosen->front_title) }}"
                                   placeholder="Dr., Prof., dll."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('front_title') border-red-500 @enderror">
                            @error('front_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" 
                                   id="nama_lengkap" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="back_title" class="block text-sm font-medium text-gray-700 mb-2">Gelar Belakang</label>
                            <input type="text" 
                                   id="back_title" 
                                   name="back_title" 
                                   value="{{ old('back_title', $dosen->back_title) }}"
                                   placeholder="M.Kom, S.T., M.T., dll."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('back_title') border-red-500 @enderror">
                            @error('back_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                            <input type="text" 
                                   id="nip" 
                                   name="nip" 
                                   value="{{ old('nip', $dosen->nip) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_dosen" class="block text-sm font-medium text-gray-700 mb-2">Kode Dosen</label>
                            <input type="text" 
                                   id="kode_dosen" 
                                   name="kode_dosen" 
                                   value="{{ old('kode_dosen', $dosen->kode_dosen) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('kode_dosen') border-red-500 @enderror">
                            @error('kode_dosen')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Jabatan --}}
                <div class="pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Jabatan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">JFA (Jabatan Fungsional Akademik)</label>
                            <select id="jabatan" 
                                    name="jabatan" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('jabatan') border-red-500 @enderror">
                                <option value="">Pilih JFA</option>
                                <option value="NJFA" {{ old('jabatan', $dosen->jabatan) == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                <option value="Asisten Ahli" {{ old('jabatan', $dosen->jabatan) == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                <option value="Lektor" {{ old('jabatan', $dosen->jabatan) == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                <option value="Lektor Kepala" {{ old('jabatan', $dosen->jabatan) == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                <option value="Profesor" {{ old('jabatan', $dosen->jabatan) == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                            </select>
                            @error('jabatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lokasi_kerja" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja</label>
                            <select id="lokasi_kerja" 
                                    name="lokasi_kerja" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('lokasi_kerja') border-red-500 @enderror">
                                <option value="">Pilih Lokasi Kerja</option>
                                <option value="Informatika" {{ old('lokasi_kerja', $dosen->lokasi_kerja) == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                                <option value="Rekayasa Perangkat Lunak" {{ old('lokasi_kerja', $dosen->lokasi_kerja) == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                <option value="Data Sains" {{ old('lokasi_kerja', $dosen->lokasi_kerja) == 'Data Sains' ? 'selected' : '' }}>Data Sains</option>
                                <option value="Teknologi Informasi" {{ old('lokasi_kerja', $dosen->lokasi_kerja) == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                            </select>
                            @error('lokasi_kerja')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status_pegawai" class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai</label>
                            <select id="status_pegawai" 
                                    name="status_pegawai" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('status_pegawai') border-red-500 @enderror">
                                <option value="">Pilih Status Pegawai</option>
                                <option value="Tetap" {{ old('status_pegawai', $dosen->status_pegawai) == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Perbantuan" {{ old('status_pegawai', $dosen->status_pegawai) == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
                                <option value="Profesional Full Time" {{ old('status_pegawai', $dosen->status_pegawai) == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                                <option value="Profesional Part Time" {{ old('status_pegawai', $dosen->status_pegawai) == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                            </select>
                            @error('status_pegawai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row justify-end space-y-4 md:space-y-0 md:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                       class="inline-flex justify-center items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
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
        <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>Ada kesalahan dalam form. Silakan periksa kembali.</span>
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

            // Load prodi berdasarkan fakultas yang dipilih
            const fakultasSelect = document.getElementById('fakultas_id');
            const prodiSelect = document.getElementById('prodi_id');
            
            function loadProdi(fakultasId) {
                const prodiOptions = prodiSelect.querySelectorAll('option[data-fakultas]');
                
                // Reset dan hide semua prodi
                prodiOptions.forEach(option => {
                    option.style.display = 'none';
                    option.selected = false;
                });
                
                // Show prodi yang sesuai dengan fakultas
                if (fakultasId) {
                    prodiOptions.forEach(option => {
                        if (option.getAttribute('data-fakultas') == fakultasId) {
                            option.style.display = 'block';
                        }
                    });
                } else {
                    // Show all prodi jika tidak ada fakultas dipilih
                    prodiOptions.forEach(option => {
                        option.style.display = 'block';
                    });
                }
            }

            // Load prodi on page load berdasarkan fakultas yang sudah dipilih
            const currentFakultasId = fakultasSelect.value;
            if (currentFakultasId) {
                loadProdi(currentFakultasId);
                
                // Set prodi yang sudah dipilih kembali
                const currentProdiId = '{{ old("prodi_id", $dosen->prodi_id) }}';
                if (currentProdiId) {
                    prodiSelect.value = currentProdiId;
                }
            }

            // Make loadProdi available globally
            window.loadProdi = loadProdi;

            // Form submission loading state
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                        submitBtn.disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>