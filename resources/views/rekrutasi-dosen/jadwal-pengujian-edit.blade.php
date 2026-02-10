<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Jadwal Pengujian - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Edit Jadwal Pengujian</h1>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <form action="{{ route('rekrutasi-dosen.jadwal-pengujian.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tahun Ajar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajar <span class="text-red-500">*</span></label>
                        <select name="tahun_ajar_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                            @foreach($tahunAjarList as $ta)
                                <option value="{{ $ta->id }}" {{ $jadwal->tahun_ajar_id == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Calon Dosen --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Calon Dosen <span class="text-red-500">*</span></label>
                        <select name="calon_dosen_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                            @foreach($calonDosenList as $calon)
                                <option value="{{ $calon->id }}" {{ $jadwal->calon_dosen_id == $calon->id ? 'selected' : '' }}>{{ $calon->nama_lengkap ?? $calon->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dosen Penguji --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dosen Penguji <span class="text-red-500">*</span></label>
                        <select name="dosen_penguji_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                            @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}" {{ $jadwal->dosen_penguji_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Ujian --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Ujian <span class="text-red-500">*</span></label>
                        <input type="date" name="jadwal_ujian" value="{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('Y-m-d') }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                    </div>

                    {{-- Gedung --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gedung <span class="text-red-500">*</span></label>
                        <select name="gedung" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                            <option value="Gedung A" {{ $jadwal->gedung == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
                            <option value="Gedung B" {{ $jadwal->gedung == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
                            <option value="Gedung C" {{ $jadwal->gedung == 'Gedung C' ? 'selected' : '' }}>Gedung C</option>
                            <option value="Gedung Teknik" {{ $jadwal->gedung == 'Gedung Teknik' ? 'selected' : '' }}>Gedung Teknik</option>
                            <option value="Gedung Rektorat" {{ $jadwal->gedung == 'Gedung Rektorat' ? 'selected' : '' }}>Gedung Rektorat</option>
                        </select>
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan <span class="text-red-500">*</span></label>
                        <select name="ruangan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                            <option value="Aula" {{ $jadwal->ruangan == 'Aula' ? 'selected' : '' }}>Aula</option>
                            <option value="R.201" {{ $jadwal->ruangan == 'R.201' ? 'selected' : '' }}>R.201</option>
                            <option value="R.301" {{ $jadwal->ruangan == 'R.301' ? 'selected' : '' }}>R.301</option>
                            <option value="Lab Komputer 1" {{ $jadwal->ruangan == 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                            <option value="Lab Komputer 2" {{ $jadwal->ruangan == 'Lab Komputer 2' ? 'selected' : '' }}>Lab Komputer 2</option>
                            <option value="Ruang Sidang" {{ $jadwal->ruangan == 'Ruang Sidang' ? 'selected' : '' }}>Ruang Sidang</option>
                        </select>
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu <span class="text-red-500">*</span></label>
                        <input type="time" name="waktu" value="{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end space-x-4 mt-8">
                    <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#C41E3A] hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
