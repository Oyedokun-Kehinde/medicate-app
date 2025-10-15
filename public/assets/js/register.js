// public/assets/js/register.js
document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('user_type_select');
    const doctorFields = document.getElementById('doctorFields');

    // Show/hide doctor fields based on selection
    userTypeSelect.addEventListener('change', function() {
        if (this.value === 'doctor') {
            doctorFields.style.display = 'block';
        } else {
            doctorFields.style.display = 'none';
        }
    });

    // Real-time validation
    const form = document.getElementById('registerForm');
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', () => {
            // Clear error when user types
            const errorDiv = input.closest('.form-group').querySelector('.error-message');
            errorDiv.textContent = '';
        });
    });

    form.addEventListener('submit', function(e) {
        let isValid = true;
        inputs.forEach(input => {
            if (!validateField.call(input)) {
                isValid = false;
            }
        });
        if (!isValid) e.preventDefault();
    });

    function validateField() {
        const input = this;
        const errorDiv = input.closest('.form-group').querySelector('.error-message');
        let error = '';

        if (input.hasAttribute('required') && !input.value.trim()) {
            error = 'This field is required.';
        } else if (input.name === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                error = 'Please enter a valid email.';
            }
        } else if (input.name === 'password' && input.value) {
            if (input.value.length < 8) {
                error = 'Password must be at least 8 characters.';
            } else if (!/[A-Z]/.test(input.value)) {
                error = 'Password must contain at least one uppercase letter.';
            } else if (!/\d/.test(input.value)) {
                error = 'Password must contain at least one number.';
            }
        } else if (input.name === 'confirm_password' && input.value) {
            const password = form.querySelector('input[name="password"]').value;
            if (input.value !== password) {
                error = 'Passwords do not match.';
            }
        }

        errorDiv.textContent = error;
        return error === '';
    }

    // Add Snackbar Function
    window.showSnackbar = function(message, type = 'info') {
        // Remove existing snackbar
        const existingSnackbar = document.querySelector('.snackbar');
        if (existingSnackbar) {
            existingSnackbar.remove();
        }

        // Create snackbar
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

        // Show snackbar
        setTimeout(() => {
            snackbar.classList.add('show');
        }, 100);

        // Hide after 5 seconds
        setTimeout(() => {
            snackbar.classList.remove('show');
            setTimeout(() => {
                snackbar.remove();
            }, 300);
        }, 5000);
    };
});