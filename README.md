# Garuda-Hub (Laravel MVC Edition)

> **Misi:** [1] The Spaghetti Code Incident  
> **Kelompok / Branch:** `The_Spaghetti_Code_Incident_gandhiMM_252410102049`

Website lelang proyek IT mahasiswa yang telah **direfaktorisasi secara total** dari *spaghetti code* PHP native menjadi aplikasi berarsitektur bersih **MVC (Model-View-Controller)** menggunakan framework **Laravel 11** dan **Eloquent ORM**.

---

## 🏛️ Arsitektur & Perubahan Database Relasional

Skema database telah dirancang ulang agar memiliki relasi relasional yang kokoh:

1. **`users`**
   - Kolom: `id`, `name`, `email` (unique), `password` (bcrypt), `role` (`klien`, `developer`, `admin`), `timestamps`.
   - Relasi:
     - `projects()`: One-to-Many (`hasMany`) ke tabel `projects` (sebagai klien).
     - `bids()`: One-to-Many (`hasMany`) ke tabel `bids` (sebagai developer).

2. **`projects`**
   - Kolom: `id`, `client_id` (foreign key `users.id` cascade on delete), `title`, `description`, `category`, `budget_min`, `budget_max`, `deadline`, `status` (`terbuka`, `ditutup`), `timestamps`.
   - Relasi:
     - `client()`: Many-to-One (`belongsTo`) ke model `User`.
     - `bids()`: One-to-Many (`hasMany`) ke model `Bid`.
     - `winningBid()`: One-to-One (`hasOne`) bid dengan status pemenang.

3. **`bids`**
   - Kolom: `id`, `project_id` (foreign key `projects.id` cascade on delete), `developer_id` (foreign key `users.id` cascade on delete), `price`, `duration_days`, `proposal`, `is_winner`, `timestamps`.
   - *Constraint Unik:* `UNIQUE(project_id, developer_id)` memastikan 1 developer hanya dapat mengajukan 1 penawaran per proyek.
   - Relasi:
     - `project()`: Many-to-One (`belongsTo`) ke model `Project`.
     - `developer()`: Many-to-One (`belongsTo`) ke model `User`.

---

## ✨ Fitur-Fitur Utama (MVC & Eloquent)

- **Multi-Role Authentication & Middleware:**
  - Registrasi & Login untuk peran `klien` dan `developer`.
  - Akun `admin` khusus untuk manajemen platform.
  - Middleware `RoleMiddleware` mengamankan setiap route sesuai hak akses.
- **Klien:**
  - Memasang proyek baru lengkap dengan rentang budget & batas lelang.
  - Memantau penawaran masuk di dasbor klien.
  - Memilih pemenang lelang (menggunakan DB Transaction untuk memastikan integritas data dan penutupan otomatis proyek).
- **Developer:**
  - Eksplorasi & pencarian proyek terbuka berdasarkan judul/kategori.
  - Pengajuan proposal penawaran (harga, durasi pengerjaan, proposal).
  - Dasbor pemantauan status penawaran dan status kemenangan lelang.
- **Admin Panel:**
  - Manajemen seluruh pengguna (ubah peran user, hapus user).
  - Manajemen dan moderasi proyek.

---

## 🚀 Panduan Menjalankan Aplikasi di Lokal

### 1. Kebutuhan Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_sqlite` atau `pdo_mysql`)
- Composer

### 2. Instalasi & Migrasi Database
```bash
# Salin konfigurasi environment jika belum ada
cp .env.example .env

# Generate application key
php artisan key:generate

# Jalankan migrasi dan isi data dummy awal
php artisan migrate:fresh --seed
```

### 3. Menjalankan Server Lokal
```bash
php artisan serve
```
Buka browser di `http://127.0.0.1:8000`.

---

## 🔑 Akun Demo (Seeder)

| Peran | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@garudahub.test` | `password123` | Akses panel admin kelola user & proyek |
| **Klien** | `budi@garudahub.test` | `password123` | Memasang proyek & memilih pemenang |
| **Developer** | `andi@garudahub.test` | `password123` | Mengajukan penawaran proposal lelang |
| **Developer** | `rina@garudahub.test` | `password123` | Mengajukan penawaran proposal lelang |

---

## 📜 Riwayat Git Commit (Memenuhi Syarat Misi)
Repositori ini memiliki minimal 5 commit terstruktur:
1. `chore: setup laravel framework and archive legacy code`
2. `feat: design relational schema migrations and eloquent models`
3. `feat: implement authentication and role-based access control`
4. `feat: implement project posting, bidding, winner selection, and dashboard`
5. `feat: add admin panel, business validation, and blade UI layout`
