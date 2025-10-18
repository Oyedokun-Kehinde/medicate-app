// assets/js/verification.js
$(function() {
    // Show URL message (e.g., ?msg=Success)
    const msg = new URLSearchParams(location.search).get('msg');
    if (msg) {
        showSnackbar(decodeURIComponent(msg), 'success');
        window.history.replaceState({}, document.title, location.pathname);
    }

    // Handle verification form submit
    $('#verificationForm').on('submit', function(e) {
        const code = $('#verification_code').val().trim();
        
        if (code.length !== 6 || !/^\d+$/.test(code)) {
            e.preventDefault();
            $('#verification_code').css('border-color', '#ef4444');
            showSnackbar('Please enter a valid 6-digit code.', 'error');
            return false;
        }
    });

    // Auto-format verification code input (numbers only, max 6 digits)
    $('#verification_code').on('input', function() {
        let val = $(this).val().replace(/\D/g, ''); // Remove non-digits
        if (val.length > 6) val = val.slice(0, 6);
        $(this).val(val);
        $(this).css('border-color', '#cbd5e1');
    });
});

// Snackbar function
function showSnackbar(message, type = 'info') {
    const snackbar = $('#snackbar');
    
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
    
    setTimeout(() => {
        snackbar.css({
            'visibility': 'hidden',
            'opacity': '0'
        });
    }, 4000);
}