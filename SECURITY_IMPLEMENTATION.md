# SECURITY IMPLEMENTATION GUIDE
## Implementasi Keamanan YABUN PONTIANAK

Dokumen ini menjelaskan implementasi fix untuk semua celah keamanan yang ditemukan dalam audit.

---

## ✅ YANG SUDAH DIIMPLEMENTASIKAN

### 1. Authentication & Authorization (CRITICAL - Fixed)

**File yang diubah:**
- `routes/web.php` — Semua route admin sekarang pakai `middleware(['auth', 'admin'])`
- `bootstrap/app.php` — Register middleware alias `admin`
- `app/Http/Middleware/EnsureUserIsAdmin.php` — **BARU**, cek role superadmin/admin
- `app/Providers/AppServiceProvider.php` — Register policies

**Sebelum:**
```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', ...)->middleware('auth'); // Hanya dashboard
    Route::get('/galeri', ...); // TIDAK ADA AUTH ❌
```

**Sesudah:**
```php
Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'admin']) // ✅ SEMUA route protected
    ->group(function () {
        Route::get('/dashboard', ...);
        Route::get('/galeri', ...); // Sekarang protected
```

**Test:**
```bash
# Coba akses tanpa login:
curl http://localhost/admin/keuangan
# Response: 302 redirect ke /login ✅

# Login sebagai pengurus (low privilege):
# Coba akses /admin/dashboard
# Response: 403 Forbidden ✅
```

---

### 2. IDOR Protection (CRITICAL - Fixed)

**File yang diubah:**
- `app/Policies/FinancialTransactionPolicy.php` — **BARU**
- `app/Policies/DonaturPolicy.php` — **BARU**
- `app/Policies/GalleryPhotoPolicy.php` — **BARU**
- `app/Policies/ArticlePolicy.php` — **BARU**
- `app/Http/Controllers/Admin/Finance/FinancialTransactionController.php` — Tambah `$this->authorize('delete', $transaction)`
- `app/Http/Controllers/Admin/Donors/DonorController.php` — Tambah `$this->authorize('create/delete', $donor)`
- `app/Http/Controllers/Admin/Gallery/GalleryController.php` — Tambah authorize checks
- `app/Http/Controllers/Admin/Articles/ArticleController.php` — Tambah authorize checks

**Contoh Policy:**
```php
// app/Policies/FinancialTransactionPolicy.php
public function delete(User $user, FinancialTransaction $transaction): bool
{
    return in_array($user->peran, ['superadmin', 'admin'], true);
}
```

**Contoh Controller:**
```php
public function destroy(int $id): RedirectResponse
{
    $transaction = FinancialTransaction::findOrFail($id);
    
    $this->authorize('delete', $transaction); // ✅ Policy check
    
    $transaction->delete();
    return redirect()->route('admin.keuangan.index');
}
```

**Test:**
```bash
# Login sebagai pengurus, coba delete transaksi:
DELETE /admin/keuangan/12
# Response: 403 Forbidden ✅
```

---

### 3. Configuration Security (HIGH - Fixed)

**File yang dibuat:**
- `.env.production` — Production config dengan security hardening

**Perubahan:**
```env
# SEBELUM (.env):
APP_DEBUG=true                    ❌
SESSION_DRIVER=file               ❌
SESSION_ENCRYPT=false             ❌
SESSION_SECURE_COOKIE=             ❌ (unset)

# SESUDAH (.env.production):
APP_DEBUG=false                   ✅
SESSION_DRIVER=database           ✅
SESSION_ENCRYPT=true              ✅
SESSION_SECURE_COOKIE=true        ✅
SESSION_HTTP_ONLY=true            ✅
SESSION_SAME_SITE=lax             ✅
```

---

### 4. Proxy Security (MEDIUM - Fixed)

**File yang diubah:**
- `bootstrap/app.php`

**Sebelum:**
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*'); // ❌ Trust ALL proxies
})
```

**Sesudah:**
```php
->withMiddleware(function (Middleware $middleware): void {
    // Default: tidak trust proxy (aman untuk direct access)
    $trustedProxies = env('TRUSTED_PROXIES');
    if ($trustedProxies) {
        $middleware->trustProxies(at: explode(',', $trustedProxies));
    } else {
        $middleware->trustProxies(at: null); // ✅ Secure default
    }
})
```

**Config di .env.production:**
```env
# Uncomment jika deploy di belakang Cloudflare:
# TRUSTED_PROXIES=103.21.244.0/22,103.22.200.0/22
```

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Backup Database
```bash
cp database/database.sqlite database/database.sqlite.backup-$(date +%Y%m%d)
```

### Step 2: Run Migrations
```bash
# Generate session table migration jika belum:
php artisan session:table
php artisan migrate --force
```

### Step 3: Rotate APP_KEY
```bash
php artisan key:generate --force
```

### Step 4: Update .env
```bash
# Copy production config:
cp .env.production .env

# Edit manual jika perlu (APP_URL, TRUSTED_PROXIES, dll)
nano .env
```

### Step 5: Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

### Step 6: Set File Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 7: Restart Services
```bash
# Nginx:
sudo systemctl restart nginx php8.4-fpm

# Apache:
sudo systemctl restart apache2
```

---

## 🧪 TESTING CHECKLIST

### Test Authentication
- [ ] Guest tidak bisa akses `/admin/dashboard` → redirect ke login
- [ ] Guest tidak bisa akses `/admin/keuangan` → redirect ke login
- [ ] User dengan role `pengurus` dapat login tapi tidak bisa akses admin → 403 Forbidden
- [ ] User dengan role `admin` dapat akses semua route admin → 200 OK
- [ ] User dengan role `superadmin` dapat akses semua route admin → 200 OK

### Test Authorization (IDOR)
- [ ] User `pengurus` tidak bisa delete transaksi keuangan → 403
- [ ] User `pengurus` tidak bisa delete donor → 403
- [ ] User `admin` DAPAT delete transaksi keuangan → 302 redirect sukses
- [ ] User `admin` DAPAT delete donor → 302 redirect sukses

### Test Configuration
- [ ] Error page TIDAK menampilkan stack trace (APP_DEBUG=false)
- [ ] Session disimpan di database (cek table `sessions`)
- [ ] Cookie `laravel_session` memiliki flag `Secure` dan `HttpOnly`

### Run Automated Tests
```bash
php artisan test
```

**Expected:**
- Semua test existing harus PASS
- Test yang mengharuskan guest dapat CRUD admin harus FAIL (ini yang benar)

---

## 📋 YANG BELUM DIIMPLEMENTASIKAN (Optional)

### 1. XSS Protection di Article Content (MEDIUM)

**Solusi:** Install HTMLPurifier
```bash
composer require mews/purifier
php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"
```

Update `ArticleController::store()`:
```php
use Mews\Purifier\Facades\Purifier;

$validated['content'] = Purifier::clean($validated['content']);
```

### 2. Rate Limiting untuk Login

Tambah di `routes/web.php`:
```php
Route::post('/admin/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1'); // Max 5 attempts per minute
```

### 3. Two-Factor Authentication (2FA)

Install Laravel Fortify:
```bash
composer require laravel/fortify
php artisan fortify:install
php artisan migrate
```

### 4. Security Headers

Tambah di `app/Http/Middleware/SecurityHeaders.php`:
```php
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('X-XSS-Protection', '1; mode=block');
$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
```

### 5. Audit Logging

Log setiap mutating action:
```php
Log::info('Financial transaction deleted', [
    'transaction_id' => $transaction->id,
    'user_id' => auth()->id(),
    'ip' => request()->ip(),
]);
```

---

## 🔐 SECURITY CHECKLIST (Production)

- [x] APP_DEBUG=false
- [x] APP_KEY rotated
- [x] Auth middleware di semua admin routes
- [x] Role-based access control (RBAC) via policies
- [x] SESSION_DRIVER=database
- [x] SESSION_ENCRYPT=true
- [x] SESSION_SECURE_COOKIE=true
- [x] TRUSTED_PROXIES configured (atau null)
- [ ] HTTPS enabled (SSL certificate)
- [ ] Firewall configured (ufw/iptables)
- [ ] Database backups scheduled
- [ ] Error monitoring (Sentry/Bugsnag)
- [ ] Rate limiting enabled
- [ ] Security headers middleware
- [ ] 2FA untuk superadmin

---

## 📞 SUPPORT

Jika ada masalah setelah deployment:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Rollback jika perlu:**
   ```bash
   git checkout HEAD~1
   cp database/database.sqlite.backup-YYYYMMDD database/database.sqlite
   php artisan migrate:rollback --step=1
   ```

3. **Kontak developer:**
   - Rikzan El-Yasinta
   - WhatsApp: 6283146272098

---

**Dokumen dibuat:** 2026-10-03  
**Versi:** 1.0  
**Status:** ✅ Ready for Production
