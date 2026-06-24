@extends('layouts.admin')

@section('title', 'Detail Buku')
@section('page_title', 'Detail Buku')

@section('admin_content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Detail Card -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-gray-600 mr-4 transition">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap tentang buku.</p>
                </div>
            </div>
            <div class="flex space-x-2">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('books.edit', $book->id) }}" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-600 font-semibold px-4 py-2 border border-yellow-200 rounded-lg text-sm transition">
                    <i class="fas fa-edit mr-1.5"></i> Edit
                </a>
                @endif
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Judul Buku</span>
                    <span class="text-base font-bold text-gray-800">{{ $book->judul }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Pengarang</span>
                    <span class="text-base text-gray-800">{{ $book->pengarang }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Penerbit</span>
                    <span class="text-base text-gray-800">{{ $book->penerbit }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Tahun Terbit</span>
                        <span class="text-base text-gray-800">{{ $book->tahun_terbit ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Kategori</span>
                        <span class="text-base text-gray-800">{{ $book->kategori ?? 'Umum' }}</span>
                    </div>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Status Ketersediaan</span>
                    <div class="mt-1">
                        @if($book->stok > 0)
                            <div class="space-y-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                                    Tersedia ({{ $book->stok }} eksemplar)
                                </span>
                                
                                <form action="{{ route('books.borrow', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition flex items-center justify-center" onclick="return confirm('Apakah Anda yakin ingin meminjam buku ini?')">
                                        <i class="fas fa-hand-holding-heart mr-2"></i> Pinjam Buku Ini
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-2 h-2 mr-2 bg-red-500 rounded-full"></span>
                                Sedang Habis Dipinjam
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Loan History Card -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-history text-blue-500 mr-2"></i>Riwayat Transaksi Peminjaman Buku ini
            </h3>
            <p class="text-sm text-gray-500 mt-1">Daftar anggota yang pernah atau sedang meminjam buku ini.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Anggota</th>
                        <th class="px-6 py-4">Tanggal Pinjam</th>
                        <th class="px-6 py-4">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($book->loans as $loan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $loan->user->name }}</td>
                        <td class="px-6 py-4"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($loan->return_date)
                                <i class="far fa-calendar-check mr-2 text-green-500"></i>{{ $loan->return_date->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400 italic">Belum dikembalikan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $loan->status === 'returned' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $loan->status === 'returned' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                {{ $loan->status === 'returned' ? 'Selesai' : 'Dipinjam' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat peminjaman untuk buku ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
