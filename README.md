# School Management System

Aplikasi manajemen sekolah berbasis web yang dibangun menggunakan **Laravel 12**. Aplikasi ini merupakan solusi terpadu untuk mengelola kegiatan administrasi dan akademik sekolah secara digital, mencakup pengelolaan data siswa, guru, kelas, jadwal pelajaran, nilai, absensi, hingga kegiatan ekstrakurikuler.

Sistem ini mendukung banyak pengguna dengan tingkat akses yang berbeda (multi-role), sehingga setiap pihak — mulai dari pihak manajemen sekolah, guru, hingga siswa — dapat mengakses fitur yang relevan sesuai kebutuhan dan kewenangannya masing-masing. Dengan adanya fitur rekap nilai dan absensi, sekolah dapat memantau perkembangan akademik dan kehadiran siswa secara lebih mudah, cepat, dan terstruktur.

Selain itu, aplikasi ini juga dilengkapi dengan fitur pengumuman (announcement) dan pencatatan aktivitas (activity log) untuk mendukung transparansi dan pengawasan penggunaan sistem.

Dengan demikian, aplikasi ini menjadi sebuah solusi lengkap untuk manajemen sekolah yang efisien, terstruktur, dan mudah digunakan oleh seluruh pihak terkait.

---

## Role Pengguna

Aplikasi ini memiliki empat peran (role) dengan hak akses yang diatur melalui middleware `RoleMiddleware`, berdasarkan kolom `role` pada tabel `tbl_users`:

### 1. Super Admin
Memiliki akses penuh ke seluruh sistem, meliputi:
- Manajemen admin
- Manajemen guru, siswa, kelas, jadwal, nilai, absensi, dan ekstrakurikuler (CRUD penuh)
- Mengelola profil pribadi

### 2. Admin
Membantu operasional harian sekolah, dengan akses ke:
- Manajemen guru, siswa, kelas, jadwal, nilai, absensi, dan ekstrakurikuler (CRUD penuh)
- **Tidak** dapat mengakses manajemen admin lain
- Mengelola profil pribadi

### 3. Teacher (Guru)
Mengelola kegiatan belajar-mengajar, dengan akses ke:
- Manajemen siswa, kelas, jadwal, nilai, absensi, dan ekstrakurikuler
- **Tidak** dapat mengakses manajemen admin maupun manajemen guru lain
- Mengelola profil pribadi

### 4. Student (Siswa)
Hanya memiliki akses baca (readonly) terhadap data pribadinya sendiri:
- Melihat dan mengedit profil pribadi
- Melihat nilai pribadi
- Melihat rekap absensi pribadi
- Melihat jadwal kelas pribadi
- Melihat dan bergabung (join) ke kegiatan ekstrakurikuler
- **Tidak** dapat melihat data siswa lain maupun mengakses menu manajemen (admin/guru/kelas/dll.)

Ringkasan perbandingan hak akses selengkapnya tersedia di [`ROLE_ACCESS.md`](./ROLE_ACCESS.md).

---

## Installation

### Clone Repository
```bash
git clone https://github.com/arlafelda/School-Management-System.git
```

### Masuk ke Direktori
```bash
cd School-Management-System
```

### Instal Dependensi
```bash
composer install
npm install
```

### Buat File Environment
```bash
cp .env.example .env
```

### Konfigurasi Environment
Sesuaikan pengaturan database (nama database, username, password) di file `.env`

### Generate App Key
```bash
php artisan key:generate
```

### Jalankan Migrasi Database
```bash
php artisan migrate
```

### Menjalankan Database Seeder
```bash
php artisan db:seed
```

### Link Storage
```bash
php artisan storage:link
```

### Build Asset Frontend
```bash
npm run build
```

### Menjalankan Server
```bash
php artisan serve
```

---

## Requirements
- PHP Version 8.2+
- Composer
- Node.js & NPM
- MySQL (atau database lain yang didukung Laravel)
