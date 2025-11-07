<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Vite assets --}}
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

<x-navbar /> {{-- Navbar component kamu --}}

<body class="flex bg-gray-50 min-h-screen font-nunito">
    <div class="flex-1 md:ml-24 p-6">
        <x-topbar /> {{-- Topbar component kamu --}}

        {{-- Page Title --}}
        @hasSection('page-title')
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">@yield('page-title')</h1>
            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')
    </div>

    {{-- Global JS behavior (optional) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate filter button
            const filterBtn = document.querySelector('button:has(i.fa-filter)');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    this.classList.add('animate-pulse');
                    setTimeout(() => this.classList.remove('animate-pulse'), 200);
                });
            }

            // Example: handle 'Tambah Data' alert
            const tambahDataBtn = document.querySelector('button:contains("Tambah Data")');
            if (tambahDataBtn) {
                tambahDataBtn.addEventListener('click', function() {
                    alert('Fitur Tambah Data akan segera tersedia');
                });
            }

            // Style selects
            document.querySelectorAll('select').forEach(select => {
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
