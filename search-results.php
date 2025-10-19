<?php
session_start();
require_once 'config/helpers.php';
$getStartedUrl = getGetStartedUrl();

// Get search query
$searchQuery = isset($_GET['s']) ? trim($_GET['s']) : '';
$results = [];

if (!empty($searchQuery)) {
    // List of all your site pages
    $allPages = [
        ['title' => 'Home', 'url' => 'index.php', 'keywords' => 'home main page'],
        ['title' => 'About Us', 'url' => 'about.php', 'keywords' => 'about medicate mission values'],
        ['title' => 'Services', 'url' => 'services.php', 'keywords' => 'services medical healthcare'],
        ['title' => 'Angioplasty', 'url' => 'services/angioplasty.php', 'keywords' => 'angioplasty heart surgery'],
        ['title' => 'Cardiology', 'url' => 'services/cardiology.php', 'keywords' => 'cardiology heart cardiac'],
        ['title' => 'Dental', 'url' => 'services/dental.php', 'keywords' => 'dental teeth oral care'],
        ['title' => 'Endocrinology', 'url' => 'services/endocrinology.php', 'keywords' => 'endocrinology hormone diabetes'],
        ['title' => 'Eye Care', 'url' => 'services/eye-care.php', 'keywords' => 'eye care vision ophthalmology'],
        ['title' => 'Neurology', 'url' => 'services/neurology.php', 'keywords' => 'neurology brain nervous system'],
        ['title' => 'Orthopaedics', 'url' => 'services/orthopaedics.php', 'keywords' => 'orthopaedics bones joints surgery'],
        ['title' => 'RMI', 'url' => 'services/rmi.php', 'keywords' => 'rmi radiology medical imaging'],
        ['title' => 'Specialists', 'url' => 'specialists.php', 'keywords' => 'specialists doctors team'],
        ['title' => 'Case Studies', 'url' => 'case-study.php', 'keywords' => 'case studies success stories'],
        ['title' => 'Blog', 'url' => 'blog.php', 'keywords' => 'blog articles health tips'],
        ['title' => 'FAQs', 'url' => 'faqs.php', 'keywords' => 'faq frequently asked questions'],
        ['title' => 'Contact Us', 'url' => 'contact.php', 'keywords' => 'contact us get in touch'],
    ];
    
    // Search through pages
    $searchLower = strtolower($searchQuery);
    foreach ($allPages as $page) {
        $titleMatch = strpos(strtolower($page['title']), $searchLower) !== false;
        $keywordMatch = strpos(strtolower($page['keywords']), $searchLower) !== false;
        
        if ($titleMatch || $keywordMatch) {
            $results[] = $page;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results – Medicate</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Search Results</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item active">Search Results</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h3 class="mb-4">
                        Search Results for: <strong><?php echo htmlspecialchars($searchQuery); ?></strong>
                    </h3>

                    <?php if (empty($searchQuery)): ?>
                        <div class="alert alert-warning">
                            <p>Please enter a search term to continue.</p>
                        </div>
                    <?php elseif (empty($results)): ?>
                        <div class="alert alert-info">
                            <p>No results found for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>".</p>
                            <p>Try searching for: cardiology, dental, neurology, specialists, blog, contact, etc.</p>
                        </div>
                    <?php else: ?>
                        <div class="search-results-list">
                            <?php foreach ($results as $result): ?>
                                <div class="search-result-item mb-4 pb-3 border-bottom">
                                    <h5>
                                        <a href="<?php echo $result['url']; ?>" class="text-primary">
                                            <?php echo htmlspecialchars($result['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted">
                                        <small><?php echo htmlspecialchars($result['keywords']); ?></small>
                                    </p>
                                    <a href="<?php echo $result['url']; ?>" class="btn btn-sm btn-outline-primary">
                                        Visit Page <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-muted mt-4">Found <?php echo count($results); ?> result(s)</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>