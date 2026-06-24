@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white flex flex-col flex-shrink-0">
        <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-700">
            <i class="fas fa-book-reader text-blue-400 text-xl mr-2"></i>
            <span class="text-xl font-bold tracking-wider">SIPerpus</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <div class="px-4 py-2 text-xs text-gray-400 uppercase tracking-wider">Menu Utama</div>
            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center px-4 py-3 rounded-lg mx-2 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase tracking-wider">Manajemen</div>
            <a href="{{ route('books.index') }}"
               class="sidebar-link flex items-center px-4 py-3 rounded-lg mx-2 {{ request()->routeIs('books.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <i class="fas fa-book w-5"></i>
                <span class="ml-3">Daftar Buku</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('loans.index') }}"
               class="sidebar-link flex items-center px-4 py-3 rounded-lg mx-2 {{ request()->routeIs('loans.*') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <i class="fas fa-exchange-alt w-5"></i>
                <span class="ml-3">Peminjaman</span>
            </a>
            @endif

            <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase tracking-wider">Akun</div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="sidebar-link flex items-center px-4 py-3 text-red-400 hover:bg-gray-700 hover:text-red-300 rounded-lg mx-2 mt-auto">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto flex flex-col bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10 border-b">
            <div class="flex items-center justify-between px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 pl-4">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3B82F6&color=fff&size=40&bold=true"
                             alt="Avatar" class="w-10 h-10 rounded-full border-2 border-blue-500">
                        <div>
                            <span class="text-sm font-semibold text-gray-700 block">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg text-green-700 shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500 text-xl"></i>
                    <div>
                        <p class="font-semibold">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-700 shadow-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500 text-xl"></i>
                    <div>
                        <p class="font-semibold">Gagal</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('admin_content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} SIPerpus. All rights reserved. | Built with ❤️ using Laravel & Tailwind CSS</p>
        </footer>
    </div>
</div>
@endsection
