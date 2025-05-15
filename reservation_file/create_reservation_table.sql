-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS reservation_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE reservation_db;

-- 예약 테이블 생성
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(100) NOT NULL,
    name VARCHAR(50) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    course_name VARCHAR(200),
    start_date DATE ,
    end_date DATE ,
    total_people INT ,
    student_people INT NOT NULL DEFAULT 0,
    staff_people INT NOT NULL DEFAULT 0,
    room_type VARCHAR(50) NOT NULL,
    start_time TIME ,
    end_time TIME ,
    room_count INT NOT NULL DEFAULT 1,
    accommodation_start DATE,
    accommodation_end DATE,
    accommodation_type VARCHAR(20),
    accommodation_room_count INT DEFAULT 0,
    meal_start DATE,
    meal_end DATE,
    meal_type VARCHAR(20),
    meal_count INT DEFAULT 0,
    inquiry TEXT,
    created_at DATETIME ,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    INDEX idx_email (email),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 