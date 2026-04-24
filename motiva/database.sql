CREATE DATABASE IF NOT EXISTS webgis_mempawah;
USE webgis_mempawah;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','operator') DEFAULT 'operator',
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY (username)
);

-- Insert default admin user
-- Password: admin123 (bcrypt hashed)
INSERT INTO users (username, password, role, email) VALUES 
('admin', '$2y$12$R9h/cIPz0gi.URNNX3kh2OPST9/PgBkqquzi.Ss7KIUgO2t0jWMUm', 'admin', 'admin@motiva.local');

-- Cagar Budaya Table
CREATE TABLE IF NOT EXISTS cagar_budaya_mempawah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  destinasi VARCHAR(255) NOT NULL,
  jenis VARCHAR(100),
  kecamata_1 VARCHAR(100),
  kelurahan_ VARCHAR(100),
  sumber_1 VARCHAR(100),
  lat DECIMAL(10, 8),
  lng DECIMAL(11, 8),
  geometry JSON,
  foto VARCHAR(255),
  deskripsi TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY (kecamata_1),
  KEY (lat, lng)
);

-- Kecamatan Table
CREATE TABLE IF NOT EXISTS kecamatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  geom JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kelurahan Table
CREATE TABLE IF NOT EXISTS kelurahan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  kecamatan_id INT,
  geom JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kecamatan_id) REFERENCES kecamatan(id)
);

-- Sungai Table
CREATE TABLE IF NOT EXISTS sungai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  geom JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kabupaten Table
CREATE TABLE IF NOT EXISTS kabupaten (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  geom JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Activity Log Table
CREATE TABLE IF NOT EXISTS activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(255),
  description TEXT,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
