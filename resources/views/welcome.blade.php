<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SIDOMA</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <img src="{{ asset('images/logo-sidoma.svg') }}" alt="Logo Kampus" class="h-64 w-auto">
                </div>

                <div class="mt-16 text-center">
                    <h2 class="text-4xl font-semibold text-gray-900">Sistem Informasi Akademik Dosen & Mahasiswa</h2>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        Selamat datang di Sistem Informasi Akademik Dosen & Mahasiswa.
                        Platform ini dirancang untuk memudahkan Mahasiswa dan Dosen dalam mengelola data perkuliahan, nilai,
                        KRS, dan jadwal secara efisien.
                    </p>
                    <p class="mt-2 text-gray-700 leading-relaxed">
                        Silakan masuk ke akun Anda atau daftar jika Anda adalah pengguna baru.
                    </p>

                    <div class="mt-8 flex justify-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Lihat Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm sm:text-left">
                        <div class="flex items-center gap-4">
                            <a href="https://github.com/yogaadipranata/sidoma" class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2c-2.4 0-4.7.9-6.5 2.5a9.5 9.5 0 0 0-2.3 6.6c0 2.4.9 4.7 2.5 6.5a9.5 9.5 0 0 0 6.6 2.3c2.4 0 4.7-.9 6.5-2.5a9.5 9.5 0 0 0 2.3-6.6c0-2.4-.9-4.7-2.5-6.5A9.5 9.5 0 0 0 12 2Zm0 1.9a7.6 7.6 0 0 1 5.4 2.2 7.6 7.6 0 0 1 2.2 5.4 7.6 7.6 0 0 1-2.2 5.4A7.6 7.6 0 0 1 12 20.1a7.6 7.6 0 0 1-5.4-2.2A7.6 7.6 0 0 1 4.4 12a7.6 7.6 0 0 1 2.2-5.4A7.6 7.6 0 0 1 12 3.9ZM8.8 8.7a.8.8 0 0 0-1.6 0v6.6c0 .4.3.8.8.8h.8c.4 0 .8-.3.8-.8V8.7Zm5.6 0a.8.8 0 0 0-1.6 0v6.6c0 .4.3.8.8.8h.8c.4 0 .8-.3.8-.8V8.7Zm-2.8-4.7c-.4 0-.8.3-.8.8v.8c0 .4.3.8.8.8s.8-.3.8-.8V4.8c0-.4-.3-.8-.8-.8Zm0 10.6a.8.8 0 0 0-1.6 0v.8c0 .4.3.8.8.8s.8-.3.8-.8v-.8Z" clip-rule="evenodd"/>
                                </svg>
                                GitHub
                            </a>
                        </div>
                    </div>

                    <div class="text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Developed by I Wayan Yoga Adi Pranata
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>