<?php
// config/config.php

// App settings
define('APP_NAME', 'Medicate');
define('BASE_URL', 'http://localhost/medicate-app/public'); // Update if your path differs
define('UPLOAD_DIR', __DIR__ . '/../uploads');

// Timezone
date_default_timezone_set('Africa/Lagos');

// Pagination
define('BLOGS_PER_PAGE', 12);
define('CONSULTATIONS_PER_PAGE', 10);
?>