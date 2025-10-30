// assets/js/login.js
$(function() {

    // Only run if user is on login page
    if (!$('#loginForm').length) return;

    // Form validation
    $('#loginForm').on('submit', function(e) {
        // Prevent default, we'll submit manually if valid
        e.preventDefault();
                
        let valid = true;
        let errorMsg = '';
        
        const email = $('#email').val().trim();
        const password = $('#password').val();

        // Reset borders
        $('#email, #password').css('border-color', '#cbd5e1');

        // Validate email with Regex
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

 /*==================================================
      [ Sticky Header ]
      ==================================================*/
      var view_width = jQuery(window).width();
      if (!jQuery('header').hasClass('pq-header-default') && view_width >= 1023) {
         var height = jQuery('header').height();
         jQuery('.pq-breadcrumb').css('padding-top', height * 1.3);
      }
      if (jQuery('header').hasClass('pq-header-default')) {
         jQuery(window).scroll(function () {
            var scrollTop = jQuery(window).scrollTop();
            if (scrollTop > 300) {
               jQuery('.pq-bottom-header').addClass('pq-header-sticky animated fadeInDown animate__faster');
            } else {
               jQuery('.pq-bottom-header').removeClass('pq-header-sticky animated fadeInDown animate__faster');
            }
         });
      }
      if (jQuery('header').hasClass('pq-has-sticky')) {
         jQuery(window).scroll(function () {
            var scrollTop = jQuery(window).scrollTop();
            if (scrollTop > 300) {
               jQuery('header').addClass('pq-header-sticky animated fadeInDown animate__faster');
            } else {
               jQuery('header').removeClass('pq-header-sticky animated fadeInDown animate__faster');
            }
         });
      }
