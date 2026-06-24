@extends('layouts.admin')

@section('title', 'Catat Peminjaman')
@section('page_title', 'Catat Peminjaman')

@section('admin_content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b">
        <div class="flex items-center">
            <a href="{{ route('loans.index') }}" class="text-gray-400 hover:text-gray-600 mr-4 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Form Transaksi Baru</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih anggota, buku yang dipinjam, dan sesuaikan tanggal peminjaman.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('loans.store') }}" method="POST" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- User Selection (Select2) -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Anggota <span class="text-red-500">*</span></label>
                <select id="user_id" name="user_id" required class="w-full">
                    <option value=""></option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Book Selection (Select2) -->
            <div>
                <label for="book_id" class="block text-sm font-semibold text-gray-700 mb-1">Cari & Pilih Buku <span class="text-red-500">*</span></label>
                <select id="book_id" name="book_id" required class="w-full">
                    <option value=""></option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->judul }} — {{ $book->pengarang }} (Stok: {{ $book->stok }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1 italic">Hanya menampilkan buku dengan stok tersedia (> 0).</p>
                @error('book_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Loan Date -->
            <div>
                <label for="loan_date" class="block text-sm font-semibold text-gray-700">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                <input type="date" id="loan_date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('loan_date') border-red-500 @enderror">
                @error('loan_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="border-t pt-6 flex justify-end space-x-3">
            <a href="{{ route('loans.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 border rounded-lg text-sm transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition shadow-sm">
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Styling Select2 to match modern UI input fields */
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #D1D5DB !important;
        border-radius: 0.5rem !important;
        height: 42px !important;
        padding: 6px 12px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1F2937 !important;
        font-size: 0.875rem !important;
        padding-left: 0px !important;
        padding-right: 20px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9CA3AF !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #9CA3AF transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #3B82F6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }
    .select2-dropdown {
        border: 1px solid #E5E7EB !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        overflow: hidden !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #D1D5DB !important;
        border-radius: 0.375rem !important;
        padding: 6px 12px !important;
        outline: none !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3B82F6 !important;
    }
    .select2-container--default .select2-results__option {
        font-size: 0.875rem !important;
        padding: 8px 12px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: "Pilih Anggota Peminjam",
            allowClear: true
        });
        $('#book_id').select2({
            placeholder: "Cari & Pilih Buku (Hanya buku tersedia)",
            allowClear: true
        });
    });
</script>
@endpush
