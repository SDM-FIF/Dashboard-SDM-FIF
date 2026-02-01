<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Daftar Kompetisi - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Data Kompetisi</h1>
            <a href="{{ route('kompetisi.create') }}"
                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i> Tambah Kompetisi
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-red-600 text-white uppercase text-sm">
                        <th class="px-6 py-4">Nama Kompetisi</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Penyelenggara</th>
                        <th class="px-6 py-4">Tingkat</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kompetisi as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $item->nama_kompetisi }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded uppercase">
                                    {{ $item->jenis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nama_penyelenggara }}</td>
                            <td class="px-6 py-4 text-sm capitalize">{{ $item->tingkat_kompetisi }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tanggal_kompetisi->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="{{ route('kompetisi.edit', $item->id) }}"
                                        class="text-green-600 hover:text-green-800 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('kompetisi.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kompetisi ini?')">
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
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data kompetisi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $kompetisi->links() }}
            </div>
        </div>
    </main>
</body>

</html>