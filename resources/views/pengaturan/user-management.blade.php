<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>User Management - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
            border-bottom: 1px solid #f3f4f6;
        }
        #roleSuggestions > div:last-child {
            border-bottom: none;
        }
        .swal2-popup {
            font-family: 'Nunito', sans-serif !important;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Pengaturan User</h1>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Pengaturan User</h2>
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
                        <form method="GET" action="{{ route('pengaturan.user-management') }}" class="flex items-center relative">
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Cari Nama/Username..."
                                       class="px-4 py-2 pr-10 border border-gray-300 rounded-l-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 w-64">
                                @if($search)
                                <a href="{{ route('pengaturan.user-management') }}" 
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
                                <a href="{{ route('pengaturan.user.export.excel', ['search' => $search]) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="{{ route('pengaturan.user.export.csv', ['search' => $search]) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="{{ route('pengaturan.user.export.pdf', ['search' => $search]) }}" 
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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Username</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Roles</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $index => $user)
                        @php
                            $totalUsers = count($users);
                            $isLastTwo = ($index >= $totalUsers - 2);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $user->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <strong>{{ $user->nama_lengkap }}</strong>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                {{ $user->username }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                @if($user->roles->count() > 0)
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                @else
                                    <span class="text-gray-400 italic">No roles</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                @if(Auth::check() && Auth::user()->hasRole('Super Admin'))
                                {{-- Dropdown Button --}}
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleActionDropdown({{ $user->id }}, event, {{ $isLastTwo ? 'true' : 'false' }})" 
                                            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A]">
                                        Aksi
                                        <i id="arrow{{ $user->id }}" class="fas fa-chevron-down ml-2 -mr-1 h-5 w-5 transition-transform duration-200"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div id="actionDropdown{{ $user->id }}" 
                                         class="hidden absolute right-0 w-40 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[100]">
                                        <div class="py-1" role="menu">
                                            {{-- Edit --}}
                                            <button onclick='openEditModal({{ json_encode([
                                                "id" => $user->id,
                                                "nama_lengkap" => $user->nama_lengkap,
                                                "username" => $user->username,
                                                "role_ids" => $user->roles->pluck("id")->toArray()
                                            ]) }})'
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                <i class="fas fa-edit mr-2 text-green-600"></i>
                                                Edit
                                            </button>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('pengaturan.user.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->nama_lengkap }}')"
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-4">
                                    <i class="fas fa-users text-4xl text-gray-300"></i>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data user</h3>
                                        <p class="text-sm text-gray-500">Belum ada user yang terdaftar.</p>
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
            const button = event.currentTarget;
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
            
            // Build role input with tags HTML
            let rolesHtml = `
                <div class="text-left mt-4 px-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Role:</label>
                    <div class="relative">
                        <div id="roleTagsContainer" class="flex flex-wrap gap-2 mb-2"></div>
                        <input type="text" 
                               id="roleInput" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-[#C41E3A]" 
                               placeholder="Ketik untuk mencari role..."
                               autocomplete="off">
                        <div id="roleSuggestions" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Tambah User Baru',
                html: `
                    <input id="namaLengkap" class="swal2-input" placeholder="Nama Lengkap">
                    <input id="username" class="swal2-input" placeholder="Username">
                    <input id="password" type="password" class="swal2-input" placeholder="Password">
                    ${rolesHtml}
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
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
                    // Create form and submit
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
                    
                    // Add roles
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
            closeAllDropdowns(); // Close any open dropdowns
            
            const roles = @json($roles);
            
            // Build role input with tags HTML
            let rolesHtml = `
                <div class="text-left mt-4 px-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Role:</label>
                    <div class="relative">
                        <div id="roleTagsContainer" class="flex flex-wrap gap-2 mb-2"></div>
                        <input type="text" 
                               id="roleInput" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C41E3A] focus:border-[#C41E3A]" 
                               placeholder="Ketik untuk mencari role..."
                               autocomplete="off">
                        <div id="roleSuggestions" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Edit User',
                html: `
                    <input id="namaLengkap" class="swal2-input" placeholder="Nama Lengkap" value="${userData.nama_lengkap}">
                    <input id="username" class="swal2-input" placeholder="Username" value="${userData.username}">
                    <input id="password" type="password" class="swal2-input" placeholder="Password (kosongkan jika tidak diubah)">
                    ${rolesHtml}
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
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
                    // Create form and submit
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
                    
                    // Add password only if provided
                    if (result.value.password) {
                        formHtml += `<input type="hidden" name="password" value="${result.value.password}">`;
                    }
                    
                    // Add roles
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
            closeAllDropdowns(); // Close any open dropdowns
            Swal.fire({
                title: 'Hapus User?',
                html: `Apakah Anda yakin ingin menghapus user <strong>${userName}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
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
            
            // Clear container first
            roleTagsContainer.innerHTML = '';
            
            // Initialize pre-selected roles
            preSelectedIds.forEach(roleId => {
                const role = allRoles.find(r => r.id == roleId);
                if (role) {
                    selectedRolesMap.set(role.id, role);
                }
            });
            
            // Render pre-selected tags
            selectedRolesMap.forEach(role => {
                addRoleTagToDOM(role);
            });
            
            // Add role tag to DOM
            function addRoleTagToDOM(role) {
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-200 text-gray-700';
                tag.setAttribute('data-role-id', role.id);
                tag.innerHTML = `
                    ${role.name}
                    <button type="button" class="ml-2 text-gray-500 hover:text-red-600" onclick="removeRoleTag(${role.id})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                roleTagsContainer.appendChild(tag);
            }
            
            // Add role tag
            function addRoleTag(role) {
                if (selectedRolesMap.has(role.id)) return;
                
                selectedRolesMap.set(role.id, role);
                window.selectedRoleIds = Array.from(selectedRolesMap.keys());
                addRoleTagToDOM(role);
            }
            
            // Remove role tag
            window.removeRoleTag = function(roleId) {
                selectedRolesMap.delete(roleId);
                window.selectedRoleIds = Array.from(selectedRolesMap.keys());
                
                // Remove tag from DOM
                const tagElement = roleTagsContainer.querySelector(`[data-role-id="${roleId}"]`);
                if (tagElement) {
                    tagElement.remove();
                }
            };
            
            // Filter and show suggestions
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
                    item.className = 'px-4 py-2 cursor-pointer hover:bg-purple-500 hover:text-white transition-colors';
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
            
            // Input event listener
            roleInput.addEventListener('input', function(e) {
                showSuggestions(e.target.value);
            });
            
            // Focus event - show all available roles
            roleInput.addEventListener('focus', function() {
                showSuggestions(roleInput.value);
            });
            
            // Click outside to close
            document.addEventListener('click', function(e) {
                if (!roleInput.contains(e.target) && !roleSuggestions.contains(e.target)) {
                    roleSuggestions.classList.add('hidden');
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

        // Show validation errors with SweetAlert
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
            confirmButtonColor: '#C41E3A',
            confirmButtonText: 'OK'
        });
        @endif
    </script>
</body>
</html>
