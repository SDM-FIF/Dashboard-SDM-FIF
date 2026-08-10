<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Jadwal Pengujian - Dashboard SDM FIF</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Edit Jadwal Pengujian</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Ubah detail jadwal, gedung, ruangan, dan pembagian dosen penguji.</p>
            </div>
            <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:shadow-md transition-shadow duration-300">
            @php
                $dosenPengujiIds = $jadwal->dosenPenguji->sortBy('pivot.urutan')->pluck('id')->toArray();
                $dosenPenguji1 = $dosenPengujiIds[0] ?? null;
                $dosenPenguji2 = $dosenPengujiIds[1] ?? null;
                $dosenPenguji3 = $dosenPengujiIds[2] ?? null;
            @endphp
            <form action="{{ route('rekrutasi-dosen.jadwal-pengujian.update', $jadwal->id) }}" method="POST" class="space-y-6" onsubmit="return validateForm()">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tahun Ajar --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tahun Ajar <span class="text-red-500">*</span></label>
                        <select name="tahun_ajar_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            @foreach($tahunAjarList as $ta)
                                <option value="{{ $ta->id }}" {{ $jadwal->tahun_ajar_id == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Calon Dosen --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Calon Dosen <span class="text-red-500">*</span></label>
                        <select name="calon_dosen_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            @foreach($calonDosenList as $calon)
                                <option value="{{ $calon->id }}" {{ $jadwal->calon_dosen_id == $calon->id ? 'selected' : '' }}>{{ $calon->nama_lengkap ?? $calon->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dosen Penguji 1 --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dosen Penguji 1 <span class="text-red-500">*</span></label>
                        <select name="dosen_penguji_id[]" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Dosen Penguji 1</option>
                            @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}" {{ $dosenPenguji1 == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dosen Penguji 2 --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dosen Penguji 2 <span class="text-red-500">*</span></label>
                        <select name="dosen_penguji_id[]" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Dosen Penguji 2</option>
                            @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}" {{ $dosenPenguji2 == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dosen Penguji 3 --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dosen Penguji 3 <span class="text-gray-400">(Opsional)</span></label>
                        <select name="dosen_penguji_id[]" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="">Pilih Dosen Penguji 3 (Opsional)</option>
                            @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->id }}" {{ $dosenPenguji3 == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}{{ $dosen->back_title ? ', ' . $dosen->back_title : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Ujian --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Ujian <span class="text-red-500">*</span></label>
                        <input type="date" name="jadwal_ujian" value="{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('Y-m-d') }}" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>

                    {{-- Gedung --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gedung <span class="text-red-500">*</span></label>
                        <select name="gedung" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="Gedung A" {{ $jadwal->gedung == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
                            <option value="Gedung B" {{ $jadwal->gedung == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
                            <option value="Gedung C" {{ $jadwal->gedung == 'Gedung C' ? 'selected' : '' }}>Gedung C</option>
                            <option value="Gedung Teknik" {{ $jadwal->gedung == 'Gedung Teknik' ? 'selected' : '' }}>Gedung Teknik</option>
                            <option value="Gedung Rektorat" {{ $jadwal->gedung == 'Gedung Rektorat' ? 'selected' : '' }}>Gedung Rektorat</option>
                        </select>
                    </div>

                    {{-- Ruangan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ruangan <span class="text-red-500">*</span></label>
                        <select name="ruangan" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                            <option value="Aula" {{ $jadwal->ruangan == 'Aula' ? 'selected' : '' }}>Aula</option>
                            <option value="R.201" {{ $jadwal->ruangan == 'R.201' ? 'selected' : '' }}>R.201</option>
                            <option value="R.301" {{ $jadwal->ruangan == 'R.301' ? 'selected' : '' }}>R.301</option>
                            <option value="Lab Komputer 1" {{ $jadwal->ruangan == 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                            <option value="Lab Komputer 2" {{ $jadwal->ruangan == 'Lab Komputer 2' ? 'selected' : '' }}>Lab Komputer 2</option>
                            <option value="Ruang Sidang" {{ $jadwal->ruangan == 'Ruang Sidang' ? 'selected' : '' }}>Ruang Sidang</option>
                        </select>
                    </div>

                    {{-- Waktu --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu <span class="text-red-500">*</span></label>
                        <input type="time" name="waktu" value="{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" 
                       class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script>
        function validateForm() {
            const selects = document.querySelectorAll('select[name="dosen_penguji_id[]"]');
            const dp1 = selects[0].value;
            const dp2 = selects[1].value;
            const dp3 = selects[2].value;

            if (!dp1) {
                alert('Dosen Penguji 1 wajib dipilih');
                return false;
            }
            if (!dp2) {
                alert('Dosen Penguji 2 wajib dipilih');
                return false;
            }

            const selected = [dp1, dp2];
            if (dp3) selected.push(dp3);

            const hasDuplicates = new Set(selected).size !== selected.length;
            if (hasDuplicates) {
                alert('Setiap Dosen Penguji harus berbeda');
                return false;
            }
            return true;
        }

        function enforceUniqueStandardSelects() {
            const selects = document.querySelectorAll('select[name="dosen_penguji_id[]"]');
            
            function onChange() {
                const values = Array.from(selects).map(sel => sel.value);
                
                selects.forEach((currentSelect, currentIndex) => {
                    const options = currentSelect.querySelectorAll('option');
                    options.forEach(opt => {
                        const optVal = opt.value;
                        if (!optVal) return;
                        
                        // Check if selected in another dropdown
                        const isSelectedElsewhere = values.some((val, idx) => idx !== currentIndex && val === optVal);
                        opt.disabled = isSelectedElsewhere;
                    });
                });
            }
            
            selects.forEach(sel => sel.addEventListener('change', onChange));
            // Run once initially
            onChange();
        }
        
        document.addEventListener('DOMContentLoaded', enforceUniqueStandardSelects);
    </script>
</body>
</html>
