@extends('layouts.admin')

@section('title', 'Transaksi Peminjaman')
@section('page_title', 'Transaksi Peminjaman')

@section('admin_content')
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <!-- Header Actions -->
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi Peminjaman</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola pencatatan peminjaman dan pengembalian buku perpustakaan.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Filter Status -->
            <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-gray-50">
                <a href="{{ route('loans.index') }}" 
                   class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ is_null($status) ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                    Semua
                </a>
                <a href="{{ route('loans.index', ['status' => 'borrowed']) }}" 
                   class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ $status === 'borrowed' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                    Dipinjam
                </a>
                <a href="{{ route('loans.index', ['status' => 'returned']) }}" 
                   class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ $status === 'returned' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                    Kembali
                </a>
            </div>

            <!-- Record Loan Button -->
            <a href="{{ route('loans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm flex items-center justify-center transition shadow-sm">
                <i class="fas fa-exchange-alt mr-2 text-xs"></i> Catat Peminjaman
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 w-12 text-center">ID</th>
                    <th class="px-6 py-4">Nama Peminjam</th>
                    <th class="px-6 py-4">Buku</th>
                    <th class="px-6 py-4">Tgl Pinjam</th>
                    <th class="px-6 py-4">Tgl Kembali</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center w-52">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center text-gray-500 font-semibold">#{{ $loan->id }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $loan->user->name }}</td>
                    <td class="px-6 py-4 max-w-xs truncate">{{ $loan->book->judul }}</td>
                    <td class="px-6 py-4"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @if($loan->return_date)
                            <i class="far fa-calendar-check mr-2 text-green-500"></i>{{ $loan->return_date->format('d/m/Y') }}
                        @else
                            <span class="text-xs font-semibold px-2 py-0.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded">Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $loan->status === 'returned' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $loan->status === 'returned' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                            {{ $loan->status === 'returned' ? 'Selesai' : 'Dipinjam' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Kembalikan (Hanya jika sedang dipinjam) -->
                            @if($loan->status === 'borrowed')
                                <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin menandai buku ini sudah dikembalikan?')">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition flex items-center shadow-sm">
                                        <i class="fas fa-check-double mr-1 text-[10px]"></i> Kembali
                                    </button>
                                </form>
                            @endif

                            <!-- Detail -->
                            <a href="{{ route('loans.show', $loan->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin menghapus transaksi ini? (Menghapus transaksi aktif akan mengembalikan stok buku)')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        @if($status)
                            Tidak ada transaksi dengan status "{{ $status === 'borrowed' ? 'Dipinjam' : 'Kembali' }}".
                        @else
                            Belum ada transaksi peminjaman buku.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($loans->hasPages())
    <div class="p-6 border-t bg-gray-50">
        {{ $loans->links() }}
    </div>
    @endif
</div>
@endsection
