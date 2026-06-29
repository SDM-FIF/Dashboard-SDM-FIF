<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Plotting Permission - Dashboard SDM FIF</title>
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
        <div class="mb-8 mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Plotting Permission</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Pengaturan izin akses khusus untuk peran pimpinan: <span class="font-bold text-[#C41E3A]">{{ $role->name }}</span></p>
            </div>
            <a href="{{ route('pengaturan') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-black font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Data Table Section Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            {{-- Card Header & Actions --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#C41E3A]">Manajemen Permission Hak Akses</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pemetaan hak akses per modul</p>
                    </div>

                    {{-- Actions Row --}}
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Save Changes Button --}}
                        <button onclick="savePermissions()"
                            class="bg-[#FBB03B] hover:bg-[#E09A2A] text-black font-semibold px-5 py-2.5 rounded-xl transition-all duration-300 shadow-sm hover:shadow flex items-center gap-2 text-sm">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>

                        {{-- Search Bar --}}
                        <div class="flex items-center">
                            <div class="relative flex items-center">
                                <input type="text" 
                                       id="searchInput"
                                       placeholder="Cari Modul atau Sub Modul..."
                                       class="h-[42px] px-4 pr-10 border border-gray-200 rounded-l-xl bg-[#F8FAFC] text-gray-700 text-sm focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-[#C41E3A] transition-all outline-none w-48 sm:w-64"
                                       onkeypress="if(event.key === 'Enter') { event.preventDefault(); searchTable(); }">
                                <button type="button" 
                                        id="clearSearch"
                                        onclick="clearSearchInput()"
                                        class="hidden absolute right-3 text-gray-400 hover:text-gray-600 cursor-pointer text-sm"
                                        title="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <button type="button" onclick="searchTable()" class="h-[42px] px-4 bg-[#C41E3A] hover:bg-[#A31830] text-white rounded-r-xl transition-all duration-200 text-sm flex items-center justify-center cursor-pointer">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        {{-- Export Dropdown --}}
                        <div class="relative">
                            <button id="exportBtn" class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:text-black hover:border-gray-300 transition-all duration-200 flex items-center space-x-2 shadow-sm">
                                <i class="fas fa-download"></i>
                                <span>Export</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Dropdown Export Menu -->
                            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-[999] overflow-hidden">
                                <a href="#" onclick="exportData('excel')" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-excel text-green-600 mr-2.5"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="#" onclick="exportData('csv')" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-csv text-blue-600 mr-2.5"></i>
                                    <span>Export CSV</span>
                                </a>
                                <a href="#" onclick="exportData('pdf')" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                    <i class="fas fa-file-pdf text-red-600 mr-2.5"></i>
                                    <span>Export PDF</span>
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
                    <table class="min-w-full w-full border-collapse" id="permissionTable">
                        <thead>
                            <tr class="bg-[#C41E3A] text-white">
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-56">Modul Parent</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-64">Nama Sub Modul</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Permissions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($permissionData as $data)
                            <tr class="hover:bg-[#F8FAFC] transition-colors duration-150">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-500">
                                    {{ $data['parent_module'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                    {{ $data['sub_module'] }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5" data-row-key="{{ $data['sub_module_key'] }}">
                                        @foreach($data['permissions'] as $permType => $perm)
                                            @if($perm['id'])
                                            <label class="flex items-center space-x-2 {{ $perm['is_disabled'] ?? false ? 'cursor-not-allowed opacity-60 bg-gray-50' : 'cursor-pointer hover:bg-red-50 hover:text-[#C41E3A] border border-gray-100 hover:border-red-100' }} px-3 py-2 rounded-xl transition-all">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $perm['id'] }}"
                                                       {{ $perm['has_permission'] ? 'checked' : '' }}
                                                       {{ ($perm['is_disabled'] ?? false) ? 'disabled' : '' }}
                                                       class="permission-checkbox w-4 h-4 text-[#C41E3A] border-gray-300 rounded focus:ring-red-200 focus:ring-2 flex-shrink-0 {{ ($perm['is_disabled'] ?? false) ? 'cursor-not-allowed' : '' }}"
                                                       data-row="{{ $data['sub_module_key'] }}"
                                                       data-type="{{ $permType }}"
                                                       onchange="handlePermissionChange(this)">
                                                {{-- Hidden input to preserve disabled checked permissions --}}
                                                @if(($perm['is_disabled'] ?? false) && $perm['has_permission'])
                                                    <input type="hidden" name="permissions[]" value="{{ $perm['id'] }}">
                                                @endif
                                                <span class="text-gray-700 text-xs font-semibold whitespace-nowrap overflow-hidden text-ellipsis {{ ($perm['is_disabled'] ?? false) ? 'text-gray-500' : '' }} group-hover:text-[#C41E3A] transition-colors">
                                                    {{ $perm['label'] }}
                                                    @if($perm['is_disabled'] ?? false)
                                                        <i class="fas fa-lock text-[10px] ml-1 text-gray-400" title="Permission ini tidak dapat diubah untuk role ini"></i>
                                                    @endif
                                                </span>
                                            </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-50 text-gray-300 rounded-full">
                                            <i class="fas fa-shield-alt text-4xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-500">Tidak ada permission ditemukan</p>
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
            
            const rowCheckboxes = document.querySelectorAll(`input[data-row="${row}"]`);
            
            if (type === 'all') {
                rowCheckboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = isChecked;
                    }
                });
            } else {
                if (!isChecked) {
                    const allCheckbox = Array.from(rowCheckboxes).find(cb => cb.dataset.type === 'all');
                    if (allCheckbox) {
                        allCheckbox.checked = false;
                    }
                } else {
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
            searchTable();
        }

        // Search function - search by Module or Sub Module
        function searchTable() {
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('permissionTable');
            const tr = table.getElementsByTagName('tr');

            if (input.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            for (let i = 1; i < tr.length; i++) {
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
            const currentUserRoles = @json(auth()->user()->roles->pluck('id'));
            const editingRoleId = {{ $role->id }};
            const isEditingOwnRole = currentUserRoles.includes(editingRoleId);
            
            let htmlContent = `
            <div class="text-left space-y-2">
                <p class="text-gray-600">Apakah Anda yakin ingin menyimpan perubahan permission untuk role:</p>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-3">
                    <p class="font-bold text-red-800 text-base">{{ $role->name }}</p>
                </div>
            `;
            
            if (isEditingOwnRole) {
                htmlContent += `
                <p class="text-xs text-amber-600 mt-3 font-semibold">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Anda perlu logout dan login kembali untuk melihat perubahan.
                </p>
                `;
            }
            htmlContent += `</div>`;
            
            Swal.fire({
                title: 'Simpan Perubahan?',
                html: htmlContent,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('permissionForm').submit();
                }
            });
        }

        // Export Data
        function exportData(format) {
            let url;
            
            switch(format) {
                case 'excel':
                    url = '{{ route('pengaturan.plotting.export.excel', ['roleId' => $role->id]) }}';
                    break;
                case 'csv':
                    url = '{{ route('pengaturan.plotting.export.csv', ['roleId' => $role->id]) }}';
                    break;
                case 'pdf':
                    url = '{{ route('pengaturan.plotting.export.pdf', ['roleId' => $role->id]) }}';
                    break;
                default:
                    return;
            }
            
            Swal.fire({
                title: 'Mengunduh...',
                html: `Sedang menyiapkan file ${format.toUpperCase()}`,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-2xl'
                },
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            window.location.href = url;
            
            setTimeout(() => {
                Swal.close();
            }, 1500);
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
    </script>
</body>
</html>
