<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user who made the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was loaned.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
