<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    public function index(Request $request)
    {
        $status = $request->input('status');
        $loans = $this->loanService->getAllLoans($status);

        return view('loans.index', compact('loans', 'status'));
    }

    public function create()
    {
        $users = $this->loanService->getUsersForLoan();
        $books = $this->loanService->getAvailableBooksForLoan();

        return view('loans.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            $this->loanService->createLoan(
                $validated['user_id'],
                $validated['book_id'],
                $validated['loan_date']
            );

            return redirect()->route('loans.index')->with('success', 'Peminjaman buku berhasil dicatat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal! '.$e->getMessage());
        }
    }

    public function userBorrow(Request $request, \App\Models\Book $book)
    {
        try {
            $this->loanService->createLoan(
                auth()->id(),
                $book->id,
                now()->format('Y-m-d')
            );

            return redirect()->route('loans.history')->with('success', 'Berhasil meminjam buku!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meminjam: '.$e->getMessage());
        }
    }

    public function show(Loan $loan)
    {
        $loan->load(['user', 'book']);

        return view('loans.show', compact('loan'));
    }

    public function returnBook(Loan $loan)
    {
        try {
            $this->loanService->returnLoan($loan->id);

            return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Loan $loan)
    {
        try {
            $this->loanService->deleteLoan($loan->id);

            return redirect()->route('loans.index')->with('success', 'Transaksi peminjaman berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
