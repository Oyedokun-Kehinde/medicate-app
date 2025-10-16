// public/assets/js/register.js
document.addEventListener('DOMContentLoaded', function() {
    // Get form element with safety check
    const form = document.getElementById('registerForm');
    if (!form) {
        console.warn('Form #registerForm not found on this page');
        return;
    }

    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const userTypeHidden = document.getElementById('user_type');

    // Update hidden user_type field when tab changes
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (userTypeHidden) {
                userTypeHidden.value = this.value;
            }
        });
    });

    // Get all form inputs
    const inputs = form.querySelectorAll('input, select, textarea');

    // Real-time validation on blur
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('input', function() {
            // Clear error when user types
            const formGroup = this.closest('.form-group');
            if (formGroup) {
                const errorDiv = formGroup.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.textContent = '';
                }
            }
        });
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            const percentage = (strength / 4) * 100;
            strengthBar.style.width = percentage + '%';

            if (strength === 0) {
                strengthBar.style.backgroundColor = '#ccc';
                strengthText.textContent = '';
            } else if (strength === 1) {
                strengthBar.style.backgroundColor = '#ff4444';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#ff4444';
            } else if (strength === 2) {
                strengthBar.style.backgroundColor = '#ff9800';
                strengthText.textContent = 'Fair';
                strengthText.style.color = '#ff9800';
            } else if (strength === 3) {
                strengthBar.style.backgroundColor = '#2196F3';
                strengthText.textContent = 'Good';
                strengthText.style.color = '#2196F3';
            } else {
                strengthBar.style.backgroundColor = '#4CAF50';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#4CAF50';
            }
        });
    }

    // Validation function
    function validateField(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return true;

        const errorDiv = formGroup.querySelector('.error-message');
        if (!errorDiv) return true;

        let error = '';

        if (input.hasAttribute('required') && !input.value.trim()) {
            error = 'This field is required.';
        } 
        else if (input.name === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                error = 'Please enter a valid email address.';
            }
        } 
        else if (input.name === 'password' && input.value) {
            if (input.value.length < 8) {
                error = 'Password must be at least 8 characters long.';
            } else if (!/[A-Z]/.test(input.value)) {
                error = 'Password must contain at least one uppercase letter.';
            } else if (!/\d/.test(input.value)) {
                error = 'Password must contain at least one number.';
            }
        } 
        else if (input.name === 'confirm_password' && input.value) {
            const passwordField = form.querySelector('input[name="password"]');
            if (passwordField && input.value !== passwordField.value) {
                error = 'Passwords do not match.';
            }
        }
        else if (input.name === 'phone' && input.value) {
            const phoneRegex = /^[0-9]{10,11}$/;
            if (!phoneRegex.test(input.value.replace(/\s/g, ''))) {
                error = 'Please enter a valid phone number (10-11 digits).';
            }
        }

        errorDiv.textContent = error;
        return error === '';
    }

    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            showSnackbar('Please fix the errors before submitting.', 'error');
            return;
        }

        const submitBtn = form.querySelector('.submit-btn');
        if (!submitBtn) return;

        const originalHTML = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';

        const formData = new FormData(this);

        fetch('../controllers/register-controller.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network error: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSnackbar(data.message || 'Registration successful!', 'success');
                setTimeout(() => {
                    window.location.href = 'login.php?registered=1';
                }, 2000);
            } else {
                showSnackbar(data.message || 'Registration failed. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            showSnackbar('An error occurred. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        });
    });

    // Snackbar function
    function showSnackbar(message, type = 'info') {
        const existingSnackbar = document.querySelector('.snackbar');
        if (existingSnackbar) {
            existingSnackbar.remove();
        }

        const snackbar = document.createElement('div');
        snackbar.className = `snackbar ${type}`;
        
        let icon = '';
        switch (type) {
            case 'success':
                icon = '<i class="fas fa-check-circle snackbar-icon"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-exclamation-circle snackbar-icon"></i>';
                break;
            case 'warning':
                icon = '<i class="fas fa-exclamation-triangle snackbar-icon"></i>';
                break;
            default:
                icon = '<i class="fas fa-info-circle snackbar-icon"></i>';
        }
        
        snackbar.innerHTML = `
            ${icon}
            <div class="snackbar-message">${message}</div>
        `;
        
        document.body.appendChild(snackbar);

        setTimeout(() => {
            snackbar.classList.add('show');
        }, 100);

        setTimeout(() => {
            snackbar.classList.remove('show');
            setTimeout(() => {
                snackbar.remove();
            }, 300);
        }, 5000);
    }

    window.showSnackbar = showSnackbar;
});