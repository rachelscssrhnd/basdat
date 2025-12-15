# **QUICK REFERENCE - AUTHENTICATION SYSTEM**

## **🚀 QUICK START**

### **1. Start Server**
```bash
cd c:\xampp\htdocs\basdat
php artisan serve
```
Visit: `http://127.0.0.1:8000`

### **2. Test Registration**
- URL: `http://127.0.0.1:8000/register`
- Fill form and submit
- Should redirect to login with success message

### **3. Test Login**
- URL: `http://127.0.0.1:8000/login`
- Use registered credentials
- Should redirect based on role (admin/user)

---

## **📋 CHEAT SHEET**

### **Routes**
| Route | Method | Purpose | Middleware |
|-------|--------|---------|------------|
| `/` | GET | Home page | Public |
| `/auth` | GET | Login page | Public |
| `/login` | GET | Login page | Public |
| `/login` | POST | Process login | Public |
| `/register` | GET | Registration page | Public |
| `/register` | POST | Process registration | Public |
| `/logout` | POST | Logout user | Public |
| `/user/home` | GET | User dashboard | `role:user` |
| `/admin/dashboard` | GET | Admin dashboard | `role:admin` |
| `/booking` | GET | Booking form | `auth.session` |
| `/myorder` | GET | User orders | `auth.session` |
| `/result` | GET | Test results | `auth.session` |

### **Session Variables**
```php
session('user_id')      // User ID (integer)
session('username')     // Username (string)
session('role')         // Role name from DB (string)
session('role_name')    // Lowercase role (string)
```

### **Check if Logged In**
```php
// In Controller
if (!session()->has('user_id')) {
    return redirect()->route('auth');
}

// In Blade
@if(session()->has('user_id'))
    <p>Welcome, {{ session('username') }}!</p>
@endif
```

### **Check User Role**
```php
// In Controller
if (session('role_name') === 'admin') {
    // Admin logic
}

// In Blade
@if(session('role_name') === 'admin')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
@endif
```

---

## **🔐 PASSWORD HELPERS**

### **Hash Password**
```php
use Illuminate\Support\Facades\Hash;

$hashedPassword = Hash::make('yourpassword');
```

### **Verify Password**
```php
if (Hash::check('inputPassword', $user->password_hash)) {
    // Password is correct
}
```

---

## **📝 FORM EXAMPLES**

### **Login Form**
```blade
<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <input type="text" name="username" placeholder="Username or Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

@if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif
```

### **Registration Form**
```blade
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
    <input type="text" name="nama" placeholder="Full Name" required>
    <input type="text" name="no_hp" placeholder="Phone Number" required>
    <input type="date" name="tgl_lahir" placeholder="Birth Date" required>
    <button type="submit">Register</button>
</form>
```

### **Logout Form**
```blade
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

---

## **🛡️ MIDDLEWARE USAGE**

### **Protect Single Route**
```php
Route::get('/booking', [BookingController::class, 'index'])
    ->middleware('auth.session');
```

### **Protect Route Group**
```php
Route::middleware('auth.session')->group(function () {
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/myorder', [MyOrderController::class, 'index']);
});
```

### **Role-Based Protection**
```php
// Admin only
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// User only
Route::middleware('role:user')->group(function () {
    Route::get('/user/home', [HomeController::class, 'index']);
});
```

---

## **📊 DATABASE QUERIES**

### **Get User with Role**
```php
$user = User::with('role')->find($userId);
echo $user->role->nama_role;
```

### **Get User with Pasien Data**
```php
$user = User::with('pasien')->find($userId);
echo $user->pasien->nama;
```

### **Create Log Activity**
```php
use App\Models\LogActivity;

LogActivity::create([
    'user_id' => session('user_id'),
    'action' => 'User performed some action',
    'created_at' => now(),
]);
```

### **Get User Bookings**
```php
$userId = session('user_id');
$bookings = Booking::whereHas('pasien', function($q) use ($userId) {
    $q->where('user_id', $userId);
})->get();
```

---

## **🎨 BLADE DIRECTIVES**

### **CSRF Token**
```blade
@csrf
<!-- Outputs: <input type="hidden" name="_token" value="..."> -->
```

### **Display Errors**
```blade
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<!-- Or single error -->
@error('username')
    <span class="error">{{ $message }}</span>
@enderror
```

### **Display Flash Messages**
```blade
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

---

## **🐛 DEBUGGING**

### **Check Session**
```php
// In Controller
dd(session()->all());

// Check specific value
dd(session('user_id'));
```

### **Check User**
```php
// Get current user
$user = User::find(session('user_id'));
dd($user);

// Check role
dd($user->role->nama_role);
```

### **Clear Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## **⚠️ COMMON ERRORS & FIXES**

### **Error: Column 'role_name' not found**
**Fix:** Database uses `nama_role`, not `role_name`
```php
// Change
Role::where('role_name', 'pasien')

// To
Role::where('nama_role', 'pasien')
```

### **Error: Middleware not working**
**Fix:** Check `bootstrap/app.php` for middleware registration
```php
$middleware->alias([
    'auth.session' => \App\Http\Middleware\AuthSession::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

### **Error: Session not persisting**
**Fix:** Check `.env` file
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### **Error: CSRF token mismatch**
**Fix:** Add `@csrf` in forms and meta tag in layout
```blade
<!-- In form -->
@csrf

<!-- In layout head -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## **🔥 USEFUL COMMANDS**

```bash
# Clear all caches
php artisan optimize:clear

# View all routes
php artisan route:list

# View routes with specific name
php artisan route:list --name=auth

# Check configuration
php artisan config:show session

# Run migrations
php artisan migrate

# Tinker (interactive shell)
php artisan tinker
>>> User::count()
>>> Role::all()
```

---

## **📱 TEST ACCOUNTS**

### **Create Test Admin**
```sql
-- In MySQL
INSERT INTO user (username, password_hash, email, role_id) VALUES 
  ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@eclinic.com', 1);

-- Password is: 'password'
```

### **Create Test User**
```sql
-- Use the registration form or:
INSERT INTO user (username, password_hash, email, role_id) VALUES 
  ('testuser', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'test@example.com', 3);

INSERT INTO pasien (user_id, nama, email, no_hp, tgl_lahir) VALUES 
  (LAST_INSERT_ID(), 'Test User', 'test@example.com', '08123456789', '1990-01-01');

-- Password is: 'password'
```

---

## **📖 DOCUMENTATION FILES**

1. **AUTHENTICATION_SYSTEM.md** - Complete authentication documentation
2. **TEST_AUTHENTICATION.md** - Testing scenarios and verification
3. **QUICK_REFERENCE.md** - This file (quick reference)

---

## **✅ VERIFICATION CHECKLIST**

### **Basic Functionality**
- [ ] User can register with valid data
- [ ] Duplicate username/email rejected
- [ ] Password is hashed in database
- [ ] User can login with username
- [ ] User can login with email
- [ ] Wrong credentials rejected
- [ ] User can logout successfully
- [ ] Session persists after page refresh

### **Middleware Protection**
- [ ] Cannot access `/booking` without login
- [ ] Cannot access `/admin/dashboard` as user
- [ ] Cannot access `/user/home` as admin
- [ ] Proper redirect with error messages

### **Database**
- [ ] User record created in `user` table
- [ ] Pasien record created in `pasien` table
- [ ] Activity logged in `log_activity` table
- [ ] Role properly assigned
- [ ] Foreign keys working

### **Security**
- [ ] Password hashed (bcrypt)
- [ ] CSRF protection enabled
- [ ] Input validation working
- [ ] Session secure (HTTPS in production)
- [ ] No sensitive data in logs

---

## **🎯 NEXT STEPS**

After authentication is working:
1. ✅ Implement booking system with session validation
2. ✅ Add payment functionality
3. ✅ Create admin CRUD operations
4. ✅ Add test result management
5. ✅ Implement notification system

---

**SISTEM AUTENTIKASI FULLY FUNCTIONAL! 🎉**

For detailed information, refer to:
- `AUTHENTICATION_SYSTEM.md` - Complete documentation
- `TEST_AUTHENTICATION.md` - Testing guide

For issues: Check error logs in `storage/logs/laravel.log`

