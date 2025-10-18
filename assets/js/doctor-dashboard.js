// assets/js/doctor-dashboard.js - FIXED VERSION
document.addEventListener('DOMContentLoaded', function () {
    
    // Snackbar function
    function showSnackbar(message, isSuccess = true) {
        let snackbar = document.getElementById('snackbar');
        if (!snackbar) {
            snackbar = document.createElement('div');
            snackbar.id = 'snackbar';
            snackbar.style.cssText = `
                visibility: hidden;
                min-width: 250px;
                margin-left: -125px;
                background-color: ${isSuccess ? '#10b981' : '#ef4444'};
                color: white;
                text-align: center;
                border-radius: 8px;
                padding: 16px;
                position: fixed;
                z-index: 1000;
                left: 50%;
                bottom: 30px;
                font-family: 'Quicksand', sans-serif;
                font-size: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            document.body.appendChild(snackbar);
        }
        snackbar.textContent = message;
        snackbar.style.backgroundColor = isSuccess ? '#10b981' : '#ef4444';
        snackbar.style.visibility = 'visible';
        setTimeout(() => {
            snackbar.style.visibility = 'hidden';
        }, 3000);
    }

    // Check for URL messages
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const error = urlParams.get('error');
    
    if (msg) {
        showSnackbar(decodeURIComponent(msg), true);
        // Clean URL without reloading
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    
    if (error) {
        showSnackbar(decodeURIComponent(error), false);
        // Clean URL without reloading
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }

    // Navigation handler
    document.querySelectorAll('[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.dataset.section;
            
            // Hide all sections
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            
            // Remove active from all nav links
            document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
            
            // Show selected section
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Add active to clicked link (only if it has nav-link class)
            if (this.classList.contains('nav-link')) {
                this.classList.add('active');
            }
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard',
                'consultations': 'All Consultations',
                'upcoming': 'Upcoming Consultations',
                'doctors': 'All Doctors',
                'patients': 'All Patients',
                'services': 'Our Services',
                'profile': 'My Profile'
            };
            
            const pageTitle = document.getElementById('pageTitle');
            if (pageTitle) {
                pageTitle.textContent = titles[sectionId] || 'Dashboard';
            }
            
            // Scroll to top
            const contentArea = document.querySelector('.content-area');
            if (contentArea) {
                contentArea.scrollTop = 0;
            }
        });
    });

    // Update date and time
    function updateDateTime() {
        const now = new Date();
        
        // Format time (HH:MM AM/PM)
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // 0 should be 12
        const timeString = `${hours}:${minutes} ${ampm}`;
        
        // Format date (e.g., "Oct 18, 2025")
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        const dateString = now.toLocaleDateString('en-US', options);
        
        // Update DOM
        const timeElement = document.getElementById('currentTime');
        const dateElement = document.getElementById('currentDate');
        
        if (timeElement) {
            timeElement.textContent = timeString;
        }
        
        if (dateElement) {
            dateElement.textContent = dateString;
        }
    }

    // Initial update
    updateDateTime();
    
    // Update time every minute (60000ms)
    setInterval(updateDateTime, 60000);

    // Form validation for profile
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const fullName = document.querySelector('input[name="full_name"]');
            const email = document.querySelector('input[name="email"]');
            
            if (!fullName || !fullName.value.trim()) {
                e.preventDefault();
                showSnackbar('Please enter your full name', false);
                return false;
            }
            
            if (!email || !email.value.trim()) {
                e.preventDefault();
                showSnackbar('Please enter your email address', false);
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                e.preventDefault();
                showSnackbar('Please enter a valid email address', false);
                return false;
            }
        });
    }

    // Confirmation for consultation actions
    const consultationForms = document.querySelectorAll('form[action="process-consultation-update.php"]');
    consultationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            
            let confirmMessage = '';
            if (status === 'cancelled') {
                confirmMessage = 'Are you sure you want to reject this consultation?';
            } else if (status === 'completed') {
                confirmMessage = 'Mark this consultation as completed?';
            } else if (status === 'confirmed') {
                confirmMessage = 'Accept this consultation request?';
            }
            
            if (confirmMessage && !confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
        });
    });

    console.log('Doctor Dashboard initialized successfully');
});

