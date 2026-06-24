<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LoanService
{
    public function getAllLoans(?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return Loan::with(['user', 'book'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getUserLoans(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Loan::with('book')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getLoanHistory(int $userId): Collection
    {
        return Loan::with('book')
            ->where('user_id', $userId)
            ->orderByDesc('loan_date')
            ->get();
    }

    public function getBookStatus(int $bookId): string
    {
        $activeLoan = Loan::where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->exists();

        return $activeLoan ? 'Sedang Dipinjam' : 'Tersedia';
    }

    public function createLoan(int $userId, int $bookId, string $loanDate): Loan
    {
        $book = Book::findOrFail($bookId);

        if ($book->stok <= 0) {
            throw new \Exception("Stok buku '{$book->judul}' sedang kosong.");
        }

        $hasActiveLoan = Loan::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->exists();

        if ($hasActiveLoan) {
            throw new \Exception("Anda masih meminjam buku ini dan belum mengembalikannya.");
        }

        return DB::transaction(function () use ($userId, $bookId, $loanDate, $book) {
            $loan = Loan::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'loan_date' => $loanDate,
                'status' => 'borrowed',
            ]);

            $book->decrement('stok');

            return $loan;
        });
    }

    public function returnLoan(int $loanId): Loan
    {
        $loan = Loan::findOrFail($loanId);

        if ($loan->status === 'returned') {
            throw new \Exception('Buku ini sudah dikembalikan sebelumnya!');
        }

        return DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'returned',
                'return_date' => Carbon::today(),
            ]);

            $loan->book->increment('stok');

            return $loan;
        });
    }

    public function deleteLoan(int $loanId): bool
    {
        $loan = Loan::findOrFail($loanId);

        return DB::transaction(function () use ($loan) {
            if ($loan->status === 'borrowed') {
                $loan->book->increment('stok');
            }

            return $loan->delete();
        });
    }

    public function getAvailableBooksForLoan(): Collection
    {
        return Book::where('stok', '>', 0)
            ->orderBy('judul')
            ->get();
    }

    public function getUsersForLoan(): Collection
    {
        return User::orderBy('name')->get();
    }
}
