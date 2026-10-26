<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Laporan Pengembangan TPA - Dashboard SDM FIF</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 min-h-screen text-gray-800">
    <x-navbar />

    <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        <x-topbar />

        @php
        $jumlahMengikuti = (int) ($ringkasan['jumlah_mengikuti'] ?? 0);
        $jumlahTenaga = (int) ($ringkasan['jumlah_tenaga'] ?? 0);

        $persentase = $jumlahTenaga > 0
        ? round(($jumlahMengikuti / $jumlahTenaga) * 100, 2)
        : 0;
        @endphp

        <div class="mt-6 mb-8">
            <h1 class="text-3xl font-bold text-[#C41E3A]">
                Laporan Pengembangan Tenaga Kependidikan
            </h1>
            <p class="text-gray-500 mt-2">
                Kelola data pelatihan, sertifikasi, dan studi lanjut TPA untuk kebutuhan akreditasi.
            </p>
        </div>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
            <p class="font-semibold mb-2">Data belum dapat disimpan:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-gray-100">
                <p class="text-sm text-gray-500">Peserta Pengembangan Kompetensi</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $jumlahMengikuti }}
                </h2>
                <p class="text-xs text-gray-400 mt-2">Tenaga kependidikan</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Total Tenaga Kependidikan</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $jumlahTenaga }}
                </h2>
                <p class="text-xs text-gray-400 mt-2">Jumlah keseluruhan</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Persentase Peserta</p>
                <h2 class="text-3xl font-bold text-[#C41E3A] mt-2">
                    {{ number_format($persentase, 2, ',', '.') }}%
                </h2>
                <p class="text-xs text-gray-400 mt-2">Dihitung otomatis</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h2 class="text-xl font-bold mb-2">Input Data Kegiatan</h2>
            <p class="text-sm text-gray-500 mb-6">
                Masukkan jumlah peserta untuk setiap jenis kegiatan dan tahun.
                Data dengan jenis kegiatan dan tahun yang sama akan diperbarui.
            </p>

            <form action="{{ route('laporan-pengembangan-tpa.kegiatan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="kegiatan" class="block text-sm font-semibold mb-2">
                            Jenis Kegiatan
                        </label>
                        <select id="kegiatan" name="kegiatan" required class="w-full border border-gray-200 rounded-xl p-3 bg-white
               focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none">
                            <option value="">Pilih kegiatan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Sertifikasi">Sertifikasi</option>
                            <option value="Studi Lanjut">Studi Lanjut</option>
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-semibold mb-2">
                            Tahun
                        </label>

                        <input id="tahun" type="number" name="tahun" min="2000" max="2100" required
                            value="{{ old('tahun', date('Y')) }}" class="w-full border border-gray-200 rounded-xl p-3 bg-white
               focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none">
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-semibold mb-2">
                            Jumlah Peserta
                        </label>
                        <input id="jumlah" type="number" name="jumlah" min="0" required value="{{ old('jumlah', 0) }}"
                            class="w-full border border-gray-200 rounded-xl p-3 bg-white
               focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none">
                    </div>
                </div>

                <button type="submit"
                    class="mt-5 px-5 py-3 rounded-xl bg-[#C41E3A] hover:bg-red-800 text-white font-semibold">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Kegiatan
                </button>
            </form>
        </div>

        <div class="w-full border border-gray-200 rounded-xl p-3 bg-white
               focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none mb-8">
            <h2 class="text-xl font-bold mb-2">Ringkasan Pengembangan Kompetensi</h2>
            <p class="text-sm text-gray-500 mb-6">
                Perbarui jumlah peserta dan jumlah seluruh tenaga kependidikan.
            </p>

            <form action="{{ route('laporan-pengembangan-tpa.ringkasan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="jumlah_mengikuti" class="block text-sm font-semibold mb-2">
                            Jumlah yang Mengikuti Pengembangan Kompetensi
                        </label>
                        <input id="jumlah_mengikuti" type="number" name="jumlah_mengikuti" min="0" required
                            value="{{ old('jumlah_mengikuti', $jumlahMengikuti) }}"
                            class="w-full border rounded-xl p-3">
                    </div>

                    <div>
                        <label for="jumlah_tenaga" class="block text-sm font-semibold mb-2">
                            Jumlah Seluruh Tenaga Kependidikan
                        </label>
                        <input id="jumlah_tenaga" type="number" name="jumlah_tenaga" min="0" required
                            value="{{ old('jumlah_tenaga', $jumlahTenaga) }}" class="w-full border rounded-xl p-3">
                    </div>
                </div>

                <button type="submit"
                    class="mt-5 px-5 py-3 rounded-xl bg-[#C41E3A] hover:bg-red-800 text-white font-semibold">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Ringkasan
                </button>
            </form>
        </div>



        
<!-- Impor Data dari Excel -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-2">
        Impor Data dari Excel
    </h2>

    <p class="text-sm text-gray-500 mb-6">
        Unduh template, isi data sesuai format, kemudian unggah kembali
        untuk memperbarui laporan pengembangan tenaga kependidikan.
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        <!-- Download Template: Kiri -->
        <div class="flex flex-col items-center justify-center text-center
                    bg-gray-50 border border-gray-100 rounded-xl p-6">

            <div class="flex items-center justify-center gap-2 mb-3">
                <i class="fa-solid fa-file-excel text-[#C41E3A] text-xl"></i>
                <p class="text-sm font-semibold text-gray-700">
                    1. Unduh Template
                </p>
            </div>

            <p class="text-sm text-gray-500 mb-4">
                Unduh template resmi untuk acuan pengisian data.
            </p>

            <a
                href="{{ asset('templates/template_laporan_pengembangan_tpa.xlsx') }}"
                download
                class="inline-flex items-center justify-center gap-2 px-5 py-3
                       rounded-xl bg-white border border-gray-200
                       text-gray-700 font-semibold hover:bg-gray-100 transition">

                <i class="fa-solid fa-download"></i>
                Download Template Excel
            </a>
        </div>

        <!-- Upload Excel: Kanan -->
        <form
            action="{{ route('laporan-pengembangan-tpa.import') }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex flex-col justify-center bg-gray-50
                   border border-gray-100 rounded-xl p-6">

            @csrf

            <label
                for="file_excel"
                class="block text-sm font-semibold text-gray-700 mb-3">
                2. Pilih File Excel
            </label>

            <input
                id="file_excel"
                type="file"
                name="file"
                accept=".xlsx,.xls"
                required
                class="block w-full text-sm text-gray-600
                       border border-gray-200 rounded-xl p-3 bg-white
                       focus:ring-2 focus:ring-red-200
                       focus:border-red-400 mb-5">

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2
                       w-full sm:w-fit px-5 py-3 rounded-xl
                       bg-[#C41E3A] text-white font-semibold
                       hover:bg-red-800 transition">

                <i class="fa-solid fa-upload"></i>
                Upload dan Simpan Data
            </button>

        </form>

    </div>
</div>





            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-5">Riwayat Kegiatan</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">No.</th>
                                <th class="p-3">Jenis Kegiatan</th>
                                <th class="p-3">Tahun</th>
                                <th class="p-3">Jumlah Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kegiatan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3">{{ $loop->iteration }}</td>
                                <td class="p-3">{{ $item->kegiatan }}</td>
                                <td class="p-3">{{ $item->tahun }}</td>
                                <td class="p-3">{{ $item->jumlah }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-5 text-center text-gray-500">
                                    Belum ada data kegiatan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </main>
</body>

</html>