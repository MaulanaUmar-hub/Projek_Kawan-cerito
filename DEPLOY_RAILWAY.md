# Deploy Kawan Cerito ke Railway

Panduan singkat ini menyiapkan Laravel + Vite + MySQL untuk Railway.

## File Deployment

- `railway.json` mengatur build, pre-deploy command, start command, healthcheck, dan restart policy.
- `.env.railway.example` berisi contoh environment variable production.
- `/up` adalah endpoint healthcheck Railway.

## Environment Variable Railway

Isi variable berikut di service aplikasi Railway:

```env
APP_NAME="Kawan Cerito"
APP_ENV=production
APP_KEY=base64:ISI_DARI_PHP_ARTISAN_KEY_GENERATE_SHOW
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=log
VITE_APP_NAME="${APP_NAME}"
```

Generate app key lokal:

```bash
php artisan key:generate --show
```

Copy hasilnya ke variable `APP_KEY` di Railway.

## Database

Tambahkan service MySQL di Railway, lalu referensikan `MYSQL_URL` ke variable `DB_URL` aplikasi:

```env
DB_URL=${{MySQL.MYSQL_URL}}
```

Migration otomatis dijalankan oleh `preDeployCommand`:

```bash
php artisan migrate --force
```

## Upload Foto Profil

Project ini memakai disk `public` untuk foto profil konseli/konselor. Di Railway, filesystem container bisa hilang saat redeploy.

Pilihan aman:

1. Tambahkan Railway Volume dan mount ke:

```txt
/app/storage/app/public
```

2. Atau pindahkan upload ke S3-compatible storage untuk production.

Setelah deploy, `php artisan storage:link --force` sudah dijalankan otomatis.

## Healthcheck

Railway akan mengecek:

```txt
/healthcheck.txt
```

Respons normal:

```txt
ok
```

Healthcheck memakai file statis di `public/healthcheck.txt` supaya proses deploy tidak gagal hanya karena Laravel belum bisa boot akibat environment variable yang belum lengkap.

Jika halaman aplikasi masih error setelah healthcheck berhasil, cek variable berikut di Railway:

- `APP_KEY` wajib terisi hasil `php artisan key:generate --show`
- `DB_URL` wajib mengarah ke service MySQL Railway
- `APP_DEBUG=false`

## Catatan

- Jangan commit file `.env`.
- Pastikan `APP_DEBUG=false` di production.
- Jika deployment gagal di step `npm run build`, hapus `node_modules` lokal dan commit ulang hanya `package-lock.json`, bukan folder `node_modules`.
