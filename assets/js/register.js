// assets/js/register.js
$(function() {
    // Only run if on register page
    if (!$('#registerForm').length) return;

    // Inject password rules if not present
    if ($('#strength-rules').length === 0) {
        const rules = `
            <div id="strength-rules" style="font-size:13px; margin:8px 0; padding-left:20px;">
                <ul style="line-height:1.5; list-style:none; padding:0;">
                    <li id="rule-length" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> At least 8 characters</li>
                    <li id="rule-uppercase" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One uppercase letter (A-Z)</li>
                    <li id="rule-number" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One number (0-9)</li>
                    <li id="rule-symbol" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One symbol (!@#$%^&*)</li>
                </ul>
            </div>
        `;
        $('#password').parent().append(rules);
    }

    // Add password strength bar if not present
    if ($('#password-strength').children().length === 0) {
        $('#password-strength').html(`
            <div style="margin-top:8px;">
                <div style="display:flex; gap:4px; height:4px; border-radius:2px; overflow:hidden;">
                    <div id="strength-bar-1" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-2" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-3" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-4" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                </div>
                <div id="strength-text" style="font-size:12px; margin-top:4px; color:#64748b;"></div>
            </div>
        `);
    }

    // Real-time password feedback
    $('#password').on('input', function() {
        const pwd = $(this).val();
        
        // Check each rule
        const checks = [
            pwd.length >= 8,
            /[A-Z]/.test(pwd),
            /[0-9]/.test(pwd),
            /[^A-Za-z0-9]/.test(pwd)
        ];
        
        // Update rule colors
        const rules = ['#rule-length', '#rule-uppercase', '#rule-number', '#rule-symbol'];
        rules.forEach((selector, i) => {
            $(selector).css('color', checks[i] ? '#10b981' : '#ef4444');
            $(selector).find('i').css('color', checks[i] ? '#10b981' : '#ef4444');
        });

        // Calculate strength
        const strength = checks.filter(Boolean).length;
        const strengthColors = ['#ef4444', '#f59e0b', '#eab308', '#10b981'];
        const strengthTexts = ['Weak', 'Fair', 'Good', 'Strong'];

        // Update strength bars
        for (let i = 1; i <= 4; i++) {
            const bar = $(`#strength-bar-${i}`);
            if (i <= strength) {
                bar.css('background', strengthColors[strength - 1]);
            } else {
                bar.css('background', '#e2e8f0');
            }
        }

        // Update strength text
        if (pwd.length > 0) {
            $('#strength-text').text(`Password strength: ${strengthTexts[strength - 1] || 'Too weak'}`);
            $('#strength-text').css('color', strengthColors[strength - 1] || '#ef4444');
        } else {
            $('#strength-text').text('');
        }
    });

    // Form validation
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default, we'll submit manually if valid
        
        let valid = true;
        let errorMsg = '';
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const userType = $('#user_type').val();

        // Reset borders
        $('#email, #password, #user_type').css('border-color', '#cbd5e1');

        // Validate email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').css('border-color', '#ef4444');
            errorMsg = 'Please enter a valid email address.';
            valid = false;
        }
        
        // Validate password strength
        const checks = [
            password.length >= 8,
            /[A-Z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        if (!checks.every(Boolean)) {
            $('#password').css('border-color', '#ef4444');
            errorMsg = 'Password must meet all requirements.';
            valid = false;
        }
        
        // Validate user type
        if (!userType) {
            $('#user_type').css('border-color', '#ef4444');
            errorMsg = 'Please select your account type.';
            valid = false;
        }

        if (!valid) {
            showSnackbar(errorMsg, 'error');
            return false;
        }

        // If valid, submit the form
        this.submit();
    });

    // Show URL message (e.g., ?msg=Success)
    const msg = new URLSearchParams(location.search).get('msg');
    if (msg) {
        showSnackbar(decodeURIComponent(msg), 'success');
        // Clean URL without reload
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
}// assets/js/register.js
$(function() {
    // Only run if on register page
    if (!$('#registerForm').length) return;

    // Inject password rules if not present
    if ($('#strength-rules').length === 0) {
        const rules = `
            <div id="strength-rules" style="font-size:13px; margin:8px 0; padding-left:20px;">
                <ul style="line-height:1.5; list-style:none; padding:0;">
                    <li id="rule-length" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> At least 8 characters</li>
                    <li id="rule-uppercase" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One uppercase letter (A-Z)</li>
                    <li id="rule-number" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One number (0-9)</li>
                    <li id="rule-symbol" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One symbol (!@#$%^&*)</li>
                </ul>
            </div>
        `;
        $('#password').parent().append(rules);
    }

    // Add password strength bar if not present
    if ($('#password-strength').children().length === 0) {
        $('#password-strength').html(`
            <div style="margin-top:8px;">
                <div style="display:flex; gap:4px; height:4px; border-radius:2px; overflow:hidden;">
                    <div id="strength-bar-1" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-2" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-3" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-4" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                </div>
                <div id="strength-text" style="font-size:12px; margin-top:4px; color:#64748b;"></div>
            </div>
        `);
    }

    // Real-time password feedback
    $('#password').on('input', function() {
        const pwd = $(this).val();
        
        // Check each rule
        const checks = [
            pwd.length >= 8,
            /[A-Z]/.test(pwd),
            /[0-9]/.test(pwd),
            /[^A-Za-z0-9]/.test(pwd)
        ];
        
        // Update rule colors
        const rules = ['#rule-length', '#rule-uppercase', '#rule-number', '#rule-symbol'];
        rules.forEach((selector, i) => {
            $(selector).css('color', checks[i] ? '#10b981' : '#ef4444');
            $(selector).find('i').css('color', checks[i] ? '#10b981' : '#ef4444');
        });

        // Calculate strength
        const strength = checks.filter(Boolean).length;
        const strengthColors = ['#ef4444', '#f59e0b', '#eab308', '#10b981'];
        const strengthTexts = ['Weak', 'Fair', 'Good', 'Strong'];

        // Update strength bars
        for (let i = 1; i <= 4; i++) {
            const bar = $(`#strength-bar-${i}`);
            if (i <= strength) {
                bar.css('background', strengthColors[strength - 1]);
            } else {
                bar.css('background', '#e2e8f0');
            }
        }

        // Update strength text
        if (pwd.length > 0) {
            $('#strength-text').text(`Password strength: ${strengthTexts[strength - 1] || 'Too weak'}`);
            $('#strength-text').css('color', strengthColors[strength - 1] || '#ef4444');
        } else {
            $('#strength-text').text('');
        }
    });

    // Form validation
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default, we'll submit manually if valid
        
        let valid = true;
        let errorMsg = '';
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const userType = $('#user_type').val();

        // Reset borders
        $('#email, #password, #user_type').css('border-color', '#cbd5e1');

        // Validate email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').css('border-color', '#ef4444');
            errorMsg = 'Please enter a valid email address.';
            valid = false;
        }
        
        // Validate password strength
        const checks = [
            password.length >= 8,
            /[A-Z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        if (!checks.every(Boolean)) {
            $('#password').css('border-color', '#ef4444');
            errorMsg = 'Password must meet all requirements.';
            valid = false;
        }
        
        // Validate user type
        if (!userType) {
            $('#user_type').css('border-color', '#ef4444');
            errorMsg = 'Please select your account type.';
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

// assets/js/register.js
$(function() {
    // Only run if on register page
    if (!$('#registerForm').length) return;

    // Inject password rules if not present
    if ($('#strength-rules').length === 0) {
        const rules = `
            <div id="strength-rules" style="font-size:13px; margin:8px 0; padding-left:20px;">
                <ul style="line-height:1.5; list-style:none; padding:0;">
                    <li id="rule-length" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> At least 8 characters</li>
                    <li id="rule-uppercase" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One uppercase letter (A-Z)</li>
                    <li id="rule-number" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One number (0-9)</li>
                    <li id="rule-symbol" style="color:#64748b;"><i class="fas fa-circle" style="font-size:8px;"></i> One symbol (!@#$%^&*)</li>
                </ul>
            </div>
        `;
        $('#password').parent().append(rules);
    }

    // Add password strength bar if not present
    if ($('#password-strength').children().length === 0) {
        $('#password-strength').html(`
            <div style="margin-top:8px;">
                <div style="display:flex; gap:4px; height:4px; border-radius:2px; overflow:hidden;">
                    <div id="strength-bar-1" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-2" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-3" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                    <div id="strength-bar-4" style="flex:1; background:#e2e8f0; transition:all 0.3s;"></div>
                </div>
                <div id="strength-text" style="font-size:12px; margin-top:4px; color:#64748b;"></div>
            </div>
        `);
    }

    // Real-time password feedback
    $('#password').on('input', function() {
        const pwd = $(this).val();
        
        // Check each rule
        const checks = [
            pwd.length >= 8,
            /[A-Z]/.test(pwd),
            /[0-9]/.test(pwd),
            /[^A-Za-z0-9]/.test(pwd)
        ];
        
        // Update rule colors
        const rules = ['#rule-length', '#rule-uppercase', '#rule-number', '#rule-symbol'];
        rules.forEach((selector, i) => {
            $(selector).css('color', checks[i] ? '#10b981' : '#ef4444');
            $(selector).find('i').css('color', checks[i] ? '#10b981' : '#ef4444');
        });

        // Calculate strength
        const strength = checks.filter(Boolean).length;
        const strengthColors = ['#ef4444', '#f59e0b', '#eab308', '#10b981'];
        const strengthTexts = ['Weak', 'Fair', 'Good', 'Strong'];

        // Update strength bars
        for (let i = 1; i <= 4; i++) {
            const bar = $(`#strength-bar-${i}`);
            if (i <= strength) {
                bar.css('background', strengthColors[strength - 1]);
            } else {
                bar.css('background', '#e2e8f0');
            }
        }

        // Update strength text
        if (pwd.length > 0) {
            $('#strength-text').text(`Password strength: ${strengthTexts[strength - 1] || 'Too weak'}`);
            $('#strength-text').css('color', strengthColors[strength - 1] || '#ef4444');
        } else {
            $('#strength-text').text('');
        }
    });

    // Form validation
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default, we'll submit manually if valid
        
        let valid = true;
        let errorMsg = '';
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const userType = $('#user_type').val();

        // Reset borders
        $('#email, #password, #user_type').css('border-color', '#cbd5e1');

        // Validate email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').css('border-color', '#ef4444');
            errorMsg = 'Please enter a valid email address.';
            valid = false;
        }
        
        // Validate password strength
        const checks = [
            password.length >= 8,
            /[A-Z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        if (!checks.every(Boolean)) {
            $('#password').css('border-color', '#ef4444');
            errorMsg = 'Password must meet all requirements.';
            valid = false;
        }
        
        // Validate user type
        if (!userType) {
            $('#user_type').css('border-color', '#ef4444');
            errorMsg = 'Please select your account type.';
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