<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data TPA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for exact match */
        .dropdown-arrow {
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 12px center;
            background-size: 16px;
        }
        
        select {
            appearance: none;
        }
        
        .table-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
    </style>
</head>
<x-navbar />
<body class="flex bg-gray-50 min-h-screen font-nunito">
    
    <!-- Top Navigation Bar -->
    

    <!-- Main Content -->
    <div class="flex-1 md:ml-24 p-6">
        <x-topbar />
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Kelola Data TPA</h1>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
            <!-- Filter Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <!-- Lokasi Kerja -->
                <div>
                    <label class="block text-base font-semibold text-red-600 mb-3">Lokasi Kerja</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="bandung">Bandung</option>
                            <option value="surabaya">Surabaya</option>
                        </select>
                    </div>
                </div>

                <!-- JFA -->
                <div>
                    <label class="block text-base font-semibold text-red-600 mb-3">Pangkat/Gol.</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            <option value="asisten-ahli">Asisten Ahli</option>
                            <option value="lektor">Lektor</option>
                            <option value="lektor-kepala">Lektor Kepala</option>
                            <option value="profesor">Profesor</option>
                        </select>
                    </div>
                </div>

                <!-- Kelompok Keahlian -->
                <div>
                    <label class="block text-base font-semibold text-red-600 mb-3">Pendidikan Terakhir</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            <option value="teknik-informatika">Teknik Informatika</option>
                            <option value="sistem-informasi">Sistem Informasi</option>
                            <option value="teknik-elektro">Teknik Elektro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Status Pegawai -->
                <div>
                    <label class="block text-base font-semibold text-red-600 mb-3">Status Pegawai</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Pilih</option>
                            <option value="pns">PNS</option>
                            <option value="kontrak">Kontrak</option>
                            <option value="honorer">Honorer</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Button -->
            <div class="flex justify-start">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-red-600">Data TPA</h2>
                </div>
                
                <!-- Action Buttons Row -->
                <div class="flex items-center justify-between">
                    <!-- Tambah Data Button -->
                    <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        Tambah Data
                    </button>
                    
                    <!-- Right Side Controls -->
                    <div class="flex items-center space-x-4">
                        <!-- Filter Dropdown -->
                        <div class="relative">
                            <select class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <option value="">Filter</option>
                                <option value="nama">Nama</option>
                                <option value="jfa">JFA</option>
                                <option value="lokasi">Lokasi</option>
                            </select>
                        </div>
                        
                        <!-- Sort Dropdown -->
                        <div class="relative">
                            <select class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <option value="terbaru">Terbaru</option>
                                <option value="terlama">Terlama</option>
                                <option value="nama-az">Nama A-Z</option>
                                <option value="nama-za">Nama Z-A</option>
                            </select>
                        </div>
                        
                        <!-- Export Dropdown -->
                        <div class="relative">
                            <select class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg dropdown-arrow focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <option value="">Export</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead>
                        <tr class="table-header text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">No. Registrasi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Pangkat/Gol.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi Kerja</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Table Body - Empty rows for now -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                            <td class="px-6 py-4 text-sm text-gray-900"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Add interactive functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Filter button functionality
            const filterBtn = document.querySelector('button:has(i.fa-filter)');
            filterBtn.addEventListener('click', function() {
                this.classList.add('animate-pulse');
                setTimeout(() => {
                    this.classList.remove('animate-pulse');
                }, 200);
            });
            
            // Add data button functionality
            const tambahDataBtn = document.querySelector('button:contains("Tambah Data")');
            if (tambahDataBtn) {
                tambahDataBtn.addEventListener('click', function() {
                    alert('Fitur Tambah Data akan segera tersedia');
                });
            }
            
            // Make dropdowns more interactive
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500');
                });
                
                select.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500');
                });
            });
        });
    </script>
</body>
</html>