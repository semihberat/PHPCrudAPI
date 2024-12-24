CREATE DATABASE IF NOT EXISTS user_management;

USE user_management;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    age INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, age) VALUES
('Ali Yılmaz', 'ali@gmail.com', 25),
('Ayşe Kaya', 'ayse@gmail.com', 30),
('Mehmet Demir', 'mehmet@gmail.com', 28),
('Fatma Çelik', 'fatma@gmail.com', 32),
('Ahmet Öz', 'ahmet@gmail.com', 29),
('Zeynep Koç', 'zeynep@gmail.com', 27),
('Murat Yıldız', 'murat@gmail.com', 31),
('Elif Şahin', 'elif@gmail.com', 24),
('Hakan Kılıç', 'hakan@gmail.com', 26),
('Sevil Aksoy', 'sevil@gmail.com', 33);
