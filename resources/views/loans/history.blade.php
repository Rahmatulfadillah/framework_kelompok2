@extends('layouts.admin')

@section('title', 'Riwayat Peminjaman Saya')
@section('page_title', 'Riwayat Peminjaman Saya')

@section('admin_content')
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <!-- Header -->
    <div class="p-6 border-b">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Riwayat Peminjaman Buku</h3>
            <p class="text-sm text-gray-500 mt-1">Lihat semua peminjaman buku Anda dengan status dan tanggal.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 w-12 text-center">ID</th>
                    <th class="px-6 py-4">Judul Buku</th>
                    <th class="px-6 py-4">Pengarang</th>
                    <th class="px-6 py-4">Tgl Pinjam</th>
                    <th class="px-6 py-4">Tgl Kembali</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center text-gray-500 font-semibold">#{{ $loan->id }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $loan->book->judul }}</td>
                    <td class="px-6 py-4 max-w-xs truncate">{{ $loan->book->pengarang }}</td>
                    <td class="px-6 py-4"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @if($loan->return_date)
                            <i class="far fa-calendar-check mr-2 text-green-500"></i>{{ $loan->return_date->format('d/m/Y') }}
                        @else
                            <span class="text-xs font-semibold px-2 py-0.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded">Belum Dikembalikan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $loan->status === 'returned' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $loan->status === 'returned' ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                            {{ $loan->status === 'returned' ? 'Dikembalikan' : 'Sedang Dipinjam' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada peminjaman buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Info Box -->
    @if($loans->count() > 0)
    <div class="p-6 border-t bg-blue-50 border-blue-200">
        <p class="text-sm text-blue-800">
            <i class="fas fa-info-circle mr-2"></i>
            Total peminjaman Anda: <strong>{{ $loans->count() }}</strong> |
            Buku yang masih dipinjam: <strong>{{ $loans->where('status', 'borrowed')->count() }}</strong>
        </p>
    </div>
    @endif
</div>
@endsection
