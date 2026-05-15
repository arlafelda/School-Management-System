# Role Access Documentation

Dokumentasi ini menjelaskan hak akses untuk setiap role yang digunakan dalam sistem.

## Role yang tersedia
- `super_admin`
- `admin`
- `teacher`
- `student`

## How role access is enforced
Akses pada sistem dikontrol melalui middleware `RoleMiddleware` pada setiap route.
Middleware ini memeriksa nilai `Auth::user()->role` terhadap daftar role yang diizinkan.

## Hak akses per role

### `super_admin`
- Dashboard: `/dashboard/superadmin`
- Akses penuh ke:
  - Manajemen admin (`/admin/*`)
  - Manajemen guru (`/teachers/*`)
  - Manajemen siswa (`/students/*`)
  - Manajemen kelas (`/classes/*`)
  - Manajemen jadwal (`/schedule/*`)
  - Manajemen nilai (`/grades/*`)
  - Manajemen absensi (`/attendance/*`)
  - Manajemen ekstrakurikuler (`/extracurricular/*`)
- Dapat membuat, melihat, mengedit, dan menghapus semua resource di atas.
- Dapat mengakses halaman profil (`/profile`).

### `admin`
- Dashboard: `/dashboard/admin`
- Akses ke:
  - Manajemen guru (`/teachers/*`)
  - Manajemen siswa (`/students/*`)
  - Manajemen kelas (`/classes/*`)
  - Manajemen jadwal (`/schedule/*`)
  - Manajemen nilai (`/grades/*`)
  - Manajemen absensi (`/attendance/*`)
  - Manajemen ekstrakurikuler (`/extracurricular/*`)
- Tidak dapat mengakses manajemen admin (`/admin/*`).
- Dapat mengakses halaman profil (`/profile`).

### `teacher`
- Dashboard: `/dashboard/teacher`
- Akses ke:
  - Manajemen siswa (`/students/*`)
  - Manajemen kelas (`/classes/*`)
  - Manajemen jadwal (`/schedule/*`)
  - Manajemen nilai (`/grades/*`)
  - Manajemen absensi (`/attendance/*`)
  - Manajemen ekstrakurikuler (`/extracurricular/*`)
- Tidak dapat mengakses manajemen admin (`/admin/*`) dan manajemen guru (`/teachers/*`).
- Dapat mengakses halaman profil (`/profile`).

### `student`
- Dashboard: `/dashboard/student`
- Akses hanya untuk data pribadi (readonly):
  - Halaman ekstrakurikuler siswa (`/student/extracurricular`) - lihat & join
  - Aksi join ekstrakurikuler (`POST /extracurricular/{id}/join`)
  - Halaman profil pribadi (`/profile`) - lihat & edit profil sendiri
- Fitur yang seharusnya ditambahkan (untuk akses data pribadi):
  - Lihat nilai pribadi (`/student/grades`)
  - Lihat absensi pribadi (`/student/attendance`)
  - Lihat jadwal kelas pribadi (`/student/schedule`)
- Tidak dapat mengakses:
  - `/admin/*` - manajemen admin
  - `/teachers/*` - manajemen guru
  - `/students/*` - lihat/edit data siswa lain
  - `/classes/*` - manajemen kelas
  - `/schedule/*` (CRUD) - hanya lihat jadwal pribadi
  - `/grades/*` (CRUD) - hanya lihat nilai pribadi
  - `/attendance/*` (CRUD) - hanya lihat absensi pribadi
  - `/extracurricular/*` (CRUD) - hanya lihat, tidak bisa edit/delete ekstrakurikuler

## Rangkuman route yang digunakan
- `role:super_admin`:
  - `/dashboard/superadmin`
  - semua route `/admin/*`
- `role:admin`:
  - `/dashboard/admin`
  - semua route `/teachers/*`
- `role:teacher`:
  - `/dashboard/teacher`
- `role:student`:
  - `/dashboard/student`
  - `/student/extracurricular`
  - `POST /extracurricular/{id}/join`
- `role:super_admin,admin`:
  - `/teachers/*`
- `role:super_admin,admin,teacher`:
  - `/students/*`, `/classes/*`, `/schedule/*`, `/grades/*`, `/attendance/*`, `/extracurricular/*`

## Tabel perbandingan hak akses data

| Data | Student | Teacher | Admin | Super Admin |
|------|---------|---------|-------|-------------|
| **Profil Pribadi** | ✅ Lihat & Edit | ✅ Lihat & Edit | ✅ CRUD | ✅ CRUD |
| **Nilai Pribadi** | ✅ Lihat | ✅ Kelola | ✅ CRUD | ✅ CRUD |
| **Absensi Pribadi** | ✅ Lihat | ✅ Kelola | ✅ CRUD | ✅ CRUD |
| **Jadwal Pribadi** | ✅ Lihat | ✅ Lihat & CRUD | ✅ CRUD | ✅ CRUD |
| **Data Siswa Lain** | ❌ | ✅ Lihat | ✅ CRUD | ✅ CRUD |
| **Data Guru** | ❌ | ❌ | ✅ CRUD | ✅ CRUD |
| **Data Kelas** | ❌ | ✅ Lihat | ✅ CRUD | ✅ CRUD |
| **Ekstrakurikuler** | ✅ Join | ✅ CRUD | ✅ CRUD | ✅ CRUD |

## Konsep akses data untuk `student`

### Prinsip Utama
`student` adalah role yang memiliki akses **readonly** untuk data akademik pribadi mereka saja. Mereka tidak bisa melakukan CRUD operation (kecuali join ekstrakurikuler) dan tidak boleh melihat data siswa lain.

### Yang boleh dilihat `student`:
1. **Data pribadi sendiri** (`/profile`)
   - Nama, email, NISN, kelas, dll
   - Boleh edit password saja
   - Tidak boleh edit data akademik seperti NISN, kelas, dll (hanya admin/teacher)

2. **Nilai pribadi** (`/student/grades`) - 📌 BELUM ADA
   - Filter: `Grade::where('student_id', Auth::user()->student->id)`
   - Lihat nilai per mata pelajaran

3. **Absensi pribadi** (`/student/attendance`) - 📌 BELUM ADA
   - Filter: `Attendance::where('student_id', Auth::user()->student->id)`
   - Lihat rekap kehadiran dengan status (hadir/sakit/izin/alpa)

4. **Jadwal pribadi** (`/student/schedule`) - 📌 BELUM ADA
   - Filter: Ambil kelas dari `Auth::user()->student->class_id`
   - Tampilkan jadwal untuk kelas tersebut
   - Lihat guru, mata pelajaran, jam

5. **Ekstrakurikuler**
   - Lihat daftar ekstrakurikuler yang tersedia ✅ (sudah ada)
   - Join ekstrakurikuler ✅ (sudah ada)
   - Lihat ekstrakurikuler yang diikuti

### Yang TIDAK boleh dilihat `student`:
- Data siswa lain (`/students/{student}`)
- Manajemen guru (`/teachers/*`)
- Manajemen admin (`/admin/*`)
- CRUD jadwal, nilai, absensi (hanya lihat)
- Edit/delete ekstrakurikuler

## Implementasi Teknis untuk `student`

### Route yang perlu ditambahkan (di `routes/web.php`)
```php
Route::middleware(['auth', 'role:student'])->group(function () {
    // Lihat nilai pribadi
    Route::get('/student/grades', [GradeController::class, 'studentGrades'])
        ->name('student.grades');
    
    // Lihat absensi pribadi
    Route::get('/student/attendance', [AttendanceController::class, 'studentAttendance'])
        ->name('student.attendance');
    
    // Lihat jadwal pribadi
    Route::get('/student/schedule', [ScheduleController::class, 'studentSchedule'])
        ->name('student.schedule');
});
```

### Contoh Controller Method untuk `GradeController`
```php
public function studentGrades()
{
    $user = Auth::user();
    $student = $user->student;
    
    // Filter: hanya nilai siswa yang login
    $grades = Grade::with(['schedule.teacher', 'schedule.class'])
        ->where('student_id', $student->id)
        ->get();
    
    return view('student.grades-index', compact('grades'));
}
```

### Contoh Controller Method untuk `AttendanceController`
```php
public function studentAttendance()
{
    $user = Auth::user();
    $student = $user->student;
    
    // Filter: hanya absensi siswa yang login
    $attendances = Attendance::with(['schedule.teacher', 'schedule.class'])
        ->where('student_id', $student->id)
        ->get();
    
    // Hitung persentase kehadiran
    $total = $attendances->count();
    $present = $attendances->where('status', 'present')->count();
    $attendancePercent = $total > 0 ? round(($present / $total) * 100) : 0;
    
    return view('student.attendance-index', compact('attendances', 'attendancePercent'));
}
```

### Contoh Controller Method untuk `ScheduleController`
```php
public function studentSchedule()
{
    $user = Auth::user();
    $student = $user->student;
    
    // Filter: jadwal untuk kelas siswa yang login
    $schedules = Schedule::with(['teacher', 'class'])
        ->where('class_id', $student->class_id)
        ->orderBy('day', 'asc')
        ->get();
    
    return view('student.schedule-index', compact('schedules'));
}
```

## Checklist Implementasi untuk Role `student`

### ✅ Sudah ada (done)
- [x] Route `/dashboard/student` - dashboard siswa dengan absensi
- [x] Route `/student/extracurricular` - lihat ekstrakurikuler yang tersedia
- [x] Action `POST /extracurricular/{id}/join` - join ekstrakurikuler
- [x] Route `/profile` - edit profil pribadi

### 📌 Belum ada (todo)
- [ ] Route `/student/grades` - lihat nilai pribadi siswa
- [ ] Route `/student/attendance` - lihat absensi pribadi dengan rekap
- [ ] Route `/student/schedule` - lihat jadwal pribadi (jadwal kelas mereka)
- [ ] Update `DashboardController@index()` method untuk teacher - filter jadwal by `teacher_id`
- [ ] Update `ScheduleController@index()` - teacher hanya lihat jadwal dirinya sendiri
- [ ] Update `GradeController@index()` - teacher hanya lihat nilai siswa yang mereka ajar
- [ ] Update `AttendanceController@index()` - teacher hanya lihat absensi untuk kelas mereka

## Catatan
Role disimpan di kolom `role` pada tabel `tbl_users`.
