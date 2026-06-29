<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Data Mahasiswa - Dashboard SDM FIF</title>
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

        {{-- Header Section --}}
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Data Mahasiswa</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Ubah informasi akademik untuk mahasiswa <strong>{{ $mahasiswa->nama_lengkap }}</strong>.</p>
            </div>
            <a href="{{ route('mahasiswa.kelola-data') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Identitas Section --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-100">
                        <i class="fas fa-id-card text-[#C41E3A]"></i>
                        <span>Identitas Mahasiswa</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- NIM --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('nim') border-red-500 @enderror">
                            @error('nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Status Akademik Section --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-100">
                        <i class="fas fa-university text-[#C41E3A]"></i>
                        <span>Status Akademik</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Program Studi --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('prodi_id') border-red-500 @enderror">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodi as $p)
                                    <option value="{{ $p->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                            @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Mahasiswa <span class="text-red-500">*</span></label>
                            <select name="status" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none @error('status') border-red-500 @enderror">
                                @php
                                    $statuses = ['aktif', 'cuti', 'nonaktif', 'lulus', 'resign', 'dikeluarkan'];
                                @endphp
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" {{ old('status', $mahasiswa->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Panel --}}
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-100 flex-wrap">
                    <p class="text-xs text-gray-400 font-semibold"><span class="text-red-500">*</span> Data wajib diisi dengan benar.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('mahasiswa.kelola-data') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Update Mahasiswa</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    {{-- Error Alert Catch --}}
    @if($errors->has('error'))
        <div id="errorNotification"
            class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3 transition-opacity duration-300">
            <i class="fas fa-exclamation-triangle"></i>
            <p class="font-bold">{{ $errors->first('error') }}</p>
        </div>
    @endif

    <script>
        setTimeout(() => {
            const errorNotif = document.getElementById('errorNotification');
            if (errorNotif) {
                errorNotif.style.opacity = '0';
                setTimeout(() => errorNotif.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>