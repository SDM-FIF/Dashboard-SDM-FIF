<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Kompetisi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 bg-red-600 text-white font-bold">
                <i class="fas fa-edit mr-2"></i> Edit Data Kompetisi
            </div>
            
            <form action="{{ route('kompetisi.update', $kompetisi->id) }}" method="POST" class="p-8 space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-gray-700 font-bold mb-1">Nama Kompetisi</label>
                    <input type="text" name="nama_kompetisi" value="{{ old('nama_kompetisi', $kompetisi->nama_kompetisi) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">Jenis</label>
                        <select name="jenis" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($jenisOptions as $opt)
                                <option value="{{ $opt }}" {{ $kompetisi->jenis == $opt ? 'selected' : '' }}>
                                    {{ ucfirst($opt) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">Tingkat</label>
                        <select name="tingkat_kompetisi" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="universitas" {{ $kompetisi->tingkat_kompetisi == 'universitas' ? 'selected' : '' }}>Universitas</option>
                            <option value="nasional" {{ $kompetisi->tingkat_kompetisi == 'nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="internasional" {{ $kompetisi->tingkat_kompetisi == 'internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">Nama Penyelenggara</label>
                    <input type="text" name="nama_penyelenggara" value="{{ old('nama_penyelenggara', $kompetisi->nama_penyelenggara) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_kompetisi" value="{{ $kompetisi->tanggal_kompetisi->format('Y-m-d') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none">
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('kompetisi.index') }}" class="flex-1 py-3 bg-gray-200 text-gray-700 text-center rounded-lg font-bold hover:bg-gray-300 transition">Batal</a>
                    <button type="submit" class="flex-[2] py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition shadow-lg">
                        Perbarui Kompetisi
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>