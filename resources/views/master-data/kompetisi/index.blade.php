<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Daftar Kompetisi - Dashboard SDM FIF</title>
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Data Kompetisi</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola daftar kompetisi dan kejuaraan akademik maupun non-akademik.</p>
            </div>

            @can('master-data-kompetisi.create')
            <a href="{{ route('kompetisi.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Kompetisi</span>
            </a>
            @endcan
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#C41E3A]">Daftar Kompetisi</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Menampilkan total {{ $kompetisi->total() }} kompetisi</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Kompetisi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-32">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Penyelenggara</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">Tingkat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-40">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($kompetisi as $item)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{{ $item->nama_kompetisi }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $item->nama_penyelenggara }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium capitalize">{{ $item->tingkat_kompetisi }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $item->tanggal_kompetisi->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <div class="flex items-center justify-center gap-2.5">
                                        <a href="{{ route('kompetisi.show', $item->id) }}"
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition-all"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        @can('master-data-kompetisi.edit')
                                        <a href="{{ route('kompetisi.edit', $item->id) }}"
                                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 border border-transparent hover:border-green-100 rounded-lg transition-all"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @endcan

                                        @can('master-data-kompetisi.delete')
                                        <form action="{{ route('kompetisi.destroy', $item->id) }}" method="POST"
                                              class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all delete-btn"
                                                    data-nama="{{ $item->nama_kompetisi }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-trophy text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada data kompetisi ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC]">
                {{ $kompetisi->links() }}
            </div>
        </div>
    </main>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            // SWEETALERT DELETE CONFIRMATION
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    const nama = this.getAttribute('data-nama');

                    Swal.fire({
                        title: 'Hapus Kompetisi?',
                        html: `
                        <div class="text-left space-y-2">
                            <p class="text-gray-600">Anda akan menghapus Kompetisi:</p>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-3">
                                <p class="font-bold text-red-800 text-base">${nama}</p>
                            </div>
                            <p class="text-xs text-red-600 mt-3 font-semibold">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Data yang dihapus tidak dapat dikembalikan!
                            </p>
                        </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#C41E3A',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm',
                            cancelButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>