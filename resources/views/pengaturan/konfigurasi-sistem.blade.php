<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Konfigurasi Sistem - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .swal2-popup {
            font-family: 'Outfit', sans-serif !important;
            border-radius: 1.25rem !important;
        }
        .swal2-confirm {
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }
        .swal2-cancel {
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }
        .swal2-input {
            border-radius: 0.75rem !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: none !important;
            font-size: 0.875rem !important;
            height: 44px !important;
            padding: 0 1rem !important;
            margin-top: 1rem !important;
            margin-bottom: 0 !important;
        }
        .swal2-input:focus {
            border-color: #C41E3A !important;
            box-shadow: 0 0 0 2px rgba(196, 30, 58, 0.1) !important;
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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Pengaturan Hak Akses</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Atur hak akses, modifikasi role pimpinan, dan kelola pemetaan permission.</p>
            </div>

            @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
            <div class="flex items-center gap-3">
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Role</span>
                </button>
            </div>
            @endif
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            {{-- Card Header & Actions --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Pengaturan Hak Akses</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar sistem role yang terdaftar</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Bar --}}
                        <form method="GET" action="{{ route('pengaturan') }}" class="flex items-center">
                            <div class="relative flex items-center">
                                <input type="text" 
                                       id="searchInput"
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Cari Nama Role..."
                                       class="h-[42px] px-4 pr-10 border border-gray-200 rounded-l-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none w-48 sm:w-64">
                                @if($search)
                                <a href="{{ route('pengaturan') }}" 
                                   id="clearSearch"
                                   class="absolute right-3 text-gray-400 hover:text-gray-600 cursor-pointer text-sm"
                                   title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                            <button type="submit" class="h-[42px] px-4 bg-[#C41E3A] hover:bg-[#A31830] text-white rounded-r-xl transition-all duration-200 text-sm flex items-center justify-center">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        {{-- Export Dropdown --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:text-black hover:border-gray-300 transition-all duration-200 flex items-center space-x-2 shadow-sm">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export Menu -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-[999] overflow-hidden">
                                <a href="{{ route('pengaturan.export.excel', ['search' => $search]) }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-excel text-green-600 mr-2.5"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="{{ route('pengaturan.export.csv', ['search' => $search]) }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-csv text-blue-600 mr-2.5"></i>
                                    <span>Export CSV</span>
                                </a>
                                <a href="{{ route('pengaturan.export.pdf', ['search' => $search]) }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-pdf text-red-600 mr-2.5"></i>
                                    <span>Export PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto overflow-y-visible">
                <table class="min-w-full w-full border-collapse">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-24">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Role</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($roles as $index => $role)
                        @php
                            $totalRoles = count($roles);
                            $isLastTwo = ($index >= $totalRoles - 2);
                        @endphp
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-500">{{ $role->id }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{{ $role->name }}</td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
                                {{-- Action Dropdown --}}
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleActionDropdown({{ $role->id }}, event, {{ $isLastTwo ? 'true' : 'false' }})" 
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-black bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all focus:outline-none">
                                        <span>Aksi</span>
                                        <i id="arrow{{ $role->id }}" class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div id="actionDropdown{{ $role->id }}" 
                                         class="hidden absolute right-0 w-44 rounded-xl shadow-lg bg-white border border-gray-100 z-[100] overflow-hidden">
                                        <div class="py-1">
                                            {{-- Edit --}}
                                            <button onclick="openEditModal({{ $role->id }}, '{{ $role->name }}')"
                                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                                <i class="fas fa-edit mr-2 text-green-600 text-sm"></i>
                                                <span>Edit</span>
                                            </button>
                                            
                                            {{-- Plotting --}}
                                            <a href="{{ route('pengaturan.plotting', $role->id) }}"
                                               class="block w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                                <i class="fas fa-users-cog mr-2 text-blue-600 text-sm"></i>
                                                <span>Plotting</span>
                                            </a>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('pengaturan.role.destroy', $role->id) }}" 
                                                  method="POST" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center transition-colors">
                                                    <i class="fas fa-trash mr-2 text-sm"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span class="text-gray-400 font-medium">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                        <i class="fas fa-user-shield text-4xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Tidak ada data role ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Toggle Export Dropdown
        document.getElementById('exportBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('exportDropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const exportDropdown = document.getElementById('exportDropdown');
            const exportBtn = document.getElementById('exportBtn');
            
            if (!exportBtn.contains(e.target)) {
                exportDropdown.classList.add('hidden');
            }

            // Close all action dropdowns
            document.querySelectorAll('[id^="actionDropdown"]').forEach(dropdown => {
                if (!dropdown.previousElementSibling.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    // Reset arrow direction
                    const roleId = dropdown.id.replace('actionDropdown', '');
                    const arrow = document.getElementById('arrow' + roleId);
                    if (arrow) {
                        arrow.classList.remove('fa-chevron-up');
                        arrow.classList.add('fa-chevron-down');
                    }
                }
            });
        });

        // Toggle Action Dropdown
        function toggleActionDropdown(roleId, event, isLastTwo) {
            const dropdown = document.getElementById('actionDropdown' + roleId);
            const arrow = document.getElementById('arrow' + roleId);
            
            // Close all other dropdowns and reset their arrows
            document.querySelectorAll('[id^="actionDropdown"]').forEach(d => {
                if (d.id !== 'actionDropdown' + roleId) {
                    d.classList.add('hidden');
                    const otherId = d.id.replace('actionDropdown', '');
                    const otherArrow = document.getElementById('arrow' + otherId);
                    if (otherArrow) {
                        otherArrow.classList.remove('fa-chevron-up');
                        otherArrow.classList.add('fa-chevron-down');
                    }
                }
            });
            
            // Toggle current dropdown
            const isHidden = dropdown.classList.contains('hidden');
            
            if (isHidden) {
                dropdown.classList.remove('top-full', 'bottom-full', 'mt-2', 'mb-2', 'origin-top-right', 'origin-bottom-right');
                
                if (isLastTwo) {
                    dropdown.classList.add('bottom-full', 'mb-2', 'origin-bottom-right');
                } else {
                    dropdown.classList.add('top-full', 'mt-2', 'origin-top-right');
                }
                
                dropdown.classList.remove('hidden');
                arrow.classList.remove('fa-chevron-down');
                arrow.classList.add('fa-chevron-up');
            } else {
                dropdown.classList.add('hidden');
                arrow.classList.remove('fa-chevron-up');
                arrow.classList.add('fa-chevron-down');
            }
        }

        // Helper function to close all dropdowns
        function closeAllDropdowns() {
            document.querySelectorAll('[id^="actionDropdown"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
                const roleId = dropdown.id.replace('actionDropdown', '');
                const arrow = document.getElementById('arrow' + roleId);
                if (arrow) {
                    arrow.classList.remove('fa-chevron-up');
                    arrow.classList.add('fa-chevron-down');
                }
            });
        }

        // Open Create Modal
        function openCreateModal() {
            Swal.fire({
                title: 'Tambah Role Baru',
                html: `
                    <div class="text-left px-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Role</label>
                        <input id="roleName" class="swal2-input w-full" placeholder="Contoh: Admin Akademik">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-sm font-semibold',
                    cancelButton: 'px-5 py-2.5 rounded-xl text-sm font-semibold'
                },
                preConfirm: () => {
                    const name = document.getElementById('roleName').value;
                    if (!name) {
                        Swal.showValidationMessage('Nama role harus diisi');
                        return false;
                    }
                    return { name: name };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("pengaturan.role.store") }}';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="name" value="${result.value.name}">
                    `;
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Open Edit Modal
        function openEditModal(roleId, currentName) {
            closeAllDropdowns();
            Swal.fire({
                title: 'Edit Role',
                html: `
                    <div class="text-left px-1">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Role</label>
                        <input id="roleName" class="swal2-input w-full" placeholder="Nama Role" value="${currentName}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-sm font-semibold',
                    cancelButton: 'px-5 py-2.5 rounded-xl text-sm font-semibold'
                },
                preConfirm: () => {
                    const name = document.getElementById('roleName').value;
                    if (!name) {
                        Swal.showValidationMessage('Nama role harus diisi');
                        return false;
                    }
                    return { name: name };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/pengaturan/role/${roleId}`;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="name" value="${result.value.name}">
                    `;
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Delete
        function confirmDelete(roleId, roleName) {
            closeAllDropdowns();
            Swal.fire({
                title: 'Hapus Role?',
                html: `
                <div class="text-left space-y-2">
                    <p class="text-gray-600">Anda akan menghapus role:</p>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-3">
                        <p class="font-bold text-red-800 text-base">${roleName}</p>
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
                    const forms = document.querySelectorAll('.delete-form');
                    forms.forEach(form => {
                        if (form.action.includes(`/pengaturan/role/${roleId}`)) {
                            form.submit();
                        }
                    });
                }
            });
        }

        // Notification Toasts
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

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#C41E3A'
        });
        @endif

        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal!',
            html: `
                <div class="text-left">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonColor: '#C41E3A'
        });
        @endif
    </script>
</body>
</html>
