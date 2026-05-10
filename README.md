# Warehouse Backend

Workspace Backend untuk project tim Warehouse. Repository ini disiapkan agar bisa di instalasi dari branch GitHub lain dalam ekositem repository ini, lalu dijalankan di lokal oleh seluruh anggota tim dengan konfigurasi yang sama.

Namun, bagian lainnya (Laravel setup, PHP, Composer, artisan commands) perlu disesuaikan juga. Beri tahu preferensi Anda.


## Ringkasan

- Framework: Laravel 13
- Auth API: Laravel Sanctum
- RBAC: berbasis role dan permission di tabel users
- Fokus awal database: autentikasi login password dan kontrol akses role

## Kebutuhan Sistem

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- Database MySQL / MariaDB / PostgreSQL / SQLite
- Git

## Struktur Auth dan RBAC

Project ini menggunakan tabel users sebagai pusat autentikasi dan kontrol akses.

Role yang tersedia:

- super_admin
- admin
- finance
- project_manager

Kolom penting pada users:

- name
- email
- password
- role
- is_active

Catatan:

- Login menggunakan endpoint API dan token Sanctum
- Akses fitur dibatasi dengan permission sesuai role
- Jika ingin database awal hanya berisi data autentikasi, cukup jalankan migrasi users dan tabel bawaan Laravel/Sanctum, lalu isi user admin seperlunya

## Cara Clone dari Repository GitHub

1. Clone repository:

	```bash
	git clone https://github.com/<username>/<nama-repo>.git
	cd <nama-repo>
	```

2. Install dependency backend:

	```bash
	composer install
	```

3. Install dependency frontend:

	```bash
	npm install
	```

4. Salin file environment:

	```bash
	copy .env.example .env
	```

5. Atur koneksi database di file .env:

	- DB_CONNECTION
	- DB_HOST
	- DB_PORT
	- DB_DATABASE
	- DB_USERNAME
	- DB_PASSWORD

6. Generate application key:

	```bash
	php artisan key:generate
	```

## Migrasi Database

Untuk setup awal project tim, jalankan migrasi:

```bash
php artisan migrate
```

Jika ingin sekalian mengisi user default untuk demo atau testing role, jalankan:

```bash
php artisan migrate --seed
```

Seeder bawaan akan membuat akun contoh berikut:

| Role | Email | Password |
| --- | --- | --- |
| super_admin | superadmin@warehouse.test | **** |
| admin | admin@warehouse.test | **** |
| finance | finance@warehouse.test | **** |
| project_manager | pm@warehouse.test | **** |

## Menjalankan Backend

Jalankan server Laravel:

```bash
php artisan serve
```

Jika ingin menjalankan backend dan frontend sekaligus dalam mode development:

```bash
npm run dev
```

Atau gunakan script bawaan Laravel:

```bash
composer run dev
```

## Endpoint Auth Utama

- POST /api/login
- POST /api/logout
- GET /api/me

## Alur Setup Cepat untuk Tim

1. Clone repository
2. Jalankan composer install
3. Jalankan npm install
4. Copy .env.example ke .env
5. Isi konfigurasi database
6. Jalankan php artisan key:generate
7. Jalankan php artisan migrate atau php artisan migrate --seed
8. Jalankan php artisan serve

## Catatan Pengembangan Tim

- Pastikan file .env tidak di-commit ke repository
- Setiap anggota tim cukup clone repository lalu mengikuti langkah setup di atas
- Untuk data awal yang bersih, gunakan migrate tanpa seed
- Untuk akun demo RBAC, gunakan migrate --seed

## License

Proyek ini menggunakan lisensi MIT.
