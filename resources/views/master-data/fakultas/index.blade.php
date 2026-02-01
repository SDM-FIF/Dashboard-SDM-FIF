<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Kelola Data Fakultas - Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    <x-navbar />

    <main class="flex-1 p-4 md:p-6 min-h-screen">
        <x-topbar />

        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Kelola Data Fakultas</h1>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('fakultas.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama Fakultas..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-red-600 mb-3">Urutkan</label>
                        <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="nama-az" {{ request('sort') == 'nama-az' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama-za" {{ request('sort') == 'nama-za' ? 'selected' : '' }}>Nama Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('fakultas.index') }}"
                        class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition">Reset</a>
                    <button type="submit"
                        class="bg-orange-500 text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition shadow-md transform hover:scale-105">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-2xl font-bold text-red-600">Daftar Fakultas</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-600 text-white text-left text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Fakultas</th>
                            <th class="px-6 py-4">Dekan</th>
                            <th class="px-6 py-4">Wakil Dekan 1</th>
                            <th class="px-6 py-4">Wakil Dekan 2</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($fakultas as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $item->nama_fakultas }}</td>

                                {{-- Ganti nama_dosen menjadi nama_lengkap --}}
                                <td class="px-6 py-4 text-sm">{{ $item->dekan->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->wadek1->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->wadek2->nama_lengkap ?? '-' }}</td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-3 text-lg">
                                        <a href="{{ route('fakultas.edit', $item->id) }}"
                                            class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">Data Fakultas tidak
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $fakultas->appends(request()->query())->links() }}
            </div>
        </div>
    </main>

    {{-- Toast Notif --}}
    @if(session('success'))
        <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.style.display = 'none';
        }, 3000);
    </script>
</body>

</html>