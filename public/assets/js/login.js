
// Snackbar function
function showSnackbar(message, type = 'info') {
    const existingSnackbar = document.querySelector('.snackbar');
    if (existingSnackbar) existingSnackbar.remove();

    const snackbar = document.createElement('div');
    snackbar.className = `snackbar ${type}`;
    let icon = '';
    switch (type) {
        case 'success': icon = '<i class="fas fa-check-circle snackbar-icon"></i>'; break;
        case 'error': icon = '<i class="fas fa-exclamation-circle snackbar-icon"></i>'; break;
        default: icon = '<i class="fas fa-info-circle snackbar-icon"></i>';
    }
    snackbar.innerHTML = `${icon}<div class="snackbar-message">${message}</div>`;
    document.body.appendChild(snackbar);

    setTimeout(() => snackbar.classList.add('show'), 100);
    setTimeout(() => {
        snackbar.classList.remove('show');
        setTimeout(() => snackbar.remove(), 300);
    }, 5000);
}

// Login form submission
document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('.pq-button');
    const originalHTML = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="pq-button-text">Signing In...</span>';

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSnackbar('Login successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showSnackbar(data.message || 'Invalid credentials. Please try again.', 'error');
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