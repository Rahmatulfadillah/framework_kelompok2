<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookSearchService
{
    public function searchBooks(string $query = '', int $perPage = 10): LengthAwarePaginator
    {
        return Book::when($query, function ($queryBuilder, $search) {
            return $queryBuilder->where('judul', 'like', "%{$search}%")
                ->orWhere('pengarang', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function searchBooksAPI(string $query = ''): Collection
    {
        return Book::when($query, function ($queryBuilder, $search) {
            return $queryBuilder->where('judul', 'like', "%{$search}%")
                ->orWhere('pengarang', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        })
            ->select('id', 'judul', 'pengarang', 'penerbit', 'kategori', 'stok', 'tahun_terbit')
            ->orderBy('judul')
            ->limit(50)
            ->get();
    }

    public function getBookWithStatus(int $bookId): ?Book
    {
        $book = Book::find($bookId);

        if (! $book) {
            return null;
        }

        $book->setAttribute('status', $this->getBookStatus($bookId));

        return $book;
    }

    public function getBooksByCategory(string $kategori): Collection
    {
        return Book::where('kategori', $kategori)
            ->orderBy('judul')
            ->get();
    }

    public function getAvailableBooks(): Collection
    {
        return Book::where('stok', '>', 0)
            ->orderBy('judul')
            ->get();
    }

    private function getBookStatus(int $bookId): string
    {
        $activeLoan = Loan::where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->exists();

        return $activeLoan ? 'Sedang Dipinjam' : 'Tersedia';
    }
}
