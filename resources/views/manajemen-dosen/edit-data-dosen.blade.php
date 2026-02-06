<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Data Dosen - Dashboard SDM FIF</title>
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
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Kelola Data
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2" style="color: #C41E3A;">Edit Data Dosen</h1>
        </div>

        {{-- Form Edit Dosen --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 md:p-8">
            <form action="{{ route('manajemen-dosen.update', $dosen->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Informasi Akun --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-6">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $dosen->user->username) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Kosongkan jika tidak ingin mengubah"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi password baru"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                </div>

                {{-- Informasi Akademik --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-6">Informasi Akademik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fakultas_id" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                            <select id="fakultas_id" 
                                    name="fakultas_id" 
                                    required
                                    onchange="loadProdi(this.value)"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('fakultas_id') border-red-500 @enderror">
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $fak)
                                    <option value="{{ $fak->id }}" {{ old('fakultas_id', $dosen->user->fakultas_id) == $fak->id ? 'selected' : '' }}>
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fakultas_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <select id="prodi_id" 
                                    name="prodi_id" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('prodi_id') border-red-500 @enderror">
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
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kelompok_keahlian_id" class="block text-sm font-medium text-gray-700 mb-2">Kelompok Keahlian</label>
                            <select id="kelompok_keahlian_id" 
                                    name="kelompok_keahlian_id" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kelompok_keahlian_id') border-red-500 @enderror">
                                <option value="">Pilih Kelompok Keahlian</option>
                                @foreach($kelompokKeahlian as $kelompok)
                                    <option value="{{ $kelompok->id }}" {{ old('kelompok_keahlian_id', $dosen->kelompok_keahlian_id) == $kelompok->id ? 'selected' : '' }}>
                                        {{ $kelompok->nama_kelompok_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelompok_keahlian_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Personal --}}
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-6">Informasi Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="front_title" class="block text-sm font-medium text-gray-700 mb-2">Gelar Depan</label>
                            <input type="text" 
                                   id="front_title" 
                                   name="front_title" 
                                   value="{{ old('front_title', $dosen->front_title) }}"
                                   placeholder="Dr., Prof., dll."
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('front_title') border-red-500 @enderror">
                            @error('front_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" 
                                   id="nama_lengkap" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="back_title" class="block text-sm font-medium text-gray-700 mb-2">Gelar Belakang</label>
                            <input type="text" 
                                   id="back_title" 
                                   name="back_title" 
                                   value="{{ old('back_title', $dosen->back_title) }}"
                                   placeholder="M.Kom, S.T., M.T., dll."
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('back_title') border-red-500 @enderror">
                            @error('back_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                            <input type="text" 
                                   id="nip" 
                                   name="nip" 
                                   value="{{ old('nip', $dosen->nip) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_dosen" class="block text-sm font-medium text-gray-700 mb-2">Kode Dosen</label>
                            <input type="text" 
                                   id="kode_dosen" 
                                   name="kode_dosen" 
                                   value="{{ old('kode_dosen', $dosen->kode_dosen) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kode_dosen') border-red-500 @enderror">
                            @error('kode_dosen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Jabatan --}}
                <div class="pb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-6">Informasi Jabatan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">JFA (Jabatan Fungsional Akademik)</label>
                            <select id="jabatan" 
                                    name="jabatan" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('jabatan') border-red-500 @enderror">
                                <option value="">Pilih JFA</option>
                                <option value="NJFA" {{ old('jabatan', $dosen->jabatan) == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                <option value="Asisten Ahli" {{ old('jabatan', $dosen->jabatan) == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                <option value="Lektor" {{ old('jabatan', $dosen->jabatan) == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                <option value="Lektor Kepala" {{ old('jabatan', $dosen->jabatan) == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                <option value="Profesor" {{ old('jabatan', $dosen->jabatan) == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                            </select>
                            @error('jabatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status_pegawai" class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai</label>
                            <select id="status_pegawai" 
                                    name="status_pegawai" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('status_pegawai') border-red-500 @enderror">
                                <option value="">Pilih Status Pegawai</option>
                                <option value="Tetap" {{ old('status_pegawai', $dosen->status_pegawai) == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Perbantuan" {{ old('status_pegawai', $dosen->status_pegawai) == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
                                <option value="Profesional Full Time" {{ old('status_pegawai', $dosen->status_pegawai) == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                                <option value="Profesional Part Time" {{ old('status_pegawai', $dosen->status_pegawai) == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                            </select>
                            @error('status_pegawai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row justify-end space-y-4 md:space-y-0 md:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('manajemen-dosen.kelola-data') }}" 
                       class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-2.5 text-white font-medium rounded-lg transition-colors duration-200 text-sm" style="background-color: #FBB03B;" onmouseover="this.style.backgroundColor='#E09A2A'" onmouseout="this.style.backgroundColor='#FBB03B'">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show success message with SweetAlert2
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#C41E3A'
                });
            @endif

            // Show error message with SweetAlert2
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#C41E3A'
                });
            @endif

            // Show validation errors with SweetAlert2
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terdapat kesalahan!',
                    html: '<ul style="text-align: left;">' +
                        @foreach($errors->all() as $error)
                            '<li>{{ $error }}</li>' +
                        @endforeach
                        '</ul>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#C41E3A'
                });
            @endif

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

            // Form submission with SweetAlert2 confirmation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Validate required fields
                    const requiredFields = form.querySelectorAll('[required]');
                    let hasEmpty = false;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            hasEmpty = true;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                    
                    if (hasEmpty) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Mohon lengkapi semua field yang wajib diisi.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#C41E3A'
                        });
                        return;
                    }
                    
                    // Show confirmation dialog
                    Swal.fire({
                        icon: 'question',
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan data dosen ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#FBB03B',
                        cancelButtonColor: '#6B7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menyimpan...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Submit the form
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>