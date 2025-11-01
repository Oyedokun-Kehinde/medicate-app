<?php
/**
 * Medicate Platform - Core Helper Functions
 * Centralized functions for notifications, appointments, and other features
 */

// =====================================================
// NOTIFICATION FUNCTIONS
// =====================================================

/**
 * Create a notification for a user
 */
function createNotification($pdo, $user_id, $type, $title, $message, $link = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $type, $title, $message, $link]);
    } catch (PDOException $e) {
        error_log("Notification creation error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get unread notification count for a user
 */
function getUnreadNotificationCount($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Get notification count error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get recent notifications for a user
 */
function getRecentNotifications($pdo, $user_id, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get notifications error: " . $e->getMessage());
        return [];
    }
}

/**
 * Mark notification as read
 */
function markNotificationAsRead($pdo, $notification_id, $user_id) {
    try {
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_read = TRUE 
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notification_id, $user_id]);
    } catch (PDOException $e) {
        error_log("Mark notification read error: " . $e->getMessage());
        return false;
    }
}

/**
 * Mark all notifications as read for a user
 */
function markAllNotificationsAsRead($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    } catch (PDOException $e) {
        error_log("Mark all notifications read error: " . $e->getMessage());
        return false;
    }
}

// =====================================================
// APPOINTMENT FUNCTIONS
// =====================================================

/**
 * Get appointments for a user (patient or doctor)
 */
function getUserAppointments($pdo, $user_id, $user_type, $status = null) {
    try {
        $sql = "
            SELECT 
                c.*,
                s.name as service_name,
                pp.full_name as patient_name,
                pp.phone as patient_phone,
                dp.full_name as doctor_name,
                dp.specialization as doctor_specialization,
                dp.phone as doctor_phone
            FROM consultations c
            LEFT JOIN services s ON c.service_id = s.id
            LEFT JOIN patient_profiles pp ON c.patient_id = pp.user_id
            LEFT JOIN doctor_profiles dp ON c.doctor_id = dp.user_id
            WHERE " . ($user_type === 'patient' ? 'c.patient_id' : 'c.doctor_id') . " = ?
        ";
        
        if ($status) {
            $sql .= " AND c.status = ?";
        }
        
        $sql .= " ORDER BY c.consultation_date DESC, c.consultation_time DESC";
        
        $stmt = $pdo->prepare($sql);
        
        if ($status) {
            $stmt->execute([$user_id, $status]);
        } else {
            $stmt->execute([$user_id]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get appointments error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get upcoming appointments
 */
function getUpcomingAppointments($pdo, $user_id, $user_type, $limit = 5) {
    try {
        $sql = "
            SELECT 
                c.*,
                s.name as service_name,
                pp.full_name as patient_name,
                dp.full_name as doctor_name,
                dp.specialization as doctor_specialization
            FROM consultations c
            LEFT JOIN services s ON c.service_id = s.id
            LEFT JOIN patient_profiles pp ON c.patient_id = pp.user_id
            LEFT JOIN doctor_profiles dp ON c.doctor_id = dp.user_id
            WHERE " . ($user_type === 'patient' ? 'c.patient_id' : 'c.doctor_id') . " = ?
            AND c.consultation_date >= CURDATE()
            AND c.status IN ('pending', 'confirmed')
            ORDER BY c.consultation_date ASC, c.consultation_time ASC
            LIMIT ?
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get upcoming appointments error: " . $e->getMessage());
        return [];
    }
}

/**
 * Update consultation status
 */
function updateConsultationStatus($pdo, $consultation_id, $status, $user_id, $user_type) {
    try {
        // Verify user has permission to update this consultation
        $check_stmt = $pdo->prepare("
            SELECT id FROM consultations 
            WHERE id = ? AND " . ($user_type === 'patient' ? 'patient_id' : 'doctor_id') . " = ?
        ");
        $check_stmt->execute([$consultation_id, $user_id]);
        
        if (!$check_stmt->fetch()) {
            return false;
        }
        
        // Update status
        $stmt = $pdo->prepare("UPDATE consultations SET status = ? WHERE id = ?");
        $result = $stmt->execute([$status, $consultation_id]);
        
        // Create notification for the other party
        if ($result) {
            $consultation = getConsultationById($pdo, $consultation_id);
            if ($consultation) {
                $notify_user_id = ($user_type === 'patient') ? $consultation['doctor_id'] : $consultation['patient_id'];
                $message = "Consultation status updated to: " . ucfirst($status);
                createNotification($pdo, $notify_user_id, 'consultation', 'Consultation Update', $message, 'appointments-dashboard.php');
            }
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Update consultation status error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get consultation by ID
 */
function getConsultationById($pdo, $consultation_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                c.*,
                s.name as service_name,
                pp.full_name as patient_name,
                pp.phone as patient_phone,
                pp.allergies,
                pp.blood_group,
                pp.medical_history,
                dp.full_name as doctor_name,
                dp.specialization as doctor_specialization,
                dp.phone as doctor_phone
            FROM consultations c
            LEFT JOIN services s ON c.service_id = s.id
            LEFT JOIN patient_profiles pp ON c.patient_id = pp.user_id
            LEFT JOIN doctor_profiles dp ON c.doctor_id = dp.user_id
            WHERE c.id = ?
        ");
        $stmt->execute([$consultation_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get consultation error: " . $e->getMessage());
        return null;
    }
}

// =====================================================
// MEDICAL RECORDS FUNCTIONS
// =====================================================

/**
 * Upload medical document
 */
function uploadMedicalDocument($pdo, $patient_id, $uploaded_by, $file_data, $document_type, $description = '') {
    try {
        // Create upload directory if it doesn't exist
        $upload_dir = __DIR__ . '/../uploads/medical_documents/' . $patient_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($file_data['name'], PATHINFO_EXTENSION);
        $file_name = uniqid('doc_') . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        // Move uploaded file
        if (move_uploaded_file($file_data['tmp_name'], $file_path)) {
            // Save to database
            $relative_path = 'uploads/medical_documents/' . $patient_id . '/' . $file_name;
            $stmt = $pdo->prepare("
                INSERT INTO medical_documents 
                (patient_id, document_type, file_path, file_name, file_size, description, uploaded_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $patient_id,
                $document_type,
                $relative_path,
                $file_data['name'],
                $file_data['size'],
                $description,
                $uploaded_by
            ]);
            
            if ($result) {
                // Create notification
                createNotification($pdo, $patient_id, 'system', 'New Document Uploaded', 
                    'A new medical document has been uploaded to your records.', 'medical-records.php');
                return $pdo->lastInsertId();
            }
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Upload document error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get medical documents for a patient
 */
function getPatientDocuments($pdo, $patient_id, $document_type = null) {
    try {
        $sql = "
            SELECT 
                md.*,
                u.email as uploaded_by_email,
                CASE 
                    WHEN u.user_type = 'patient' THEN pp.full_name
                    WHEN u.user_type = 'doctor' THEN dp.full_name
                    ELSE 'System'
                END as uploaded_by_name
            FROM medical_documents md
            LEFT JOIN users u ON md.uploaded_by = u.id
            LEFT JOIN patient_profiles pp ON u.id = pp.user_id AND u.user_type = 'patient'
            LEFT JOIN doctor_profiles dp ON u.id = dp.user_id AND u.user_type = 'doctor'
            WHERE md.patient_id = ? AND md.is_deleted = FALSE
        ";
        
        if ($document_type) {
            $sql .= " AND md.document_type = ?";
        }
        
        $sql .= " ORDER BY md.upload_date DESC";
        
        $stmt = $pdo->prepare($sql);
        
        if ($document_type) {
            $stmt->execute([$patient_id, $document_type]);
        } else {
            $stmt->execute([$patient_id]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get documents error: " . $e->getMessage());
        return [];
    }
}

// =====================================================
// HEALTH VITALS FUNCTIONS
// =====================================================

/**
 * Add health vital
 */
function addHealthVital($pdo, $patient_id, $vital_type, $value, $unit, $systolic = null, $diastolic = null, $notes = '', $recorded_by = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO health_vitals 
            (patient_id, vital_type, value, systolic, diastolic, unit, notes, recorded_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $patient_id,
            $vital_type,
            $value,
            $systolic,
            $diastolic,
            $unit,
            $notes,
            $recorded_by
        ]);
    } catch (PDOException $e) {
        error_log("Add vital error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get health vitals for a patient
 */
function getHealthVitals($pdo, $patient_id, $vital_type = null, $limit = 30) {
    try {
        $sql = "SELECT * FROM health_vitals WHERE patient_id = ?";
        
        if ($vital_type) {
            $sql .= " AND vital_type = ?";
        }
        
        $sql .= " ORDER BY recorded_at DESC LIMIT ?";
        
        $stmt = $pdo->prepare($sql);
        
        if ($vital_type) {
            $stmt->execute([$patient_id, $vital_type, $limit]);
        } else {
            $stmt->execute([$patient_id, $limit]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get vitals error: " . $e->getMessage());
        return [];
    }
}

// =====================================================
// RATING & REVIEW FUNCTIONS
// =====================================================

/**
 * Add doctor review
 */
function addDoctorReview($pdo, $doctor_id, $patient_id, $consultation_id, $rating, $review) {
    try {
        $pdo->beginTransaction();
        
        // Insert review
        $stmt = $pdo->prepare("
            INSERT INTO doctor_reviews (doctor_id, patient_id, consultation_id, rating, review)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$doctor_id, $patient_id, $consultation_id, $rating, $review]);
        
        // Update doctor's average rating
        updateDoctorRating($pdo, $doctor_id);
        
        // Create notification for doctor
        createNotification($pdo, $doctor_id, 'review', 'New Review Received', 
            "You received a new $rating-star review.", 'reviews.php');
        
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Add review error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update doctor's average rating
 */
function updateDoctorRating($pdo, $doctor_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
            FROM doctor_reviews 
            WHERE doctor_id = ? AND is_visible = TRUE
        ");
        $stmt->execute([$doctor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $update_stmt = $pdo->prepare("
            UPDATE doctor_profiles 
            SET average_rating = ?, total_reviews = ? 
            WHERE user_id = ?
        ");
        
        return $update_stmt->execute([
            round($result['avg_rating'], 2),
            $result['total_reviews'],
            $doctor_id
        ]);
    } catch (PDOException $e) {
        error_log("Update rating error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get doctor reviews
 */
function getDoctorReviews($pdo, $doctor_id, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                dr.*,
                pp.full_name as patient_name
            FROM doctor_reviews dr
            JOIN patient_profiles pp ON dr.patient_id = pp.user_id
            WHERE dr.doctor_id = ? AND dr.is_visible = TRUE
            ORDER BY dr.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$doctor_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get reviews error: " . $e->getMessage());
        return [];
    }
}

// =====================================================
// UTILITY FUNCTIONS
// =====================================================

/**
 * Format date for display
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Format time for display
 */
function formatTime($time) {
    return date('g:i A', strtotime($time));
}

/**
 * Get status badge class
 */
function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

/**
 * Calculate age from date of birth
 */
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    return $birthDate->diff($today)->y;
}

/**
 * Sanitize file name
 */
function sanitizeFileName($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    return $filename;
}

/**
 * Check if file type is allowed
 */
function isAllowedFileType($filename) {
    $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $allowed_extensions);
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < 3) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
