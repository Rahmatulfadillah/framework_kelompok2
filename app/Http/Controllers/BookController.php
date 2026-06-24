<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookSearchService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookSearchService $bookSearchService) {}

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $books = $this->bookSearchService->searchBooks($search);

        return view('books.index', compact('books', 'search'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'tahun_terbit' => 'nullable|integer|min:1000|max:'.(date('Y') + 1),
            'kategori' => 'nullable|string|max:255',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Book $book)
    {
        $book->load(['loans.user']);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'tahun_terbit' => 'nullable|integer|min:1000|max:'.(date('Y') + 1),
            'kategori' => 'nullable|string|max:255',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
