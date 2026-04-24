# MOTIVA WebGIS - Setup Guide

## Persyaratan
- PHP 7.4+
- MySQL/MariaDB
- Composer

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/cahpunke/motiva.my.id.git
cd motiva
```

### 2. Konfigurasi Database
```bash
# Copy file .env
cp .env.example .env

# Edit .env dengan kredensial database Anda
vim .env
```

### 3. Setup Database
```bash
mysql -u root -p < database.sql
```

### 4. Install Dependencies (Opsional)
```bash
composer install
```

### 5. Konfigurasi Web Server

#### Apache
Pastikan `.htaccess` support aktif dan `mod_rewrite` enabled.

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/motiva;
    
    index index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Set Permissions
```bash
chmod 755 -R motiva
chmod 777 motiva/uploads
chmod 777 motiva/uploads/foto
chmod 777 motiva/uploads/geojson
```

### 7. Login
- URL: `http://localhost/motiva/pages/login.php`
- Username: `admin`
- Password: (dari database.sql)

## Default Credentials
Username: `admin`
Password: (lihat database.sql)

## Security Tips
1. Ubah password default setelah login pertama
2. Jangan share `.env` file
3. Setup HTTPS untuk production
4. Configure firewall untuk melindungi MySQL
5. Regular backup database

## Troubleshooting

### Database Connection Error
- Pastikan MySQL running
- Cek kredensial di `.env`
- Pastikan database sudah dibuat

### Permission Denied
```bash
chown -R www-data:www-data motiva
chmod 755 motiva
chmod 777 motiva/uploads/*
```

### Session Error
Pastikan folder sessions writable:
```bash
chmod 777 /var/lib/php/sessions
```

## Support
Kontak: your-email@example.com
