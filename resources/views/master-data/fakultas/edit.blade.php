<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Fakultas - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Select2 untuk dropdown searchable (Opsional, jika ingin pakai search di dropdown) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />

    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        {{-- Header & Back Button --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Edit Data Fakultas</h1>
                <p class="text-gray-600">Perbarui informasi nama fakultas dan struktur pimpinan.</p>
            </div>
            <a href="{{ route('fakultas.index') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 bg-red-600 border-b border-red-700">
                <h2 class="text-xl font-bold text-white"><i class="fas fa-edit mr-2"></i> Form Perubahan Data</h2>
            </div>

            <div class="p-8">
                <form action="{{ route('fakultas.update', $fakultas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Fakultas --}}
                    <div class="mb-6">
                        <label for="nama_fakultas" class="block text-gray-700 font-bold mb-2">Nama Fakultas <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_fakultas" id="nama_fakultas"
                            value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('nama_fakultas') border-red-500 @enderror"
                            placeholder="Contoh: Fakultas Informatika">
                        @error('nama_fakultas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 my-6 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Struktur Pimpinan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Dekan --}}
                            <div class="col-span-1 md:col-span-2">
                                <label for="id_dekan" class="block text-gray-700 font-bold mb-2">Dekan</label>
                                <select name="id_dekan" id="id_dekan"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 select-search">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosenList as $dosen)
                                        {{-- Gunakan $fakultas->dekan_id (sesuai nama kolom di DB) --}}
                                        <option value="{{ $dosen->id }}" {{ old('id_dekan', $fakultas->dekan_id) == $dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->nama_lengkap }} ({{ $dosen->kode_dosen ?? $dosen->nip }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Wakil Dekan 1 --}}
                            <div>
                                <label for="id_wadek1" class="block text-gray-700 font-bold mb-2">Wakil Dekan 1
                                    (Akademik)</label>
                                <select name="id_wadek1" id="id_wadek1"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 select-search">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosenList as $dosen)
                                        {{-- Gunakan $fakultas->wadek1_id --}}
                                        <option value="{{ $dosen->id }}" {{ old('id_wadek1', $fakultas->wadek1_id) == $dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Wakil Dekan 2 --}}
                            <div>
                                <label for="id_wadek2" class="block text-gray-700 font-bold mb-2">Wakil Dekan 2 (Sumber
                                    Daya)</label>
                                <select name="id_wadek2" id="id_wadek2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 select-search">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosenList as $dosen)
                                        {{-- Gunakan $fakultas->wadek2_id --}}
                                        <option value="{{ $dosen->id }}" {{ old('id_wadek2', $fakultas->wadek2_id) == $dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Action Buttons --}}
                            <div class="flex justify-end space-x-4 mt-8">
                                <a href="{{ route('fakultas.index') }}"
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition shadow-lg transform hover:scale-105 font-bold flex items-center">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                </form>
            </div>
        </div>
    </main>

    {{-- Script untuk Searchable Dropdown (Opsional tapi direkomendasikan jika dosen banyak) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select-search').select2({
                placeholder: "Cari nama dosen...",
                allowClear: true,
                width: '100%' // Fix width issue
            });
        });
    </script>
</body>

</html>