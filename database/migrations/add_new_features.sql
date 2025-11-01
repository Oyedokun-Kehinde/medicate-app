-- =====================================================
-- Medicate Platform - Feature Enhancement Migration
-- Date: November 1, 2025
-- Description: Adds tables for appointments, medical records,
--              notifications, health tracking, reviews, and prescriptions
-- =====================================================

-- 1. MEDICAL DOCUMENTS TABLE
CREATE TABLE IF NOT EXISTS medical_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    document_type ENUM('lab_result', 'imaging', 'prescription', 'medical_report', 'other') DEFAULT 'other',
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size INT,
    description TEXT,
    uploaded_by INT NOT NULL,
    shared_with_doctor INT DEFAULT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    FOREIGN KEY (shared_with_doctor) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_patient (patient_id),
    INDEX idx_type (document_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. NOTIFICATIONS TABLE
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('appointment', 'consultation', 'blog', 'comment', 'system', 'review', 'prescription') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. DOCTOR REVIEWS TABLE
CREATE TABLE IF NOT EXISTS doctor_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    consultation_id INT,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    doctor_response TEXT,
    response_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE SET NULL,
    UNIQUE KEY unique_review (consultation_id, patient_id),
    INDEX idx_doctor (doctor_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. HEALTH VITALS TABLE
CREATE TABLE IF NOT EXISTS health_vitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    vital_type ENUM('blood_pressure', 'weight', 'glucose', 'temperature', 'heart_rate', 'oxygen_saturation', 'bmi') NOT NULL,
    value VARCHAR(50) NOT NULL,
    systolic INT DEFAULT NULL,
    diastolic INT DEFAULT NULL,
    unit VARCHAR(20) NOT NULL,
    notes TEXT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    recorded_by INT,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_patient_type (patient_id, vital_type),
    INDEX idx_date (recorded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. MEDICATIONS TABLE
CREATE TABLE IF NOT EXISTS medications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    medication_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(100),
    frequency VARCHAR(100),
    start_date DATE NOT NULL,
    end_date DATE,
    prescribed_by INT,
    prescription_id INT,
    notes TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (prescribed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_patient_active (patient_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. PRESCRIPTIONS TABLE
CREATE TABLE IF NOT EXISTS prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    diagnosis TEXT,
    prescription_details TEXT NOT NULL,
    instructions TEXT,
    valid_until DATE,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE SET NULL,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_patient (patient_id),
    INDEX idx_doctor (doctor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. PRESCRIPTION ITEMS TABLE
CREATE TABLE IF NOT EXISTS prescription_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    medication_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(100) NOT NULL,
    frequency VARCHAR(100) NOT NULL,
    duration VARCHAR(100),
    quantity INT,
    instructions TEXT,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE,
    INDEX idx_prescription (prescription_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. APPOINTMENT REMINDERS TABLE
CREATE TABLE IF NOT EXISTS appointment_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT NOT NULL,
    reminder_type ENUM('email', 'sms', 'notification') NOT NULL,
    scheduled_time TIMESTAMP NOT NULL,
    sent BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP NULL,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
    INDEX idx_scheduled (scheduled_time, sent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. CONSULTATION NOTES TABLE (For doctor notes during/after consultation)
CREATE TABLE IF NOT EXISTS consultation_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT NOT NULL,
    doctor_id INT NOT NULL,
    symptoms TEXT,
    diagnosis TEXT,
    treatment_plan TEXT,
    follow_up_instructions TEXT,
    next_appointment_recommendation DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_consultation_note (consultation_id),
    INDEX idx_doctor (doctor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. DOCTOR AVAILABILITY TABLE
CREATE TABLE IF NOT EXISTS doctor_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_doctor_day (doctor_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. MESSAGES TABLE (For in-app messaging)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    attachment_path VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sender (sender_id),
    INDEX idx_receiver (receiver_id),
    INDEX idx_consultation (consultation_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. ACTIVITY LOG TABLE (For tracking user actions)
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ALTER EXISTING TABLES
-- =====================================================

-- Add additional fields to consultations table if they don't exist
ALTER TABLE consultations 
ADD COLUMN IF NOT EXISTS is_virtual BOOLEAN DEFAULT FALSE AFTER status,
ADD COLUMN IF NOT EXISTS meeting_link VARCHAR(255) AFTER is_virtual,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

-- Add rating fields to doctor_profiles if they don't exist
ALTER TABLE doctor_profiles
ADD COLUMN IF NOT EXISTS average_rating DECIMAL(3,2) DEFAULT 0.00 AFTER bio,
ADD COLUMN IF NOT EXISTS total_reviews INT DEFAULT 0 AFTER average_rating,
ADD COLUMN IF NOT EXISTS years_experience INT DEFAULT 0 AFTER total_reviews,
ADD COLUMN IF NOT EXISTS consultation_fee DECIMAL(10,2) DEFAULT 0.00 AFTER years_experience,
ADD COLUMN IF NOT EXISTS languages_spoken VARCHAR(255) AFTER consultation_fee,
ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) AFTER languages_spoken;

-- Add fields to patient_profiles if they don't exist
ALTER TABLE patient_profiles
ADD COLUMN IF NOT EXISTS date_of_birth DATE AFTER full_name,
ADD COLUMN IF NOT EXISTS gender ENUM('male', 'female', 'other') AFTER date_of_birth,
ADD COLUMN IF NOT EXISTS height DECIMAL(5,2) AFTER blood_group,
ADD COLUMN IF NOT EXISTS weight DECIMAL(5,2) AFTER height,
ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) AFTER emergency_contact_phone;

-- =====================================================
-- CREATE VIEWS FOR EASIER QUERIES
-- =====================================================

-- View for doctor statistics
CREATE OR REPLACE VIEW doctor_statistics AS
SELECT 
    u.id as doctor_id,
    dp.full_name,
    dp.specialization,
    dp.average_rating,
    dp.total_reviews,
    COUNT(DISTINCT c.id) as total_consultations,
    COUNT(DISTINCT CASE WHEN c.status = 'completed' THEN c.id END) as completed_consultations,
    COUNT(DISTINCT CASE WHEN c.status = 'pending' THEN c.id END) as pending_consultations,
    COUNT(DISTINCT p.id) as total_prescriptions
FROM users u
JOIN doctor_profiles dp ON u.id = dp.user_id
LEFT JOIN consultations c ON u.id = c.doctor_id
LEFT JOIN prescriptions p ON u.id = p.doctor_id
WHERE u.user_type = 'doctor'
GROUP BY u.id, dp.full_name, dp.specialization, dp.average_rating, dp.total_reviews;

-- View for patient health summary
CREATE OR REPLACE VIEW patient_health_summary AS
SELECT 
    u.id as patient_id,
    pp.full_name,
    pp.blood_group,
    pp.allergies,
    COUNT(DISTINCT c.id) as total_consultations,
    COUNT(DISTINCT md.id) as total_documents,
    COUNT(DISTINCT m.id) as active_medications,
    MAX(c.created_at) as last_consultation_date
FROM users u
JOIN patient_profiles pp ON u.id = pp.user_id
LEFT JOIN consultations c ON u.id = c.patient_id
LEFT JOIN medical_documents md ON u.id = md.patient_id AND md.is_deleted = FALSE
LEFT JOIN medications m ON u.id = m.patient_id AND m.is_active = TRUE
WHERE u.user_type = 'patient'
GROUP BY u.id, pp.full_name, pp.blood_group, pp.allergies;

-- =====================================================
-- INSERT SAMPLE NOTIFICATION TYPES
-- =====================================================

-- This will be used by the application to send standardized notifications
CREATE TABLE IF NOT EXISTS notification_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_key VARCHAR(50) UNIQUE NOT NULL,
    title_template VARCHAR(255) NOT NULL,
    message_template TEXT NOT NULL,
    type ENUM('appointment', 'consultation', 'blog', 'comment', 'system', 'review', 'prescription') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO notification_templates (template_key, title_template, message_template, type) VALUES
('appointment_confirmed', 'Appointment Confirmed', 'Your appointment with {doctor_name} on {date} at {time} has been confirmed.', 'appointment'),
('appointment_reminder', 'Appointment Reminder', 'Reminder: You have an appointment with {doctor_name} tomorrow at {time}.', 'appointment'),
('appointment_cancelled', 'Appointment Cancelled', 'Your appointment with {doctor_name} scheduled for {date} has been cancelled.', 'appointment'),
('consultation_completed', 'Consultation Completed', 'Your consultation with {doctor_name} has been marked as completed.', 'consultation'),
('new_prescription', 'New Prescription', 'Dr. {doctor_name} has created a new prescription for you. View it in your dashboard.', 'prescription'),
('review_request', 'Rate Your Experience', 'How was your consultation with {doctor_name}? Please leave a review.', 'review'),
('new_blog_post', 'New Health Article', '{doctor_name} has published a new article: {blog_title}', 'blog'),
('comment_reply', 'New Comment Reply', '{user_name} replied to your comment on "{blog_title}"', 'comment')
ON DUPLICATE KEY UPDATE 
    title_template = VALUES(title_template),
    message_template = VALUES(message_template);

-- =====================================================
-- COMPLETION MESSAGE
-- =====================================================
SELECT 'Database migration completed successfully! All tables created.' AS Status;
