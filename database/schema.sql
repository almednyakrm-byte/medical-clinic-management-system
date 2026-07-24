CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
);

CREATE TABLE patients (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (patient_id) REFERENCES patients(id),
  FOREIGN KEY (doctor_id) REFERENCES doctors(id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_doctor_id (doctor_id)
);

CREATE TABLE doctors (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  specialty VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
);

CREATE TABLE medical_records (
  id INT AUTO_INCREMENT,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  record_date DATE NOT NULL,
  diagnosis TEXT NOT NULL,
  treatment TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (patient_id) REFERENCES patients(id),
  FOREIGN KEY (doctor_id) REFERENCES doctors(id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_doctor_id (doctor_id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
('user', 'user@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'user'),
('guest', 'guest@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'guest');

INSERT INTO patients (name, email, phone, address) VALUES
('John Doe', 'john@example.com', '1234567890', '123 Main St'),
('Jane Doe', 'jane@example.com', '9876543210', '456 Elm St'),
('Bob Smith', 'bob@example.com', '5551234567', '789 Oak St');

INSERT INTO doctors (name, email, phone, specialty) VALUES
('Dr. John Smith', 'john.smith@example.com', '1234567890', 'Cardiology'),
('Dr. Jane Doe', 'jane.doe@example.com', '9876543210', 'Neurology'),
('Dr. Bob Johnson', 'bob.johnson@example.com', '5551234567', 'Oncology');

INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES
(1, 1, '2024-09-16', '10:00:00'),
(2, 2, '2024-09-17', '11:00:00'),
(3, 3, '2024-09-18', '12:00:00');

INSERT INTO medical_records (patient_id, doctor_id, record_date, diagnosis, treatment) VALUES
(1, 1, '2024-09-16', 'Hypertension', 'Medication and lifestyle changes'),
(2, 2, '2024-09-17', 'Diabetes', 'Insulin therapy and diet changes'),
(3, 3, '2024-09-18', 'Cancer', 'Chemotherapy and radiation therapy');