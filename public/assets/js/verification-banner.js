/**
 * Email Verification Banner Management
 * Handles the verification banner and modal interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    initVerificationBanner();
    setupVerificationModal();
    checkVerificationStatus();
});

/**
 * Initialize verification banner
 */
function initVerificationBanner() {
    const banner = document.getElementById('verificationBanner');
    
    if (!banner) return;

    // Check if banner should be dismissed (stored in sessionStorage)
    const bannerDismissed = sessionStorage.getItem('verificationBannerDismissed');
    
    if (bannerDismissed) {
        banner.classList.add('hidden');
    }
}

/**
 * Setup verification modal interactions
 */
function setupVerificationModal() {
    const modal = document.getElementById('verificationModal');
    const overlay = document.querySelector('.modal-overlay');
    
    if (!modal) return;

    // Close modal when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', closeVerificationModal);
    }

    // Close modal when pressing Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVerificationModal();
        }
    });
}

/**
 * Open verification modal
 */
function openVerificationModal() {
    const modal = document.getElementById('verificationModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close verification modal
 */
function closeVerificationModal() {
    const modal = document.getElementById('verificationModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

/**
 * Dismiss verification banner (only for this session)
 */
function dismissBanner() {
    const banner = document.getElementById('verificationBanner');
    if (banner) {
        banner.classList.add('hidden');
        // Store dismissal in sessionStorage (clears when browser closes)
        sessionStorage.setItem('verificationBannerDismissed', 'true');
    }
}

/**
 * Check verification status periodically
 * This allows the banner to disappear automatically after verification
 */
function checkVerificationStatus() {
    // Check every 5 seconds if email has been verified
    const verificationCheck = setInterval(function() {
        // Make an AJAX call to check if user is verified
        fetch('check-verification-status.php', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.email_verified) {
                // User is now verified, hide banner and close modal
                const banner = document.getElementById('verificationBanner');
                const modal = document.getElementById('verificationModal');
                
                if (banner) {
                    banner.classList.add('hidden');
                }
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }

                // Stop checking
                clearInterval(verificationCheck);

                // Show success notification
                showVerificationSuccess();
            }
        })
        .catch(error => {
            console.error('Verification check error:', error);
        });
    }, 5000);
}

/**
 * Show success notification after verification
 */
function showVerificationSuccess() {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #27ae60;
        color: white;
        padding: 16px 24px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        font-weight: 600;
        z-index: 10001;
        animation: slideInNotification 0.3s ease-out;
    `;
    notification.innerHTML = '<i class="fas fa-check-circle"></i> Email verified successfully!';
    document.body.appendChild(notification);

    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutNotification 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Add notification animations if not already present
if (!document.getElementById('notification-animations')) {
    const style = document.createElement('style');
    style.id = 'notification-animations';
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