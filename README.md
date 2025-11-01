# 🏥 Medicate - Healthcare Management Platform

A comprehensive, modern healthcare management system built with PHP, MySQL, and Bootstrap. Medicate connects patients with healthcare providers, offering appointment scheduling, medical records management, health tracking, and real-time notifications.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---

## 📋 Table of Contents

- [Features](#-features)
- [Screenshots](#-screenshots)
- [Tech Stack](#-tech-stack)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## ✨ Features

### For Patients
- 🔐 **Secure User Registration & Authentication**
- 📅 **Appointment Booking & Management**
- 📊 **Personal Health Dashboard** - Track vitals, BMI, medications
- 📁 **Medical Records Management** - Upload and organize documents
- 🔔 **Real-time Notifications** - Appointment reminders and updates
- ⭐ **Doctor Rating & Reviews**
- 💊 **Prescription Tracking**
- 📱 **Responsive Mobile Design**

### For Doctors
- 👨‍⚕️ **Professional Profile Management**
- 📆 **Appointment Schedule Dashboard**
- 👥 **Patient Records Access**
- 📝 **Consultation Notes**
- 💊 **Digital Prescription Management**
- 📊 **Performance Analytics**
- ✍️ **Blog Publishing System**

### For Administrators
- 📊 **System Analytics Dashboard**
- 👥 **User Management**
- 🏥 **Service Management**
- 📧 **Email Notifications**
- 🔒 **Access Control**

### General Features
- 🎨 **Modern, Professional UI/UX**
- 📱 **Fully Responsive Design**
- 🔍 **Advanced Search & Filters**
- 🌐 **Multi-language Ready**
- 🔐 **Secure Authentication**
- 📧 **Email Integration**
- 🗄️ **Database Migration Scripts**

---

## 📸 Screenshots

### Homepage
Modern, welcoming interface showcasing services and specialists.

### Patient Dashboard
Comprehensive health tracking and appointment management.

### Doctor Dashboard
Professional interface for managing consultations and patient care.

### Appointments Manager
Full-featured calendar and scheduling system.

### Medical Records
Secure document storage and organization.

---

## 🛠️ Tech Stack

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with custom properties
- **JavaScript (ES6+)** - Interactive features
- **Bootstrap 5** - Responsive framework
- **Font Awesome 5** - Icon library
- **Chart.js** - Data visualization
- **Owl Carousel** - Image sliders

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL 5.7+** - Database management
- **PDO** - Database abstraction layer

### Libraries & Tools
- **jQuery 3.x** - DOM manipulation
- **AJAX** - Asynchronous requests
- **PHPMailer** - Email handling (optional)
- **Session Management** - User authentication

---

## 💻 System Requirements

### Minimum Requirements
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **PHP:** 7.4 or higher
- **MySQL:** 5.7 or higher
- **RAM:** 512MB minimum (1GB recommended)
- **Disk Space:** 500MB minimum

### PHP Extensions Required
- `mysqli` or `pdo_mysql`
- `gd` (for image processing)
- `mbstring`
- `json`
- `session`
- `fileinfo`

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Opera 76+

---

## 📥 Installation

### Step 1: Clone or Download

```bash
# Using Git
git clone https://github.com/yourusername/medicate.git

# Or download and extract ZIP to your web server directory
# Example: C:\xampp\htdocs\medicate (Windows)
# Example: /var/www/html/medicate (Linux)
```

### Step 2: Configure Web Server

#### For XAMPP (Windows/Mac/Linux)
1. Copy project folder to `C:\xampp\htdocs\medicate`
2. Start Apache and MySQL from XAMPP Control Panel
3. Access: `http://localhost/medicate`

#### For WAMP (Windows)
1. Copy project folder to `C:\wamp64\www\medicate`
2. Start WAMP services
3. Access: `http://localhost/medicate`

#### For Linux (Apache)
```bash
sudo cp -r medicate /var/www/html/
sudo chown -R www-data:www-data /var/www/html/medicate
sudo chmod -R 755 /var/www/html/medicate
```

### Step 3: Set Permissions

```bash
# Create and set permissions for uploads directory
mkdir -p uploads/medical_documents
chmod -R 777 uploads/
```

---

## 🗄️ Database Setup

### Method 1: Using phpMyAdmin

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `medicate`
3. Click "Import" tab
4. Choose file: `database/migrations/add_new_features.sql`
5. Click "Go"

### Method 2: Using MySQL Command Line

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE medicate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p medicate < database/migrations/add_new_features.sql
```

### Database Structure

The system includes **13 main tables**:
- `users` - User accounts
- `patient_profiles` - Patient information
- `doctor_profiles` - Doctor information
- `consultations` - Appointment records
- `services` - Medical services
- `medical_documents` - Patient documents
- `notifications` - User notifications
- `doctor_reviews` - Rating system
- `health_vitals` - Patient health data
- `medications` - Medication tracking
- `prescriptions` - Digital prescriptions
- `appointment_reminders` - Automated reminders
- `consultation_notes` - Doctor notes

---

## ⚙️ Configuration

### Step 1: Database Configuration

Edit `config/database.php`:

```php
<?php
$host = 'localhost';
$dbname = 'medicate';
$username = 'root';  // Change if needed
$password = '';      // Add your password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

### Step 2: Email Configuration (Optional)

For email notifications, configure SMTP settings in your email handler.

### Step 3: Site Settings

Edit `config/helpers.php` to customize:
- Site name
- Contact information
- Default settings
- Upload limits

---

## 🚀 Usage

### First Time Setup

1. **Access the site:** `http://localhost/medicate`
2. **Register a new account:**
   - Patient: General user registration
   - Doctor: Contact admin for doctor registration
3. **Login with credentials**
4. **Complete your profile**

### For Patients

#### Booking an Appointment
1. Navigate to "Consultation" from homepage
2. Select service and preferred doctor
3. Choose date and time
4. Submit appointment request
5. Receive notification when confirmed

#### Managing Health Records
1. Go to "Medical Records" from dashboard
2. Click "Upload Document"
3. Select category (Lab Results, Imaging, etc.)
4. Upload file (PDF, JPG, PNG, DOC)
5. Add description and save

#### Tracking Health Vitals
1. Access "Health Dashboard"
2. Click "Record New Vital"
3. Enter measurements (BP, weight, glucose, etc.)
4. View trends and history

### For Doctors

#### Managing Appointments
1. Access "Appointments Manager"
2. View pending requests
3. Confirm or reject appointments
4. Add consultation notes
5. Mark as completed

#### Accessing Patient Records
1. View patient list from dashboard
2. Click on patient name
3. Access medical history and documents
4. Review past consultations

---

## 📁 Project Structure

```
medicate/
├── ajax/                       # AJAX request handlers
│   ├── delete-document.php
│   └── update-appointment-status.php
├── assets/                     # Static assets
│   ├── css/                   # Stylesheets
│   ├── js/                    # JavaScript files
│   ├── images/                # Images and graphics
│   └── fonts/                 # Font files
├── config/                     # Configuration files
│   ├── database.php           # Database connection
│   └── helpers.php            # Helper functions
├── database/                   # Database scripts
│   └── migrations/            # SQL migration files
├── includes/                   # Reusable components
│   ├── functions.php          # Core functions library
│   ├── appointment-card.php   # Appointment display
│   └── document-card.php      # Document display
├── services/                   # Service pages
│   ├── angioplasty.php
│   ├── cardiology.php
│   ├── dental.php
│   └── ...
├── uploads/                    # User uploaded files
│   └── medical_documents/     # Medical documents
├── index.php                   # Homepage
├── about.php                   # About page
├── contact.php                 # Contact page
├── services.php                # Services listing
├── specialists.php             # Doctors listing
├── blog.php                    # Blog listing
├── login.php                   # Login page
├── register.php                # Registration page
├── patient-dashboard.php       # Patient dashboard
├── doctor-dashboard.php        # Doctor dashboard
├── appointments-dashboard.php  # Appointments manager
├── medical-records.php         # Medical records
├── notifications.php           # Notifications center
├── patient-health-dashboard.php # Health tracking
├── rate-doctor.php             # Doctor rating
├── appointment-details.php     # Appointment details
├── LICENSE                     # MIT License
└── README.md                   # This file
```

---

## 📚 API Documentation

### Authentication

#### Login
```php
POST /login.php
Parameters:
  - email: string (required)
  - password: string (required)
Response: Session + redirect to dashboard
```

#### Register
```php
POST /register.php
Parameters:
  - email: string (required)
  - password: string (required)
  - user_type: patient|doctor
Response: Success message + redirect to login
```

### Appointments

#### Create Appointment
```php
POST /process-consultation.php
Parameters:
  - service_id: int (required)
  - consultation_date: date (required)
  - consultation_time: time (required)
  - notes: text (optional)
```

#### Update Appointment Status
```php
GET /ajax/update-appointment-status.php
Parameters:
  - id: int (appointment_id)
  - action: confirm|cancel|complete
```

### Medical Records

#### Upload Document
```php
POST /medical-records.php
Parameters:
  - document: file (required)
  - document_type: string (required)
  - description: text (optional)
Max Size: 10MB
Allowed: PDF, JPG, PNG, DOC, DOCX, TXT
```

#### Delete Document
```php
GET /ajax/delete-document.php
Parameters:
  - id: int (document_id)
```

### Notifications

#### Get Notifications
```php
GET /notifications.php
Parameters:
  - filter: all|unread|appointment|consultation|prescription
```

#### Mark as Read
```php
GET /notifications.php
Parameters:
  - mark_read: int (notification_id)
  - mark_all_read: boolean
```

---

## 🔐 Security

### Security Features Implemented

1. **Authentication & Authorization**
   - Session-based authentication
   - Role-based access control (Patient/Doctor/Admin)
   - Password hashing with PHP `password_hash()`

2. **SQL Injection Prevention**
   - PDO prepared statements
   - Parameter binding
   - Input validation

3. **XSS Protection**
   - `htmlspecialchars()` on all outputs
   - Content Security Policy headers
   - Input sanitization

4. **CSRF Protection**
   - Session tokens
   - Form validation

5. **File Upload Security**
   - File type validation
   - File size limits (10MB max)
   - Unique filenames
   - Secure storage paths

6. **Data Privacy**
   - Patient data access control
   - Doctor-patient relationship verification
   - Secure file permissions

### Best Practices

```php
// Always validate user input
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Use prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);

// Escape output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// Check permissions
if ($_SESSION['user_type'] !== 'patient') {
    header('Location: index.php');
    exit;
}
```

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Reporting Bugs

1. Check if the issue already exists
2. Create a detailed bug report with:
   - Steps to reproduce
   - Expected behavior
   - Actual behavior
   - Screenshots (if applicable)
   - System information

### Suggesting Features

1. Open an issue with the `enhancement` label
2. Describe the feature and use cases
3. Explain the benefits

### Pull Requests

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Test thoroughly
5. Commit: `git commit -m "Add your feature"`
6. Push: `git push origin feature/your-feature`
7. Open a pull request

### Code Standards

- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Comment complex logic
- Write clean, readable code
- Test before submitting

---

## 🐛 Known Issues

1. **Email notifications** - SMTP configuration required (not included by default)
2. **Video consultations** - Feature planned but not yet implemented
3. **Payment integration** - Requires third-party gateway setup

---

## 🗺️ Roadmap

### Version 1.1 (Q1 2025)
- [ ] Email notification system (SMTP)
- [ ] Advanced analytics dashboard
- [ ] Export reports (PDF)
- [ ] Multi-language support

### Version 1.2 (Q2 2025)
- [ ] Video consultation integration
- [ ] Mobile app (React Native)
- [ ] Payment gateway integration
- [ ] Prescription QR codes

### Version 2.0 (Q3 2025)
- [ ] AI-powered appointment scheduling
- [ ] Telemedicine features
- [ ] Insurance integration
- [ ] Wearable device integration

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 Oyedokun Kehinde

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

---

## 📞 Contact

**Project Maintainer:** Oyedokun Kehinde

- **Email:** info@medicate.com
- **Phone:** +234 8028134942
- **Address:** Medicate Lab, S5/808B, Oba Adesida Road, Akure, Ondo State
- **Website:** [https://medicate.com](https://medicate.com)

---

## 🙏 Acknowledgments

- Bootstrap team for the excellent framework
- Font Awesome for the icon library
- Chart.js for data visualization
- All contributors and testers

---

## 📊 Statistics

- **Lines of Code:** ~12,000+
- **PHP Files:** 40+
- **Database Tables:** 13
- **Features:** 25+
- **Development Time:** 6 months
- **Team Size:** 1 developer

---

## 🌟 Support

If you find this project helpful, please consider:
- ⭐ Starring the repository
- 🐛 Reporting bugs
- 💡 Suggesting features
- 🤝 Contributing code
- 📢 Sharing with others

---

## 📝 Changelog

### Version 1.0.0 (November 2025)
- Initial release
- Patient dashboard
- Doctor dashboard
- Appointment management
- Medical records system
- Health tracking
- Notifications center
- Doctor rating system
- Blog system

---

**Built with ❤️ for better healthcare management**

© 2025 Medicate. All Rights Reserved.
