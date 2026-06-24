@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('page_title', 'Edit Buku')

@section('admin_content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b">
        <div class="flex items-center">
            <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-gray-600 mr-4 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Edit Data Buku</h3>
                <p class="text-sm text-gray-500 mt-1">Ubah data buku "{{ $book->judul }}" perpustakaan.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('books.update', $book->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $book->judul) }}" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengarang -->
                <div>
                    <label for="pengarang" class="block text-sm font-semibold text-gray-700">Pengarang <span class="text-red-500">*</span></label>
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang', $book->pengarang) }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pengarang') border-red-500 @enderror">
                    @error('pengarang')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-sm font-semibold text-gray-700">Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $book->penerbit) }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('penerbit') border-red-500 @enderror">
                    @error('penerbit')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stok -->
                <div>
                    <label for="stok" class="block text-sm font-semibold text-gray-700">Stok Buku <span class="text-red-500">*</span></label>
                    <input type="number" id="stok" name="stok" value="{{ old('stok', $book->stok) }}" min="0" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stok') border-red-500 @enderror">
                    @error('stok')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-sm font-semibold text-gray-700">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" min="1000" max="{{ date('Y') + 1 }}"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tahun_terbit') border-red-500 @enderror">
                    @error('tahun_terbit')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700">Kategori</label>
                    <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $book->kategori) }}" placeholder="Contoh: Fiksi, Teknologi"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori') border-red-500 @enderror">
                    @error('kategori')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="border-t pt-6 flex justify-end space-x-3">
            <a href="{{ route('books.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 border rounded-lg text-sm transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition shadow-sm">
                Perbarui Buku
            </button>
        </div>
    </form>
</div>
@endsection
