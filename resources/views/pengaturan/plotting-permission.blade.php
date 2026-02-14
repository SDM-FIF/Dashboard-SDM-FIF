<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Plotting Permission - Dashboard SDM FIF</title>
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
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-[#C41E3A]">Plotting Permission</h1>
                    <p class="text-gray-600 mt-2">Role: <span class="font-semibold text-[#C41E3A]">{{ $role->name }}</span></p>
                </div>
                <a href="{{ route('pengaturan') }}" 
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- Data Table Section --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            {{-- Table Header Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Permission Hak Akses</h2>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    {{-- Save Button --}}
                    <button onclick="savePermissions()"
                        class="bg-[#FBB03B] hover:bg-orange-600 text-[#B91432] font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>

                    {{-- Right Side Controls --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Bar --}}
                        <div class="flex items-center relative">
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       placeholder="Cari Modul atau Sub Modul..."
                                       class="px-4 py-2 pr-10 border border-gray-300 rounded-l-lg bg-white text-gray-700 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 w-64"
                                       onkeypress="if(event.key === 'Enter') { event.preventDefault(); searchTable(); }">
                                <button type="button" 
                                        id="clearSearch"
                                        onclick="clearSearchInput()"
                                        class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                                        title="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <button type="button" onclick="searchTable()" class="px-4 py-2 bg-[#C41E3A] hover:bg-red-700 text-white rounded-r-lg transition-all duration-200 cursor-pointer">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        {{-- Export Button (Placeholder) --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]">
                                <a href="#" onclick="exportData('excel')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                    Export Excel
                                </a>
                                <a href="#" onclick="exportData('csv')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                    Export CSV
                                </a>
                                <a href="#" onclick="exportData('pdf')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <form id="permissionForm" method="POST" action="{{ route('pengaturan.plotting.update', $role->id) }}">
                @csrf
                @method('PUT')
                
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full" id="permissionTable">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-48">Modul Parent</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Sub Modul</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Permissions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($permissionData as $data)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $data['parent_module'] }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <strong>{{ $data['sub_module'] }}</strong>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" data-row-key="{{ $data['sub_module_key'] }}">
                                        @foreach($data['permissions'] as $permType => $perm)
                                            @if($perm['id'])
                                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 px-3 py-2 rounded-lg transition-colors min-w-0">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $perm['id'] }}"
                                                       {{ $perm['has_permission'] ? 'checked' : '' }}
                                                       class="permission-checkbox w-4 h-4 text-[#C41E3A] border-gray-300 rounded focus:ring-[#C41E3A] flex-shrink-0"
                                                       data-row="{{ $data['sub_module_key'] }}"
                                                       data-type="{{ $permType }}"
                                                       onchange="handlePermissionChange(this)">
                                                <span class="text-gray-700 text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $perm['label'] }}</span>
                                            </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center space-y-4">
                                        <i class="fas fa-shield-alt text-4xl text-gray-300"></i>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada permission</h3>
                                            <p class="text-sm text-gray-500">Belum ada permission yang tersedia untuk module ini.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Toggle Export Dropdown
        document.getElementById('exportBtn')?.addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('exportDropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const exportDropdown = document.getElementById('exportDropdown');
            const exportBtn = document.getElementById('exportBtn');
            
            if (exportBtn && !exportBtn.contains(e.target)) {
                exportDropdown.classList.add('hidden');
            }
        });

        // Handle permission checkbox changes
        function handlePermissionChange(checkbox) {
            const row = checkbox.dataset.row;
            const type = checkbox.dataset.type;
            const isChecked = checkbox.checked;
            
            // Get all checkboxes in the same row
            const rowCheckboxes = document.querySelectorAll(`input[data-row="${row}"]`);
            
            // If "All" checkbox is clicked
            if (type === 'all') {
                // When All is checked, check all other checkboxes
                // When All is unchecked, uncheck all other checkboxes
                rowCheckboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = isChecked;
                    }
                });
            } else {
                // If any other checkbox is unchecked, uncheck the "All" checkbox
                if (!isChecked) {
                    const allCheckbox = Array.from(rowCheckboxes).find(cb => cb.dataset.type === 'all');
                    if (allCheckbox) {
                        allCheckbox.checked = false;
                    }
                } else {
                    // If all other checkboxes (except All) are checked, check the All checkbox
                    const allCheckbox = Array.from(rowCheckboxes).find(cb => cb.dataset.type === 'all');
                    const otherCheckboxes = Array.from(rowCheckboxes).filter(cb => cb.dataset.type !== 'all');
                    const allOthersChecked = otherCheckboxes.every(cb => cb.checked);
                    
                    if (allCheckbox && allOthersChecked && otherCheckboxes.length > 0) {
                        allCheckbox.checked = true;
                    }
                }
            }
        }

        // Clear search input
        function clearSearchInput() {
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearch');
            
            input.value = '';
            clearBtn.classList.add('hidden');
            searchTable(); // Trigger search to show all rows
        }

        // Search function - search by Module or Sub Module
        function searchTable() {
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('permissionTable');
            const tr = table.getElementsByTagName('tr');

            // Show/hide clear button
            if (input.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            for (let i = 1; i < tr.length; i++) { // Start from 1 to skip header
                const tdModule = tr[i].getElementsByTagName('td')[0];
                const tdSubModule = tr[i].getElementsByTagName('td')[1];
                
                if (tdModule && tdSubModule) {
                    const txtModule = tdModule.textContent || tdModule.innerText;
                    const txtSubModule = tdSubModule.textContent || tdSubModule.innerText;
                    
                    if (txtModule.toLowerCase().indexOf(filter) > -1 || 
                        txtSubModule.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        // Save permissions function
        function savePermissions() {
            // Check if user is editing their own role
            const currentUserRoles = @json(auth()->user()->roles->pluck('id'));
            const editingRoleId = {{ $role->id }};
            const isEditingOwnRole = currentUserRoles.includes(editingRoleId);
            
            let htmlContent = `<p>Apakah Anda yakin ingin menyimpan perubahan permission untuk role <strong>{{ $role->name }}</strong>?</p>`;
            
            if (isEditingOwnRole) {
                htmlContent += `<p class="text-sm text-amber-600 mt-2"><i class="fas fa-exclamation-triangle mr-1"></i>Anda perlu logout dan login kembali untuk melihat perubahan.</p>`;
            }
            
            Swal.fire({
                title: 'Simpan Perubahan?',
                html: htmlContent,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('permissionForm').submit();
                }
            });
        }

        // Export Data (placeholder)
        function exportData(format) {
            Swal.fire({
                title: 'Export Data',
                html: `Fitur export ${format.toUpperCase()} akan segera tersedia.`,
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#C41E3A'
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
