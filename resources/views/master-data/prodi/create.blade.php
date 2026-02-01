<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Prodi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen ">
        <x-topbar />

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden max-w-2xl">
            <div class="p-6 bg-red-600 text-white font-bold">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Prodi Baru
            </div>
            <form action="{{ route('prodi.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" placeholder="Contoh: S1 Teknik Elektro" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Pilih Fakultas</label>
                    <select name="fakultas_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultas as $f)
                            <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition">
                    Simpan Data
                </button>
            </form>
        </div>
    </main>
</body>

</html>