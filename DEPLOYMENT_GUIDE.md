# Panduan Update/Deploy ke Server Proxmox CT

## Ringkasan Perubahan
- **CSS Framework**: Bootstrap → Tailwind CSS
- **Build Tools**: Vite untuk compile Tailwind + JS
- **Files yang berubah** (perlu di-upload):
  - `resources/css/admin.css` → Tailwind rewrite
  - `resources/css/frontend.css` → Tailwind rewrite
  - `resources/views/` → Semua Blade templates (Tailwind)
  - `tailwind.config.js` → Konfigurasi Tailwind
  - `vite.config.js` → Konfigurasi Vite
  - `package.json` → Dependencies baru (Tailwind dkk)
  - `composer.json` → (mungkin ada perubahan)
  - `public/frontend/assets/css/style.css` → Bootstrap CSS (tidak dipakai lagi, bisa dihapus)
  - `public/frontend/assets/js/main.js` → (mungkin ada perubahan)

---

## Metode 1: Deploy via Git Pull (Rekomendasi)

### Step 1: SSH ke Server Proxmox CT
```bash
ssh user@ip-proxmox-ct
```

### Step 2: Masuk ke direktori project
```bash
cd /path/to/dewiga-website
```

### Step 3: Backup dulu (opsional tapi disarankan)
```bash
# Backup folder public/build dan vendor jika perlu
cp -r public/build public/build-backup-bootstrap
```

### Step 4: Git pull kode terbaru
```bash
git pull origin main
```

### Step 5: Install/update PHP dependencies
```bash
composer install --no-dev --optimize-autoloader
```
> Jika ada error memory, jalankan: `COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader`

### Step 6: Install/update Node.js dependencies + Build
```bash
# Install node_modules (jika belum ada atau ada perubahan)
npm ci --production

# Build Tailwind + JS dengan Vite
npm run build
```

### Step 7: Set environment (jika file .env belum ada)
```bash
cp .env.example .env
php artisan key:generate
```
> Sesuaikan `.env` dengan konfigurasi database dan APP_URL server

### Step 8: Clear cache
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 9: Jalankan migration (jika ada migration baru)
```bash
php artisan migrate
```

### Step 10: Restart PHP-FPM (jika ada)
```bash
sudo systemctl restart php8.2-fpm
# atau
sudo supervisorctl restart all
```

---

## Metode 2: Deploy Manual via SCP/FileZilla

### Step 1: Build di lokal dulu
```bash
# Di komputer lokal, jalankan:
npm run build
```

### Step 2: Compress project (kecuali folder yang tidak perlu)
```bash
# Di komputer lokal, buat archive:
tar -czf dewiga-update.tar.gz \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='storage' \
  --exclude='.env' \
  .
```

### Step 3: Upload ke server
```bash
# Via SCP dari komputer lokal:
scp dewiga-update.tar.gz user@ip-proxmox-ct:/path/to/dewiga-website/
```

Atau upload via FileZilla / WinSCP.

### Step 4: Extract di server
```bash
ssh user@ip-proxmox-ct
cd /path/to/dewiga-website
tar -xzf dewiga-update.tar.gz
```

### Step 5: Jalankan di server
```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate
```

### Step 6: Set permission
```bash
# Pastikan storage & bootstrap/cache writable:
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Catatan Penting

### 1. Vite Build Output
Setelah `npm run build`, file CSS/JS akan ada di:
```
public/build/assets/admin-xxxxx.css
public/build/assets/frontend-xxxxx.css
public/build/assets/app-xxxxx.js
public/build/manifest.json
```
> File `public/frontend/assets/css/style.css` (Bootstrap) SUDAH TIDAK DIPAKAI dan bisa dihapus.

### 2. Pastikan Server Punya Node.js + NPM
```bash
node -v   # Minimal v16
npm -v
```
Jika belum ada:
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 3. Vite build membutuhkan memory
Jika build gagal karena Out of Memory:
```bash
NODE_OPTIONS="--max-old-space-size=512" npm run build
```

### 4. Jika ada error 404 CSS/JS setelah deploy
- Hapus cache browser (CTRL+F5 / CMD+Shift+R)
- Atau buka di tab incognito
- Pastikan `APP_URL` di `.env` sudah benar

### 5. Rollback jika terjadi masalah
```bash
git stash
# atau
git checkout <commit-sebelum-update>
npm run build
php artisan optimize:clear
```

---

## Troubleshooting Umum

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `Class "Tailwind" not found` | Tailwind belum terinstall | `npm install -D tailwindcss` |
| `vite manifest not found` | Belum build | `npm run build` |
| Halaman putih/blank | Error PHP | Cek `storage/logs/laravel.log` |
| CSS tidak ke-load | Cache browser | Hard refresh (CTRL+F5) |
| 404 assets | `APP_URL` salah | Update `.env` `APP_URL` |

---

## Checklist Deploy
- [ ] Git pull / upload kode terbaru
- [ ] `composer install --no-dev`
- [ ] `npm ci --production` (hanya jika perlu)
- [ ] `npm run build`
- [ ] `php artisan optimize:clear`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan migrate` (jika ada migration baru)
- [ ] Set permission `storage/` & `bootstrap/cache/`
- [ ] Cek website di browser (hard refresh)