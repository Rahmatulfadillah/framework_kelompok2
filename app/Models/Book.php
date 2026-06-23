<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'stok',
        'tahun_terbit',
        'kategori',
    ];

    /**
     * Get the loans for the book.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
