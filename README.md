# SINTAKS — Sistem Penataan Kontak Sederhana Kontak

[![Website SINTAKS](https://img.shields.io/badge/Live%20Demo-View%20SINTAKS-blue)](lightsalmon-mallard-735558.hostingersite.com)

Aplikasi web manajemen kontak modern yang dibangun dengan PHP, menerapkan prinsip form handling, validasi, sanitasi, dan keamanan. Dibuat untuk memenuhi tugas akhir praktikum pemrograman web dengan fokus pada session management dan security.

Website ini menggunakan **PHP native**, **CSS custom**, dan **session-based storage**.

---

## ✨ Fitur Utama

### 🔐 Autentikasi
- **Register**: Daftar akun baru dengan validasi
- **Login**: Masuk dengan email dan password
- **Logout**: Keluar dari sistem
- **Session Management**: Data tersimpan di session

### 📋 Manajemen Kontak
- **Tambah Kontak**: Form tambah kontak dengan validasi lengkap
- **Edit Kontak**: Ubah data kontak yang sudah ada
- **Hapus Kontak**: Hapus kontak dengan konfirmasi
- **Cari & Filter**: Pencarian berdasarkan nama/email/telepon dan filter kategori
- **Kategori**: Pribadi, Kerja, Keluarga, Lainnya

### 🎨 UI/UX
- **Desain Elegan**: Black & white theme yang tidak terlalu gelap
- **Responsive**: Tampilan optimal di semua device
- **External CSS**: Styling terpisah di `assets/css/style.css`
- **Animasi**: Smooth transitions dan fade-in effects
- **Flash Messages**: Notifikasi sukses/error yang informatif

## 🔒 Keamanan

### Validasi Form
- **Nama**: Hanya huruf dan spasi (regex: `^[a-zA-Z\s]+$`)
- **Email**: Format email valid (`filter_var` dengan `FILTER_VALIDATE_EMAIL`)
- **Telepon**: Format telepon valid (regex: `^[0-9+\-\(\)\s]+$`)
- **Password**: Minimal 6 karakter, dienkripsi dengan `password_hash()`

### Sanitasi Input
- Semua input di-sanitize dengan `htmlspecialchars()` dan `trim()`
- Mencegah XSS (Cross-Site Scripting)
- Validasi kategori dengan whitelist

### Session Security
- Session-based authentication
- Protected pages dengan `requireLogin()`
- Guest-only pages dengan `requireGuest()`
- Session timeout otomatis

## 📂 Struktur Proyek

```
tugas-akhir/
├── assets/
│   └── css/
│       └── style.css          # External CSS dengan elegant black-white theme
├── auth/
│   ├── login.php              # Halaman login
│   ├── register.php           # Halaman registrasi
│   └── logout.php             # Proses logout
├── includes/
│   ├── header.php             # HTML header template
│   ├── footer.php             # Footer template
│   └── functions.php          # Core functions & auth system
├── pages/
│   ├── add-contact.php        # Form tambah kontak
│   ├── edit-contact.php       # Form edit kontak
│   └── delete-contact.php     # Proses hapus kontak
├── index.php                  # Dashboard utama (contact list)
└── README.md                  # Dokumentasi lengkap
```

## 🧱 Teknologi

- **PHP 7.4+**: Server-side logic dan session management
- **HTML5**: Semantic markup
- **CSS3 Custom**: External styling
- **Session Storage**: Data persistence tanpa database
- **Regex & Filter**: Validasi form sisi server
- **Bcrypt**: Password hashing (PHP `password_hash()`)

---

## 🚀 Cara Menjalankan Lokal

### 1. Persyaratan
- PHP 7.4 atau lebih tinggi
- Web server (Apache/Nginx) atau PHP built-in server
- Browser modern (Chrome, Firefox, Edge)

### 2. Setup Cepat

**Via XAMPP/Laragon:**
```bash
# Letakkan folder di htdocs/www
# Akses: http://localhost/PraktikumPPW4/tugas-akhir/
```

**Via PHP Built-in Server:**
```bash
cd tugas-akhir
php -S localhost:8000
# Akses: http://localhost:8000/
```

### 3. Akses Aplikasi
```
http://localhost/PraktikumPPW4/tugas-akhir/auth/login.php
```

### 4. Alur Penggunaan

**A. Registrasi Akun Baru**
1. Akses halaman register: `/auth/register.php`
2. Isi form registrasi:
   - Nama Lengkap (hanya huruf dan spasi)
   - Email (format valid)
   - Password (minimal 6 karakter)
   - Konfirmasi Password
3. Klik "Daftar Sekarang"
4. Redirect otomatis ke halaman login

**B. Login**
1. Masukkan email dan password yang terdaftar
2. Sistem akan memvalidasi kredensial
3. Session `$_SESSION['user_logged_in']` akan dibuat
4. Redirect ke dashboard utama (`/index.php`)

**C. Kelola Kontak**

Setelah login berhasil, Anda dapat:

#### ➕ Tambah Kontak
1. Klik tombol "**+ Tambah Kontak**"
2. Isi form:
   - Nama (hanya huruf dan spasi)
   - Email (format valid)
   - Telepon (angka, +, -, (, ), spasi)
   - Kategori (Pribadi/Kerja/Keluarga/Lainnya)
3. Klik "**Simpan Kontak**"

#### ✏️ Edit Kontak
1. Klik tombol "**Edit**" pada kontak yang ingin diubah
2. Ubah data yang diperlukan
3. Klik "**Simpan Perubahan**"

#### 🗑️ Hapus Kontak
1. Klik tombol "**Hapus**" pada kontak
2. Konfirmasi penghapusan
3. Kontak akan dihapus dari daftar

#### 🔍 Cari & Filter
- **Search**: Ketik nama/email/telepon di kolom pencarian
- **Filter Kategori**: Pilih kategori dari dropdown
- **Reset**: Klik "Reset" untuk menampilkan semua kontak

**D. Logout**
- Klik tombol "Logout" di navbar
- Session akan dihancurkan (`session_destroy()`)
- Redirect ke halaman login

---

## 🧪 Validasi & Sanitasi Form

### Server-Side Validation
Semua validasi dilakukan di server (PHP) untuk keamanan maksimal:

```php
// Contoh validasi nama
if (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
    $errors[] = 'Nama hanya boleh berisi huruf dan spasi';
}

// Contoh validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid';
}

// Contoh validasi telepon
if (!preg_match("/^[0-9+\-\(\)\s]+$/", $telepon)) {
    $errors[] = 'Format telepon tidak valid';
}
```

### Input Sanitization
```php
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
```

## 🎨 Design System

### Color Palette
```css
/* Elegant Black & White Theme */
--primary-dark: #1a1a1a;      /* Not pure black - softer */
--gray-[100-800]: ...         /* Grayscale system */
--accent-blue: #3b82f6;       /* Badge Pribadi */
--accent-green: #10b981;      /* Badge Kerja */
--accent-yellow: #f59e0b;     /* Badge Keluarga */
--accent-red: #ef4444;        /* Error states */
```

### UI Components
- **Navbar**: Sticky header dengan gradient background
- **Cards**: Soft shadows dan hover effects
- **Tables**: Striped rows dengan responsive scroll
- **Forms**: 2px borders, focus states, validation hints
- **Badges**: Rounded pills untuk kategori kontak
- **Alerts**: Color-coded flash messages
- **Buttons**: Primary, secondary, danger variants

### Typography
- Font: **Google Fonts Inter** (300-800 weights)
- Hierarchy: Clear visual separation antar sections

---

## 🎓 Konsep yang Diterapkan

### 1. PHP Form Handling
- `$_POST` untuk form submission
- `$_GET` untuk search & filter
- `$_SERVER['REQUEST_METHOD']` untuk deteksi method

### 2. Validasi Form
- **Client-side**: HTML5 attributes (`required`, `type="email"`)
- **Server-side**: PHP validation dengan regex dan filter_var
- Multiple validation rules per field

### 3. Sanitasi Input
- `htmlspecialchars()`: Prevent XSS
- `trim()`: Remove whitespace
- `stripslashes()`: Remove backslashes

### 4. Session Management
- `session_start()`: Initialize session
- `$_SESSION`: Store user & contact data
- `session_destroy()`: Logout

### 5. Security
- Password hashing dengan `password_hash()`
- Password verification dengan `password_verify()`
- XSS prevention
- CSRF protection (session-based)

---

## 📚 Learning Objectives

Proyek ini mengimplementasikan konsep:

- ✅ **PHP Form Handling**: `$_POST`, `$_GET`, `$_SERVER['REQUEST_METHOD']`
- ✅ **Validasi Form**: Regex patterns, `filter_var()`, whitelist validation
- ✅ **Sanitasi Input**: `htmlspecialchars()`, `trim()`, `stripslashes()`
- ✅ **Session Management**: `session_start()`, `$_SESSION`, `session_destroy()`
- ✅ **Password Security**: `password_hash()`, `password_verify()`
- ✅ **Routing Sederhana**: Multi-page application dengan includes
- ✅ **External CSS**: Separation of concerns (no inline styles)

---

## 📞 Kontak
- **Email:** sulthon.alfarizky@gmail.com
- **LinkedIn:** [linkedin.com/in/sulthon](https://www.linkedin.com/in/sulthon)
- **GitHub:** [github.com/sulthonalfarizky](https://github.com/sulthonalfarizky)

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan tugas akhir praktikum **Praktikum Pemrograman Web Judul 4** dengan topik:
- PHP Form Handling
- Validasi dan Sanitasi Form
- Keamanan Form (Authentication & Security Best Practices)
