<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tambah Kompetisi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 bg-red-600 text-white font-bold flex justify-between items-center">
                <span><i class="fas fa-trophy mr-2"></i> Tambah Kompetisi Baru</span>
                <a href="{{ route('kompetisi.index') }}" class="text-sm bg-red-700 px-3 py-1 rounded hover:bg-red-800 transition">Batal</a>
            </div>
            
            <form action="{{ route('kompetisi.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-gray-700 font-bold mb-1">Nama Kompetisi</label>
                    <input type="text" name="nama_kompetisi" required placeholder="Masukkan nama kompetisi..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">Jenis</label>
                        <select name="jenis" required class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none">
                            @foreach($jenisOptions as $opt)
                                <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">Tingkat</label>
                        <select name="tingkat_kompetisi" required class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none">
                            <option value="universitas">Universitas</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">Nama Penyelenggara</label>
                    <input type="text" name="nama_penyelenggara" required placeholder="Lembaga/Instansi penyelenggara"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_kompetisi" required class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none">
                </div>

                <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Data Kompetisi
                </button>
            </form>
        </div>
    </main>
</body>
</html>