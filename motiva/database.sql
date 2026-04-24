CREATE DATABASE IF NOT EXISTS webgis_mempawah;
USE webgis_mempawah;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255),
  role ENUM('admin','operator')
);
INSERT INTO users (username,password,role)
VALUES ('admin', '$2y$10$wHhYFZz6examplehash', 'admin');

CREATE TABLE cagar_budaya (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  kategori VARCHAR(50),
  deskripsi TEXT,
  foto VARCHAR(255),
  geom JSON,
  geom_spatial GEOMETRY NULL
);

CREATE TABLE kecamatan (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100), geom JSON);
CREATE TABLE kelurahan (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100), kecamatan_id INT, geom JSON);
CREATE TABLE sungai (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100), geom JSON);
CREATE TABLE kabupaten (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100), geom JSON);
