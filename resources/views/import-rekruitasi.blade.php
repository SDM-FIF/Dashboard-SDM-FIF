@extends('layouts.main') {{-- sesuaikan dengan layout navbar kamu --}}

@section('content')
    <div class="flex flex-col w-full bg-gray-50 min-h-screen px-8 py-10">
        <!-- Judul Halaman -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-8">Rekruitasi Dosen</h1>

        <!-- Step Progress -->
        <div class="flex items-center space-x-8 mb-12">
            <!-- Step 1: Template -->
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center bg-[#fbb03b] w-12 h-12 rounded-2xl shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-redmain" fill="currentColor"
                        viewBox="0 0 30 30" stroke="currentColor">
                        <path
                            d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-[#B71C1C]">Template</p>
                    <p class="text-sm text-gray-500 -mt-1">Unduh Template</p>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <!-- Step 2: Import -->
            <div class="flex items-center space-x-3 opacity-50">
                <div class="flex items-center justify-center bg-gray-200 w-12 h-12 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-6-4l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-500">Import</p>
                    <p class="text-sm text-gray-400 -mt-1">Import File</p>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <!-- Step 3: Selesai -->
            <div class="flex items-center space-x-3 opacity-50">
                <div class="flex items-center justify-center bg-gray-200 w-12 h-12 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-500">Selesai</p>
                    <p class="text-sm text-gray-400 -mt-1">Hasil Import</p>
                </div>
            </div>
        </div>

        <!-- Card Utama -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 py-60 px-80 w-7xl flex flex-col items-center justify-center text-center mx-auto">
            <div class="flex flex-col items-center space-y-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-redmain" fill="currentColor" viewBox="0 0 30 30"
                    stroke="currentColor">
                    <path
                        d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z">
                    </path>
                </svg>

                <div>
                    <h2 class="text-xl font-semibold text-[#B71C1C]">Template Import Rekruitasi Dosen</h2>
                    <p class="text-gray-500 mt-1">Klik tombol dibawah ini</p>
                </div>

                <button
                    class="bg-[#F4B400] hover:bg-[#EAA600] transition text-[#B71C1C] font-semibold px-6 py-2 rounded-lg shadow-md flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#B71C1C]" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Unduh Template</span>
                </button>
            </div>
        </div>
    </div>
@endsection