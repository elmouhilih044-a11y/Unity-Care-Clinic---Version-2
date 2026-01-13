-- Database Creation
CREATE DATABASE IF NOT EXISTS unity_care_v2;
USE unity_care_v2;

-- Users Table (Abstract Parent)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'doctor', 'patient') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Doctors Table (Extension)
CREATE TABLE IF NOT EXISTS doctors (
    id INT PRIMARY KEY,
    specialization VARCHAR(100) NOT NULL,
    department_id INT,
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);

-- Patients Table (Extension)
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY,
    gender ENUM('Male', 'Female', 'Other'),
    date_of_birth DATE,
    address TEXT,
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);

-- Medications Table
CREATE TABLE IF NOT EXISTS medications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    reason TEXT,
    status ENUM('scheduled', 'done', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- Prescriptions Table
CREATE TABLE IF NOT EXISTS prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT, -- Optional link to appointment
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    medication_id INT NOT NULL,
    dosage VARCHAR(100) NOT NULL,
    instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (medication_id) REFERENCES medications(id)
);

-- SEED DATA

-- Admin (Pass: password123)
INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES 
('Super', 'Admin', 'admin@unitycare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567890', 'admin');

-- Doctors (Pass: password123)
INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES 
('Gregory', 'House', 'house@unitycare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567891', 'doctor'),
('Meredith', 'Grey', 'grey@unitycare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567892', 'doctor');

INSERT INTO doctors (id, specialization, department_id) VALUES 
((SELECT id FROM users WHERE email='house@unitycare.com'), 'Diagnostician', 1),
((SELECT id FROM users WHERE email='grey@unitycare.com'), 'General Surgery', 2);

-- Patients (Pass: password123)
INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES 
('John', 'Doe', 'john@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567893', 'patient'),
('Jane', 'Smith', 'jane@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567894', 'patient');

INSERT INTO patients (id, gender, date_of_birth, address) VALUES 
((SELECT id FROM users WHERE email='john@gmail.com'), 'Male', '1980-01-01', '123 Main St'),
((SELECT id FROM users WHERE email='jane@gmail.com'), 'Female', '1990-05-15', '456 Oak Ave');

-- Medications
INSERT INTO medications (name, description, stock_quantity) VALUES 
('Paracetamol', 'Pain reliever', 100),
('Amoxicillin', 'Antibiotic', 50),
('Ibuprofen', 'Anti-inflammatory', 75);

-- Appointments
INSERT INTO appointments (patient_id, doctor_id, date, time, reason, status) VALUES 
((SELECT id FROM users WHERE email='john@gmail.com'), (SELECT id FROM users WHERE email='house@unitycare.com'), CURDATE() + INTERVAL 1 DAY, '10:00:00', 'Persistent headache', 'scheduled');
