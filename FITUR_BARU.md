# IMPLEMENTASI FITUR BARU

Dokumentasi lengkap untuk fitur-fitur baru yang telah diimplementasikan.

## 1. Manajemen Peminjaman (Loan Management)

### Deskripsi
Sistem CRUD lengkap untuk mengelola peminjaman buku dengan fitur:
- Catat peminjaman buku
- Tandai pengembalian buku
- Lihat status peminjaman per buku
- Riwayat peminjaman per user

### Lokasi File
- **Model**: `app/Models/Loan.php`
- **Controller**: `app/Http/Controllers/LoanController.php`
- **Service**: `app/Services/LoanService.php`
- **Routes**: `routes/web.php` (resource route)

### Fungsi Service LoanService
```php
// Mendapatkan semua peminjaman dengan filter status
$loanService->getAllLoans($status = null, $perPage = 10);

// Mendapatkan peminjaman user tertentu
$loanService->getUserLoans($userId, $perPage = 10);

// Mendapatkan riwayat peminjaman lengkap
$loanService->getLoanHistory($userId);

// Mendapatkan status buku
$loanService->getBookStatus($bookId); // Returns: "Tersedia" atau "Sedang Dipinjam"

// Membuat peminjaman baru
$loanService->createLoan($userId, $bookId, $loanDate);

// Menandai buku sudah dikembalikan
$loanService->returnLoan($loanId);

// Menghapus transaksi peminjaman
$loanService->deleteLoan($loanId);
```

### Routes
```
GET    /loans              - Daftar semua peminjaman
GET    /loans/create       - Form catat peminjaman
POST   /loans              - Simpan peminjaman
GET    /loans/{id}         - Detail peminjaman
POST   /loans/{id}/return  - Tandai pengembalian
DELETE /loans/{id}         - Hapus peminjaman
GET    /my-loans           - Riwayat peminjaman user
```

---

## 2. Email Verification (Verifikasi Email)

### Deskripsi
Sistem verifikasi email setelah registrasi menggunakan Laravel's built-in email verification.

### Lokasi File
- **Model**: `app/Models/User.php` (implements MustVerifyEmail)
- **Service**: `app/Services/EmailVerificationService.php`
- **Controller**: `app/Http/Controllers/Auth/RegisterController.php`
- **View**: `resources/views/auth/verify-email.blade.php`

### Fungsi Service EmailVerificationService
```php
// Mengirim email verifikasi ke user
$emailVerificationService->sendVerificationEmail($user);

// Verifikasi email user
$emailVerificationService->verifyEmail($user);

// Cek apakah email sudah diverifikasi
$emailVerificationService->isEmailVerified($user);

// Kirim ulang email verifikasi
$emailVerificationService->resendVerificationEmail($user);
```

### Routes
```
POST   /register                  - Register baru (mengirim email verifikasi)
GET    /email/verify              - Halaman verifikasi email
GET    /email/verify/{id}/{hash}  - Verify link dari email
POST   /email/resend              - Kirim ulang email verifikasi
```

### Konfigurasi Email
Edit `.env` file untuk menggunakan SMTP service (Mailtrap, Mailgun, dll):

**Untuk Mailtrap:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Your App Name"
```

**Untuk Mailgun:**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your_mailgun_secret
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Your App Name"
```

**Untuk Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="Your App Name"
```

---

## 3. API Pencarian Buku (Book Search API)

### Deskripsi
REST API untuk mencari buku tanpa input manual, dengan response JSON yang terstruktur.

### Lokasi File
- **Controller**: `app/Http/Controllers/Api/BookSearchController.php`
- **Service**: `app/Services/BookSearchService.php`
- **Routes**: `routes/api.php`

### Fungsi Service BookSearchService
```php
// Pencarian dengan pagination untuk web
$bookSearchService->searchBooks($query = '', $perPage = 10);

// Pencarian untuk API (tanpa pagination)
$bookSearchService->searchBooksAPI($query = '');

// Mendapatkan buku dengan status
$bookSearchService->getBookWithStatus($bookId);

// Mendapatkan buku berdasarkan kategori
$bookSearchService->getBooksByCategory($kategori);

// Mendapatkan buku yang tersedia
$bookSearchService->getAvailableBooks();
```

### API Endpoints

**1. Search Books**
```
GET /api/books/search?q=query
```
Query minimal 2 karakter.

Response (200):
```json
{
  "success": true,
  "message": "Ditemukan 5 buku",
  "data": [
    {
      "id": 1,
      "judul": "Clean Code",
      "pengarang": "Robert C. Martin",
      "penerbit": "Prentice Hall",
      "kategori": "Programming",
      "stok": 3,
      "tahun_terbit": 2008,
      "status": "Tersedia"
    }
  ]
}
```

Error (400):
```json
{
  "success": false,
  "message": "Query harus minimal 2 karakter",
  "data": []
}
```

**2. Get Available Books**
```
GET /api/books/available
```

Response (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "Clean Code",
      "pengarang": "Robert C. Martin",
      "penerbit": "Prentice Hall",
      "kategori": "Programming",
      "stok": 3
    }
  ]
}
```

**3. Get Book Details**
```
GET /api/books/{id}
```

Response (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "judul": "Clean Code",
    "pengarang": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "kategori": "Programming",
    "stok": 3,
    "tahun_terbit": 2008,
    "status": "Tersedia"
  }
}
```

Response (404):
```json
{
  "success": false,
  "message": "Buku tidak ditemukan"
}
```

### Contoh Penggunaan JavaScript

```javascript
// Search books
fetch('/api/books/search?q=clean', {
  headers: {
    'Accept': 'application/json'
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Found books:', data.data);
  }
});

// Get available books
fetch('/api/books/available')
  .then(response => response.json())
  .then(data => console.log(data.data));

// Get specific book
fetch('/api/books/1')
  .then(response => response.json())
  .then(data => console.log(data.data));
```

---

## 4. Riwayat Peminjaman Per User

### Deskripsi
User dapat melihat riwayat lengkap peminjaman mereka dengan status dan tanggal.

### Lokasi File
- **Controller**: `app/Http/Controllers/LoanHistoryController.php`
- **View**: `resources/views/loans/history.blade.php`

### Routes
```
GET /my-loans - Halaman riwayat peminjaman user
```

### Access
Hanya bisa diakses oleh user yang sudah login dan email terverifikasi.

---

## Database Schema

### Loans Table
```sql
CREATE TABLE loans (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  book_id BIGINT UNSIGNED NOT NULL,
  loan_date DATE NOT NULL,
  return_date DATE NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'borrowed', -- 'borrowed' atau 'returned'
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
```

### Books Table Status
- `stok > 0`: "Tersedia"
- `stok = 0` atau ada peminjaman aktif: "Sedang Dipinjam"

---

## Middleware & Authentication

Semua route yang memerlukan autentikasi menggunakan middleware:
- `auth`: User sudah login
- `verified`: Email user sudah diverifikasi

Routes yang dilindungi:
- Dashboard
- Books (CRUD)
- Loans (CRUD)
- My Loans (History)

---

## Setup & Installation

### 1. Setup Database
```bash
php artisan migrate
```

### 2. Setup Email
Edit `.env` dengan konfigurasi SMTP pilihan Anda (lihat bagian Email Verification).

### 3. Clear Cache
```bash
php artisan config:cache
php artisan route:cache
```

### 4. Test Email Verification
Daftar user baru dan periksa log email atau email service terkonfigurasi.

### 5. Test API
```bash
# Search
curl "http://localhost/api/books/search?q=test"

# Available books
curl "http://localhost/api/books/available"

# Book detail
curl "http://localhost/api/books/1"
```

---

## Status Book Display

Sistem menampilkan status buku di beberapa tempat:
1. **Halaman Daftar Buku**: Menampilkan jumlah stok
2. **Halaman Detail Buku**: Menampilkan peminjaman aktif
3. **Form Catat Peminjaman**: Hanya menampilkan buku dengan stok > 0
4. **API Response**: Menampilkan status "Tersedia" atau "Sedang Dipinjam"

---

## Testing Checklist

- [ ] Register user baru dan verifikasi email
- [ ] Login dengan email terverifikasi
- [ ] Catat peminjaman buku
- [ ] Tandai buku sudah dikembalikan
- [ ] Lihat riwayat peminjaman user
- [ ] Search buku via API dengan 2+ karakter
- [ ] Validasi bahwa buku tidak bisa dipinjam jika stok habis
- [ ] Email verification tidak diperlukan untuk existing user (migrate langsung)

---

## Troubleshooting

**Email tidak terkirim?**
- Periksa konfigurasi MAIL_* di `.env`
- Jalankan: `php artisan config:cache`
- Periksa log di `storage/logs/`

**Stok buku tidak berubah?**
- Pastikan transaksi berjalan dengan baik (check database)
- Verify Loan::create() dan decrement/increment dipanggil

**API tidak merespons?**
- Periksa route di `routes/api.php`
- Jalankan: `php artisan route:cache`
- Periksa error di browser console atau log

---

## Fitur Tambahan yang Dapat Dikembangkan

1. Notification ketika buku harus dikembalikan
2. Denda keterlambatan peminjaman
3. Export peminjaman ke Excel/PDF
4. QR code untuk buku
5. Wishlist buku untuk user
6. Rating dan review buku
7. SMS notification untuk pengembalian
8. Dashboard analytics untuk admin
