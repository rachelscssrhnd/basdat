# **PANDUAN TESTING SISTEM AUTENTIKASI**

## **QUICK START GUIDE**

### **1. Setup Database**

```sql
-- Pastikan database sudah ada
CREATE DATABASE IF NOT EXISTS basdat;
USE basdat;

-- Cek tabel yang diperlukan
SHOW TABLES LIKE 'user';
SHOW TABLES LIKE 'role';
SHOW TABLES LIKE 'pasien';
SHOW TABLES LIKE 'log_activity';

-- Insert default roles (jika belum ada)
INSERT INTO role (nama_role, description) VALUES 
    ('admin', 'Administrator role'),
    ('pasien', 'Patient role'),
    ('staf', 'Staff role');
```

### **2. Check Configuration**

```bash
# Di terminal, check .env configuration
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Verify session driver
php artisan tinker
>>> config('session.driver')  # Should output: "file" or "database"
```

### **3. Test Routes**

```bash
# Check all routes
php artisan route:list --name=auth
php artisan route:list --name=login
php artisan route:list --name=register
```

---

## **TESTING SCENARIO**

### **SCENARIO 1: NEW USER REGISTRATION**

#### **Test Case 1.1: Successful Registration**

**Steps:**
1. Open browser → `http://127.0.0.1:8000/register`
2. Fill form:
   ```
   Username: testuser1
   Email: testuser1@example.com
   Password: password123
   Confirm Password: password123
   Nama: Test User One
   No HP: 081234567890
   Tanggal Lahir: 1995-05-15
   ```
3. Click "Register" or "Daftar"

**Expected Results:**
- ✅ Pop-up message: "Registrasi berhasil! Silakan login."
- ✅ Redirect to `/auth` (login page)
- ✅ Check database:
  ```sql
  SELECT * FROM user WHERE username = 'testuser1';
  SELECT * FROM pasien WHERE email = 'testuser1@example.com';
  SELECT * FROM log_activity WHERE action LIKE '%registered%' ORDER BY log_id DESC LIMIT 1;
  ```
- ✅ Password is hashed (not plain text)
- ✅ role_id = 3 (pasien)

#### **Test Case 1.2: Duplicate Username**

**Steps:**
1. Try to register with same username as Test Case 1.1
2. Use different email

**Expected Results:**
- ❌ Error message: "The username has already been taken."
- ✅ Form shows validation error
- ✅ User remains on registration page
- ✅ No new record in database

#### **Test Case 1.3: Duplicate Email**

**Steps:**
1. Try to register with different username
2. Use same email as Test Case 1.1

**Expected Results:**
- ❌ Error message: "The email has already been taken."
- ✅ Form shows validation error
- ✅ User remains on registration page
- ✅ No new record in database

#### **Test Case 1.4: Password Mismatch**

**Steps:**
1. Fill form with new credentials
2. Password: `password123`
3. Confirm Password: `password456`

**Expected Results:**
- ❌ Error message: "The password confirmation does not match."
- ✅ User remains on registration page
- ✅ No record created in database

#### **Test Case 1.5: Invalid Email Format**

**Steps:**
1. Fill form
2. Email: `invalidemail.com` (without @)

**Expected Results:**
- ❌ Error message: "The email must be a valid email address."
- ✅ User remains on registration page

---

### **SCENARIO 2: USER LOGIN**

#### **Test Case 2.1: Login with Username (Pasien)**

**Prerequisites:** Complete Test Case 1.1 first

**Steps:**
1. Open `http://127.0.0.1:8000/login`
2. Fill form:
   ```
   Username: testuser1
   Password: password123
   ```
3. Click "Login" or "Masuk"

**Expected Results:**
- ✅ Success message: "Login successful!"
- ✅ Redirect to `/user/home` (because role = pasien)
- ✅ Navbar shows "Logout" button instead of "Login"
- ✅ Session variables set:
  ```php
  session('user_id')     // Should be user's ID
  session('username')    // Should be 'testuser1'
  session('role')        // Should be 'pasien'
  session('role_name')   // Should be 'pasien'
  ```
- ✅ Check log_activity:
  ```sql
  SELECT * FROM log_activity WHERE action LIKE '%logged in%' ORDER BY log_id DESC LIMIT 1;
  ```

#### **Test Case 2.2: Login with Email**

**Steps:**
1. Open `http://127.0.0.1:8000/login`
2. Fill form:
   ```
   Username: testuser1@example.com  (use email instead of username)
   Password: password123
   ```
3. Click Login

**Expected Results:**
- ✅ Same as Test Case 2.1
- ✅ System accepts both username and email for login

#### **Test Case 2.3: Login with Wrong Password**

**Steps:**
1. Try login with correct username
2. Use wrong password: `wrongpassword`

**Expected Results:**
- ❌ Error message: "Invalid credentials"
- ✅ User remains on login page
- ✅ No session created
- ✅ No log activity recorded

#### **Test Case 2.4: Login with Non-existent User**

**Steps:**
1. Try login with username that doesn't exist
2. Any password

**Expected Results:**
- ❌ Error message: "Invalid credentials"
- ✅ User remains on login page
- ✅ No session created

#### **Test Case 2.5: Login as Admin**

**Prerequisites:** Create admin user first
```sql
-- Create admin user manually in database
INSERT INTO user (username, password_hash, email, role_id) VALUES 
  ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@eclinic.com', 1);
  -- Password is 'password'
```

**Steps:**
1. Login with admin credentials:
   ```
   Username: admin
   Password: password
   ```

**Expected Results:**
- ✅ Success message: "Login successful!"
- ✅ Redirect to `/admin/dashboard` (because role = admin)
- ✅ Can access admin routes
- ✅ Cannot access user-only routes

---

### **SCENARIO 3: MIDDLEWARE PROTECTION**

#### **Test Case 3.1: Access Protected Route Without Login**

**Steps:**
1. Make sure you're logged out (clear cookies/session)
2. Try to access: `http://127.0.0.1:8000/booking`

**Expected Results:**
- ❌ Cannot access booking page
- ✅ Redirect to `/auth` (login page)
- ✅ Error message: "Please login to continue."

#### **Test Case 3.2: User Trying to Access Admin Routes**

**Prerequisites:** Login as regular user (pasien)

**Steps:**
1. Login as testuser1 (pasien)
2. Try to access: `http://127.0.0.1:8000/admin/dashboard`

**Expected Results:**
- ❌ Cannot access admin dashboard
- ✅ Redirect to `/user/home`
- ✅ Error message: "Unauthorized. Admin only."

#### **Test Case 3.3: Admin Trying to Access User Routes**

**Prerequisites:** Login as admin

**Steps:**
1. Login as admin
2. Try to access: `http://127.0.0.1:8000/user/home`

**Expected Results:**
- ❌ Cannot access user home
- ✅ Redirect to `/admin/dashboard`
- ✅ Error message: "Unauthorized. Users only."

#### **Test Case 3.4: Access After Login**

**Prerequisites:** Login successfully

**Steps:**
1. Login as testuser1
2. Access: `http://127.0.0.1:8000/booking`

**Expected Results:**
- ✅ Can access booking page
- ✅ Page loads successfully
- ✅ User data available in session

---

### **SCENARIO 4: LOGOUT**

#### **Test Case 4.1: Normal Logout**

**Prerequisites:** Login first

**Steps:**
1. Login as testuser1
2. Click "Logout" button
3. Or access: `http://127.0.0.1:8000/logout` (POST request)

**Expected Results:**
- ✅ Success message: "Logged out successfully"
- ✅ Redirect to `/auth` (login page)
- ✅ Session cleared:
  ```php
  session()->has('user_id')  // Should be false
  ```
- ✅ Cannot access protected routes anymore
- ✅ Check log_activity:
  ```sql
  SELECT * FROM log_activity WHERE action LIKE '%logged out%' ORDER BY log_id DESC LIMIT 1;
  ```
- ✅ Navbar shows "Login" button

#### **Test Case 4.2: Try to Access Protected Route After Logout**

**Steps:**
1. After logout, try to access: `http://127.0.0.1:8000/booking`

**Expected Results:**
- ❌ Cannot access
- ✅ Redirect to login page

---

### **SCENARIO 5: SESSION PERSISTENCE**

#### **Test Case 5.1: Session Remains After Page Refresh**

**Steps:**
1. Login successfully
2. Refresh the page (F5)
3. Navigate to different pages

**Expected Results:**
- ✅ User remains logged in
- ✅ Session variables persist
- ✅ No need to login again

#### **Test Case 5.2: Session Expires After Timeout**

**Steps:**
1. Login successfully
2. Wait for session timeout (default: 120 minutes)
3. Or manually clear session:
   ```bash
   php artisan cache:clear
   rm -rf storage/framework/sessions/*
   ```
4. Try to access protected route

**Expected Results:**
- ❌ Session expired
- ✅ Redirect to login page

---

## **DATABASE VERIFICATION**

### **Check User Records**

```sql
-- View all users
SELECT u.user_id, u.username, u.email, r.nama_role 
FROM user u 
LEFT JOIN role r ON u.role_id = r.role_id;

-- View user with pasien data
SELECT 
    u.user_id,
    u.username,
    u.email,
    r.nama_role,
    p.nama,
    p.no_hp,
    p.tgl_lahir
FROM user u
LEFT JOIN role r ON u.role_id = r.role_id
LEFT JOIN pasien p ON u.user_id = p.user_id;
```

### **Check Activity Logs**

```sql
-- Recent activities
SELECT 
    l.log_id,
    u.username,
    l.action,
    l.created_at
FROM log_activity l
LEFT JOIN user u ON l.user_id = u.user_id
ORDER BY l.log_id DESC
LIMIT 20;

-- Count activities by user
SELECT 
    u.username,
    COUNT(l.log_id) as activity_count
FROM user u
LEFT JOIN log_activity l ON u.user_id = l.user_id
GROUP BY u.username
ORDER BY activity_count DESC;
```

### **Check Roles**

```sql
-- View all roles
SELECT * FROM role;

-- Count users per role
SELECT 
    r.nama_role,
    COUNT(u.user_id) as user_count
FROM role r
LEFT JOIN user u ON r.role_id = u.role_id
GROUP BY r.nama_role;
```

---

## **TROUBLESHOOTING**

### **Problem 1: "Column 'role_name' not found"**

**Solution:**
The database uses `nama_role` not `role_name`. This has been fixed in the updated AuthController.

**Verify:**
```sql
DESCRIBE role;
```
Should show `nama_role` column.

### **Problem 2: "Middleware not working"**

**Check:**
1. Middleware registered in `bootstrap/app.php`
2. Routes using correct middleware name
3. Clear cache: `php artisan config:clear`

### **Problem 3: "Session not persisting"**

**Check:**
1. `.env` file: `SESSION_DRIVER=file`
2. Storage permissions: `chmod -R 775 storage/`
3. Clear session: `php artisan cache:clear`

### **Problem 4: "Password not matching"**

**Check:**
1. Password is hashed with `Hash::make()`
2. Verification uses `Hash::check()`
3. Column name is `password_hash` not `password`

### **Problem 5: "CSRF token mismatch"**

**Check:**
1. Form includes `@csrf` directive
2. Meta tag in layout: `<meta name="csrf-token" content="{{ csrf_token() }}">`

---

## **AUTOMATED TESTING (Optional)**

### **Feature Test Example**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        // Create role
        Role::create(['nama_role' => 'pasien', 'description' => 'Patient']);

        // Attempt registration
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nama' => 'Test User',
            'no_hp' => '08123456789',
            'tgl_lahir' => '1990-01-01',
        ]);

        // Assert
        $response->assertRedirect('/auth');
        $this->assertDatabaseHas('user', ['username' => 'testuser']);
        $this->assertDatabaseHas('pasien', ['email' => 'test@example.com']);
    }

    /** @test */
    public function user_can_login()
    {
        // Create user
        $user = User::factory()->create([
            'username' => 'testuser',
            'password_hash' => bcrypt('password123'),
        ]);

        // Attempt login
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertRedirect('/user/home');
        $this->assertTrue(session()->has('user_id'));
    }

    /** @test */
    public function user_cannot_access_admin_routes()
    {
        // Login as regular user
        $user = User::factory()->create(['role_id' => 3]); // pasien
        $this->actingAs($user);

        // Try to access admin route
        $response = $this->get('/admin/dashboard');

        // Assert
        $response->assertRedirect('/user/home');
    }
}
```

---

## **CHECKLIST SEBELUM PRODUCTION**

- [ ] ✅ Semua test cases passed
- [ ] ✅ Password hashing berfungsi
- [ ] ✅ Session management works
- [ ] ✅ Middleware protection active
- [ ] ✅ Role-based access works
- [ ] ✅ Activity logging works
- [ ] ✅ Error handling proper
- [ ] ✅ CSRF protection enabled
- [ ] ✅ Input validation works
- [ ] ✅ Database transactions work
- [ ] ✅ No sensitive data in logs
- [ ] ✅ HTTPS enabled (production)
- [ ] ✅ SESSION_SECURE_COOKIE=true (production)
- [ ] ✅ APP_DEBUG=false (production)

---

## **PERFORMANCE TESTING**

### **Load Testing**

```bash
# Using Apache Bench
ab -n 1000 -c 100 http://127.0.0.1:8000/login

# Expected:
# - Requests per second: > 100
# - Average response time: < 100ms
# - No failed requests
```

### **Session Testing**

```bash
# Check session storage
ls -la storage/framework/sessions/

# Monitor session size
du -sh storage/framework/sessions/
```

---

## **FINAL VERIFICATION**

Run this complete test suite:

```bash
# 1. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 2. Run migrations (if needed)
php artisan migrate:fresh --seed

# 3. Start server
php artisan serve

# 4. Open browser and test:
# - Registration
# - Login
# - Access protected routes
# - Logout
# - Middleware protection
# - Role-based access
```

✅ **SISTEM AUTENTIKASI FULLY FUNCTIONAL DAN SIAP DIGUNAKAN!**

