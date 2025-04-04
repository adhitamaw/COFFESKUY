# Daftar Perbaikan Website Coffeskuy

## 1. Struktur Database

### Tabel Ratings
- ✅ Menambahkan kolom `status` pada tabel `ratings` (sudah diimplementasikan)
- Menyesuaikan fillable pada model Review untuk mencakup semua kolom yang dibutuhkan:
  ```php
  protected $fillable = ['komentar', 'rating', 'cafe_id', 'user_id', 'status'];
  ```

## 2. Fitur Rating dan Komentar

### Validasi Input
- Menambahkan validasi untuk mencegah review kosong atau terlalu pendek
- Membatasi panjang komentar (misal: maksimal 500 karakter) 

### Pengalaman Pengguna
- ✅ Membatasi user hanya bisa memberikan satu review per kafe (sudah diimplementasikan)
- ✅ Menambahkan tombol hapus review untuk user yang membuat review (sudah diimplementasikan)
- Menambahkan fitur edit review yang memungkinkan user mengubah review mereka

## 3. Admin Dashboard

### Pengelolaan Review
- Menyempurnakan tampilan halaman admin untuk mengelola review:
  - Menambahkan filter review berdasarkan rating (tinggi ke rendah atau sebaliknya)
  - Menambahkan filter review berdasarkan tanggal
- Memberikan admin kemampuan untuk memoderasi review (menyetujui/menolak)

### Analitik
- Menambahkan dashboard statistik rating untuk admin:
  - Grafik rata-rata rating per bulan
  - Persentase rating (berapa persen rating 5 bintang, 4 bintang, dst.)

## 4. Tampilan UI/UX

### Halaman Detail Kafe
- Memperbaiki tampilan responsif untuk layar mobile
- Menambahkan animasi smooth scroll saat navigasi
- Menambahkan indikator loading saat submit review

### Sistem Rating
- Memperbaiki tampilan sistem rating bintang:
  - Menambahkan animasi hover
  - Menampilkan label teks saat hover di atas bintang (mis. "Sangat Baik", "Baik", "Cukup", dll.)

### Pesan Notifikasi
- Menyeragamkan gaya notifikasi (success, error, info)
- Menambahkan animasi fade untuk notifikasi

## 5. Keamanan

### Validasi
- Menerapkan validasi server-side yang lebih ketat
- Menambahkan sanitasi input untuk mencegah XSS

### Otorisasi
- Memastikan pengguna hanya dapat menghapus review mereka sendiri
- Membatasi akses fitur admin hanya untuk role admin

## 6. Performa

### Optimasi Database
- Menambahkan indeks pada kolom yang sering dicari (user_id, cafe_id)
- Mengoptimalkan query untuk mengambil rating

### Caching
- Menerapkan caching untuk data yang jarang berubah
- Menggunakan lazy loading untuk gambar

## 7. Usabilitas

### Sistem Pencarian
- Memperbaiki fitur pencarian agar lebih relevan
- Menambahkan filter pencarian (lokasi, rating, dll.)

### Paginasi
- Menerapkan pagination untuk daftar review yang panjang
- Infinite scroll untuk review

## 8. Konsistensi Kode

### Standarisasi
- Mengikuti konvensi penamaan yang konsisten (camelCase, snake_case)
- Menstandarisasi penggunaan blade template

### Refaktorisasi
- Memindahkan logika bisnis dari controller ke service/repository
- Menerapkan Laravel Resources untuk API response

## 9. Testing

### Unit Testing
- Menambahkan unit test untuk model dan controller
- Menambahkan test untuk validasi input

### Browser Testing
- Mengimplementasikan testing UI dengan Laravel Dusk

## 10. Dokumentasi

### Kode
- Menambahkan docblock yang menjelaskan fungsi dan parameter
- Menambahkan komentar pada bagian kode yang kompleks

### Penggunaan
- Membuat panduan penggunaan sistem untuk admin
- Membuat dokumentasi API jika diperlukan
