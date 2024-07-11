CREATE DATABASE IF NOT EXISTS patient_management_system;

USE patient_management_system;

CREATE TABLE patient (
   patientID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   first_name VARCHAR(50) NOT NULL,
   last_name VARCHAR(50) NOT NULL,
   dob DATE NOT NULL,
   address VARCHAR(255) NOT NULL,
   phone_number VARCHAR(15) NOT NULL,
   email VARCHAR(100) NOT NULL,
   password VARCHAR(255) NOT NULL,
   created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   UNIQUE KEY email (email)
);

CREATE TABLE patientRecord (
   patientRecordID INT AUTO_INCREMENT PRIMARY KEY,
   patientID INT NOT NULL,
   appointmentID INT NOT NULL,
   version INT NOT NULL DEFAULT 1,
   medical_history TEXT,
   height DECIMAL(5, 2),
   weight DECIMAL(5, 2),
   blood_pressure VARCHAR(50),
   pulse_rate INT,
   temperature DECIMAL(4, 1),
   respiratory_rate INT,
   current_medications TEXT,
   past_medications TEXT,
   allergies TEXT,
   major_past_illnesses TEXT,
   created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   FOREIGN KEY (patientID) REFERENCES patient(patientID),
   FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID)
);

CREATE TABLE prescription (
   prescriptionID INT AUTO_INCREMENT PRIMARY KEY,
   patientID INT NOT NULL,
   prescription_text TEXT,
   doctors_notes TEXT,
   diagnosis TEXT,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (patientID) REFERENCES patient(patientID)
);

CREATE TABLE appointments (
   appointmentID INT AUTO_INCREMENT PRIMARY KEY,
   patientID INT(11) NOT NULL, -- fk
   date_preference DATE NOT NULL, -- filled by patient
   time_preference TIME NOT NULL, -- filled by patient
   appointment_type VARCHAR(50) NOT NULL,-- filled by patient
   reason TEXT NOT NULL,-- filled by patient
   chief_complaint TEXT,
   duration_severity TEXT,
   general_appearance TEXT,
   visible_signs TEXT,
   approved BOOLEAN DEFAULT false,
   archived BOOLEAN DEFAULT false,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (patientID) REFERENCES patient(patientID)
);

CREATE TABLE staff (
   staffID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   fname VARCHAR(50) DEFAULT NULL,
   lname VARCHAR(50) DEFAULT NULL,
   email VARCHAR(100) DEFAULT NULL,
   password VARCHAR(255) DEFAULT NULL,
   staffType ENUM('secretary', 'doctor', 'admin') DEFAULT NULL,
   created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
