<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>User Management - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Tag input styling */
        #roleTagsContainer {
            min-height: 38px;
        }
        #roleTagsContainer span {
            animation: fadeIn 0.2s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        #roleSuggestions > div {
            border-bottom: 1px solid #f1f5f9;
        }
        #roleSuggestions > div:last-child {
            border-bottom: none;
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
            margin: 0 !important;
            width: 100% !important;
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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Pengaturan User</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola hak akses pengguna, pemetaan role akun, dan tambahkan administrator baru.</p>
            </div>

            @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
            <div class="flex items-center gap-3">
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-[#C41E3A] hover:bg-[#A31830] text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah User</span>
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
                        <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Pengaturan User</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar pengguna sistem dan peran masing-masing</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Bar --}}
                        <form method="GET" action="{{ route('pengaturan.user-management') }}" class="flex items-center">
                            <div class="relative flex items-center">
                                <input type="text" 
                                       id="searchInput"
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Cari Nama/Username..."
                                       class="h-[42px] px-4 pr-10 border border-gray-200 rounded-l-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none w-48 sm:w-64">
                                @if($search)
                                <a href="{{ route('pengaturan.user-management') }}" 
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
                                <a href="{{ route('pengaturan.user.export.excel', ['search' => $search]) }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-excel text-green-600 mr-2.5"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="{{ route('pengaturan.user.export.csv', ['search' => $search]) }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-csv text-blue-600 mr-2.5"></i>
                                    <span>Export CSV</span>
                                </a>
                                <a href="{{ route('pengaturan.user.export.pdf', ['search' => $search]) }}" 
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
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($users as $index => $user)
                        @php
                            $totalUsers = count($users);
                            $isLastTwo = ($index >= $totalUsers - 2);
                        @endphp
                        <tr class="hover:bg-[#F8FAFC] transition-colors duration-150 group">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{{ $user->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($user->roles->count() > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#F8FAFC] text-gray-700 border border-gray-200">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic font-medium">No roles</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
                                {{-- Action Dropdown --}}
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleActionDropdown({{ $user->id }}, event, {{ $isLastTwo ? 'true' : 'false' }})" 
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-black bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all focus:outline-none">
                                        <span>Aksi</span>
                                        <i id="arrow{{ $user->id }}" class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div id="actionDropdown{{ $user->id }}" 
                                         class="hidden absolute right-0 w-44 rounded-xl shadow-lg bg-white border border-gray-100 z-[100] overflow-hidden">
                                        <div class="py-1">
                                            {{-- Edit --}}
                                            <button onclick='openEditModal({{ json_encode([
                                                "id" => $user->id,
                                                "nama_lengkap" => $user->nama_lengkap,
                                                "username" => $user->username,
                                                "role_ids" => $user->roles->pluck("id")->toArray()
                                            ]) }})'
                                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                                <i class="fas fa-edit mr-2 text-green-600 text-sm"></i>
                                                <span>Edit</span>
                                            </button>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('pengaturan.user.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->nama_lengkap }}')"
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
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                        <i class="fas fa-users text-4xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-500">Tidak ada data user ditemukan</p>
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
                    const userId = dropdown.id.replace('actionDropdown', '');
                    const arrow = document.getElementById('arrow' + userId);
                    if (arrow) {
                        arrow.classList.remove('fa-chevron-up');
                        arrow.classList.add('fa-chevron-down');
                    }
                }
            });
        });

        // Toggle Action Dropdown
        function toggleActionDropdown(userId, event, isLastTwo) {
            const dropdown = document.getElementById('actionDropdown' + userId);
            const arrow = document.getElementById('arrow' + userId);
            
            // Close all other dropdowns and reset their arrows
            document.querySelectorAll('[id^="actionDropdown"]').forEach(d => {
                if (d.id !== 'actionDropdown' + userId) {
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
                const userId = dropdown.id.replace('actionDropdown', '');
                const arrow = document.getElementById('arrow' + userId);
                if (arrow) {
                    arrow.classList.remove('fa-chevron-up');
                    arrow.classList.add('fa-chevron-down');
                }
            });
        }

        // Open Create Modal
        function openCreateModal() {
            const roles = @json($roles);
            
            let rolesHtml = `
                <div class="text-left mt-4 px-1">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Pilih Role</label>
                    <div class="relative">
                        <div id="roleTagsContainer" class="flex flex-wrap gap-1.5 mb-2"></div>
                        <input type="text" 
                               id="roleInput" 
                               class="swal2-input" 
                               placeholder="Ketik nama role..."
                               autocomplete="off">
                        <div id="roleSuggestions" class="hidden absolute z-[999] w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Tambah User Baru',
                html: `
                    <div class="space-y-4 text-left px-1">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Lengkap</label>
                            <input id="namaLengkap" class="swal2-input" placeholder="Nama Lengkap">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Username</label>
                            <input id="username" class="swal2-input" placeholder="Username">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Password</label>
                            <input id="password" type="password" class="swal2-input" placeholder="Password">
                        </div>
                        ${rolesHtml}
                    </div>
                `,
                width: '500px',
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
                didOpen: () => {
                    initRoleTagInput(roles, []);
                },
                preConfirm: () => {
                    const namaLengkap = document.getElementById('namaLengkap').value;
                    const username = document.getElementById('username').value;
                    const password = document.getElementById('password').value;
                    const selectedRoles = window.selectedRoleIds || [];
                    
                    if (!namaLengkap) {
                        Swal.showValidationMessage('Nama lengkap harus diisi');
                        return false;
                    }
                    if (!username) {
                        Swal.showValidationMessage('Username harus diisi');
                        return false;
                    }
                    if (!password) {
                        Swal.showValidationMessage('Password harus diisi');
                        return false;
                    }
                    if (selectedRoles.length === 0) {
                        Swal.showValidationMessage('Minimal 1 role harus dipilih');
                        return false;
                    }
                    
                    return { 
                        namaLengkap: namaLengkap,
                        username: username,
                        password: password,
                        roles: selectedRoles
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("pengaturan.user.store") }}';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    let formHtml = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="nama_lengkap" value="${result.value.namaLengkap}">
                        <input type="hidden" name="username" value="${result.value.username}">
                        <input type="hidden" name="password" value="${result.value.password}">
                    `;
                    
                    result.value.roles.forEach(roleId => {
                        formHtml += `<input type="hidden" name="roles[]" value="${roleId}">`;
                    });
                    
                    form.innerHTML = formHtml;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Open Edit Modal
        function openEditModal(userData) {
            closeAllDropdowns();
            
            const roles = @json($roles);
            
            let rolesHtml = `
                <div class="text-left mt-4 px-1">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Pilih Role</label>
                    <div class="relative">
                        <div id="roleTagsContainer" class="flex flex-wrap gap-1.5 mb-2"></div>
                        <input type="text" 
                               id="roleInput" 
                               class="swal2-input" 
                               placeholder="Ketik nama role..."
                               autocomplete="off">
                        <div id="roleSuggestions" class="hidden absolute z-[999] w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Edit User',
                html: `
                    <div class="space-y-4 text-left px-1">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Lengkap</label>
                            <input id="namaLengkap" class="swal2-input" placeholder="Nama Lengkap" value="${userData.nama_lengkap}">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Username</label>
                            <input id="username" class="swal2-input" placeholder="Username" value="${userData.username}">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Password</label>
                            <input id="password" type="password" class="swal2-input" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        ${rolesHtml}
                    </div>
                `,
                width: '500px',
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
                didOpen: () => {
                    initRoleTagInput(roles, userData.role_ids);
                },
                preConfirm: () => {
                    const namaLengkap = document.getElementById('namaLengkap').value;
                    const username = document.getElementById('username').value;
                    const password = document.getElementById('password').value;
                    const selectedRoles = window.selectedRoleIds || [];
                    
                    if (!namaLengkap) {
                        Swal.showValidationMessage('Nama lengkap harus diisi');
                        return false;
                    }
                    if (!username) {
                        Swal.showValidationMessage('Username harus diisi');
                        return false;
                    }
                    if (selectedRoles.length === 0) {
                        Swal.showValidationMessage('Minimal 1 role harus dipilih');
                        return false;
                    }
                    
                    return { 
                        namaLengkap: namaLengkap,
                        username: username,
                        password: password,
                        roles: selectedRoles
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/pengaturan/user/${userData.id}`;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    let formHtml = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="nama_lengkap" value="${result.value.namaLengkap}">
                        <input type="hidden" name="username" value="${result.value.username}">
                    `;
                    
                    if (result.value.password) {
                        formHtml += `<input type="hidden" name="password" value="${result.value.password}">`;
                    }
                    
                    result.value.roles.forEach(roleId => {
                        formHtml += `<input type="hidden" name="roles[]" value="${roleId}">`;
                    });
                    
                    form.innerHTML = formHtml;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Delete
        function confirmDelete(userId, userName) {
            closeAllDropdowns();
            Swal.fire({
                title: 'Hapus User?',
                html: `
                <div class="text-left space-y-2">
                    <p class="text-gray-600">Anda akan menghapus user:</p>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-3">
                        <p class="font-bold text-red-800 text-base">${userName}</p>
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
                        if (form.action.includes(`/pengaturan/user/${userId}`)) {
                            form.submit();
                        }
                    });
                }
            });
        }

        // Initialize Role Tag Input with Autocomplete
        function initRoleTagInput(allRoles, preSelectedIds = []) {
            window.selectedRoleIds = [...preSelectedIds];
            const selectedRolesMap = new Map();
            
            const roleInput = document.getElementById('roleInput');
            const roleTagsContainer = document.getElementById('roleTagsContainer');
            const roleSuggestions = document.getElementById('roleSuggestions');
            
            roleTagsContainer.innerHTML = '';
            
            preSelectedIds.forEach(roleId => {
                const role = allRoles.find(r => r.id == roleId);
                if (role) {
                    selectedRolesMap.set(role.id, role);
                }
            });
            
            selectedRolesMap.forEach(role => {
                addRoleTagToDOM(role);
            });
            
            function addRoleTagToDOM(role) {
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-[#C41E3A] border border-red-100';
                tag.setAttribute('data-role-id', role.id);
                tag.innerHTML = `
                    <span>${role.name}</span>
                    <button type="button" class="ml-1.5 text-[#C41E3A] hover:text-[#A31830] transition-colors" onclick="removeRoleTag(${role.id})">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                `;
                roleTagsContainer.appendChild(tag);
            }
            
            function addRoleTag(role) {
                if (selectedRolesMap.has(role.id)) return;
                
                selectedRolesMap.set(role.id, role);
                window.selectedRoleIds = Array.from(selectedRolesMap.keys());
                addRoleTagToDOM(role);
            }
            
            window.removeRoleTag = function(roleId) {
                selectedRolesMap.delete(roleId);
                window.selectedRoleIds = Array.from(selectedRolesMap.keys());
                
                const tagElement = roleTagsContainer.querySelector(`[data-role-id="${roleId}"]`);
                if (tagElement) {
                    tagElement.remove();
                }
            };
            
            function showSuggestions(searchTerm) {
                const filtered = allRoles.filter(role => {
                    const isNotSelected = !selectedRolesMap.has(role.id);
                    const matchesSearch = role.name.toLowerCase().includes(searchTerm.toLowerCase());
                    return isNotSelected && (searchTerm === '' || matchesSearch);
                });
                
                if (filtered.length === 0) {
                    roleSuggestions.classList.add('hidden');
                    return;
                }
                
                roleSuggestions.innerHTML = '';
                filtered.forEach(role => {
                    const item = document.createElement('div');
                    item.className = 'px-4 py-2.5 text-xs font-semibold text-gray-700 cursor-pointer hover:bg-red-50 hover:text-[#C41E3A] transition-colors';
                    item.textContent = role.name;
                    item.onclick = function() {
                        addRoleTag(role);
                        roleInput.value = '';
                        roleSuggestions.classList.add('hidden');
                    };
                    roleSuggestions.appendChild(item);
                });
                
                roleSuggestions.classList.remove('hidden');
            }
            
            roleInput.addEventListener('input', function(e) {
                showSuggestions(e.target.value);
            });
            
            roleInput.addEventListener('focus', function() {
                showSuggestions(roleInput.value);
            });
            
            document.addEventListener('click', function(e) {
                if (!roleInput.contains(e.target) && !roleSuggestions.contains(e.target)) {
                    roleSuggestions.classList.add('hidden');
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
