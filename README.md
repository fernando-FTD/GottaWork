# GottaWork - Coworking Space Reservation System

## 🚀 Tentang GottaWork
GottaWork adalah platform digital modern untuk mengoptimalkan pengelolaan coworking space. Sistem ini menyediakan solusi terpadu untuk reservasi ruang kerja, pembayaran online, manajemen keanggotaan, serta pelaporan dan analisis data. Dengan GottaWork, efisiensi operasional meningkat, transaksi transparan, dan pengalaman pengguna jauh lebih baik melalui fitur pencarian workspace, pemesanan otomatis, pengelolaan jadwal, serta sistem pembayaran terintegrasi.

### Fitur Utama
- **Reservasi Online & Otomatis**: Pelanggan dapat melihat ketersediaan workspace dan melakukan reservasi dengan mudah.
- **Pembayaran Online**: Proses pembayaran reservasi yang aman dan terintegrasi.
- **Manajemen Keanggotaan**: Kelola profil dan reservasi.
- **Pencarian & Filter Canggih**: Staff dapat memfilter reservasi berdasarkan ID, nama, email, workspace, lokasi mall, meja, tanggal, dan waktu.
- **Manajemen Workspace**: Staff dapat mengatur ketersediaan workspace dan mengecek daftar reservasi.
- **Skema Tarif & Finance**: Manager dapat mengatur harga dan memantau data keuangan secara real-time.
- **Dashboard Visual**: denah workspace.
- **UI Modern & Responsif**: Desain berbasis Tailwind CSS, nyaman di desktop maupun mobile.

---

## 🧩 Fungsi-fungsi Perangkat Lunak
- Melakukan Login
- Melakukan Register
- Mengedit Akun
- Melihat Ketersediaan Workspace
- Melakukan Reservasi
- Melakukan Pembayaran Reservasi
- Membatalkan Reservasi
- Mengelola Profil Akun
- Mengatur Ketersediaan Workspace
- Memeriksa Daftar Reservasi
- Mengatur Skema Tarif
- Mengelola Data Keuangan

---

## 🏢 Lingkungan Operasi
- **Server**: Windows 11, PHP, MySQL
- **Prototype**: Figma
- **Frontend**: HTML, CSS, JS, Tailwind CSS
- **Backend**: PHP (Native, OOP), MySQL
- **Client**: Web browser

---

## 🎯 Target Audiens
- Penyewa/mahasiswa yang mencari ruang kerja/belajar fleksibel
- Staff coworking yang mengelola reservasi & fasilitas
- Manager yang mengawasi operasional & keuangan coworking space

---

## 🗄️ Skema Database GottaWork
Terdiri dari lima tabel utama:
- **bookings**: Data reservasi (nama, email, tanggal, waktu, workspace, meja, lokasi)
- **expenses**: Data pengeluaran (operasional, gaji, dll)
- **transactions**: Data pembayaran reservasi (tanggal, deskripsi, kategori, jumlah, status)
- **users**: Data pengguna (customer, staff, manager)
- **workspaces**: Data ruang kerja (nama, lokasi, tipe, kapasitas, fasilitas, harga, status)

### Relasi Tabel (ERD)
- `users` → `bookings` (One to Many): Satu user bisa punya banyak booking
- `bookings` → `workspaces` (Many to One): Banyak booking untuk satu workspace
- `bookings` → `transactions` (One to One): Satu booking satu transaksi pembayaran
- `users` → `transactions` (Indirect via bookings)
- `expenses`: Standalone, hanya dicatat oleh manager

---

## 📁 Struktur Folder
```
GottaWork-main/
├── assets/                # Gambar, logo, ilustrasi
├── backend/               # File database (ibd)
├── customer/              # Halaman & fitur untuk customer
├── includes/              # Header, footer, komponen global
├── manager/               # Halaman & fitur untuk manager
├── staff/                 # Halaman & fitur untuk staff
├── tpweb10/               # Tugas/arsip tambahan
├── db.php                 # Koneksi database
├── database_gottawork.sql # Struktur & seed database
├── index.php              # Landing page
├── login.php              # Login user
├── register.php           # Registrasi user
└── ...
```

---

## 💡 Tips & Catatan Tambahan
- Gunakan drawer filter di halaman staff untuk filter reservasi super cepat!
- Semua data workspace, mall, dan user bisa diatur lewat database.
- UI responsif, cocok untuk resepsionais/staff, admin/manager, dan user/customer.
- Backup database bisa dilakukan via batch file atau native PHP lihat backup.php.

---

## ✨ Kontribusi & Lisensi
Proyek ini memiliki LISENSI dan dikelola oleh Gottawork yang beranggotakan:
### Kelompok 5 - Kelas D - GottaWork
- Carissa Oktavia Sanjaya 2317051005
- Rizqi Ananda Pratama 2317051038
- Fernando Ramadhani 2317051060
- Oryza Surya Hapsari 2317051107

---

> "2025 GottaWork : Reservasi coworking space mudah, cepat, kapan saja dan dimana saja!"
