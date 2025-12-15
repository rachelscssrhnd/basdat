# **STRUKTUR DATABASE - E-CLINIC LAB AUTHENTICATION**

## **OVERVIEW**

Dokumen ini menjelaskan struktur database yang benar untuk sistem autentikasi E-Clinic Lab, sesuai dengan spesifikasi yang diberikan.

---

## **1. STRUKTUR TABEL**

### **1.1 Tabel `user` (Autentikasi)**
```sql
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);
```

**Atribut:**
- `user_id` - Primary Key (INT, AUTO_INCREMENT)
- `username` - Username unik (VARCHAR(50), UNIQUE, NOT NULL)
- `password_hash` - Password yang sudah di-hash (VARCHAR(255), NOT NULL)
- `email` - Email unik (VARCHAR(100), UNIQUE, NOT NULL)
- `role_id` - Foreign Key ke tabel `role` (INT)

### **1.2 Tabel `role` (Definisi Role)**
```sql
CREATE TABLE role (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    description TEXT
);
```

**Atribut:**
- `role_id` - Primary Key (INT, AUTO_INCREMENT)
- `role_name` - Nama role (VARCHAR(50), NOT NULL) ⚠️ **PENTING: `role_name` bukan `nama_role`**
- `description` - Deskripsi role (TEXT)

**Data Default:**
```sql
INSERT INTO role (role_name, description) VALUES 
    ('admin', 'Administrator - Full access'),
    ('staf', 'Staff - Limited admin access'),
    ('pasien', 'Patient - User access only');
```

### **1.3 Tabel `pasien` (Data Pasien)**
```sql
CREATE TABLE pasien (
    pasien_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20),
    tgl_lahir DATE,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

**Atribut:**
- `pasien_id` - Primary Key (INT, AUTO_INCREMENT)
- `user_id` - Foreign Key ke tabel `user` (INT, UNIQUE)
- `nama` - Nama lengkap pasien (VARCHAR(100), NOT NULL)
- `email` - Email pasien (VARCHAR(100), UNIQUE, NOT NULL)
- `no_hp` - Nomor HP (VARCHAR(20))
- `tgl_lahir` - Tanggal lahir (DATE)

### **1.4 Tabel `staf` (Data Staff)**
```sql
CREATE TABLE staf (
    staf_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    cabang_id INT,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (cabang_id) REFERENCES cabang(cabang_id)
);
```

**Atribut:**
- `staf_id` - Primary Key (INT, AUTO_INCREMENT)
- `user_id` - Foreign Key ke tabel `user` (INT, UNIQUE)
- `cabang_id` - Foreign Key ke tabel `cabang` (INT)
- `nama` - Nama lengkap staff (VARCHAR(100), NOT NULL)
- `jabatan` - Jabatan staff (VARCHAR(100))

### **1.5 Tabel `log_activity` (Log Aktivitas)**
```sql
CREATE TABLE log_activity (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

**Atribut:**
- `log_id` - Primary Key (INT, AUTO_INCREMENT)
- `user_id` - Foreign Key ke tabel `user` (INT)
- `action` - Deskripsi aktivitas (TEXT, NOT NULL)
- `created_at` - Timestamp aktivitas (TIMESTAMP)

---

## **2. RELASI ANTAR TABEL**

### **2.1 Diagram Relasi**
```
┌─────────────┐    1:N    ┌─────────────┐
│    role     │◄──────────│    user     │
│             │           │             │
│ role_id (PK)│           │ user_id (PK)│
│ role_name   │           │ username    │
│ description │           │ password_   │
└─────────────┘           │ email       │
                          │ role_id (FK)│
                          └─────────────┘
                                │
                                │ 1:1
                                ▼
                          ┌─────────────┐
                          │   pasien    │
                          │             │
                          │ pasien_id   │
                          │ user_id (FK)│
                          │ nama        │
                          │ email       │
                          │ no_hp       │
                          │ tgl_lahir   │
                          └─────────────┘

┌─────────────┐    1:N    ┌─────────────┐
│   cabang    │◄──────────│    staf     │
│             │           │             │
│ cabang_id   │           │ staf_id (PK)│
│ nama_cabang │           │ user_id (FK)│
│ alamat      │           │ cabang_id   │
└─────────────┘           │ nama        │
                          │ jabatan     │
                          └─────────────┘
```

### **2.2 Relasi Detail**

**User → Role (Many-to-One)**
- Satu user memiliki satu role
- Satu role bisa dimiliki banyak user
- Foreign Key: `user.role_id` → `role.role_id`

**User → Pasien (One-to-One)**
- Satu user bisa memiliki satu profil pasien
- Satu pasien pasti memiliki satu user account
- Foreign Key: `pasien.user_id` → `user.user_id`

**User → Staf (One-to-One)**
- Satu user bisa memiliki satu profil staf
- Satu staf pasti memiliki satu user account
- Foreign Key: `staf.user_id` → `user.user_id`

**Staf → Cabang (Many-to-One)**
- Satu staf bekerja di satu cabang
- Satu cabang bisa memiliki banyak staf
- Foreign Key: `staf.cabang_id` → `cabang.cabang_id`

---

## **3. MODEL LARAVEL**

### **3.1 User Model**
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

### **3.2 Role Model**
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
        'role_name',    // ⚠️ PENTING: role_name bukan nama_role
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
```

### **3.3 Pasien Model**
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
        'user_id',
        'nama',
        'email',
        'no_hp',
        'tgl_lahir',
    ];

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

### **3.4 Staf Model**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    protected $table = 'staf';
    protected $primaryKey = 'staf_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cabang_id',
        'nama',
        'jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }
}
```

---

## **4. IMPLEMENTASI AUTHENTICATION**

### **4.1 Registration Flow**
```php
// 1. Validasi input
$validated = $request->validate([
    'username' => ['required', 'string', 'unique:user,username'],
    'email' => ['required', 'email', 'unique:pasien,email'],
    'password' => ['required', 'string', 'min:8'],
    'password_confirmation' => ['required', 'same:password'],
    'nama' => ['required', 'string'],
    'no_hp' => ['required', 'string'],
    'tgl_lahir' => ['required', 'date'],
]);

// 2. Database transaction
\DB::beginTransaction();

// 3. Get/create role 'pasien'
$userRole = Role::where('role_name', 'pasien')->first();
if (!$userRole) {
    $userRole = Role::create([
        'role_name' => 'pasien',
        'description' => 'Default patient role',
    ]);
}

// 4. Create user
$user = User::create([
    'username' => $validated['username'],
    'password_hash' => Hash::make($validated['password']),
    'role_id' => $userRole->role_id,
    'email' => $validated['email'],
]);

// 5. Create pasien profile
$pasien = Pasien::create([
    'user_id' => $user->user_id,
    'nama' => $validated['nama'],
    'email' => $validated['email'],
    'no_hp' => $validated['no_hp'],
    'tgl_lahir' => $validated['tgl_lahir'],
]);

\DB::commit();
```

### **4.2 Login Flow**
```php
// 1. Find user by username or email
$user = User::where('username', $input)
    ->orWhere('email', $input)
    ->first();

// 2. Verify password
if (!$user || !Hash::check($password, $user->password_hash)) {
    return back()->withErrors(['error' => 'Invalid credentials']);
}

// 3. Set session
session([
    'user_id' => $user->user_id,
    'username' => $user->username,
    'role' => $user->role->role_name,        // ⚠️ role_name bukan nama_role
    'role_name' => strtolower($user->role->role_name),
]);

// 4. Role-based redirect
if (strtolower($user->role->role_name) === 'admin') {
    return redirect()->route('admin.dashboard');
}
return redirect()->route('user.home');
```

---

## **5. QUERIES CONTOH**

### **5.1 Get User dengan Role**
```sql
SELECT 
    u.user_id,
    u.username,
    u.email,
    r.role_name,
    r.description
FROM user u
LEFT JOIN role r ON u.role_id = r.role_id
WHERE u.user_id = ?;
```

### **5.2 Get User dengan Pasien Data**
```sql
SELECT 
    u.user_id,
    u.username,
    u.email,
    r.role_name,
    p.nama,
    p.no_hp,
    p.tgl_lahir
FROM user u
LEFT JOIN role r ON u.role_id = r.role_id
LEFT JOIN pasien p ON u.user_id = p.user_id
WHERE u.user_id = ?;
```

### **5.3 Get User dengan Staf Data**
```sql
SELECT 
    u.user_id,
    u.username,
    u.email,
    r.role_name,
    s.nama,
    s.jabatan,
    c.nama_cabang
FROM user u
LEFT JOIN role r ON u.role_id = r.role_id
LEFT JOIN staf s ON u.user_id = s.user_id
LEFT JOIN cabang c ON s.cabang_id = c.cabang_id
WHERE u.user_id = ?;
```

### **5.4 Count Users by Role**
```sql
SELECT 
    r.role_name,
    COUNT(u.user_id) as user_count
FROM role r
LEFT JOIN user u ON r.role_id = u.role_id
GROUP BY r.role_name
ORDER BY user_count DESC;
```

---

## **6. VALIDASI & CONSTRAINTS**

### **6.1 Unique Constraints**
- `user.username` - Username harus unik
- `user.email` - Email di tabel user harus unik
- `pasien.email` - Email di tabel pasien harus unik
- `pasien.user_id` - Satu user hanya bisa punya satu profil pasien
- `staf.user_id` - Satu user hanya bisa punya satu profil staf

### **6.2 Foreign Key Constraints**
- `user.role_id` → `role.role_id`
- `pasien.user_id` → `user.user_id`
- `staf.user_id` → `user.user_id`
- `staf.cabang_id` → `cabang.cabang_id`
- `log_activity.user_id` → `user.user_id`

### **6.3 Data Validation Rules**
```php
// Registration validation
'username' => 'required|string|unique:user,username|max:50',
'email' => 'required|email|unique:pasien,email|max:100',
'password' => 'required|string|min:8|confirmed',
'nama' => 'required|string|max:100',
'no_hp' => 'required|string|max:20',
'tgl_lahir' => 'required|date|before:today',

// Login validation
'username' => 'required|string',
'password' => 'required|string',
```

---

## **7. TESTING DATABASE**

### **7.1 Insert Test Data**
```sql
-- Insert roles
INSERT INTO role (role_name, description) VALUES 
    ('admin', 'Administrator'),
    ('staf', 'Staff'),
    ('pasien', 'Patient');

-- Insert test admin user
INSERT INTO user (username, password_hash, email, role_id) VALUES 
    ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@eclinic.com', 1);

-- Insert test patient user
INSERT INTO user (username, password_hash, email, role_id) VALUES 
    ('testuser', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'test@example.com', 3);

-- Insert patient profile
INSERT INTO pasien (user_id, nama, email, no_hp, tgl_lahir) VALUES 
    (2, 'Test User', 'test@example.com', '08123456789', '1990-01-01');
```

### **7.2 Verify Data**
```sql
-- Check all users with roles
SELECT 
    u.user_id,
    u.username,
    u.email,
    r.role_name,
    CASE 
        WHEN p.user_id IS NOT NULL THEN p.nama
        WHEN s.user_id IS NOT NULL THEN s.nama
        ELSE 'No Profile'
    END as profile_name
FROM user u
LEFT JOIN role r ON u.role_id = r.role_id
LEFT JOIN pasien p ON u.user_id = p.user_id
LEFT JOIN staf s ON u.user_id = s.user_id;
```

---

## **8. TROUBLESHOOTING**

### **8.1 Common Issues**

**Issue: Column 'nama_role' not found**
```sql
-- Check actual column name
DESCRIBE role;
-- Should show: role_name (not nama_role)
```

**Issue: Foreign key constraint fails**
```sql
-- Check if referenced data exists
SELECT * FROM role WHERE role_id = ?;
SELECT * FROM user WHERE user_id = ?;
```

**Issue: Duplicate entry for email**
```sql
-- Check existing emails
SELECT email FROM user WHERE email = ?;
SELECT email FROM pasien WHERE email = ?;
```

### **8.2 Debug Queries**
```sql
-- Check table structure
SHOW CREATE TABLE user;
SHOW CREATE TABLE role;
SHOW CREATE TABLE pasien;
SHOW CREATE TABLE staf;

-- Check foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = 'basdat';
```

---

## **9. MIGRATION FILES**

### **9.1 Create User Table**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50)->unique();
            $table->string('password_hash');
            $table->string('email', 100)->unique();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('role_id')->on('role');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
};
```

### **9.2 Create Role Table**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 50);  // ⚠️ role_name bukan nama_role
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
};
```

---

## **KESIMPULAN**

✅ **Struktur database sudah sesuai dengan spesifikasi:**
- Tabel `user` dengan `role_id` sebagai FK ke `role`
- Tabel `role` dengan `role_name` (bukan `nama_role`)
- Tabel `pasien` dengan `user_id` sebagai FK ke `user`
- Tabel `staf` dengan `user_id` dan `cabang_id` sebagai FK
- Relasi yang benar antar tabel
- Model Laravel yang sesuai
- Authentication flow yang terintegrasi

⚠️ **Poin Penting:**
- Field di tabel `role` adalah `role_name` bukan `nama_role`
- Semua kode sudah disesuaikan dengan struktur yang benar
- Foreign key constraints sudah benar
- Unique constraints sudah diterapkan

Sistem authentication sekarang fully functional dengan struktur database yang benar!
