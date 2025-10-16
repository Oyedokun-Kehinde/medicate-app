// public/assets/js/login.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    if (!form) {
        console.warn('Form #loginForm not found');
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('.submit-btn');
        const originalHTML = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';

        const formData = new FormData(this);

        fetch('../controllers/login-controller.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSnackbar(data.message || 'Login successful!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                showSnackbar(data.message || 'Login failed', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            showSnackbar('An error occurred. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        });
    });

    function showSnackbar(message, type = 'info') {
        const existingSnackbar = document.querySelector('.snackbar');
        if (existingSnackbar) existingSnackbar.remove();

        const snackbar = document.createElement('div');
        snackbar.className = `snackbar ${type}`;
        
        let icon = type === 'success' ? '<i class="fas fa-check-circle snackbar-icon"></i>' : '<i class="fas fa-exclamation-circle snackbar-icon"></i>';
        
        snackbar.innerHTML = `${icon}<div class="snackbar-message">${message}</div>`;
        document.body.appendChild(snackbar);

        setTimeout(() => snackbar.classList.add('show'), 100);
        setTimeout(() => {
            snackbar.classList.remove('show');
            setTimeout(() => snackbar.remove(), 300);
        }, 5000);
    }

    window.showSnackbar = showSnackbar;
});