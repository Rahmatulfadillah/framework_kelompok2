<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookSearchApiController extends Controller
{
    public function searchExternal(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['error' => 'Query parameter "q" is required'], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get('https://openlibrary.org/search.json', [
                'q' => $query,
                'limit' => 5
            ]);

            if ($response->successful()) {
                $docs = $response->json('docs') ?? [];
                
                $results = collect($docs)->map(function ($doc) {
                    return [
                        'judul' => $doc['title'] ?? 'Tanpa Judul',
                        'pengarang' => isset($doc['author_name']) ? implode(', ', (array) $doc['author_name']) : 'Tidak diketahui',
                        'penerbit' => isset($doc['publisher']) ? ((array) $doc['publisher'])[0] : 'Tidak diketahui',
                        'tahun_terbit' => $doc['first_publish_year'] ?? null,
                        'kategori' => isset($doc['subject']) ? ((array) $doc['subject'])[0] : 'Umum',
                    ];
                });

                return response()->json($results);
            }

            return response()->json([
                'error' => 'Failed to fetch from external API', 
                'status' => $response->status(), 
                'body' => $response->json()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
