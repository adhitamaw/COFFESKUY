# CoffeSkuy

Cari cafe, lihat menu, pesan kopi, dan beri review.

## Fitur

- **Pengunjung**: cari cafe, lihat detail, menu & peta
- **Pembeli**: favorit, keranjang, bayar via Stripe, tulis/edit review
- **Penjual**: kelola cafe & menu miliknya (aktif setelah disetujui admin)
- **Admin**: semua di atas + kelola user, setujui penjual, hapus review

## Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Isi `STRIPE_KEY` dan `STRIPE_SECRET` di `.env` untuk pembayaran.

## Akun contoh (seeder)

| Role    | Email                  | Password    |
|---------|------------------------|-------------|
| Admin   | admin@coffeskuy.com    | password123 |
| Penjual | penjual@coffeskuy.com  | password123 |
| Pembeli | user@coffeskuy.com     | password123 |

## Tes

```bash
php artisan test
```
