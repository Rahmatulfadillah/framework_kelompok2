@extends('layouts.admin')

@section('title', 'Tambah Buku')
@section('page_title', 'Tambah Buku')

@section('admin_content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b">
        <div class="flex items-center">
            <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-gray-600 mr-4 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Form Buku Baru</h3>
                <p class="text-sm text-gray-500 mt-1">Masukkan informasi detail buku baru perpustakaan.</p>
            </div>
        </div>
    </div>

    <!-- External API Search Section -->
    <div class="p-6 border-b bg-gray-50">
        <label for="api_search" class="block text-sm font-semibold text-gray-700 mb-2">Cari Data Buku Otomatis (via Google Books)</label>
        <div class="flex space-x-2">
            <input type="text" id="api_search" placeholder="Ketik judul buku lalu klik Cari..." 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="button" id="btn_api_search" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
        </div>
        <p id="api_search_status" class="text-xs text-gray-500 mt-2 hidden">Sedang mencari...</p>
        <div id="api_search_results" class="mt-3 space-y-2"></div>
    </div>

    <form action="{{ route('books.store') }}" method="POST" class="p-6 space-y-6" id="book_form">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengarang -->
                <div>
                    <label for="pengarang" class="block text-sm font-semibold text-gray-700">Pengarang <span class="text-red-500">*</span></label>
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pengarang') border-red-500 @enderror">
                    @error('pengarang')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-sm font-semibold text-gray-700">Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required
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
                    <input type="number" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stok') border-red-500 @enderror">
                    @error('stok')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-sm font-semibold text-gray-700">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1000" max="{{ date('Y') + 1 }}"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tahun_terbit') border-red-500 @enderror">
                    @error('tahun_terbit')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700">Kategori</label>
                    <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Fiksi, Teknologi"
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
                Simpan Buku
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnSearch = document.getElementById('btn_api_search');
        const inputSearch = document.getElementById('api_search');
        const statusText = document.getElementById('api_search_status');
        const resultsContainer = document.getElementById('api_search_results');

        btnSearch.addEventListener('click', function() {
            const query = inputSearch.value.trim();
            if (query.length < 3) {
                alert('Masukkan minimal 3 karakter untuk mencari.');
                return;
            }

            statusText.classList.remove('hidden');
            statusText.textContent = 'Sedang mencari data dari Google Books...';
            resultsContainer.innerHTML = '';

            fetch(`/api/books/search-external?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    statusText.classList.add('hidden');
                    
                    if (data.error || !data.length) {
                        resultsContainer.innerHTML = '<p class="text-sm text-red-500">Buku tidak ditemukan atau terjadi kesalahan.</p>';
                        return;
                    }

                    data.forEach((book, index) => {
                        const div = document.createElement('div');
                        div.className = 'p-3 border rounded-lg bg-white hover:bg-blue-50 cursor-pointer transition flex flex-col';
                        div.innerHTML = `
                            <span class="font-bold text-sm text-gray-800">${book.judul}</span>
                            <span class="text-xs text-gray-600">${book.pengarang} | ${book.penerbit} | ${book.tahun_terbit || '-'}</span>
                        `;
                        div.addEventListener('click', () => fillForm(book));
                        resultsContainer.appendChild(div);
                    });
                })
                .catch(err => {
                    statusText.classList.add('hidden');
                    resultsContainer.innerHTML = '<p class="text-sm text-red-500">Terjadi kesalahan koneksi.</p>';
                });
        });

        function fillForm(book) {
            document.getElementById('judul').value = book.judul || '';
            document.getElementById('pengarang').value = book.pengarang || '';
            document.getElementById('penerbit').value = book.penerbit || '';
            document.getElementById('tahun_terbit').value = book.tahun_terbit || '';
            document.getElementById('kategori').value = book.kategori || '';
            
            // Highlight fields slightly to show they were auto-filled
            ['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'kategori'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.add('bg-blue-50');
                setTimeout(() => el.classList.remove('bg-blue-50'), 1500);
            });

            resultsContainer.innerHTML = '<p class="text-sm text-green-600 font-semibold mt-2"><i class="fas fa-check-circle mr-1"></i> Form berhasil diisi otomatis!</p>';
        }
    });
</script>
@endpush
