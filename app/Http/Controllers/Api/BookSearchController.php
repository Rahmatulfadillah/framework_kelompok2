<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookSearchController extends Controller
{
    public function __construct(private BookSearchService $bookSearchService) {}

    public function search(Request $request): JsonResponse
    {
        $query = $request->string('q')->trim();

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query harus minimal 2 karakter',
                'data' => [],
            ], 400);
        }

        $books = $this->bookSearchService->searchBooksAPI($query);

        return response()->json([
            'success' => true,
            'message' => "Ditemukan {$books->count()} buku",
            'data' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'judul' => $book->judul,
                    'pengarang' => $book->pengarang,
                    'penerbit' => $book->penerbit,
                    'kategori' => $book->kategori,
                    'stok' => $book->stok,
                    'tahun_terbit' => $book->tahun_terbit,
                    'status' => $book->stok > 0 ? 'Tersedia' : 'Sedang Dipinjam',
                ];
            }),
        ]);
    }

    public function available(): JsonResponse
    {
        $books = $this->bookSearchService->getAvailableBooks();

        return response()->json([
            'success' => true,
            'data' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'judul' => $book->judul,
                    'pengarang' => $book->pengarang,
                    'penerbit' => $book->penerbit,
                    'kategori' => $book->kategori,
                    'stok' => $book->stok,
                ];
            }),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $book = $this->bookSearchService->getBookWithStatus($id);

        if (! $book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'judul' => $book->judul,
                'pengarang' => $book->pengarang,
                'penerbit' => $book->penerbit,
                'kategori' => $book->kategori,
                'stok' => $book->stok,
                'tahun_terbit' => $book->tahun_terbit,
                'status' => $book->status,
            ],
        ]);
    }
}
