<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Kompetisi - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Kompetisi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium font-medium">Ubah informasi nama kompetisi, jenis, tingkat, penyelenggara, dan tanggal pelaksanaan.</p>
            </div>
            <a href="{{ route('kompetisi.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-2xl hover:shadow-md transition-shadow duration-300">
            <form action="{{ route('kompetisi.update', $kompetisi->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Kompetisi --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Kompetisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kompetisi" value="{{ old('nama_kompetisi', $kompetisi->nama_kompetisi) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                </div>

                {{-- Jenis & Tingkat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jenis <span class="text-red-500">*</span></label>
                        <select name="jenis" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            @foreach($jenisOptions as $opt)
                                <option value="{{ $opt }}" {{ $kompetisi->jenis == $opt ? 'selected' : '' }}>
                                    {{ ucfirst($opt) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tingkat <span class="text-red-500">*</span></label>
                        <select name="tingkat_kompetisi" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="universitas" {{ $kompetisi->tingkat_kompetisi == 'universitas' ? 'selected' : '' }}>Universitas</option>
                            <option value="nasional" {{ $kompetisi->tingkat_kompetisi == 'nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="internasional" {{ $kompetisi->tingkat_kompetisi == 'internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                    </div>
                </div>

                {{-- Nama Penyelenggara --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Penyelenggara <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_penyelenggara" value="{{ old('nama_penyelenggara', $kompetisi->nama_penyelenggara) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                </div>

                {{-- Tanggal Pelaksanaan --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kompetisi" value="{{ $kompetisi->tanggal_kompetisi->format('Y-m-d') }}" required 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                </div>

                {{-- Mahasiswa Terlibat --}}
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Mahasiswa Terlibat</h3>
                            <p class="text-xs text-gray-500 mt-1">Ubah atau tambahkan mahasiswa yang mengikuti kompetisi ini.</p>
                        </div>
                        <button type="button" id="add-mahasiswa" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold rounded-lg text-xs transition-all flex items-center gap-2 shadow-sm border border-blue-100">
                            <i class="fas fa-plus"></i> Tambah Mahasiswa
                        </button>
                    </div>

                    <div id="mahasiswa-container" class="space-y-4">
                        @foreach($kompetisi->mahasiswa as $index => $mhs)
                        <div class="mahasiswa-row bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                            <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-4 gap-4 w-full">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">NIM <span class="text-red-500">*</span></label>
                                    <input type="number" name="mahasiswa[{{ $index }}][nim]" value="{{ $mhs->nim }}" required placeholder="Cth: 130120xxxx"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Mahasiswa <span class="text-red-500">*</span></label>
                                    <input type="text" name="mahasiswa[{{ $index }}][nama_lengkap]" value="{{ $mhs->nama_lengkap }}" required placeholder="Nama Lengkap"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Asal Prodi <span class="text-red-500">*</span></label>
                                    <select name="mahasiswa[{{ $index }}][prodi_id]" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                        <option value="" disabled>Pilih Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ $mhs->prodi_id == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Capaian <span class="text-red-500">*</span></label>
                                    <select name="mahasiswa[{{ $index }}][capaian]" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                        @php $capaianOpts = ['Peserta', 'Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1', 'Harapan 2', 'Harapan 3']; @endphp
                                        @foreach($capaianOpts as $opt)
                                            <option value="{{ $opt }}" {{ $mhs->pivot->juara == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-mahasiswa flex-shrink-0 w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors mt-6 sm:mt-0">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Template Mahasiswa Row --}}
                <template id="mahasiswa-template">
                    <div class="mahasiswa-row bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-4 gap-4 w-full">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">NIM <span class="text-red-500">*</span></label>
                                <input type="number" name="mahasiswa[__INDEX__][nim]" required placeholder="Cth: 130120xxxx"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Mahasiswa <span class="text-red-500">*</span></label>
                                <input type="text" name="mahasiswa[__INDEX__][nama_lengkap]" required placeholder="Nama Lengkap"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Asal Prodi <span class="text-red-500">*</span></label>
                                <select name="mahasiswa[__INDEX__][prodi_id]" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                    <option value="" disabled selected>Pilih Prodi</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Capaian <span class="text-red-500">*</span></label>
                                <select name="mahasiswa[__INDEX__][capaian]" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-[#F8FAFC] text-gray-700 text-xs focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                                    <option value="Peserta">Peserta</option>
                                    <option value="Juara 1">Juara 1</option>
                                    <option value="Juara 2">Juara 2</option>
                                    <option value="Juara 3">Juara 3</option>
                                    <option value="Harapan 1">Harapan 1</option>
                                    <option value="Harapan 2">Harapan 2</option>
                                    <option value="Harapan 3">Harapan 3</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="remove-mahasiswa flex-shrink-0 w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors mt-6 sm:mt-0">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </template>

                {{-- Action Panel --}}
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-gray-100 flex-wrap">
                    <p class="text-xs text-gray-400 font-semibold"><span class="text-red-500">*</span> Data wajib diisi dengan benar.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('kompetisi.index') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('mahasiswa-container');
            const template = document.getElementById('mahasiswa-template').innerHTML;
            const addButton = document.getElementById('add-mahasiswa');
            
            // Start index beyond the existing elements
            let mhsIndex = {{ $kompetisi->mahasiswa->count() > 0 ? $kompetisi->mahasiswa->count() : 0 }};

            // Bind remove event to existing rows
            document.querySelectorAll('.remove-mahasiswa').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.mahasiswa-row').remove();
                });
            });

            function addRow() {
                const html = template.replace(/__INDEX__/g, mhsIndex);
                const div = document.createElement('div');
                div.innerHTML = html.trim();
                const row = div.firstChild;

                row.querySelector('.remove-mahasiswa').addEventListener('click', function() {
                    row.remove();
                });

                container.appendChild(row);
                mhsIndex++;
            }

            addButton.addEventListener('click', function() {
                addRow();
            });
            
            // Tambah satu baris default saat form load hanya jika belum ada mahasiswa
            if (mhsIndex === 0) {
                addRow();
            }
        });
    </script>
</body>
</html>