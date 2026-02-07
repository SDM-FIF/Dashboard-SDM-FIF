<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Data Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar Navigation --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Header Section --}}
        <div class="mb-6">
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ route('manajemen-dosen.kelola-data') }}" class="hover:text-[#C41E3A] transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Kelola Data
                </a>
            </nav>
            
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Tambah Data Dosen</h1>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#C41E3A]">
                    <i class="fas fa-user-plus mr-2"></i>
                    Form Tambah Dosen
                </h2>
            </div>

            <form action="{{ route('manajemen-dosen.store') }}" method="POST" class="p-6">
                @csrf
                
                {{-- Row 1: Data Personal --}}
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user mr-2"></i>
                        Data Personal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Front Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gelar Depan
                            </label>
                            <input type="text" 
                                   name="front_title" 
                                   value="{{ old('front_title') }}"
                                   placeholder="Dr., Prof."
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('front_title') border-red-500 @enderror">
                            @error('front_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_lengkap" 
                                   value="{{ old('nama_lengkap') }}"
                                   placeholder="Nama lengkap dosen"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Back Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gelar Belakang
                            </label>
                            <input type="text" 
                                   name="back_title" 
                                   value="{{ old('back_title') }}"
                                   placeholder="S.Kom, M.Kom, Ph.D"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('back_title') border-red-500 @enderror">
                            @error('back_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nip" 
                                   value="{{ old('nip') }}"
                                   placeholder="Nomor Induk Pegawai"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Dosen --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Dosen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kode_dosen" 
                                   value="{{ old('kode_dosen') }}"
                                   placeholder="DSN001, DSN002, ..."
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kode_dosen') border-red-500 @enderror">
                            @error('kode_dosen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 2: Data Akademik --}}
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Data Akademik
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Fakultas --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <select name="fakultas_id" 
                                    id="fakultas_id"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('fakultas_id') border-red-500 @enderror">
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
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Program Studi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <select name="prodi_id" 
                                    id="prodi_id"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('prodi_id') border-red-500 @enderror">
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
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kelompok Keahlian --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kelompok Keahlian <span class="text-red-500">*</span>
                            </label>
                            <select name="kelompok_keahlian_id" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kelompok_keahlian_id') border-red-500 @enderror">
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
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- JFA (Jabatan) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                JFA (Jabatan Fungsional Akademik) <span class="text-red-500">*</span>
                            </label>
                            <select name="jabatan" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('jabatan') border-red-500 @enderror">
                                <option value="">Pilih JFA</option>
                                <option value="NJFA" {{ old('jabatan') == 'NJFA' ? 'selected' : '' }}>NJFA</option>
                                <option value="Asisten Ahli" {{ old('jabatan') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                <option value="Lektor" {{ old('jabatan') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                                <option value="Lektor Kepala" {{ old('jabatan') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                <option value="Profesor" {{ old('jabatan') == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                            </select>
                            @error('jabatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Pegawai --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pegawai <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pegawai" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('status_pegawai') border-red-500 @enderror">
                                <option value="">Pilih Status Pegawai</option>
                                <option value="Tetap" {{ old('status_pegawai') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Perbantuan" {{ old('status_pegawai') == 'Perbantuan' ? 'selected' : '' }}>Perbantuan</option>
                                <option value="Profesional Full Time" {{ old('status_pegawai') == 'Profesional Full Time' ? 'selected' : '' }}>Profesional Full Time</option>
                                <option value="Profesional Part Time" {{ old('status_pegawai') == 'Profesional Part Time' ? 'selected' : '' }}>Profesional Part Time</option>
                            </select>
                            @error('status_pegawai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 3: Data Account --}}
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-[#C41E3A] mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user-cog mr-2"></i>
                        Data Account
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   placeholder="Username untuk login sistem"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   placeholder="Password minimal 8 karakter"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi password"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                           class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        
                        {{-- Submit Button --}}
                        <button type="submit" 
                                class="px-6 py-2.5 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-sm" style="background-color: #FBB03B;" onmouseover="this.style.backgroundColor='#E09A2A'" onmouseout="this.style.backgroundColor='#FBB03B'">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data Dosen
                        </button>
                    </div>
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

            // Form validation enhancement and submission
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
                        text: 'Apakah Anda yakin ingin menyimpan data dosen ini?',
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