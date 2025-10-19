// assets/js/doctor-dashboard.js - FIXED VERSION
document.addEventListener('DOMContentLoaded', function () {
    
    // Snackbar function
    function showSnackbar(message, isSuccess = true) {
        let snackbar = document.getElementById('snackbar');
        if (!snackbar) {
            snackbar = document.createElement('div');
            snackbar.id = 'snackbar';
            snackbar.style.cssText = `
                visibility: hidden;
                min-width: 250px;
                margin-left: -125px;
                background-color: ${isSuccess ? '#10b981' : '#ef4444'};
                color: white;
                text-align: center;
                border-radius: 8px;
                padding: 16px;
                position: fixed;
                z-index: 1000;
                left: 50%;
                bottom: 30px;
                font-family: 'Quicksand', sans-serif;
                font-size: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            document.body.appendChild(snackbar);
        }
        snackbar.textContent = message;
        snackbar.style.backgroundColor = isSuccess ? '#10b981' : '#ef4444';
        snackbar.style.visibility = 'visible';
        setTimeout(() => {
            snackbar.style.visibility = 'hidden';
        }, 3000);
    }

    // Check for URL messages
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const error = urlParams.get('error');
    
    if (msg) {
        showSnackbar(decodeURIComponent(msg), true);
        // Clean URL without reloading
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    
    if (error) {
        showSnackbar(decodeURIComponent(error), false);
        // Clean URL without reloading
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }

    // Navigation handler
    document.querySelectorAll('[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.dataset.section;
            
            // Hide all sections
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            
            // Remove active from all nav links
            document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
            
            // Show selected section
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Add active to clicked link (only if it has nav-link class)
            if (this.classList.contains('nav-link')) {
                this.classList.add('active');
            }
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard',
                'consultations': 'All Consultations',
                'upcoming': 'Upcoming Consultations',
                'doctors': 'All Doctors',
                'patients': 'All Patients',
                'services': 'Our Services',
                'profile': 'My Profile'
            };
            
            const pageTitle = document.getElementById('pageTitle');
            if (pageTitle) {
                pageTitle.textContent = titles[sectionId] || 'Dashboard';
            }
            
            // Scroll to top
            const contentArea = document.querySelector('.content-area');
            if (contentArea) {
                contentArea.scrollTop = 0;
            }
        });
    });

    // Update date and time
    function updateDateTime() {
        const now = new Date();
        
        // Format time (HH:MM AM/PM)
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // 0 should be 12
        const timeString = `${hours}:${minutes} ${ampm}`;
        
        // Format date (e.g., "Oct 18, 2025")
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        const dateString = now.toLocaleDateString('en-US', options);
        
        // Update DOM
        const timeElement = document.getElementById('currentTime');
        const dateElement = document.getElementById('currentDate');
        
        if (timeElement) {
            timeElement.textContent = timeString;
        }
        
        if (dateElement) {
            dateElement.textContent = dateString;
        }
    }

    // Initial update
    updateDateTime();
    
    // Update time every minute (60000ms)
    setInterval(updateDateTime, 60000);

    // Form validation for profile
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const fullName = document.querySelector('input[name="full_name"]');
            const email = document.querySelector('input[name="email"]');
            
            if (!fullName || !fullName.value.trim()) {
                e.preventDefault();
                showSnackbar('Please enter your full name', false);
                return false;
            }
            
            if (!email || !email.value.trim()) {
                e.preventDefault();
                showSnackbar('Please enter your email address', false);
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                e.preventDefault();
                showSnackbar('Please enter a valid email address', false);
                return false;
            }
        });
    }

    // Confirmation for consultation actions
    const consultationForms = document.querySelectorAll('form[action="process-consultation-update.php"]');
    consultationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            
            let confirmMessage = '';
            if (status === 'cancelled') {
                confirmMessage = 'Are you sure you want to reject this consultation?';
            } else if (status === 'completed') {
                confirmMessage = 'Mark this consultation as completed?';
            } else if (status === 'confirmed') {
                confirmMessage = 'Accept this consultation request?';
            }
            
            if (confirmMessage && !confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
        });
    });

    console.log('Doctor Dashboard initialized successfully');
});


// Add this to doctor-dashboard.js or create as blog-functions.js

// Blog Management
document.getElementById('createBlogBtn').addEventListener('click', function() {
    document.getElementById('blogFormModal').style.display = 'block';
    document.getElementById('blogForm').reset();
});

document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('blogFormModal').style.display = 'none';
});

document.getElementById('cancelBlogBtn').addEventListener('click', function() {
    document.getElementById('blogFormModal').style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('blogFormModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Submit blog form
document.getElementById('blogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('blogFormMessage');
    
    fetch('process-create-blog.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            messageDiv.className = 'alert success';
            messageDiv.textContent = 'Blog post published! Redirecting...';
            messageDiv.style.display = 'block';
            
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            messageDiv.className = 'alert error';
            messageDiv.textContent = 'Error publishing post. Please try again.';
            messageDiv.style.display = 'block';
        }
    })
    .catch(error => {
        messageDiv.className = 'alert error';
        messageDiv.textContent = 'Error: ' + error.message;
        messageDiv.style.display = 'block';
    });
});

// Load doctor's blogs when blogs section is clicked
document.addEventListener('click', function(e) {
    if (e.target.matches('a[data-section="blogs"]') || 
        (e.target.parentElement && e.target.parentElement.matches('a[data-section="blogs"]'))) {
        loadMyBlogs();
    }
});

function loadMyBlogs() {
    fetch('get-blogs.php?action=my_blogs')
        .then(response => response.json())
        .then(data => {
            const blogsList = document.getElementById('blogsList');
            
            if (data.success && data.blogs.length > 0) {
                let html = '';
                data.blogs.forEach(blog => {
                    html += `
                        <div class="blog-item">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <h4>${escapeHtml(blog.title)}</h4>
                                    <div class="blog-meta">
                                        Published: ${new Date(blog.created_at).toLocaleDateString()}
                                        ${blog.updated_at !== blog.created_at ? ` • Updated: ${new Date(blog.updated_at).toLocaleDateString()}` : ''}
                                    </div>
                                    <p style="color: #666; margin: 8px 0;">
                                        ${blog.excerpt ? escapeHtml(blog.excerpt) : 'No excerpt provided'}
                                    </p>
                                </div>
                                <span class="blog-status ${blog.status}">${blog.status.toUpperCase()}</span>
                            </div>
                            <div class="blog-actions">
                                <button class="btn btn-sm btn-primary" onclick="editBlog(${blog.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBlog(${blog.id})">Delete</button>
                                <a href="blog-single.php?id=${blog.id}" class="btn btn-sm btn-outline-primary" target="_blank">View</a>
                            </div>
                        </div>
                    `;
                });
                blogsList.innerHTML = html;
            } else {
                blogsList.innerHTML = '<p style="text-align: center; color: #999;">No blog posts yet. Create your first post!</p>';
            }
        })
        .catch(error => {
            console.error('Error loading blogs:', error);
            document.getElementById('blogsList').innerHTML = '<p style="color: red;">Error loading blogs</p>';
        });
}

function editBlog(blogId) {
    alert('Edit functionality coming soon');
    // TODO: Implement edit functionality
}

function deleteBlog(blogId) {
    if (confirm('Are you sure you want to delete this blog post?')) {
        fetch('delete-blog.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'blog_id=' + blogId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Blog post deleted');
                loadMyBlogs();
            } else {
                alert('Error deleting blog: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}