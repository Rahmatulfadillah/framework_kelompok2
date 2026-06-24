@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('page_title', 'Detail Peminjaman')

@section('admin_content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('loans.index') }}" class="text-gray-400 hover:text-gray-600 mr-4 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Detail Transaksi #{{ $loan->id }}</h3>
                <p class="text-sm text-gray-500 mt-1">Rincian data transaksi peminjaman buku.</p>
            </div>
        </div>
        <div>
            @if($loan->status === 'borrowed')
                <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin menandai buku ini sudah dikembalikan?')">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition shadow-sm flex items-center">
                        <i class="fas fa-check-double mr-1.5 text-xs"></i> Kembalikan Buku
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- Status Banner -->
        <div class="p-4 rounded-xl flex items-center justify-between {{ $loan->status === 'returned' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-yellow-50 border border-yellow-200 text-yellow-800' }}">
            <div class="flex items-center">
                <i class="fas {{ $loan->status === 'returned' ? 'fa-check-circle text-green-500' : 'fa-hourglass-half text-yellow-500' }} text-xl mr-3"></i>
                <div>
                    <p class="font-bold">Status: {{ $loan->status === 'returned' ? 'Selesai' : 'Sedang Dipinjam' }}</p>
                    <p class="text-xs opacity-90">
                        {{ $loan->status === 'returned' 
                            ? 'Buku telah dikembalikan dan stok telah dipulihkan.' 
                            : 'Buku masih berada di tangan anggota. Harap ingatkan untuk mengembalikan tepat waktu.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Info -->
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Nama Anggota (Peminjam)</span>
                    <span class="text-base font-bold text-gray-800">{{ $loan->user->name }}</span>
                    <span class="block text-xs text-gray-500 mt-0.5">{{ $loan->user->email }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Buku yang Dipinjam</span>
                    <a href="{{ route('books.show', $loan->book->id) }}" class="text-base font-bold text-blue-600 hover:text-blue-800 transition block">
                        {{ $loan->book->judul }} <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                    </a>
                    <span class="block text-xs text-gray-500 mt-0.5">Pengarang: {{ $loan->book->pengarang }}</span>
                </div>
            </div>

            <!-- Right Info -->
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Tanggal Pinjam</span>
                    <span class="text-base font-semibold text-gray-800"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $loan->loan_date->format('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Tanggal Pengembalian</span>
                    @if($loan->return_date)
                        <span class="text-base font-semibold text-green-700"><i class="far fa-calendar-check mr-2 text-green-500"></i>{{ $loan->return_date->format('d F Y') }}</span>
                    @else
                        <span class="text-base font-semibold text-gray-400 italic">Belum dikembalikan</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer actions -->
        <div class="border-t pt-6 flex justify-between items-center">
            <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menghapus transaksi ini? (Menghapus transaksi aktif akan mengembalikan stok buku)')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm flex items-center transition">
                    <i class="fas fa-trash-alt mr-1.5"></i> Hapus Transaksi Ini
                </button>
            </form>
            <a href="{{ route('loans.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
