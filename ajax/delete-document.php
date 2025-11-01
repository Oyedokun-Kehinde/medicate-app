<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    $_SESSION['error'] = 'Unauthorized access.';
    header('Location: ../medical-records.php');
    exit;
}

$doc_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

if ($doc_id <= 0) {
    $_SESSION['error'] = 'Invalid document ID.';
    header('Location: ../medical-records.php');
    exit;
}

try {
    // Verify ownership before deleting
    $check_stmt = $pdo->prepare("SELECT id FROM medical_documents WHERE id = ? AND patient_id = ?");
    $check_stmt->execute([$doc_id, $user_id]);
    
    if (!$check_stmt->fetch()) {
        $_SESSION['error'] = 'Document not found or you do not have permission to delete it.';
        header('Location: ../medical-records.php');
        exit;
    }
    
    // Soft delete (mark as deleted)
    $stmt = $pdo->prepare("UPDATE medical_documents SET is_deleted = TRUE WHERE id = ? AND patient_id = ?");
    $result = $stmt->execute([$doc_id, $user_id]);
    
    if ($result) {
        $_SESSION['success'] = 'Document deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete document. Please try again.';
    }
    
} catch (PDOException $e) {
    error_log("Delete document error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while deleting the document.';
}

header('Location: ../medical-records.php');
exit;
