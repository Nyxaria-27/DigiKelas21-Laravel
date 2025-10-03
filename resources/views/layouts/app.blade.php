<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name', 'EL-SMK21'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Breeze + Vite --}}
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">
    <div class="min-h-screen flex flex-col">
        {{-- NAVBAR --}}
        <nav class="bg-white shadow">
            <div class="container mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="font-semibold text-lg text-indigo-600">{{ config('app.name','EL-SMK21') }}</a>
                    @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">Dashboard</a>
                    <a href="{{ route('kelas.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Kelas</a>
                    @include('partials.notifications_dropdown')
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @guest
                    <a href="{{ route('login') }}" class="text-sm px-3 py-1 rounded-md hover:bg-indigo-50">Login</a>
                    <a href="{{ route('register') }}" class="text-sm px-3 py-1 bg-indigo-600 text-white rounded-md">Register</a>
                    @else
                    <span class="text-sm text-gray-600 mr-2">Halo, <strong>{{ auth()->user()->nama ?? auth()->user()->name }}</strong> <span class="text-xs text-gray-400">({{ auth()->user()->role }})</span></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm px-3 py-1 bg-red-500 text-white rounded-md">Logout</button>
                    </form>
                    @endguest
                </div>
            </div>
        </nav>

        {{-- CONTENT --}}
        <main class="container mx-auto px-4 py-6 flex-1">
            @include('partials.flash')
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- SIDEBAR --}}
                <aside class="lg:col-span-1 bg-white border rounded-lg p-4 shadow-sm">
                    @auth
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-700">Menu</h3>
                        <ul class="mt-2 space-y-2 text-sm">
                            <li><a href="{{ route('dashboard') }}" class="block hover:text-indigo-600">Dashboard</a></li>
                            <li><a href="{{ route('kelas.index') }}" class="block hover:text-indigo-600">Kelas</a></li>
                            @if(auth()->user()->role === 'Guru')
                            <li><a href="{{ route('kelas.create') }}" class="block hover:text-indigo-600">Buat Kelas</a></li>
                            @endif
                            @if(auth()->user()->role === 'Siswa')
                            <li>
                                <form action="{{ route('kelas.join') }}" method="POST" class="flex gap-2 mt-2">
                                    @csrf
                                    <input type="text" name="kode_kelas" placeholder="Masukkan kode kelas" class="px-2 py-1 border rounded w-full text-sm" />
                                    <button class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Gabung</button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs text-gray-500">Akun Demo</h4>
                        <p class="text-xs text-gray-600 mt-1">Email: <span class="font-medium">demo@smk21.test</span></p>
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Silakan login untuk akses fitur.</p>
                    @endauth
                </aside>

                {{-- MAIN CONTENT --}}
                <section class="lg:col-span-3">
                    <h1 class="text-2xl font-semibold mb-4">@yield('page_title')</h1>
                    <div class="space-y-6">
                        @yield('content')
                    </div>
                </section>
            </div>
        </main>

        <footer class="bg-white border-t">
            <div class="container mx-auto px-4 py-4 text-sm text-center text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name','EL-SMK21') }} — SMKN 21 Jakarta
            </div>
        </footer>
    </div>
</body>

</html>