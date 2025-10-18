// assets/js/login.js
$(function() {
    // Only run if on login page
    if (!$('#loginForm').length) return;

    // Form validation
    $('#loginForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default, we'll submit manually if valid
        
        let valid = true;
        let errorMsg = '';
        
        const email = $('#email').val().trim();
        const password = $('#password').val();

        // Reset borders
        $('#email, #password').css('border-color', '#cbd5e1');

        // Validate email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').css('border-color', '#ef4444');
            errorMsg = 'Please enter a valid email address.';
            valid = false;
        }
        
        // Validate password
        if (password.length === 0) {
            $('#password').css('border-color', '#ef4444');
            errorMsg = 'Password is required.';
            valid = false;
        }

        if (!valid) {
            showSnackbar(errorMsg, 'error');
            return false;
        }

        // If valid, submit the form
        this.submit();
    });

    // Show URL message (e.g., ?msg=Success or ?error=...)
    const msg = new URLSearchParams(location.search).get('msg');
    const error = new URLSearchParams(location.search).get('error');
    
    if (msg) {
        showSnackbar(decodeURIComponent(msg), 'success');
        window.history.replaceState({}, document.title, location.pathname);
    }
    
    if (error) {
        showSnackbar(decodeURIComponent(error), 'error');
        window.history.replaceState({}, document.title, location.pathname);
    }
});

// Snackbar function
function showSnackbar(message, type = 'info') {
    const snackbar = $('#snackbar');
    
    // Set colors based on type
    const colors = {
        success: { bg: '#10b981', icon: 'fa-check-circle' },
        error: { bg: '#ef4444', icon: 'fa-exclamation-circle' },
        warning: { bg: '#f59e0b', icon: 'fa-exclamation-triangle' },
        info: { bg: '#3b82f6', icon: 'fa-info-circle' }
    };
    
    const color = colors[type] || colors.info;
    
    snackbar.html(`
        <i class="fas ${color.icon}" style="margin-right:8px;"></i>
        ${message}
    `);
    
    snackbar.css({
        'background-color': color.bg,
        'visibility': 'visible',
        'opacity': '1'
    });
    
    // Hide after 4 seconds
    setTimeout(() => {
        snackbar.css({
            'visibility': 'hidden',
            'opacity': '0'
        });
    }, 4000);
}