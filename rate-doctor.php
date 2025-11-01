<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';
require_once 'includes/functions.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$getStartedUrl = getGetStartedUrl();

// Get consultation and doctor IDs
$consultation_id = isset($_GET['consultation']) ? (int)$_GET['consultation'] : 0;
$doctor_id = isset($_GET['doctor']) ? (int)$_GET['doctor'] : 0;

if ($consultation_id <= 0 || $doctor_id <= 0) {
    $_SESSION['error'] = 'Invalid consultation or doctor.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Verify consultation belongs to this patient and is completed
$consultation = getConsultationById($pdo, $consultation_id);
if (!$consultation || $consultation['patient_id'] != $user_id || $consultation['status'] !== 'completed') {
    $_SESSION['error'] = 'You can only review completed consultations.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Check if already reviewed
$check_stmt = $pdo->prepare("SELECT id FROM doctor_reviews WHERE consultation_id = ? AND patient_id = ?");
$check_stmt->execute([$consultation_id, $user_id]);
if ($check_stmt->fetch()) {
    $_SESSION['error'] = 'You have already reviewed this consultation.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Get doctor details
$doctor_stmt = $pdo->prepare("
    SELECT dp.*, u.email 
    FROM doctor_profiles dp 
    JOIN users u ON dp.user_id = u.id 
    WHERE dp.user_id = ?
");
$doctor_stmt->execute([$doctor_id]);
$doctor = $doctor_stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    $_SESSION['error'] = 'Doctor not found.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $review = isset($_POST['review']) ? trim($_POST['review']) : '';
    
    if ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = 'Please select a rating between 1 and 5 stars.';
    } elseif (empty($review)) {
        $_SESSION['error'] = 'Please write a review.';
    } else {
        $result = addDoctorReview($pdo, $doctor_id, $user_id, $consultation_id, $rating, $review);
        
        if ($result) {
            $_SESSION['success'] = 'Thank you for your review! Your feedback helps us improve our services.';
            header('Location: appointments-dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to submit review. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Doctor – Medicate</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        :root {
            --primary-color: #2490eb;
            --primary-dark-color: #14457b;
            --dark-color: #18100f;
            --secondary-color: #666666;
            --grey-color: #f4f6f9;
        }
        
        .rating-container {
            padding: 40px 0;
            min-height: 70vh;
        }
        
        .rating-card {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .doctor-info-section {
            background: var(--grey-color);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .doctor-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .doctor-details {
            flex: 1;
        }
        
        .doctor-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .doctor-specialization {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .consultation-info {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .rating-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--primary-color);
        }
        
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 3rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #ffc107;
            transform: scale(1.1);
        }
        
        .rating-description {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-color);
            min-height: 30px;
        }
        
        .review-textarea {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 2px solid var(--grey-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            transition: border-color 0.3s;
        }
        
        .review-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .character-count {
            text-align: right;
            color: var(--secondary-color);
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .review-guidelines {
            background: #fffbf0;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .review-guidelines h6 {
            color: var(--dark-color);
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .review-guidelines ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .review-guidelines li {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .submit-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            gap: 15px;
        }
        
        .btn-submit {
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background: var(--primary-dark-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 144, 235, 0.3);
        }
        
        .btn-cancel {
            background: var(--grey-color);
            color: var(--dark-color);
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-cancel:hover {
            background: #e0e0e0;
            color: var(--dark-color);
        }
        
        @media (max-width: 768px) {
            .doctor-info-section {
                flex-direction: column;
                text-align: center;
            }
            
            .star-rating label {
                font-size: 2rem;
            }
            
            .submit-section {
                flex-direction: column-reverse;
            }
            
            .btn-submit, .btn-cancel {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Rate Your Experience</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="patient-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="appointments-dashboard.php">Appointments</a></li>
                                <li class="breadcrumb-item active">Rate Doctor</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="rating-container">
        <div class="container">
            <div class="rating-card">
                
                <!-- Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- Doctor Information -->
                <div class="doctor-info-section">
                    <div class="doctor-avatar">
                        <?php echo strtoupper(substr($doctor['full_name'], 0, 1)); ?>
                    </div>
                    <div class="doctor-details">
                        <div class="doctor-name">Dr. <?php echo htmlspecialchars($doctor['full_name']); ?></div>
                        <div class="doctor-specialization">
                            <i class="fas fa-stethoscope"></i>
                            <?php echo htmlspecialchars($doctor['specialization']); ?>
                        </div>
                        <div class="consultation-info">
                            <i class="far fa-calendar"></i>
                            Consultation on <?php echo formatDate($consultation['consultation_date'], 'F j, Y'); ?>
                            at <?php echo formatTime($consultation['consultation_time']); ?>
                        </div>
                    </div>
                </div>

                <!-- Rating Form -->
                <form method="POST" id="rating-form">
                    
                    <!-- Star Rating Section -->
                    <div class="rating-section">
                        <div class="section-title">
                            <i class="fas fa-star"></i>
                            Rate Your Experience
                        </div>
                        
                        <div class="star-rating">
                            <input type="radio" name="rating" id="star5" value="5" required>
                            <label for="star5"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" name="rating" id="star4" value="4">
                            <label for="star4"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" name="rating" id="star3" value="3">
                            <label for="star3"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" name="rating" id="star2" value="2">
                            <label for="star2"><i class="fas fa-star"></i></label>
                            
                            <input type="radio" name="rating" id="star1" value="1">
                            <label for="star1"><i class="fas fa-star"></i></label>
                        </div>
                        
                        <div class="rating-description" id="rating-text">
                            Click on a star to rate
                        </div>
                    </div>

                    <!-- Review Section -->
                    <div class="rating-section">
                        <div class="section-title">
                            <i class="fas fa-comment-medical"></i>
                            Write Your Review
                        </div>
                        
                        <div class="review-guidelines">
                            <h6><i class="fas fa-info-circle"></i> Review Guidelines:</h6>
                            <ul>
                                <li>Share your honest experience with the doctor</li>
                                <li>Be respectful and constructive in your feedback</li>
                                <li>Mention specific aspects: professionalism, communication, treatment effectiveness</li>
                                <li>Your review helps other patients make informed decisions</li>
                            </ul>
                        </div>
                        
                        <textarea 
                            name="review" 
                            id="review" 
                            class="review-textarea" 
                            placeholder="Tell us about your experience with Dr. <?php echo htmlspecialchars($doctor['full_name']); ?>. How was the consultation? Did you feel heard and understood? Was the treatment plan clear?"
                            maxlength="1000"
                            required></textarea>
                        <div class="character-count">
                            <span id="char-count">0</span> / 1000 characters
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <a href="appointments-dashboard.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Review
                        </button>
                    </div>
                    
                </form>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
        // Star rating interaction
        const ratingDescriptions = {
            1: '⭐ Poor - Not satisfied',
            2: '⭐⭐ Fair - Below expectations',
            3: '⭐⭐⭐ Good - Met expectations',
            4: '⭐⭐⭐⭐ Very Good - Exceeded expectations',
            5: '⭐⭐⭐⭐⭐ Excellent - Outstanding experience!'
        };

        document.querySelectorAll('.star-rating input').forEach(input => {
            input.addEventListener('change', function() {
                const rating = this.value;
                document.getElementById('rating-text').textContent = ratingDescriptions[rating];
                document.getElementById('rating-text').style.color = 
                    rating >= 4 ? '#28a745' : rating >= 3 ? '#ffc107' : '#dc3545';
            });
        });

        // Character count
        const textarea = document.getElementById('review');
        const charCount = document.getElementById('char-count');

        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if (this.value.length >= 950) {
                charCount.style.color = '#dc3545';
            } else {
                charCount.style.color = '#666666';
            }
        });

        // Form validation
        document.getElementById('rating-form').addEventListener('submit', function(e) {
            const rating = document.querySelector('.star-rating input:checked');
            const review = textarea.value.trim();

            if (!rating) {
                e.preventDefault();
                alert('Please select a star rating');
                return false;
            }

            if (review.length < 10) {
                e.preventDefault();
                alert('Please write a review with at least 10 characters');
                return false;
            }

            // Confirm submission
            if (!confirm('Submit your review? This cannot be edited later.')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
