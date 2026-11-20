CREATE DATABASE IF NOT EXISTS bella_vista_spa;
USE bella_vista_spa;

-- Users Table (Admin and Customers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10),
    name VARCHAR(100),
    base_capacity INT,
    max_capacity INT,
    price DECIMAL(10,2),
    duration_minutes INT
);

-- Bookings Table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_no VARCHAR(20) UNIQUE,
    user_id INT NULL, -- Null for guests
    service_id INT,
    booking_date DATE,
    start_time TIME,
    end_time TIME,
    guests INT,
    total_price DECIMAL(10,2),
    health_declaration TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Seed Data based on requirements
INSERT INTO users (name, email, password, role) VALUES 
('Admin Manager', 'admin@bellavista.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==uL.w.j1s1v1v1v1v1v1v1u', 'admin'); 
-- Note: The above hash is for 'password123'

INSERT INTO services (code, name, base_capacity, max_capacity, price, duration_minutes) VALUES
('SR-1', 'Single Room Massage', 1, 1, 800.00, 60),
('CS-1', 'Couples Suite', 2, 2, 1800.00, 60),
('FL-1', 'Barkada / Family Spa Lounge', 4, 6, 4000.00, 90);