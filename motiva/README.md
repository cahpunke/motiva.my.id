# MOTIVA WebGIS - AdminLTE Documentation

## Struktur Aplikasi

```
motiva/
├── config/
│   ├── .htaccess                 # Protect config files
│   ├── Database.php              # Database connection class
│   ├── Security.php              # Security utilities (CSRF, hashing, validation)
│   ├── Auth.php                  # Authentication & authorization
│   └── database.php              # (Legacy, deprecated)
├── layout/
│   ├── header.php                # AdminLTE header & navbar
│   └── footer.php                # AdminLTE footer & dependencies
├── pages/
│   ├── login.php                 # Login page
│   ├── index.php                 # Dashboard dengan statistik
│   ├── map.php                   # Peta interaktif
│   ├── cagar.php                 # Data table dengan pagination
│   ├── create_cagar.php          # Form tambah cagar
│   ├── edit_cagar.php            # Form edit cagar
│   ├── delete_cagar.php          # Delete handler
│   ├── users.php                 # User management
│   ├── create_user.php           # Form tambah user
│   ├── edit_user.php             # Form edit user
│   ├── settings.php              # System settings
│   ├── logs.php                  # Activity log viewer
│   └── logout.php                # Logout handler
├── api/                          # API endpoints (existing)
├── uploads/
│   ├── foto/                     # Folder for images
│   └── geojson/                  # Folder for geojson files
├── vendor/                       # Composer dependencies
├── .env.example                  # Environment template
├── .env                          # Environment config (git ignored)
├── .gitignore                    # Git ignore rules
├── composer.json                 # PHP dependencies
├── database.sql                  # Database schema
├── SETUP.md                      # Setup guide
└── README.md                     # This file
```

## Fitur-Fitur Utama

### 1. **Authentication & Authorization**
- Login dengan username/password
- Password hashing (bcrypt)
- Session management dengan timeout (1 jam)
- Role-based access control (admin, operator)
- Logout functionality

### 2. **Security Features**
- CSRF token protection pada semua form
- SQL Injection prevention (prepared statements)
- XSS protection (htmlspecialchars)
- File upload validation
- Environment variables untuk credentials
- Session timeout

### 3. **Dashboard**
- Statistik total cagar budaya
- Statistik per kecamatan
- Chart (Chart.js)
- Quick access links
- Responsive design

### 4. **Data Management**
- CRUD operations untuk cagar budaya
- Pagination untuk data besar
- Search & filter
- Soft/hard delete
- Image management

### 5. **Map Visualization**
- Interactive map (Leaflet.js)
- Multiple layers (cagar, kecamatan, sungai)
- Layer control
- Geolocation support
- Popup dengan info & foto

### 6. **User Management** (Admin Only)
- Create/Edit/Delete users
- Role assignment
- Email support
- Password reset

### 7. **Activity Logging**
- Log user activities
- IP tracking
- Timestamp
- Pagination

## API Endpoints

### Public APIs
```
GET /api/cagar.php                    # Get semua cagar data (GeoJSON)
GET /api/kecamatan.php                # Get kecamatan (GeoJSON)
GET /api/sungai.php                   # Get sungai (GeoJSON)
GET /api/kabupaten.php                # Get kabupaten (GeoJSON)
GET /api/statistik.php                # Get statistik
```

### Admin APIs
```
POST /api/proses_import.php           # Import GeoJSON
POST /api/upload_foto.php             # Upload photo
POST /api/cagar-baru.php              # Add new cagar
POST /api/update_cagar.php            # Update cagar
```

## Default Login

```
Username: admin
Password: admin123
```

⚠️ **PENTING**: Ubah password segera setelah login pertama!

## Database Schema

### users table
```sql
┌─────────┬──────────────┬─────────┬──────────────┐
│ id (PK) │ username (U) │ password│ role         │
│ email   │ created_at   │ updated_at             │
└─────────┴──────────────┴─────────┴──────────────┘
```

### cagar_budaya_mempawah table
```sql
┌──────────────┬──────────────┬──────────┬──────────┐
│ id (PK)      │ destinasi    │ jenis    │ kecamata_1
│ kelurahan_   │ sumber_1     │ lat      │ lng
│ geometry     │ foto         │ deskripsi│ timestamps
└──────────────┴──────────────┴──────────┴──────────┘
```

### activity_logs table
```sql
┌──────────┬─────────┬────────┬─────────────┬──────────┐
│ id (PK)  │ user_id │ action │ description │ ip_addr
│ created_at                                        │
└──────────┴─────────┴────────┴─────────────┴──────────┘
```

## Customization Guide

### Change Sidebar Logo
```php
// layout/header.php (line 84)
<img src="path/to/your/logo.png" alt="MOTIVA Logo">
```

### Add New Menu Item
```php
// layout/header.php (line 140+)
<li class="nav-item">
    <a href="yourpage.php" class="nav-link">
        <i class="nav-icon fas fa-icon"></i>
        <p>Your Menu</p>
    </a>
</li>
```

### Change Primary Color
```html
<!-- Ubah di layout/header.php CSS section -->
--primary-color: #your-color;
```

### Add Form Field
```php
<div class="form-group">
    <label for="fieldname">Label</label>
    <input type="text" class="form-control" id="fieldname" name="fieldname">
</div>
```

## Troubleshooting

### Login Failed
- Pastikan database sudah di-setup
- Cek username/password di database
- Lihat error di browser console

### Session Expired
- Default timeout: 1 jam
- Ubah di `config/Auth.php` line 6

### Permission Denied
```bash
chmod 777 uploads/foto
chmod 777 uploads/geojson
```

### CSRF Token Error
- Pastikan session aktif
- Clear browser cache
- Jangan reload form sebelum submit

## Performance Tips

1. **Database Indexing**
```sql
CREATE INDEX idx_kecamatan ON cagar_budaya_mempawah(kecamata_1);
CREATE INDEX idx_location ON cagar_budaya_mempawah(lat, lng);
```

2. **Enable Caching**
```php
// Add Redis/Memcached untuk API responses
```

3. **Optimize Images**
```bash
Optimize uploaded images ke ukuran lebih kecil
```

4. **Database Query Optimization**
- Gunakan EXPLAIN untuk analyze queries
- Add proper indexes
- Avoid N+1 queries

## Deployment Checklist

- [ ] Copy .env.example ke .env
- [ ] Update database credentials di .env
- [ ] Run database.sql
- [ ] Set correct file permissions
- [ ] Enable HTTPS
- [ ] Change default passwords
- [ ] Configure email (untuk notifikasi)
- [ ] Setup database backup
- [ ] Configure error logging
- [ ] Test login & basic functionality
- [ ] Test maps loading
- [ ] Test file uploads
- [ ] Check performance

## Support & Contact

Untuk support atau pertanyaan, silakan hubungi administrator.

---

**Last Updated**: 2026-04-24
**Version**: 1.0.0
**License**: MIT
