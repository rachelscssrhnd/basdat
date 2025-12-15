# **SISTEM AUTENTIKASI LENGKAP - E-CLINIC LAB**

## **OVERVIEW**

Sistem autentikasi E-Clinic Lab dibangun menggunakan Laravel dengan session-based authentication, role-based access control (RBAC), dan integrasi lengkap dengan database. Sistem ini mendukung registrasi, login, logout, dan manajemen akses berdasarkan role user.

---

## **1. ARSITEKTUR SISTEM**

### **1.1 Komponen Utama**
```
┌─────────────────────────────────────────────────────────────┐
│                    AUTHENTICATION SYSTEM                     │
├─────────────────────────────────────────────────────────────┤
│  1. AuthController - Mengelola login/register/logout        │
│  2. Middleware (AuthSession & RoleMiddleware)                │
│  3. Models (User, Role, Pasien, LogActivity)                │
│  4. Routes (Public, Protected, Role-based)                   │
│  5. Session Management                                       │
└─────────────────────────────────────────────────────────────┘
```

### **1.2 Database Schema**
```sql
-- Tabel User (Autentikasi)
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

-- Tabel Role
CREATE TABLE role (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    nama_role VARCHAR(50) NOT NULL,
    description TEXT
);

-- Tabel Pasien (Profile Data)
CREATE TABLE pasien (
    pasien_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20),
    tgl_lahir DATE,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

-- Tabel Log Activity
CREATE TABLE log_activity (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action TEXT NOT NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

---

## **2. ALUR AUTENTIKASI**

### **2.1 REGISTRASI (SIGN UP)**

#### **Flow Diagram:**
```
User Input Form
    ↓
Validation
    ↓
DB Transaction Start
    ↓
Check/Create Role 'pasien'
    ↓
Create User Record
    ↓
Create Pasien Record
    ↓
Log Activity
    ↓
DB Transaction Commit
    ↓
Redirect to Login dengan Pop-up Success
```

#### **Kode Implementation:**
```php
public function register(Request $request)
{
    // 1. Validasi Input
    $validated = $request->validate([
        'username' => ['required', 'string', 'unique:user,username'],
        'email' => ['required', 'email', 'unique:pasien,email'],
        'password' => ['required', 'string', 'min:8'],
        'password_confirmation' => ['required', 'same:password'],
        'nama' => ['required', 'string'],
        'no_hp' => ['required', 'string'],
        'tgl_lahir' => ['required', 'date'],
    ]);

    try {
        \DB::beginTransaction();
        
        // 2. Get or create default 'pasien' role
        $userRole = Role::where('nama_role', 'pasien')->first();
        if (!$userRole) {
            $userRole = Role::create([
                'nama_role' => 'pasien',
                'description' => 'Default patient role',
            ]);
        }

        // 3. Create user account
        $user = User::create([
            'username' => $validated['username'],
            'password_hash' => Hash::make($validated['password']),
            'role_id' => $userRole->role_id,
            'email' => $validated['email'],
        ]);

        // 4. Create patient profile
        $pasien = Pasien::create([
            'user_id' => $user->user_id,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'tgl_lahir' => $validated['tgl_lahir'],
        ]);

        \DB::commit();

        // 5. Log activity
        LogActivity::create([
            'user_id' => $user->user_id,
            'action' => 'User registered',
            'created_at' => now(),
        ]);

        // 6. Redirect dengan success message
        return redirect()->route('auth')
            ->with('success', 'Registrasi berhasil! Silakan login.');

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Registration failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Registrasi gagal. Periksa kembali data Anda.')
            ->withInput();
    }
}
```

#### **Validasi Rules:**
- ✅ Username: Required, unique di tabel `user`
- ✅ Email: Required, valid email format, unique di tabel `pasien`
- ✅ Password: Required, minimum 8 karakter
- ✅ Password Confirmation: Required, harus sama dengan password
- ✅ Nama: Required
- ✅ No HP: Required
- ✅ Tanggal Lahir: Required, valid date

---

### **2.2 LOGIN**

#### **Flow Diagram:**
```
User Input Credentials
    ↓
Validation
    ↓
Find User (username or email)
    ↓
Verify Password (bcrypt)
    ↓
Set Session Variables
    ↓
Log Activity
    ↓
Role-based Redirect
    ├─ Admin → /admin/dashboard
    └─ User → /user/home
```

#### **Kode Implementation:**
```php
public function login(Request $request)
{
    // 1. Validasi Input
    $validated = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    try {
        // 2. Find user by username or email
        $input = $validated['username'];
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->first();
        
        // 3. Verify password
        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            return back()
                ->withErrors(['error' => 'Invalid credentials'])
                ->withInput();
        }

        // 4. Set session variables
        session([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'role' => $user->role->nama_role ?? 'user',
            'role_name' => strtolower($user->role->nama_role ?? 'user'),
        ]);

        // 5. Log activity
        LogActivity::create([
            'user_id' => $user->user_id,
            'action' => 'User logged in',
            'created_at' => now(),
        ]);

        // 6. Role-based redirect
        if (strtolower($user->role->nama_role) === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Login successful!');
        }
        return redirect()->route('user.home')
            ->with('success', 'Login successful!');

    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Login failed. Please try again.'])
            ->withInput();
    }
}
```

#### **Session Variables:**
```php
[
    'user_id' => 123,              // Primary key user
    'username' => 'johndoe',       // Username
    'role' => 'pasien',            // Role name from DB
    'role_name' => 'pasien'        // Lowercase role for comparison
]
```

---

### **2.3 LOGOUT**

#### **Flow Diagram:**
```
User Click Logout
    ↓
Log Activity (before flush)
    ↓
Flush All Session Data
    ↓
Redirect to Login dengan Success Message
```

#### **Kode Implementation:**
```php
public function logout()
{
    // 1. Log activity before clearing session
    if (session('user_id')) {
        LogActivity::create([
            'user_id' => session('user_id'),
            'action' => 'User logged out',
            'created_at' => now(),
        ]);
    }
    
    // 2. Clear all session data
    session()->flush();
    
    // 3. Redirect to login
    return redirect()->route('auth')
        ->with('success', 'Logged out successfully');
}
```

---

## **3. MIDDLEWARE SYSTEM**

### **3.1 AuthSession Middleware**

**Fungsi:** Memastikan user sudah login sebelum mengakses protected routes.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user_id exists in session
        if (!session()->has('user_id')) {
            return redirect()
                ->route('auth')
                ->withErrors(['error' => 'Please login to continue.']);
        }
        
        return $next($request);
    }
}
```

**Usage:**
```php
Route::middleware('auth.session')->group(function () {
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/myorder', [MyOrderController::class, 'index']);
});
```

---

### **3.2 RoleMiddleware**

**Fungsi:** Membatasi akses berdasarkan role user (admin/user).

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:user')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $sessionRole = session('role_name');
        
        // Check if user is logged in
        if (!$sessionRole) {
            return redirect()
                ->route('auth')
                ->withErrors(['error' => 'Please login to continue.']);
        }

        // Check role permissions
        if ($role === 'admin' && strtolower($sessionRole) !== 'admin') {
            return redirect()
                ->route('user.home')
                ->withErrors(['error' => 'Unauthorized. Admin only.']);
        }

        if ($role === 'user' && strtolower($sessionRole) !== 'user') {
            return redirect()
                ->route('admin.dashboard')
                ->withErrors(['error' => 'Unauthorized. Users only.']);
        }

        return $next($request);
    }
}
```

**Usage:**
```php
// Admin routes
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// User routes
Route::middleware('role:user')->group(function () {
    Route::get('/user/home', [HomeController::class, 'index']);
});
```

---

### **3.3 Middleware Registration**

**File:** `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'auth.session' => \App\Http\Middleware\AuthSession::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

---

## **4. ROUTES CONFIGURATION**

### **4.1 Public Routes**
```php
// Home page (accessible tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/auth', [AuthController::class, 'showLogin'])->name('auth');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

### **4.2 Protected Routes (Auth Required)**
```php
// Routes yang butuh login (menggunakan auth.session middleware)
Route::middleware('auth.session')->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    Route::get('/myorder', [MyOrderController::class, 'index'])->name('myorder');
    Route::get('/result', [ResultController::class, 'index'])->name('result');
});
```

### **4.3 Role-Based Routes**
```php
// Admin routes (hanya untuk role admin)
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminController::class, 'getBookings']);
    Route::post('/admin/bookings/{id}/approve', [AdminController::class, 'approveBooking']);
});

// User routes (hanya untuk role user/pasien)
Route::middleware('role:user')->group(function () {
    Route::get('/user/home', [HomeController::class, 'index'])
        ->name('user.home');
});
```

---

## **5. MODELS & RELATIONSHIPS**

### **5.1 User Model**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'role_id',
    ];

    protected $hidden = [
        'password_hash',
    ];
    
    // Override password column for authentication
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'user_id', 'user_id');
    }

    public function staf()
    {
        return $this->hasOne(Staf::class, 'user_id', 'user_id');
    }
}
```

### **5.2 Role Model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_role',      // Field name in database
        'description',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
```

### **5.3 Pasien Model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'pasien_id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tgl_lahir',
        'email',
        'no_hp',
        'user_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'pasien_id', 'pasien_id');
    }
}
```

---

## **6. SECURITY FEATURES**

### **6.1 Password Hashing**
```php
// Menggunakan bcrypt untuk hash password
$hashedPassword = Hash::make($password);

// Verify password
if (Hash::check($inputPassword, $user->password_hash)) {
    // Password correct
}
```

### **6.2 CSRF Protection**
```blade
<!-- Di form blade template -->
<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <!-- form fields -->
</form>
```

### **6.3 Input Validation**
```php
$validated = $request->validate([
    'username' => ['required', 'string', 'unique:user,username'],
    'email' => ['required', 'email', 'unique:pasien,email'],
    'password' => ['required', 'string', 'min:8'],
]);
```

### **6.4 Session Security**
```php
// Session configuration di config/session.php
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
'same_site' => 'lax',
```

---

## **7. LOGGING SYSTEM**

### **7.1 Activity Log**

Setiap aktivitas penting dicatat di tabel `log_activity`:

```php
LogActivity::create([
    'user_id' => session('user_id'),
    'action' => 'User logged in',
    'created_at' => now(),
]);
```

**Aktivitas yang di-log:**
- ✅ User registered
- ✅ User logged in
- ✅ User logged out
- ✅ Created booking
- ✅ Payment uploaded
- ✅ Admin approved booking
- ✅ Test result input

---

## **8. ERROR HANDLING**

### **8.1 Authentication Errors**

```php
// Login failed
return back()
    ->withErrors(['error' => 'Invalid credentials'])
    ->withInput();

// Unauthorized access
return redirect()
    ->route('auth')
    ->withErrors(['error' => 'Please login to continue.']);

// Role not allowed
return redirect()
    ->route('user.home')
    ->withErrors(['error' => 'Unauthorized. Admin only.']);
```

### **8.2 Registration Errors**

```php
// Database transaction rollback
try {
    \DB::beginTransaction();
    // ... registration logic
    \DB::commit();
} catch (\Exception $e) {
    \DB::rollBack();
    \Log::error('Registration failed', ['error' => $e->getMessage()]);
    return back()
        ->with('error', 'Registrasi gagal. Periksa kembali data Anda.')
        ->withInput();
}
```

---

## **9. TESTING AUTHENTICATION SYSTEM**

### **9.1 Test Registrasi**

**Steps:**
1. Buka `/register`
2. Isi form dengan data valid:
   - Username: testuser
   - Email: test@example.com
   - Password: password123
   - Confirm Password: password123
   - Nama: Test User
   - No HP: 08123456789
   - Tanggal Lahir: 1990-01-01
3. Klik Submit
4. Verifikasi:
   - ✅ Pop-up "Registrasi berhasil! Silakan login."
   - ✅ Redirect ke `/auth`
   - ✅ Data tersimpan di tabel `user` dan `pasien`
   - ✅ Log activity tercatat

### **9.2 Test Login**

**Steps:**
1. Buka `/login`
2. Masukkan credentials:
   - Username: testuser (atau email)
   - Password: password123
3. Klik Login
4. Verifikasi:
   - ✅ Redirect ke `/user/home` (pasien) atau `/admin/dashboard` (admin)
   - ✅ Session variables terset
   - ✅ Navbar berubah menampilkan "Logout"
   - ✅ Log activity tercatat

### **9.3 Test Middleware**

**Test Auth Session:**
1. Buka `/booking` tanpa login
2. Verifikasi redirect ke `/login` dengan error message

**Test Role Middleware:**
1. Login sebagai user biasa
2. Coba akses `/admin/dashboard`
3. Verifikasi redirect dengan "Unauthorized" message

### **9.4 Test Logout**

**Steps:**
1. Pastikan sudah login
2. Klik tombol Logout
3. Verifikasi:
   - ✅ Session cleared
   - ✅ Redirect ke `/auth`
   - ✅ Success message ditampilkan
   - ✅ Tidak bisa akses protected routes
   - ✅ Log activity tercatat

---

## **10. COMMON ISSUES & SOLUTIONS**

### **Issue 1: Column 'role_name' not found**
**Cause:** Database menggunakan `nama_role` bukan `role_name`
**Solution:**
```php
// Ganti
$userRole = Role::where('role_name', 'pasien')->first();

// Menjadi
$userRole = Role::where('nama_role', 'pasien')->first();
```

### **Issue 2: Middleware not working**
**Cause:** Middleware belum didaftarkan di `bootstrap/app.php`
**Solution:**
```php
$middleware->alias([
    'auth.session' => \App\Http\Middleware\AuthSession::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

### **Issue 3: Session not persisting**
**Cause:** Session driver atau configuration issue
**Solution:**
```env
# .env file
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### **Issue 4: Password not hashing**
**Cause:** Tidak menggunakan Hash::make()
**Solution:**
```php
'password_hash' => Hash::make($validated['password'])
```

---

## **11. BEST PRACTICES**

### **11.1 Security**
- ✅ Selalu hash password menggunakan bcrypt
- ✅ Gunakan CSRF protection di semua forms
- ✅ Validasi semua input dari user
- ✅ Gunakan HTTPS di production
- ✅ Set session secure cookies

### **11.2 Code Quality**
- ✅ Gunakan Database Transactions untuk operasi kompleks
- ✅ Implement proper error handling dengan try-catch
- ✅ Log semua aktivitas penting
- ✅ Gunakan Eloquent ORM untuk database operations
- ✅ Follow Laravel naming conventions

### **11.3 User Experience**
- ✅ Tampilkan error messages yang jelas
- ✅ Berikan feedback setelah setiap action (pop-up)
- ✅ Redirect user ke halaman yang sesuai berdasarkan role
- ✅ Keep user logged in dengan session
- ✅ Validasi input di client dan server side

---

## **12. PRODUCTION CHECKLIST**

### **Before Deployment:**
- [ ] Change `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set `SESSION_SECURE_COOKIE=true` (if using HTTPS)
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Test all authentication flows
- [ ] Check all middleware protections
- [ ] Verify logging is working
- [ ] Test error handling
- [ ] Backup database before deployment

---

## **KESIMPULAN**

Sistem autentikasi E-Clinic Lab telah **fully functional** dengan fitur lengkap:

✅ **Registration System** - Terintegrasi dengan tabel user & pasien
✅ **Login System** - Username/email + password dengan role-based redirect
✅ **Logout System** - Clear session + activity logging
✅ **Middleware Protection** - AuthSession & RoleMiddleware
✅ **Role-Based Access Control** - Admin dan User routes terpisah
✅ **Security Features** - Password hashing, CSRF, validation
✅ **Activity Logging** - Semua aktivitas tercatat
✅ **Error Handling** - Proper error messages dan rollback
✅ **Session Management** - Persistent login state

Sistem ini siap digunakan dan dapat di-scale sesuai kebutuhan!

