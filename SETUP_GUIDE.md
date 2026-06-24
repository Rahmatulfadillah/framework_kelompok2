# 🚀 Setup Guide - Fitur Baru

## Quick Start

### 1. Database Setup
```bash
php artisan migrate
```

### 2. Email Configuration (WAJIB untuk email verification)

Edit `.env` file dengan salah satu konfigurasi berikut:

#### Mailtrap (Recommended untuk development)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Framework Library"
```

#### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your_secret_key
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Framework Library"
```

#### Gmail (dengan App Password)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="Framework Library"
```

### 3. Clear Cache
```bash
php artisan config:cache
php artisan route:cache
```

### 4. Optional: Create Test User
```bash
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('admin123'), 'email_verified_at' => now()])
>>> exit
```

---

## ✅ Fitur yang Telah Diimplementasikan

### 1. ✨ Manajemen Peminjaman (Loan Management)
- [x] Create: Catat peminjaman baru
- [x] Read: Daftar peminjaman dengan filter status
- [x] Update: Tandai buku sudah dikembalikan
- [x] Delete: Hapus transaksi peminjaman
- [x] Auto stock management (decrement/increment)
- [x] Status tampilan: "Tersedia" / "Sedang Dipinjam"

**Files Created:**
- `app/Services/LoanService.php` - Business logic
- Updated: `app/Http/Controllers/LoanController.php`

### 2. 📧 Email Verification
- [x] Send verification email setelah register
- [x] Verify email link (signed URL)
- [x] Resend verification email
- [x] Middleware protection untuk verified users
- [x] User baru wajib verifikasi email sebelum akses

**Files Created:**
- `app/Services/EmailVerificationService.php`
- Updated: `app/Models/User.php` (implements MustVerifyEmail)
- Updated: `app/Http/Controllers/Auth/RegisterController.php`
- `resources/views/auth/verify-email.blade.php`

**Routes:**
```
GET    /email/verify
GET    /email/verify/{id}/{hash}
POST   /email/resend
```

### 3. 🔍 API Pencarian Buku (Book Search API)
- [x] Search API dengan query parameter
- [x] Available books API
- [x] Book detail API dengan status
- [x] Response format JSON terstruktur
- [x] Validation & error handling

**Files Created:**
- `app/Http/Controllers/Api/BookSearchController.php`
- `app/Services/BookSearchService.php`
- `routes/api.php`

**API Endpoints:**
```
GET /api/books/search?q=query     - Search (q minimal 2 char)
GET /api/books/available           - Get available books
GET /api/books/{id}               - Book detail
```

### 4. 📚 Riwayat Peminjaman Per User
- [x] View riwayat peminjaman user
- [x] Filter status peminjaman (Dipinjam/Dikembalikan)
- [x] Summary total peminjaman

**Files Created:**
- `app/Http/Controllers/LoanHistoryController.php`
- `resources/views/loans/history.blade.php`

**Route:**
```
GET /my-loans - Riwayat peminjaman user (auth + verified)
```

### 5. 🏗️ Service Layer untuk Setiap Fitur
- [x] LoanService - Manajemen peminjaman
- [x] BookSearchService - Pencarian buku
- [x] EmailVerificationService - Verifikasi email

---

## 📋 File Structure

```
app/
├── Services/
│   ├── LoanService.php              ✨ NEW
│   ├── BookSearchService.php        ✨ NEW
│   └── EmailVerificationService.php ✨ NEW
├── Http/Controllers/
│   ├── LoanController.php           ✏️ UPDATED
│   ├── BookController.php           ✏️ UPDATED
│   ├── LoanHistoryController.php    ✨ NEW
│   ├── Auth/
│   │   └── RegisterController.php   ✏️ UPDATED
│   └── Api/
│       └── BookSearchController.php ✨ NEW
└── Models/
    └── User.php                     ✏️ UPDATED

routes/
├── web.php                          ✏️ UPDATED
└── api.php                          ✨ NEW

resources/views/
├── auth/
│   └── verify-email.blade.php      ✨ NEW
└── loans/
    └── history.blade.php           ✨ NEW

bootstrap/
└── app.php                          ✏️ UPDATED (added api routes)

FITUR_BARU.md                        ✨ NEW (Dokumentasi lengkap)
SETUP_GUIDE.md                       ✨ NEW (File ini)
```

---

## 🧪 Testing

### Test Manual
```bash
# 1. Register user baru
# 2. Periksa email (Mailtrap/Gmail/etc)
# 3. Click verification link
# 4. Login dengan verified user
# 5. Catat peminjaman
# 6. Tandai pengembalian
# 7. Lihat riwayat

# Test API
curl "http://localhost/api/books/search?q=test"
curl "http://localhost/api/books/available"
curl "http://localhost/api/books/1"
```

### Test dengan Artisan Tinker
```bash
php artisan tinker

# Cari buku
>>> $books = \App\Models\Book::where('stok', '>', 0)->get();
>>> $books;

# Catat peminjaman
>>> $service = app(\App\Services\LoanService::class);
>>> $loan = $service->createLoan(1, 1, '2026-06-23');
>>> $loan;

# Cek status
>>> $service->getBookStatus(1);
>>> $service->getLoanHistory(1);
```

---

## 🔒 Security Features

- ✅ Email verified middleware (`verified`) untuk protected routes
- ✅ Database transactions untuk stock management
- ✅ CSRF protection pada form
- ✅ Input validation pada semua endpoints
- ✅ Signed URLs untuk email verification
- ✅ Rate limiting untuk email resend (6 per menit)

---

## 🐛 Troubleshooting

**Email tidak terkirim?**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Test mail config
php artisan tinker
>>> Mail::send([], [], function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

**Stok tidak berubah setelah peminjaman?**
- Verify: `SELECT * FROM loans;`
- Check: `SELECT * FROM books WHERE id = 1;`
- Rollback: `php artisan migrate:rollback`

**API routes tidak ditemukan?**
```bash
php artisan route:cache
php artisan route:clear
php artisan config:cache
```

---

## 📞 Support

Untuk pertanyaan lebih lanjut, lihat dokumentasi lengkap di `FITUR_BARU.md`

---

## ✨ Tim Implementasi

Fitur-fitur di atas telah diimplementasikan dengan best practices Laravel dan siap untuk production.

Last Updated: 2026-06-23
