<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Prodi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Data Program Studi</h1>
            <a href="{{ route('prodi.create') }}"
                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i> Tambah Prodi
            </a>
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('prodi.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama prodi..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                </div>
                <button type="submit"
                    class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-red-600 text-white text-left text-sm font-semibold uppercase">
                        <th class="px-6 py-4">Nama Program Studi</th>
                        <th class="px-6 py-4">Fakultas</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($prodi as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $item->nama_prodi }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->fakultas->nama_fakultas ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center space-x-3 text-lg">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('prodi.edit', $item->id) }}"
                                        class="text-green-600 hover:text-green-800 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Form Delete --}}
                                    <form action="{{ route('prodi.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus Prodi {{ $item->nama_prodi }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">Data prodi belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $prodi->links() }}
            </div>
        </div>
    </main>
</body>

</html>