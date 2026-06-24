@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')
<!-- Welcome Message -->
<div class="bg-gradient-to-r from-green-600 to-teal-700 rounded-xl shadow-md p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold">Selamat datang, {{ Auth::user()->name }}! 👋</h2>
    <p class="mt-1 opacity-90">Di sini Anda dapat melihat status peminjaman buku Anda saat ini.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Buku Sedang Dipinjam</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $activeLoans }}</p>
                <p class="text-xs text-yellow-500 mt-1">Harap kembalikan tepat waktu</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-full">
                <i class="fas fa-book-reader text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Riwayat Selesai</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $returnedLoans }}</p>
                <p class="text-xs text-blue-500 mt-1">Buku yang sudah dikembalikan</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-full">
                <i class="fas fa-check text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="mt-6 bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-history text-blue-500 mr-2"></i>Peminjaman Terbaru Saya
        </h3>
        <a href="{{ route('loans.history') }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold flex items-center transition">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Judul Buku</th>
                    <th class="px-6 py-4">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->id }}</td>
                    <td class="px-6 py-4 max-w-xs truncate">{{ $order->product }}</td>
                    <td class="px-6 py-4"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $order->total }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $order->status === 'Returned' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $order->status === 'Returned' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                            {{ $order->status === 'Returned' ? 'Selesai' : 'Dipinjam' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
