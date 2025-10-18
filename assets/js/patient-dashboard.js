/**
 * Patient Dashboard JavaScript
 * Location: /assets/js/patient-dashboard.js
 * Purpose: Handle all dashboard interactivity
 */

// Initialize dashboard on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    setupEventListeners();
    updateTime();
    setInterval(updateTime, 1000);
});

/**
 * Initialize dashboard components
 */
function initializeDashboard() {
    const today = new Date().toISOString().split('T')[0];
    const consultationDateInput = document.querySelector('input[name="consultation_date"]');
    
    if (consultationDateInput) {
        consultationDateInput.min = today;
    }
}

/**
 * Setup event listeners for navigation and interactions
 */
function setupEventListeners() {
    // Navigation links
    document.querySelectorAll('[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');
            showSection(sectionId);
        });
    });

    // Navigation button links (quick action cards)
    document.querySelectorAll('.nav-link-btn').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');
            showSection(sectionId);
        });
    });

    // Form submissions
    const consultationForm = document.getElementById('consultationForm');
    const profileForm = document.getElementById('profileForm');

    if (consultationForm) {
        consultationForm.addEventListener('submit', handleConsultationSubmit);
    }

    if (profileForm) {
        profileForm.addEventListener('submit', handleProfileSubmit);
    }
}

/**
 * Show specified section and hide others
 * @param {string} sectionId - The ID of the section to show
 */
function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });

    // Remove active state from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });

    // Show selected section
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.add('active');
    }

    // Set active nav link
    const activeLink = document.querySelector(`[data-section="${sectionId}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }

    // Update page title
    const titles = {
        'dashboard': 'Dashboard',
        'appointments': 'My Appointments',
        'consultation': 'Request Consultation',
        'profile': 'My Profile'
    };

    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle) {
        pageTitle.textContent = titles[sectionId] || 'Dashboard';
    }

    // Scroll to top of content area
    const contentArea = document.querySelector('.content-area');
    if (contentArea) {
        contentArea.scrollTop = 0;
    }
}

/**
 * Update current time display
 */
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const timeDisplay = document.getElementById('currentTime');
    
    if (timeDisplay) {
        timeDisplay.textContent = `${hours}:${minutes}`;
    }
}

/**
 * Handle consultation form submission
 * @param {Event} event - Form submission event
 */
function handleConsultationSubmit(event) {
    const serviceId = document.querySelector('select[name="service_id"]').value;
    const consultationDate = document.querySelector('input[name="consultation_date"]').value;
    const reason = document.querySelector('textarea[name="reason"]').value;
    
    if (!serviceId) {
        event.preventDefault();
        showNotification('Please select a service', 'error');
        return;
    }

    if (!consultationDate) {
        event.preventDefault();
        showNotification('Please select a date', 'error');
        return;
    }

    if (!reason.trim()) {
        event.preventDefault();
        showNotification('Please enter a reason for consultation', 'error');
        return;
    }
}

/**
 * Handle profile form submission
 * @param {Event} event - Form submission event
 */
function handleProfileSubmit(event) {
    const fullName = document.querySelector('input[name="full_name"]').value;
    const email = document.querySelector('input[name="email"]').value;

    if (!fullName.trim()) {
        event.preventDefault();
        showNotification('Please enter your full name', 'error');
        return;
    }

    if (!email.trim()) {
        event.preventDefault();
        showNotification('Please enter your email address', 'error');
        return;
    }

    if (!isValidEmail(email)) {
        event.preventDefault();
        showNotification('Please enter a valid email address', 'error');
        return;
    }
}

/**
 * Validate email format
 * @param {string} email - Email address to validate
 * @returns {boolean} - True if valid, false otherwise
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Show notification message to user
 * @param {string} message - Message to display
 * @param {string} type - Type of notification (success, error, info, warning)
 */
function showNotification(message, type = 'info') {
    let notificationContainer = document.getElementById('notification-container');

    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
        `;
        document.body.appendChild(notificationContainer);
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        padding: 16px 24px;
        margin-bottom: 10px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInNotification 0.3s ease-out;
        word-wrap: break-word;
    `;

    // Set background color based on type
    const colors = {
        'success': { bg: '#27ae60', color: '#fff' },
        'error': { bg: '#e74c3c', color: '#fff' },
        'warning': { bg: '#f39c12', color: '#fff' },
        'info': { bg: '#3498db', color: '#fff' }
    };

    const colorScheme = colors[type] || colors['info'];
    notification.style.backgroundColor = colorScheme.bg;
    notification.style.color = colorScheme.color;

    notification.textContent = message;
    notificationContainer.appendChild(notification);

    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutNotification 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Add animation styles for notifications
 */
(function() {
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideInNotification {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutNotification {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
})();

/**
 * Utility: Format date to readable format
 * @param {string} dateString - ISO date string
 * @returns {string} - Formatted date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

/**
 * Utility: Format time to readable format
 * @param {string} timeString - Time string (HH:MM)
 * @returns {string} - Formatted time
 */
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

/**
 * Export functions for global use
 */
window.dashboard = {
    showSection,
    showNotification,
    formatDate,
    formatTime,
    isValidEmail
};

$(function() {
    function showNotification(message, type = 'info') {
        let container = $('#notification-container');
        if (container.length === 0) {
            container = $('<div id="notification-container">')
                .css({
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    zIndex: '10000',
                    maxWidth: '400px'
                })
                .appendTo('body');
        }
        const colors = {
            success: { bg: '#27ae60', color: '#fff' },
            error: { bg: '#e74c3c', color: '#fff' },
            warning: { bg: '#f39c12', color: '#fff' },
            info: { bg: '#3498db', color: '#fff' }
        };
        const color = colors[type] || colors.info;
        const notification = $('<div>')
            .text(message)
            .css({
                padding: '16px 24px',
                marginBottom: '10px',
                borderRadius: '6px',
                fontSize: '14px',
                fontWeight: '500',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                backgroundColor: color.bg,
                color: color.color,
                animation: 'slideInNotification 0.3s ease-out'
            })
            .appendTo(container);
        setTimeout(() => {
            notification.css('animation', 'slideOutNotification 0.3s ease-out');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    if ($('#notification-styles').length === 0) {
        $('<style id="notification-styles">')
            .text(`
                @keyframes slideInNotification {
                    from { transform: translateX(400px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutNotification {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(400px); opacity: 0; }
                }
            `)
            .appendTo('head');
    }

    function showSection(sectionId) {
        $('.section').removeClass('active');
        $('.nav-link').removeClass('active');
        $(`#${sectionId}`).addClass('active');
        $(`[data-section="${sectionId}"]`).addClass('active');
        const titles = {
            dashboard: 'Dashboard',
            appointments: 'My Appointments',
            consultation: 'Request Consultation',
            profile: 'My Profile'
        };
        $('#pageTitle').text(titles[sectionId] || 'Dashboard');
        $('.content-area').scrollTop(0);
    }

    function updateTime() {
        const now = new Date();
        const time = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        $('#currentTime').text(time);
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Initialize
    updateTime();
    setInterval(updateTime, 1000);

    // Navigation
    $('[data-section]').on('click', function(e) {
        e.preventDefault();
        showSection($(this).data('section'));
    });

    // Consultation form
    $('#consultationForm').on('submit', function(e) {
        const service = $('select[name="service_id"]').val();
        const notes = $('textarea[name="notes"]').val();
        if (!service) {
            e.preventDefault();
            showNotification('Please select a service', 'error');
        } else if (!notes.trim()) {
            e.preventDefault();
            showNotification('Please enter consultation notes', 'error');
        }
    });

    // Profile form
    $('#profileForm').on('submit', function(e) {
        const name = $('input[name="full_name"]').val().trim();
        const email = $('input[name="email"]').val().trim();
        if (!name) {
            e.preventDefault();
            showNotification('Please enter your full name', 'error');
        } else if (!email || !isValidEmail(email)) {
            e.preventDefault();
            showNotification('Please enter a valid email address', 'error');
        }
    });
});
