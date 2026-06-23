@extends('layouts.admin')

@section('title', 'Daftar Buku')
@section('page_title', 'Manajemen Buku')

@section('admin_content')
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <!-- Header Actions -->
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Buku</h3>
            <p class="text-sm text-gray-500 mt-1">Koleksi buku perpustakaan yang dapat dipinjam oleh anggota.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search Form -->
            <form action="{{ route('books.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul, pengarang..." 
                       class="w-full sm:w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                @if($search)
                    <a href="{{ route('books.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </form>
            
            <!-- Add Button -->
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm flex items-center justify-center transition shadow-sm">
                <i class="fas fa-plus mr-2 text-xs"></i> Tambah Buku
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 w-12 text-center">No</th>
                    <th class="px-6 py-4">Judul</th>
                    <th class="px-6 py-4">Pengarang</th>
                    <th class="px-6 py-4">Penerbit</th>
                    <th class="px-6 py-4 text-center">Tahun</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok</th>
                    <th class="px-6 py-4 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($books as $key => $book)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center text-gray-500">{{ $books->firstItem() + $key }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $book->judul }}</td>
                    <td class="px-6 py-4">{{ $book->pengarang }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $book->penerbit }}</td>
                    <td class="px-6 py-4 text-center">{{ $book->tahun_terbit ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                            {{ $book->kategori ?? 'Umum' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($book->stok > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                {{ $book->stok }} eks
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                Habis
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Show Detail -->
                            <a href="{{ route('books.show', $book->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <!-- Edit -->
                            <a href="{{ route('books.edit', $book->id) }}" class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <!-- Delete -->
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
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
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        @if($search)
                            Tidak ada buku yang cocok dengan pencarian "{{ $search }}".
                        @else
                            Belum ada buku terdaftar. Silakan tambahkan buku baru.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
    <div class="p-6 border-t bg-gray-50">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
