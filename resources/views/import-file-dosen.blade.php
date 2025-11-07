@extends('layouts.main')

@section('title', 'Import Data Dosen')
@section('page-title', 'Import Data Dosen')

@section('content')
    <div class="flex flex-col w-full space-y-8 font-nunito">

        {{-- Step Progress --}}
        <div class="flex items-center space-x-8">
            {{-- Step 1 --}}
            <div class="flex items-center space-x-3 opacity-50">
                <div class="flex items-center justify-center bg-gray-200 w-10 h-10 rounded-2xl">
                    <i class="fas fa-file-download text-gray-500"></i>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-500">Template</p>
                    <p class="text-sm text-gray-400 -mt-1">Unduh Template</p>
                </div>
            </div>

            <i class="fas fa-chevron-right text-gray-400"></i>

            {{-- Step 2 (Active) --}}
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center bg-amber-400 w-10 h-10 rounded-2xl">
                    <i class="fas fa-upload text-redmain"></i>
                </div>
                <div>
                    <p class="text-lg font-semibold text-amber-500">Import</p>
                    <p class="text-sm text-gray-500 -mt-1">Import File</p>
                </div>
            </div>

            <i class="fas fa-chevron-right text-gray-400"></i>

            {{-- Step 3 --}}
            <div class="flex items-center space-x-3 opacity-50">
                <div class="flex items-center justify-center bg-gray-200 w-10 h-10 rounded-2xl">
                    <i class="fas fa-check text-gray-500"></i>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-500">Selesai</p>
                    <p class="text-sm text-gray-400 -mt-1">Hasil Import</p>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white shadow-md rounded-xl border border-gray-100 p-10 w-full mx-auto">

            {{-- File Upload Section --}}
            <div class="flex items-center justify-between mb-8">
                <div class="w-full mr-4">
                    <label
                        class="block bg-gray-100 border border-gray-300 rounded-md px-4 py-3 text-gray-600 cursor-pointer">
                        <input type="file" class="hidden" id="fileInput" />
                        <span id="fileName">Choose File  No File Chosen</span>
                    </label>
                </div>
                <button
                    class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-8 py-3 rounded-md shadow flex items-center">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
            </div>

            {{-- Preview Section --}}
            <div>
                <h2 class="text-xl font-semibold text-red-600 mb-1">Preview Import Dosen</h2>
                <p class="text-sm text-gray-500 mb-6">Hanya data valid yang akan diproses</p>

                {{-- Buttons --}}
                <div class="flex items-center space-x-4 mb-6">
                    <button class="bg-amber-400 hover:bg-amber-500 text-redmain px-6 py-2 rounded-xl shadow flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl shadow flex items-center">
                        <i class="fas fa-list mr-2"></i> Log Import
                    </button>
                    <div class="ml-auto flex items-center space-x-2">
                        <select class="border border-gray-300 rounded-md px-3 py-2 text-gray-700">
                            <option>Filter</option>
                        </select>
                        <button class="border border-gray-300 rounded-md px-3 py-2 text-gray-700 flex items-center">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                    </div>
                </div>

                {{-- Table Preview --}}
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-redmain text-white">
                            <tr>
                                <th class="px-4 py-3">VALID</th>
                                <th class="px-4 py-3">No. Registrasi</th>
                                <th class="px-4 py-3">NIP</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">JFA</th>
                                <th class="px-4 py-3">Lokasi Kerja</th>
                                <th class="px-4 py-3">Status Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 8; $i++)
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-3 text-center text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- JS untuk menampilkan nama file yang diupload --}}
    <script>
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');

        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'Choose File  No File Chosen';
            }
        });
    </script>
@endsection