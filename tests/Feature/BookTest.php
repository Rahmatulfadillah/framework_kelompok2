<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing books', function () {
    $this->get(route('books.index'))->assertRedirect(route('login'));
    $this->get(route('books.create'))->assertRedirect(route('login'));
});

test('authenticated users can see books list', function () {
    $user = User::factory()->create();
    Book::create([
        'judul' => 'Laskar Pelangi',
        'pengarang' => 'Andrea Hirata',
        'penerbit' => 'Bentang Pustaka',
        'stok' => 5,
        'tahun_terbit' => 2005,
        'kategori' => 'Fiksi',
    ]);

    $response = $this->actingAs($user)->get(route('books.index'));

    $response->assertStatus(200);
    $response->assertSee('Laskar Pelangi');
    $response->assertSee('Andrea Hirata');
});

test('authenticated users can create a book', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('books.store'), [
        'judul' => 'Bumi',
        'pengarang' => 'Tere Liye',
        'penerbit' => 'Gramedia',
        'stok' => 3,
        'tahun_terbit' => 2014,
        'kategori' => 'Fiksi',
    ]);

    $response->assertRedirect(route('books.index'));
    $this->assertDatabaseHas('books', [
        'judul' => 'Bumi',
        'pengarang' => 'Tere Liye',
        'stok' => 3,
    ]);
});

test('authenticated users can update a book', function () {
    $user = User::factory()->create();
    $book = Book::create([
        'judul' => 'Original Title',
        'pengarang' => 'Original Author',
        'penerbit' => 'Original Publisher',
        'stok' => 2,
    ]);

    $response = $this->actingAs($user)->put(route('books.update', $book->id), [
        'judul' => 'Updated Title',
        'pengarang' => 'Original Author',
        'penerbit' => 'Original Publisher',
        'stok' => 10,
    ]);

    $response->assertRedirect(route('books.index'));
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'judul' => 'Updated Title',
        'stok' => 10,
    ]);
});

test('authenticated users can delete a book', function () {
    $user = User::factory()->create();
    $book = Book::create([
        'judul' => 'To Be Deleted',
        'pengarang' => 'Author',
        'penerbit' => 'Publisher',
        'stok' => 1,
    ]);

    $response = $this->actingAs($user)->delete(route('books.destroy', $book->id));

    $response->assertRedirect(route('books.index'));
    $this->assertDatabaseMissing('books', [
        'id' => $book->id,
    ]);
});
