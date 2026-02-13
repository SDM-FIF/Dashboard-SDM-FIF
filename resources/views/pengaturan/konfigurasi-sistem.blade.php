<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Konfigurasi Sistem - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Pengaturan Hak Akses</h1>
        </div>

        {{-- Alert Messages - Will be shown via SweetAlert instead --}}

        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Pengaturan Hak Akses</h2>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Tambah Data Button --}}
                    @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
                    <button onclick="openCreateModal()"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </button>
                    @endif

                    {{-- Right Side Controls --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Bar --}}
                        <form method="GET" action="{{ route('pengaturan') }}" class="flex items-center relative">
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Cari Nama Role..."
                                       class="px-4 py-2 pr-10 border border-gray-300 rounded-l-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 w-64">
                                @if($search)
                                <a href="{{ route('pengaturan') }}" 
                                   id="clearSearch"
                                   class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                                   title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                            <button type="submit" class="px-4 py-2 bg-[#C41E3A] hover:bg-red-700 text-white rounded-r-lg transition-all duration-200">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        {{-- Export Button --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]">
                                <a href="{{ route('pengaturan.export.excel', ['search' => $search]) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="{{ route('pengaturan.export.csv', ['search' => $search]) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="{{ route('pengaturan.export.pdf', ['search' => $search]) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto overflow-y-visible">
                <table class="min-w-full w-full">
                    <thead>
                        <tr class="bg-[#C41E3A] text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-20">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Role</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($roles as $index => $role)
                        @php
                            $totalRoles = count($roles);
                            $isLastTwo = ($index >= $totalRoles - 2);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $role->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <strong>{{ $role->name }}</strong>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
                                {{-- Dropdown Button --}}
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleActionDropdown({{ $role->id }}, event, {{ $isLastTwo ? 'true' : 'false' }})" 
                                            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A]">
                                        Aksi
                                        <i id="arrow{{ $role->id }}" class="fas fa-chevron-down ml-2 -mr-1 h-5 w-5 transition-transform duration-200"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div id="actionDropdown{{ $role->id }}" 
                                         class="hidden absolute right-0 w-40 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[100]">
                                        <div class="py-1" role="menu">
                                            {{-- Edit --}}
                                            <button onclick="openEditModal({{ $role->id }}, '{{ $role->name }}')"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                <i class="fas fa-edit mr-2 text-green-600"></i>
                                                Edit
                                            </button>
                                            
                                            {{-- Plotting --}}
                                            <button onclick="openPlottingModal({{ $role->id }}, '{{ $role->name }}')"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                <i class="fas fa-users-cog mr-2 text-blue-600"></i>
                                                Plotting
                                            </button>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('pengaturan.role.destroy', $role->id) }}" 
                                                  method="POST" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        {{-- Empty State --}}
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-4">
                                    <i class="fas fa-user-shield text-4xl text-gray-300"></i>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data role</h3>
                                        <p class="text-sm text-gray-500">Belum ada role yang tersedia.</p>
                                    </div>
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
            const button = event.currentTarget;
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
                // Remove all positioning classes first
                dropdown.classList.remove('top-full', 'bottom-full', 'mt-2', 'mb-2', 'origin-top-right', 'origin-bottom-right');
                
                // Force last two rows to open upwards, others downwards
                if (isLastTwo) {
                    // Open upwards for last 2 rows
                    dropdown.classList.add('bottom-full', 'mb-2', 'origin-bottom-right');
                } else {
                    // Open downwards for all other rows
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
                    <input id="roleName" class="swal2-input" placeholder="Nama Role">
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
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
                    // Create form and submit
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
            closeAllDropdowns(); // Close any open dropdowns
            Swal.fire({
                title: 'Edit Role',
                html: `
                    <input id="roleName" class="swal2-input" placeholder="Nama Role" value="${currentName}">
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
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
                    // Create form and submit
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

        // Open Plotting Modal (placeholder for future implementation)
        function openPlottingModal(roleId, roleName) {
            closeAllDropdowns(); // Close any open dropdowns
            Swal.fire({
                title: 'Plotting User ke Role',
                html: `
                    <p class="mb-4">Fitur plotting user ke role <strong>${roleName}</strong> akan segera tersedia.</p>
                    <p class="text-sm text-gray-500">Anda dapat mengatur user yang memiliki role ini.</p>
                `,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#C41E3A'
            });
        }

        // Confirm Delete
        function confirmDelete(roleId, roleName) {
            closeAllDropdowns(); // Close any open dropdowns
            Swal.fire({
                title: 'Hapus Role?',
                html: `Apakah Anda yakin ingin menghapus role <strong>${roleName}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find and submit the form
                    const forms = document.querySelectorAll('.delete-form');
                    forms.forEach(form => {
                        if (form.action.includes(`/pengaturan/role/${roleId}`)) {
                            form.submit();
                        }
                    });
                }
            });
        }

        // Show success/error messages with SweetAlert
        @if(session('success'))
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#C41E3A'
        });
        @endif
    </script>
</body>
</html>
