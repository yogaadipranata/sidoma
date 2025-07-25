# Sistem Informasi Akademik Sederhana (SIAD)

**Sistem Informasi Akademik Sederhana (SIAD)** adalah platform berbasis web yang dikembangkan sebagai proyek akhir untuk [Sebutkan Program Anda, contoh: Program Pengembang Web di PT Hacktivate Teknologi Indonesia, atau tugas kampus]. Project ini dirancang untuk memfasilitasi pengelolaan data perkuliahan, nilai, Kartu Rencana Studi (KRS), dan jadwal, dengan dukungan peran pengguna yang berbeda (Dosen dan Mahasiswa). SIAD mengintegrasikan fitur-fitur penting untuk operasional akademik yang efisien dan user-friendly.

### 🛠️ Tech Stack
* **Backend:** Laravel v11.x (PHP 8.2+)
* **Database:** MySQL (digunakan via XAMPP)
* **Frontend Framework:** Tailwind CSS v3
* **Bundler Aset:** Vite v5
* **JavaScript Libraries:** Alpine.js, SweetAlert2
* **Dependency Management:** Composer (PHP), npm (Node.js)

## ✨ Fitur-fitur Utama
* **Sistem Otentikasi & Role:** Login, Register, Logout dengan dua peran pengguna (Dosen & Mahasiswa).
* **Pembatasan Akses:** Halaman dan fitur spesifik role dilindungi oleh middleware kustom.
* **Manajemen Profil:** Pengguna dapat memperbarui informasi profil umum (nama, email, telepon, alamat) dan data spesifik role (NIM untuk Mahasiswa, NIDN untuk Dosen).
* **Manajemen Mata Kuliah (CRUD Lengkap):** Dosen dapat Menambah, Melihat, Mengedit, dan Menghapus data mata kuliah dengan konfirmasi hapus via SweetAlert2.
* **Manajemen Nilai:**
    * Dosen dapat Menginput dan Memperbarui nilai mahasiswa untuk mata kuliah.
    * Konversi nilai angka ke huruf otomatis (A, B+, B, C+, C, D, E).
    * Mahasiswa dapat Melihat Transkrip Nilai mereka.
* **Sistem Kartu Rencana Studi (KRS) Sederhana:**
    * Mahasiswa dapat Mengisi/Memilih mata kuliah untuk KRS mereka per semester/tahun akademik.
    * Status KRS (Menunggu/Disetujui/Ditolak) ditampilkan kepada mahasiswa.
    * Mata kuliah yang Disetujui tidak dapat diubah oleh mahasiswa di form KRS.
    * KRS yang Ditolak tetap tampil dengan alasan dan dapat dihapus oleh mahasiswa.
* **Validasi KRS oleh Dosen:**
    * Dosen dapat Melihat daftar KRS yang Menunggu persetujuan.
    * Dosen dapat Menyetujui atau Menolak pengajuan KRS (dengan konfirmasi SweetAlert2).
* **Penjadwalan Kuliah Sederhana:**
    * Dosen dapat Mengelola (Tambah, Lihat, Edit, Hapus) jadwal mata kuliah (Hari, Waktu, Ruangan).
    * Mahasiswa dapat Melihat Jadwal Kuliah mereka yang disetujui dalam tampilan per hari.
* **Penyempurnaan UI/UX:** Notifikasi interaktif via SweetAlert2, tombol "Kembali" yang jelas, logo kustom, favicon, dan judul halaman konsisten "SIDOMA".

## 🚀 Usage

### 💻 Run Locally
Pastikan Anda memiliki lingkungan pengembangan berikut terinstal di komputer Anda:
* **XAMPP** (dengan PHP versi 8.2 atau lebih tinggi, dan MySQL)
* **Composer** (Manajer Dependensi PHP)
* **Node.js & npm** (Runtime JavaScript & Manajer Paket)

Langkah-langkah instalasi dan menjalankan proyek:

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git](https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git)
    cd YOUR_REPO_NAME # Masuk ke folder proyek yang baru di-clone
    ```
    *(Ganti `YOUR_USERNAME/YOUR_REPO_NAME` dengan URL repositori GitHub Anda)*

2.  **Konfigurasi Environment:**
    * Buat salinan file `.env.example` menjadi `.env`:
        ```bash
        cp .env.example .env
        # Atau di Windows: copy .env.example .env
        ```
    * Buka file `.env` dan konfigurasikan detail koneksi database MySQL Anda:
        ```dotenv
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=siakad_db # Pastikan database ini sudah dibuat di phpMyAdmin Anda
        DB_USERNAME=root      # Username MySQL XAMPP Anda (biasanya 'root')
        DB_PASSWORD=          # Password MySQL XAMPP Anda (biasanya kosong, tanpa spasi)
        ```
    * **Buat database `siakad_db` secara manual di phpMyAdmin** (`http://localhost/phpmyadmin`). Pastikan layanan Apache dan MySQL di XAMPP Control Panel Anda berjalan.

3.  **Instal Dependensi PHP & Laravel Breeze:**
    ```bash
    composer install
    composer require laravel/breeze --dev
    php artisan breeze:install blade
    ```

4.  **Instal Dependensi JavaScript & Kompilasi Aset:**
    ```bash
    npm install
    npm run dev # PENTING: Biarkan perintah ini berjalan di terminal terpisah selama pengujian!
    ```

5.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

6.  **Jalankan Migrasi Database:**
    * Pastikan Apache dan MySQL di XAMPP Control Panel Anda berjalan (Running).
    * Jalankan migrasi untuk membuat semua tabel database:
        ```bash
        php artisan migrate
        ```
    * *(Jika mengalami error "Column already exists" atau masalah migrasi, Anda bisa mencoba `php artisan migrate:fresh` untuk membersihkan dan membuat ulang semua tabel. Namun, ini akan menghapus semua data yang ada.)*

7.  **Bersihkan Cache Aplikasi:**
    ```bash
    php artisan optimize:clear
    ```

8.  **Jalankan Server Lokal:**
    ```bash
    php artisan serve
    ```
    * Akses aplikasi di browser Anda: `http://127.0.0.1:8000`

### 🔑 Akun Demo
Berikut adalah akun yang bisa digunakan untuk pengujian, yang perlu Anda buat secara manual setelah menjalankan migrasi database:

* **Dosen:**
    * **Email:** `dosen@example.com`
    * **Password:** `password`
    * **Cara Membuat Akun Dosen:**
        Setelah menjalankan `php artisan migrate` (Langkah 6 di atas) dan sebelum menjalankan `php artisan serve`, buka terminal baru (tetap di folder proyek Anda) dan jalankan perintah berikut. Setelah itu, akun dosen siap digunakan.
        ```bash
        php artisan tinker
        App\Models\User::create([
            'name' => 'Dosen Penguji',
            'email' => 'dosen@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);
        exit;
        ```

* **Mahasiswa:**
    * Anda dapat mendaftar akun Mahasiswa baru melalui halaman `/register` di aplikasi (`http://127.0.0.1:8000/register`).

### 🔗 Live Demo
[Tidak ada live demo untuk proyek ini karena kebutuhan hosting backend PHP dan database. Proyek ini dirancang untuk dijalankan secara lokal.]

## 🤝 Contributing Guidelines
1. Fork the project
2. Create a feature branch (`git checkout -b feature/nama-fitur`)
3. Commit your changes (`git commit -m "feat: tambahkan fitur baru"`)
4. Push to the branch (`git push origin feature/nama-fitur`)
5. Open a pull request

## 📫 Contact
I Wayan Yoga Adi Pranata
[Email](mailto:yogaadipranata10@gmail.com)

**Selamat Mengerjakan dan Semoga Berhasil!**
