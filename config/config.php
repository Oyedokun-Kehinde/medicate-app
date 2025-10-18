<?php
// config/config.php

// App settings
define('APP_NAME', 'Medicate');
define('BASE_URL', 'http://localhost/medicate-app/public'); 
define('UPLOAD_DIR', __DIR__ . '/../uploads');

// Timezone
date_default_timezone_set('Africa/Lagos');

// Pagination
define('BLOGS_PER_PAGE', 12);
define('CONSULTATIONS_PER_PAGE', 10);

// ============================================
// PATH DETECTION FOR NAVIGATION & ASSETS
// ============================================

// Detect current directory to set correct paths
$current_script = $_SERVER['PHP_SELF'];
$path_parts = explode('/', trim($current_script, '/'));

// Check if we're in a subfolder (services, blog, etc.)
$is_in_subfolder = false;
$subfolder_name = '';

// If path has more than 3 parts (medicate-app/public/SUBFOLDER/file.php)
if (count($path_parts) > 3) {
    $is_in_subfolder = true;
    $subfolder_name = $path_parts[2]; // Gets 'services', 'blog', etc.
}

// Set base path for links and assets
if ($is_in_subfolder) {
    define('SITE_PATH', '../');      // For pages in subfolders
} else {
    define('SITE_PATH', '');         // For pages in /public root
}

// Asset paths
define('ASSETS', SITE_PATH . 'assets/');
define('CSS', ASSETS . 'css/');
define('JS', ASSETS . 'js/');
define('IMAGES', ASSETS . 'images/');
define('FONTS', ASSETS . 'fonts/');
define('REV', ASSETS . 'rev/');

// Page paths - for navigation links
define('HOME', SITE_PATH . 'index.php');
define('ABOUT', SITE_PATH . 'about.php');
define('CONTACT', SITE_PATH . 'contact.php');
define('BLOG', SITE_PATH . 'blog.php');
define('SERVICES', SITE_PATH . 'services.php');
define('FAQ', SITE_PATH . 'faqs.php');
define('CONSULTATION', SITE_PATH . 'consultation.php');

// Services subfolder
define('SERVICES_DIR', SITE_PATH . 'services/');
define('SERVICE_ANGIOPLASTY', SERVICES_DIR . 'angioplasty.php');
define('SERVICE_CARDIOLOGY', SERVICES_DIR . 'cardiology.php');
define('SERVICE_DENTAL', SERVICES_DIR . 'dental.php');
define('SERVICE_ENDOCRINOLOGY', SERVICES_DIR . 'endocrinology.php');
define('SERVICE_EYECARE', SERVICES_DIR . 'eye-care.php');
define('SERVICE_NEUROLOGY', SERVICES_DIR . 'neurology.php');
define('SERVICE_ORTHOPAEDICS', SERVICES_DIR . 'orthopaedics.php');
define('SERVICE_RMI', SERVICES_DIR . 'rmi.php');

?>