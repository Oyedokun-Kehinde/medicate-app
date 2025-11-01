<?php
/**
 * Document Card Partial
 * Used to display individual medical document information
 */

if (!isset($doc)) {
    return;
}

// Determine file icon based on extension
$extension = strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION));
$icon_class = 'fas fa-file';
$icon_type = '';

if ($extension === 'pdf') {
    $icon_class = 'fas fa-file-pdf';
    $icon_type = 'pdf';
} elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
    $icon_class = 'fas fa-file-image';
    $icon_type = 'image';
} elseif (in_array($extension, ['doc', 'docx'])) {
    $icon_class = 'fas fa-file-word';
    $icon_type = 'word';
}
?>

<div class="document-card">
    <i class="document-icon <?php echo $icon_class; ?> <?php echo $icon_type; ?>"></i>
    
    <div class="document-info">
        <div class="document-title">
            <?php echo htmlspecialchars($doc['file_name']); ?>
        </div>
        <div class="document-meta">
            <span class="doc-type-badge <?php echo $doc['document_type']; ?>">
                <?php echo ucfirst(str_replace('_', ' ', $doc['document_type'])); ?>
            </span>
            <span style="margin: 0 10px;">|</span>
            <i class="far fa-calendar"></i>
            <?php echo formatDate($doc['upload_date'], 'M d, Y'); ?>
            <span style="margin: 0 10px;">|</span>
            <i class="fas fa-database"></i>
            <?php echo formatFileSize($doc['file_size']); ?>
            <?php if (!empty($doc['uploaded_by_name'])): ?>
                <span style="margin: 0 10px;">|</span>
                <i class="fas fa-user"></i>
                Uploaded by: <?php echo htmlspecialchars($doc['uploaded_by_name']); ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($doc['description'])): ?>
            <div class="document-meta" style="margin-top: 5px; font-style: italic;">
                <?php echo htmlspecialchars($doc['description']); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="document-actions">
        <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" 
           target="_blank" 
           class="btn-download"
           title="View/Download">
            <i class="fas fa-download"></i>
        </a>
        
        <?php if ($user_type === 'patient' && $viewing_patient_id === $user_id): ?>
            <button onclick="deleteDocument(<?php echo $doc['id']; ?>)" 
                    class="btn-delete"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        <?php endif; ?>
        
        <?php if ($user_type === 'patient'): ?>
            <button onclick="shareDocument(<?php echo $doc['id']; ?>)" 
                    class="btn-share"
                    title="Share with Doctor">
                <i class="fas fa-share"></i>
            </button>
        <?php endif; ?>
    </div>
</div>
