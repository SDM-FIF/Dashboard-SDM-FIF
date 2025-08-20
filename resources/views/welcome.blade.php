<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Selamat Datang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>

<body class="h-full bg-gray-100 font-nunito">
    
    <div class="min-h-full flex">
        <!-- Left side - Welcome section -->
        <div class="flex-1/4 bg-white hidden items-center justify-center px-4 sm:flex">
            <div class="mx-auto w-full max-w-sm">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 text-center">
                    Selamat Datang
                </h1>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="flex-1 bg-redmain flex items-center justify-center px-4 ">
            <div class="mx-auto w-full max-w-sm">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-8 text-center">Login</h2>
                </div>

                <form class="space-y-6" action="/dashboard" method="">
                    <div>
                        <label for="username" class="block text-sm font-medium text-white mb-2">
                            Username
                        </label>
                        <input id="username" name="username" type="text" required
                            class="w-full px-3 py-2 border bg-white border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="Masukkan username">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full px-3 py-2 pr-10 border bg-white border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Masukkan password">
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle"
                                onclick="togglePassword()">
                                <svg id="eye-icon" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-slash-icon" class="h-5 w-5 text-gray-400 hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 text-right">
                            <a href="#" class="text-sm text-red-200 hover:text-white">
                                Forgot Password
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 ease-in-out transform hover:scale-105">
                            LOGIN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById('dashboardArrow');

            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-open dropdown if current route is one of the dashboard sub-pages
        document.addEventListener('DOMContentLoaded', function () {
            const currentRoute = window.location.pathname;
            const dashboardRoutes = ['{{ route("dashboard") }}', '{{ route("data-dosen") }}', '{{ route("data-tpa") }}'];

            if (dashboardRoutes.some(route => currentRoute.includes(route.split('/').pop()))) {
                const dropdown = document.getElementById('dashboardDropdown');
                const arrow = document.getElementById('dashboardArrow');
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    </script>
</body>

</html>