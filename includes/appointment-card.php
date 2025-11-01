<?php
/**
 * Appointment Card Partial
 * Used to display individual appointment information
 */

// Ensure $appointment variable is set
if (!isset($appointment)) {
    return;
}

$status_class = $appointment['status'];
$is_patient = ($user_type === 'patient');
$other_party_name = $is_patient ? ($appointment['doctor_name'] ?? 'Not Assigned') : $appointment['patient_name'];
$other_party_role = $is_patient ? ($appointment['doctor_specialization'] ?? 'Doctor') : 'Patient';
?>

<div class="appointment-card <?php echo $status_class; ?>">
    <div class="appointment-header">
        <div class="appointment-date">
            <i class="far fa-calendar"></i>
            <?php echo formatDate($appointment['consultation_date'], 'l, F j, Y'); ?>
            <span style="margin-left: 15px;">
                <i class="far fa-clock"></i>
                <?php echo formatTime($appointment['consultation_time']); ?>
            </span>
        </div>
        <span class="status-badge <?php echo $status_class; ?>">
            <?php echo ucfirst($appointment['status']); ?>
        </span>
    </div>

    <div class="appointment-details">
        <div class="detail-item">
            <i class="fas fa-user-md"></i>
            <strong><?php echo $is_patient ? 'Doctor' : 'Patient'; ?>:</strong>
            <span style="margin-left: 8px;"><?php echo htmlspecialchars($other_party_name); ?></span>
            <?php if ($is_patient && !empty($other_party_role)): ?>
                <span style="margin-left: 5px; color: #999;">
                    (<?php echo htmlspecialchars($other_party_role); ?>)
                </span>
            <?php endif; ?>
        </div>

        <div class="detail-item">
            <i class="fas fa-stethoscope"></i>
            <strong>Service:</strong>
            <span style="margin-left: 8px;"><?php echo htmlspecialchars($appointment['service_name']); ?></span>
        </div>

        <?php if (!empty($appointment['notes'])): ?>
            <div class="detail-item">
                <i class="fas fa-notes-medical"></i>
                <strong>Notes:</strong>
                <span style="margin-left: 8px;"><?php echo htmlspecialchars(substr($appointment['notes'], 0, 100)); ?><?php echo strlen($appointment['notes']) > 100 ? '...' : ''; ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$is_patient && !empty($appointment['patient_phone'])): ?>
            <div class="detail-item">
                <i class="fas fa-phone"></i>
                <strong>Contact:</strong>
                <span style="margin-left: 8px;"><?php echo htmlspecialchars($appointment['patient_phone']); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($is_patient && !empty($appointment['patient_email'])): ?>
            <div class="detail-item">
                <i class="fas fa-envelope"></i>
                <strong>Email:</strong>
                <span style="margin-left: 8px;"><?php echo htmlspecialchars($appointment['patient_email']); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="appointment-actions">
        <!-- View Details -->
        <a href="appointment-details.php?id=<?php echo $appointment['id']; ?>" class="btn-action btn-view">
            <i class="fas fa-eye"></i> View Details
        </a>

        <?php if ($user_type === 'doctor'): ?>
            <!-- Doctor Actions -->
            <?php if ($appointment['status'] === 'pending'): ?>
                <button onclick="confirmAction(<?php echo $appointment['id']; ?>, 'confirm', 'Confirm this appointment?')" 
                        class="btn-action btn-confirm">
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button onclick="confirmAction(<?php echo $appointment['id']; ?>, 'cancel', 'Cancel this appointment?')" 
                        class="btn-action btn-cancel">
                    <i class="fas fa-times"></i> Decline
                </button>
            <?php endif; ?>

            <?php if ($appointment['status'] === 'confirmed'): ?>
                <button onclick="confirmAction(<?php echo $appointment['id']; ?>, 'complete', 'Mark as completed?')" 
                        class="btn-action btn-complete">
                    <i class="fas fa-check-double"></i> Mark Complete
                </button>
                <a href="add-consultation-notes.php?id=<?php echo $appointment['id']; ?>" class="btn-action btn-confirm">
                    <i class="fas fa-notes-medical"></i> Add Notes
                </a>
            <?php endif; ?>

            <?php if ($appointment['status'] === 'completed'): ?>
                <a href="view-consultation-notes.php?id=<?php echo $appointment['id']; ?>" class="btn-action btn-view">
                    <i class="fas fa-file-medical"></i> View Notes
                </a>
                <a href="create-prescription.php?consultation=<?php echo $appointment['id']; ?>" class="btn-action btn-complete">
                    <i class="fas fa-prescription"></i> Create Prescription
                </a>
            <?php endif; ?>

        <?php else: ?>
            <!-- Patient Actions -->
            <?php if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed'): ?>
                <button onclick="confirmAction(<?php echo $appointment['id']; ?>, 'cancel', 'Are you sure you want to cancel this appointment?')" 
                        class="btn-action btn-cancel">
                    <i class="fas fa-times"></i> Cancel Appointment
                </button>
            <?php endif; ?>

            <?php if ($appointment['status'] === 'completed'): ?>
                <?php
                // Check if review already exists
                $review_check = $pdo->prepare("SELECT id FROM doctor_reviews WHERE consultation_id = ?");
                $review_check->execute([$appointment['id']]);
                $has_review = $review_check->fetch();
                ?>
                <?php if (!$has_review): ?>
                    <a href="rate-doctor.php?consultation=<?php echo $appointment['id']; ?>&doctor=<?php echo $appointment['doctor_id']; ?>" 
                       class="btn-action btn-confirm">
                        <i class="fas fa-star"></i> Rate Doctor
                    </a>
                <?php else: ?>
                    <span class="btn-action" style="background: #6c757d; cursor: default;">
                        <i class="fas fa-check"></i> Reviewed
                    </span>
                <?php endif; ?>
                <a href="view-prescription.php?consultation=<?php echo $appointment['id']; ?>" class="btn-action btn-view">
                    <i class="fas fa-prescription"></i> View Prescription
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
