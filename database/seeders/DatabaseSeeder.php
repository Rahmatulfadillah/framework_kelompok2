<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $jane = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 2. Seed Books
        $book1 = Book::create([
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'stok' => 4, // 5 total - 1 active loan
            'tahun_terbit' => 2005,
            'kategori' => 'Fiksi',
        ]);

        $book2 = Book::create([
            'judul' => 'Bumi',
            'pengarang' => 'Tere Liye',
            'penerbit' => 'Gramedia Pustaka Utama',
            'stok' => 3, // 3 total - 0 active loan
            'tahun_terbit' => 2014,
            'kategori' => 'Fiksi',
        ]);

        $book3 = Book::create([
            'judul' => 'Filosofi Teras',
            'pengarang' => 'Henry Manampiring',
            'penerbit' => 'Penerbit Buku Kompas',
            'stok' => 8, // 8 total - 0 active loan
            'tahun_terbit' => 2018,
            'kategori' => 'Self Improvement',
        ]);

        $book4 = Book::create([
            'judul' => 'Atomic Habits',
            'pengarang' => 'James Clear',
            'penerbit' => 'Gramedia',
            'stok' => 3, // 4 total - 1 active loan
            'tahun_terbit' => 2019,
            'kategori' => 'Self Improvement',
        ]);

        $book5 = Book::create([
            'judul' => 'Sebuah Seni untuk Bersikap Bodo Amat',
            'pengarang' => 'Mark Manson',
            'penerbit' => 'Gramedia',
            'stok' => 0, // Out of stock
            'tahun_terbit' => 2016,
            'kategori' => 'Self Improvement',
        ]);

        $book6 = Book::create([
            'judul' => 'Belajar Laravel 11',
            'pengarang' => 'Rahmatullah',
            'penerbit' => 'Informatika',
            'stok' => 9, // 10 total - 1 active loan
            'tahun_terbit' => 2024,
            'kategori' => 'Teknologi',
        ]);

        // 3. Seed Loans (spread over past few days/weeks)
        // John returned Laskar Pelangi
        Loan::create([
            'user_id' => $john->id,
            'book_id' => $book1->id,
            'loan_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'return_date' => Carbon::now()->subDays(13)->format('Y-m-d'),
            'status' => 'returned',
        ]);

        // Jane returned Bumi
        Loan::create([
            'user_id' => $jane->id,
            'book_id' => $book2->id,
            'loan_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'return_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'status' => 'returned',
        ]);

        // Budi returned Filosofi Teras
        Loan::create([
            'user_id' => $budi->id,
            'book_id' => $book3->id,
            'loan_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
            'return_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'status' => 'returned',
        ]);

        // John returned Atomic Habits
        Loan::create([
            'user_id' => $john->id,
            'book_id' => $book4->id,
            'loan_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
            'return_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'status' => 'returned',
        ]);

        // Active Loans:
        // Jane is currently borrowing Laskar Pelangi
        Loan::create([
            'user_id' => $jane->id,
            'book_id' => $book1->id,
            'loan_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'return_date' => null,
            'status' => 'borrowed',
        ]);

        // Budi is currently borrowing Atomic Habits
        Loan::create([
            'user_id' => $budi->id,
            'book_id' => $book4->id,
            'loan_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'return_date' => null,
            'status' => 'borrowed',
        ]);

        // John is currently borrowing Belajar Laravel 11
        Loan::create([
            'user_id' => $john->id,
            'book_id' => $book6->id,
            'loan_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'return_date' => null,
            'status' => 'borrowed',
        ]);
    }
}
