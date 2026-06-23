@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')
<!-- Welcome Message -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-md p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h2>
    <p class="mt-1 opacity-90">Sistem Informasi Perpustakaan (SIPerpus) siap membantu Anda mengelola data buku dan transaksi peminjaman.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Koleksi Buku</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalBooks) }}</p>
                <p class="text-xs text-blue-500 mt-1">Judul Buku Terdaftar</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-full">
                <i class="fas fa-book text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Anggota</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalUsers) }}</p>
                <p class="text-xs text-green-500 mt-1">Pengguna Terdaftar</p>
            </div>
            <div class="bg-green-50 p-4 rounded-full">
                <i class="fas fa-users text-green-500 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Sedang Dipinjam</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $activeLoans }}</p>
                <p class="text-xs text-yellow-500 mt-1">Transaksi Aktif</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-full">
                <i class="fas fa-exchange-alt text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Sudah Dikembalikan</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $returnedLoans }}</p>
                <p class="text-xs text-purple-500 mt-1">Transaksi Selesai</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-full">
                <i class="fas fa-check-circle text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart and Activities/Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart.js Section -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border flex flex-col">
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>Tren Peminjaman Buku (7 Hari Terakhir)
            </h3>
        </div>
        <div class="flex-1 min-h-[300px] relative">
            <canvas id="loansChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Aksi Cepat</h3>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('books.create') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm transition text-center block">
                    <i class="fas fa-plus mr-2"></i> Tambah Buku Baru
                </a>
                <a href="{{ route('loans.create') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm transition text-center block">
                    <i class="fas fa-exchange-alt mr-2"></i> Buat Transaksi Pinjam
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h3>
            </div>
            <div class="space-y-4 max-h-[250px] overflow-y-auto pr-1">
                @forelse($recentActivities as $activity)
                <div class="flex items-start p-2 rounded-lg hover:bg-gray-50 transition duration-150">
                    <div class="bg-{{ $activity->color }}-100 p-2.5 rounded-xl flex-shrink-0 mt-0.5">
                        <i class="fas fa-{{ $activity->icon }} text-{{ $activity->color }}-600"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $activity->title }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $activity->description }}</p>
                        <span class="text-[10px] text-gray-400 mt-1 block"><i class="far fa-clock mr-1"></i>{{ $activity->time }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="mt-6 bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-history text-blue-500 mr-2"></i>Daftar Peminjaman Terbaru
        </h3>
        <a href="{{ route('loans.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold flex items-center transition">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Nama Anggota</th>
                    <th class="px-6 py-4">Judul Buku</th>
                    <th class="px-6 py-4">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->customer }}</td>
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
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('loansChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Transaksi Pinjam',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: 'rgba(37, 99, 235, 1)', // Tailwind blue-600
                    backgroundColor: 'rgba(59, 130, 246, 0.1)', // Tailwind blue-500 with opacity
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(243, 244, 246, 1)' // Gray-100
                        },
                        ticks: {
                            stepSize: 1,
                            color: '#6B7280' // Gray-500
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280' // Gray-500
                        }
                    }
                }
            }
        });
    });
</script>
@endpush