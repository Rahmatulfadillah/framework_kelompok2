<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing loans', function () {
    $this->get(route('loans.index'))->assertRedirect(route('login'));
    $this->get(route('loans.create'))->assertRedirect(route('login'));
});

test('authenticated users can see loans list', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $book = Book::create([
        'judul' => 'Laskar Pelangi',
        'pengarang' => 'Andrea Hirata',
        'penerbit' => 'Bentang Pustaka',
        'stok' => 5,
    ]);

    Loan::create([
        'user_id' => $member->id,
        'book_id' => $book->id,
        'loan_date' => Carbon::today()->format('Y-m-d'),
        'status' => 'borrowed',
    ]);

    $response = $this->actingAs($admin)->get(route('loans.index'));

    $response->assertStatus(200);
    $response->assertSee($member->name);
    $response->assertSee('Laskar Pelangi');
});

test('borrowing a book decrements its stock', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $book = Book::create([
        'judul' => 'Atomic Habits',
        'pengarang' => 'James Clear',
        'penerbit' => 'Gramedia',
        'stok' => 4,
    ]);

    $response = $this->actingAs($admin)->post(route('loans.store'), [
        'user_id' => $member->id,
        'book_id' => $book->id,
        'loan_date' => Carbon::today()->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('loans.index'));
    $this->assertDatabaseHas('loans', [
        'user_id' => $member->id,
        'book_id' => $book->id,
        'status' => 'borrowed',
    ]);

    // Check stock decremented
    $this->assertEquals(3, $book->fresh()->stok);
});

test('cannot borrow a book with zero stock', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $book = Book::create([
        'judul' => 'Out of Stock Book',
        'pengarang' => 'Author',
        'penerbit' => 'Publisher',
        'stok' => 0,
    ]);

    $response = $this->actingAs($admin)->from(route('loans.create'))->post(route('loans.store'), [
        'user_id' => $member->id,
        'book_id' => $book->id,
        'loan_date' => Carbon::today()->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('loans.create'));
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('loans', [
        'user_id' => $member->id,
        'book_id' => $book->id,
    ]);
});

test('returning a book increments its stock', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $book = Book::create([
        'judul' => 'Laskar Pelangi',
        'pengarang' => 'Andrea Hirata',
        'penerbit' => 'Bentang Pustaka',
        'stok' => 4,
    ]);

    $loan = Loan::create([
        'user_id' => $member->id,
        'book_id' => $book->id,
        'loan_date' => Carbon::today()->subDays(5)->format('Y-m-d'),
        'status' => 'borrowed',
    ]);

    $response = $this->actingAs($admin)->post(route('loans.return', $loan->id));

    $response->assertRedirect(route('loans.index'));
    $this->assertEquals('returned', $loan->fresh()->status);
    $this->assertNotNull($loan->fresh()->return_date);

    // Check stock incremented back to 5
    $this->assertEquals(5, $book->fresh()->stok);
});

test('deleting an active loan increments book stock', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $book = Book::create([
        'judul' => 'Atomic Habits',
        'pengarang' => 'James Clear',
        'penerbit' => 'Gramedia',
        'stok' => 3,
    ]);

    $loan = Loan::create([
        'user_id' => $member->id,
        'book_id' => $book->id,
        'loan_date' => Carbon::today()->format('Y-m-d'),
        'status' => 'borrowed',
    ]);

    $response = $this->actingAs($admin)->delete(route('loans.destroy', $loan->id));

    $response->assertRedirect(route('loans.index'));
    $this->assertDatabaseMissing('loans', ['id' => $loan->id]);

    // Check stock incremented back to 4 (since the borrowed book was returned/deleted)
    $this->assertEquals(4, $book->fresh()->stok);
});
