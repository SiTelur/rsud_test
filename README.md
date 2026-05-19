<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 25" src="https://github.com/user-attachments/assets/5e898bfe-8201-4912-8d9f-4de8f6b70a5d" /># RSUD API

A simple Laravel API for managing products, orders, and dashboard statistics with Sanctum authentication.

## Fitur Utama

- Autentikasi login menggunakan Laravel Sanctum
- Endpoint produk dengan filter `is_active` dan paginasi
- Buat order dengan pengecekan stok dan transaksi database
- Dashboard summary dengan cache TTL 300 detik
- Hapus cache dashboard melalui API
- Order details dengan validasi kepemilikan user
- Resource response menggunakan `ProductResource` dan `OrderSummaryResource`

## Setup

1. Install dependencies:

```bash
composer install
```

2. Salin file environment:

```bash
cp .env.example .env
```

3. Atur konfigurasi database di `.env`

4. Generate app key:

```bash
php artisan key:generate
```

5. Jalankan migrasi dan seed:

```bash
php artisan migrate:fresh --seed
```

6. Mulai server:

```bash
php artisan serve
```

## Default User

Seeder menambahkan user default yang mudah digunakan:

- Email: `admin@example.com`
- Password: `password`

Gunakan akun ini untuk login di Insomnia.

## API Endpoints

### Login

<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 25" src="https://github.com/user-attachments/assets/c6a47dcf-3e6d-4dc1-870a-11d7be60181f" />
<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 31" src="https://github.com/user-attachments/assets/30a6fd02-ffc6-40d2-ab70-fcd7cea2c2ce" />
<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 31" src="https://github.com/user-attachments/assets/c14473fc-3717-4a21-bee3-3527d09a0a22" />

`POST /api/login`

Request body:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Response:

```json
{
  "message": "Login berhasil.",
  "data": {
    "user": { ... },
    "token": "..."
  }
}
```

### Products

<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 34" src="https://github.com/user-attachments/assets/5f395d67-7200-42ca-a382-6c304b2da563" />

`GET /api/products`

Query params:

- `search` (optional) — search nama produk

### Create Order

<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 31" src="https://github.com/user-attachments/assets/fdcb0e85-397b-45fb-a6f9-176dd5083611" />

`POST /api/order`

Header:

- `Authorization: Bearer <token>`

Body:

```json
{
  "product_id": 1,
  "qty": 2
}
```

### Dashboard

<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 38 37" src="https://github.com/user-attachments/assets/6cc96c05-f891-44a2-a924-cd2605976656" />


`GET /api/dashboard`

Header:

- `Authorization: Bearer <token>`

Response contains:

- `total_revenue_ordercomplete`
- `total_order_today`
- `total_product_active`
- `low_stock_count`
- `top_products`
- `latest_orders`
- `from_cache`

### Clear Dashboard Cache

<img width="1236" height="927" alt="Screenshot 2026-05-19 at 12 40 24" src="https://github.com/user-attachments/assets/bd430834-a52e-4a84-b181-35959263a7b5" />


`DELETE /api/dashboard/cache`

Header:

- `Authorization: Bearer <token>`

### Cek Order Saya

`GET /api/orders/{order}`

Header:

- `Authorization: Bearer <token>`

Response hanya akan berhasil jika order milik user yang login.

## Insomnia

Untuk Insomnia, buat request collection berikut:

- `POST /api/login`
- `GET /api/products`
- `POST /api/order`
- `GET /api/dashboard`
- `DELETE /api/dashboard/cache`
- `GET /api/orders/{order}`

Masukkan header `Accept: application/json` dan `Authorization: Bearer <token>` untuk endpoint yang dilindungi.

![Insomnia Screenshot](docs/insomnia-screenshot.png)

> Letakkan tangkapan layar Insomnia di file `docs/insomnia-screenshot.png` jika ingin menampilkan tampilan request.

## Notes

- Pastikan `auth:sanctum` middleware aktif pada endpoint yang butuh login.
- `dashboard_summary` di-cache selama 300 detik.
- Jika cache harus dihapus, panggil `DELETE /api/dashboard/cache`.

## Run Tests

Jika ingin menambahkan testing di masa depan, gunakan:

```bash
php artisan test
```
