<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');

        $loans = Loan::with(['user', 'book'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('loans.index', compact('loans', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        // Only load books that are in stock
        $books = Book::where('stok', '>', 0)->orderBy('judul')->get();

        return view('loans.create', compact('users', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date|before_or_equal:today',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stok <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal! Stok buku "'.$book->judul.'" sedang kosong (habis).');
        }

        DB::transaction(function () use ($validated, $book) {
            // Create the loan
            Loan::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'loan_date' => $validated['loan_date'],
                'status' => 'borrowed',
            ]);

            // Decrement book stock
            $book->decrement('stok');
        });

        return redirect()->route('loans.index')->with('success', 'Peminjaman buku berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'book']);

        return view('loans.show', compact('loan'));
    }

    /**
     * Update the specified resource in storage to mark the book as returned.
     */
    public function returnBook(Loan $loan)
    {
        if ($loan->status === 'returned') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya!');
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'returned',
                'return_date' => Carbon::today(),
            ]);

            // Increment book stock
            $loan->book->increment('stok');
        });

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        DB::transaction(function () use ($loan) {
            // If loan is still active, return the book stock
            if ($loan->status === 'borrowed') {
                $loan->book->increment('stok');
            }
            $loan->delete();
        });

        return redirect()->route('loans.index')->with('success', 'Transaksi peminjaman berhasil dihapus!');
    }
}
